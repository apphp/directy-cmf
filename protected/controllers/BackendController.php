<?php
/**
 * Backend controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              	
 * indexAction
 * dashboardAction
 * loginAction
 * logoutAction
 *
 */

class BackendController extends CController
{
	private $_checkBruteforce;
	private $_redirectDelay;
	private $_badLogins;
	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Set backend mode 
        Website::setBackend();
		
        $this->_view->actionMessage = '';
        $this->_view->errorField = ''; 
    }
        
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect('backend/dashboard');    
    }

    /**
     * Dashboard action handler
     */
  	public function dashboardAction()
	{		
        // Block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');

        $alerts = array();
        // Draw predefined alerts
        if(APPHP_MODE == 'debug') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Debug Mode Alert'));
		if(CConfig::get('cookies.path') == '/' && A::app()->getRequest()->getBasePath() != '/') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Cookies Base Path Alert', array('{path}'=>A::app()->getRequest()->getBasePath())));			 
        if(CAuth::getLoggedEmail() == '' || preg_match('/email.me/i', CAuth::getLoggedEmail())) $alerts[] = array('type'=>'error', 'message'=>A::t('app', 'Default Email Alert'));
		// Draw alerts from modules		
		$modules = Modules::model()->findAll('is_active = 1');		
		if(is_array($modules)){
			foreach($modules as $key => $val){
                $class = $val['code'].'Component';
                if(class_exists($class, false) && method_exists($class, 'drawAlerts')){
                    if($alert = call_user_func_array($class.'::drawAlerts', array())){
                        $alerts[] = array('type'=>'warning', 'message'=>$alert);
                    }
                }
			}
		}
        $this->_view->alerts = $alerts;
        
        // Fetch modules that need to be shown in hotkeys
        $this->_view->modulesToShow = Modules::model()->findAll('show_on_dashboard=1 AND is_active=1');

		// Fetch datetime format from settings table
    	$dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		
        $this->_view->dashboardHotkeys = Bootstrap::init()->getSettings('dashboard_hotkeys');
        $this->_view->dashboardNotifications = Bootstrap::init()->getSettings('dashboard_notifications');
        $this->_view->dashboardStatistics = Bootstrap::init()->getSettings('dashboard_statistics');
        $this->_view->todayDate = date($dateTimeFormat);
        $this->_view->lastLoginDate = strtotime(CAuth::getLoggedLastVisit()) > 0 ? date($dateTimeFormat, strtotime(CAuth::getLoggedLastVisit())) : A::t('app', 'Never');
        $this->_view->adminsCount = Admins::model()->count();
		$this->_view->scriptName = CConfig::get('name');
		$this->_view->scriptVersion = CConfig::get('version');

		// Fetch last 5 admins
		$lastAdmins = Admins::model()->findAll(array('condition'=>"role != 'owner'", 'order'=>'id DESC', 'limit'=>'5'));
		$lastAdminsList = array();
		if(is_array($lastAdmins)){
			foreach($lastAdmins as $admin){
				$lastAdminsList[] = $admin['username'];	
			}
		}
		$this->_view->lastAdminsList = $lastAdminsList;
		
		// Fetch admins who changed password
		$changedPasswordAdmins = Admins::model()->findAll(array('condition'=>"role != 'owner' AND password_changed_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)", 'order'=>'id DESC', 'limit'=>'5'));
		$changedPasswordAdminsList = array();
		if(is_array($changedPasswordAdmins)){
			foreach($changedPasswordAdmins as $admin){
				$changedPasswordAdminsList[] = $admin['username'];	
			}
		}
		$this->_view->changedPasswordAdminsList = $changedPasswordAdminsList;
		
		// Prepare session's data
		$this->_view->customStorage = CConfig::get('session.customStorage');
		$sessions = CDatabase::init()->select('SELECT COUNT(*) cnt FROM '.CConfig::get('db.prefix').'sessions');
		$this->_view->activeSessions = isset($sessions[0]['cnt']) ? $sessions[0]['cnt'] : 0;

		// Prepare notifications 
		$systemNotifications = array();		
		$condition = '';
		$loggedRole = CAuth::getLoggedRole();
		if($loggedRole == 'owner'){
			$condition = '';
		}elseif($loggedRole == 'mainadmin'){
			$condition = "minimal_role != 'owner'";
		}elseif($loggedRole == 'admin'){
			$condition = "minimal_role NOT IN ('owner', 'mainadmin')";
		}elseif(!empty($loggedRole)){
			$condition = "(minimal_role = '".$loggedRole."')";
		}		
		
        if($notifications = SystemNotifications::model()->findAll(array('condition'=>$condition, 'order'=>'id DESC', 'limit'=>'0, 10'))){
			foreach($notifications as $key => $val){
				$systemNotifications[$key] = array(
					'title' => $val['title'],
					'content' => $val['content'],
					'type' => $val['type'],
					'module_code' => $val['module_code'],
					'date' => $val['created_at'],
				);
			}
		}
		$this->_view->systemNotifications = $systemNotifications;
		
        $this->_view->render('backend/dashboard');
    }

    /**
     * Admin login action handler
     */
	public function loginAction()
	{
        // Redirect logged in admins
		CAuth::handleLoggedIn('backend/dashboard');
        // Set default language for backend
        Website::setDefaultLanguage();		
		
		$cRequest = A::app()->getRequest();
		$this->_view->username = $cRequest->getPost('username');
		$this->_view->password = $cRequest->getPost('password');
        $this->_view->remember = $cRequest->getPost('remember');
		$alert = A::t('app', 'Login Message');
        $alertType = '';
		$errors = array();
		
		//#000 
		$this->_checkBruteforce = CConfig::get('validation.bruteforce.enable');
		$this->_redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
		$this->_badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);
		
		$admin = new Admins();			

		// Check if access is blocked to this IP address
		$ipBanned = Website::checkBan('ip_address', $cRequest->getUserHostAddress(), $errors);
        if($ipBanned){
			// do nothing			
			$this->_view->actionMessage = CWidget::create('CMessage', array($errors['alertType'], $errors['alert']));
		}else{			
			// -------------------------------------------------
			// Perform auto-login "remember me"
			// --------------------------------------------------
			if(!CAuth::isLoggedIn()){
				parse_str(A::app()->getCookie()->get('auth'));
				if(!empty($usr) && !empty($hash)){
					$username = CHash::decrypt($usr, CConfig::get('password.hashKey'));
					$password = $hash;
	
					// Check if access is blocked to this username 
					$usernameBanned = Website::checkBan('username', $username);
					if($usernameBanned){
						// do nothing
					}elseif(!$usernameBanned && $admin->login($username, $password, true, true)){
						$this->redirect('backend/index');				
					}
				}            
			}
			
			// -------------------------------------------------
			// Handle form submission
			// --------------------------------------------------
			if($cRequest->getPost('act') == 'send'){			
				// Perform login form validation
				$result = CWidget::create('CFormValidation', array(
					'fields'=>array(
						'username'=>array('title'=>A::t('app', 'Username'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>20)),
						'password'=>array('title'=>A::t('app', 'Password'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>20)),
						'remember'=>array('title'=>A::t('app', 'Remember me'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1))),
					),            
				));
				
				if($result['error']){
					$alert = $result['errorMessage'];
					$alertType = 'validation';                
					$this->_view->errorField = $result['errorField'];
				}else{
					// Check if access is blocked to this username 
					$usernameBanned = Website::checkBan('username', $this->_view->username, $errors);
					if($usernameBanned){
						// do nothing			
						$alert = $errors['alert'];
						$alertType = $errors['alertType'];						
					}else{
						if($admin->login($this->_view->username, $this->_view->password, false, $this->_view->remember)){
							if($this->_view->remember){
								// Username may be decoded
								$usernameHash = CHash::encrypt($this->_view->username, CConfig::get('password.hashKey'));
								// Password cannot be decoded, so we save ID + username + salt + HTTP_USER_AGENT
								$httpUserAgent = A::app()->getRequest()->getUserAgent();
								$passwordHash = CHash::create(CConfig::get('password.encryptAlgorithm'), $admin->id.$admin->username.$admin->getPasswordSalt().$httpUserAgent);
								A::app()->getCookie()->set('auth', 'usr='.$usernameHash.'&hash='.$passwordHash, (time() + 3600 * 24 * 14));
							}
							//#001 clean login attempts counter
							if($this->_checkBruteforce){
								A::app()->getSession()->remove('loginAttempts');
								A::app()->getCookie()->remove('loginAttemptsAuth');
							}
							$this->redirect('backend/dashboard');	
						}else{
							$alert = $admin->getErrorDescription();
							$alertType = 'error';
							$this->_view->errorField = 'username';
						}
					}
				}
	
				if(!empty($alert)){				
					$this->_view->username = $cRequest->getPost('username');
					$this->_view->password = $cRequest->getPost('password');
					$this->_view->remember = $cRequest->getPost('remember', 'string');
					$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
					
					//#002 increment login attempts counter
					if($this->_checkBruteforce && $this->_view->username != '' && $this->_view->password != ''){
						$logAttempts = A::app()->getSession()->get('loginAttempts', 1);
						if($logAttempts < $this->_badLogins){
							A::app()->getSession()->set('loginAttempts', $logAttempts+1);
						}else{
							A::app()->getCookie()->set('loginAttemptsAuth', md5(uniqid()));
							sleep($this->_redirectDelay);
							$this->redirect('backend/login');
						}					
					}
				}				
			}else{
				//#003 validate login attempts coockie
				if($this->_checkBruteforce){
					$logAttempts = A::app()->getSession()->get('loginAttempts', 1);
					$logAttemptsAuthCookie = A::app()->getCookie()->get('loginAttemptsAuth');
					$logAttemptsAuthPost = $cRequest->getPost('loginAttemptsAuth');
					if($logAttempts >= $this->_badLogins){
						if($logAttemptsAuthCookie != '' && $logAttemptsAuthCookie == $logAttemptsAuthPost){
							A::app()->getSession()->remove('loginAttempts');
							A::app()->getCookie()->remove('loginAttemptsAuth');
							$this->redirect('backend/login');
						}
					}
				}
				
				$this->_view->actionMessage = CWidget::create('CMessage', array('info', $alert));
			}
		}
       
		$this->_view->render('backend/login');	        
    }

    /**
     * Admin logout action handler
     * @return void
     */
    public function logoutAction()
	{
        A::app()->getSession()->endSession();
		A::app()->getCookie()->remove('auth');
        // Clear cache
        if(CConfig::get('cache.enable')) CFile::emptyDirectory('protected/tmp/cache/');
        $this->redirect('backend/login');
    }

}