<?php
/**
 * Backend controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _checkBan
 * indexAction
 * dashboardAction
 * loginAction
 * logoutAction
 *
 */

class BackendController extends CController
{
	private $checkBruteforce;
	private $redirectDelay;
	private $badLogins;		
	
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
		$lastAdmins = Admins::model()->findAll(array('order'=>'id DESC', 'limit'=>'5'));
		$lastAdminsList = '';
		if(is_array($lastAdmins)){
			foreach($lastAdmins as $admin){
				$lastAdminsList = ($lastAdminsList == '') ? $admin['username'] : $lastAdminsList.', '.$admin['username'];	
			}
		}
		$this->_view->lastAdminsList = $lastAdminsList;
		
		// Prepare active sessions
		$this->_view->customStorage = CConfig::get('session.customStorage');
		if($this->_view->customStorage){
			$sessions = CDatabase::init()->select('SELECT COUNT(*) as cnt FROM '.CConfig::get('db.prefix').'sessions');
			$this->_view->activeSessions = isset($sessions[0]['cnt']) ? $sessions[0]['cnt'] : '0';
		}

		// Prepare notifications 
		$systemNotifications = array();		
		$condition = '';
		$loggedRole = CAuth::getLoggedRole();
		if($loggedRole == 'owner'){
			$condition = '';
		}else if($loggedRole == 'mainadmin'){
			$condition = "minimal_role != 'owner'";
		}else if($loggedRole == 'admin'){
			$condition = "minimal_role NOT IN ('owner', 'mainadmin')";
		}else if(!empty($loggedRole)){
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
		$msg = A::t('app', 'Login Message');
        $msgType = '';
		
		//#000 
		$this->checkBruteforce = CConfig::get('validation.bruteforce.enable');
		$this->redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
		$this->badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);
		
		$admin = new Admins();			

		// Check if access is blocked to this IP address
		$userBanned = $this->_checkBan('ip', $cRequest->getUserHostAddress());

		// -------------------------------------------------
		// Perform auto-login "remember me"
		// --------------------------------------------------
        if(!$userBanned && !CAuth::isLoggedIn()){
            parse_str(A::app()->getCookie()->get('auth'));
            if(!empty($usr) && !empty($hash)){
                $username = CHash::decrypt($usr, CConfig::get('password.hashKey'));
                $password = $hash;

				// Check if access is blocked to this username 
				$userBanned = $this->_checkBan('username', $username);

                if(!$userBanned && $admin->login($username, $password, true, true)){
                    $this->redirect('backend/index');				
                }
            }            
        }
		
		// -------------------------------------------------
		// Handle form submission
		// --------------------------------------------------
		if($cRequest->getPost('act') == 'send'){			
			if(!$userBanned){
				// Perform login form validation
				$result = CWidget::create('CFormValidation', array(
					'fields'=>array(
						'username'=>array('title'=>A::t('app', 'Username'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>20)),
						'password'=>array('title'=>A::t('app', 'Password'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>20)),
						'remember'=>array('title'=>A::t('app', 'Remember me'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1))),
					),            
				));
				
				if($result['error']){
					$msg = $result['errorMessage'];
					$msgType = 'validation';                
					$this->_view->errorField = $result['errorField'];
				}else{
					// Check if access is blocked to this username 
					$userBanned = $this->_checkBan('username', $this->_view->username);
					if(!$userBanned){
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
							if($this->checkBruteforce){
								A::app()->getSession()->remove('loginAttempts');
								A::app()->getCookie()->remove('loginAttemptsAuth');
							}
							$this->redirect('backend/dashboard');	
						}else{
							$msg = $admin->getErrorDescription();
							$msgType = 'error';
							$this->_view->errorField = 'username';
						}
					}else{
						$msg = A::t('app', 'This username is banned.');
						$msgType = 'error';
					}
				}
	
				if(!empty($msg)){				
					$this->_view->username = $cRequest->getPost('username');
					$this->_view->password = $cRequest->getPost('password');
					$this->_view->remember = $cRequest->getPost('remember', 'string');
					$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg));
					
					//#002 increment login attempts counter
					if($this->checkBruteforce && $this->_view->username != '' && $this->_view->password != ''){
						$logAttempts = A::app()->getSession()->get('loginAttempts', 1);
						if($logAttempts < $this->badLogins){
							A::app()->getSession()->set('loginAttempts', $logAttempts+1);
						}else{
							A::app()->getCookie()->set('loginAttemptsAuth', md5(uniqid()));
							sleep($this->redirectDelay);
							$this->redirect('backend/login');
						}					
					}
				}				
			}			
        }else{
			//#003 validate login attempts coockie
			if($this->checkBruteforce){
				$logAttempts = A::app()->getSession()->get('loginAttempts', 1);
				$logAttemptsAuthCookie = A::app()->getCookie()->get('loginAttemptsAuth');
				$logAttemptsAuthPost = $cRequest->getPost('loginAttemptsAuth');
				if($logAttempts >= $this->badLogins){
					if($logAttemptsAuthCookie != '' && $logAttemptsAuthCookie == $logAttemptsAuthPost){
						A::app()->getSession()->remove('loginAttempts');
						A::app()->getCookie()->remove('loginAttemptsAuth');
						$this->redirect('backend/login');
					}
				}
			}
			
            $this->_view->actionMessage = CWidget::create('CMessage', array('info', $msg));
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

	/**
	 * Checks if admin is banned ban by different parameters
	 * @param $itemType
	 * @param $itemValue
	 * @return bool
	 */
	private function _checkBan($itemType = '', $itemValue = '')
	{
		$isBanned = BanLists::model()->count(
			"item_type = '".$itemType."' AND item_value = :item_value AND is_active = 1 AND (expires_at > :expires_at OR expires_at = '0000-00-00 00:00:00')",
			array(':item_value' => $itemValue, ':expires_at' => LocalTime::currentDateTime())
		);
		
		if($isBanned){

			$msg = '';
			switch($itemType){
				case 'ip':
					$msg = A::t('app', 'This IP address is banned.');
					break;
				case 'username':
					$msg = A::t('app', 'This username is banned.');
					break;
				case 'email':
					$msg = A::t('app', 'This email is banned.');
					break;
				default:
					$msg = A::t('app', 'This username, email or IP address is banned.');
					break;					
			}
			
			$this->_view->actionMessage = CWidget::create('CMessage', array('error', $msg));
		}
		
		return $isBanned;
	}

}