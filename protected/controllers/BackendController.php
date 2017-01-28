<?php
/**
 * Backend controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              
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
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');

		// set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('app', 'Dashboard')));

        $alerts = array();
        // draw predefined alerts
        if(APPHP_MODE == 'debug') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Debug Mode Alert'));
		if(CConfig::get('cookies.path') == '/' && A::app()->getRequest()->getBasePath() != '/') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Cookies Base Path Alert', array('{path}'=>A::app()->getRequest()->getBasePath())));			 
        if(CAuth::getLoggedEmail() == '' || preg_match('/email.me/i', CAuth::getLoggedEmail())) $alerts[] = array('type'=>'error', 'message'=>A::t('app', 'Default Email Alert'));
		// draw alerts from modules		
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
        
        // fetch modules that need to be shown in hotkeys
        $this->_view->modulesToShow = Modules::model()->findAll('show_on_dashboard=1 AND is_active=1');

		// fetch datetime format from settings table
    	$dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		
        $this->_view->dashboardHotkeys = Bootstrap::init()->getSettings('dashboard_hotkeys');
        $this->_view->dashboardNews = Bootstrap::init()->getSettings('dashboard_news');
        $this->_view->dashboardStatistics = Bootstrap::init()->getSettings('dashboard_statistics');
        $this->_view->todayDate = date($dateTimeFormat);
        $this->_view->lastLoginDate = strtotime(CAuth::getLoggedLastVisit()) > 0 ? date($dateTimeFormat, strtotime(CAuth::getLoggedLastVisit())) : A::t('app', 'Never');
        $this->_view->adminsCount = Admins::model()->count();
		$this->_view->scriptName = CConfig::get('name');
		$this->_view->scriptVersion = CConfig::get('version');

		// fetch last 5 admins
		$lastAdmins = Admins::model()->findAll(array('order'=>'id DESC', 'limit'=>'5'));
		$lastAdminsList = '';
		if(is_array($lastAdmins)){
			foreach($lastAdmins as $admin){
				$lastAdminsList = ($lastAdminsList == '') ? $admin['username'] : $lastAdminsList.', '.$admin['username'];	
			}
		}
		$this->_view->lastAdminsList = $lastAdminsList;

		// prepare news 
		$newsContent = array();
        if(Modules::model()->exists("code = 'news' AND is_installed = 1")){                    
			$news = News::model()->findAll(array('condition'=>'is_published = 1', 'limit'=>'0, 5'));
			foreach($news as $key => $val){
				$newsContent[$key] = array(
					'date' => $val['created_at'],
					'header' => $val['news_header'],
					//'content' => $val['news_text'],
				);
			}
		}
		$this->_view->newsContent = $newsContent;
		
        $this->_view->render('backend/dashboard');
    }

    /**
     * Admin login action handler
     */
	public function loginAction()
	{
        // redirect logged in admins
		CAuth::handleLoggedIn('backend/dashboard');
        // set default language for backend
        Website::setDefaultLanguage();		
        // set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('app', 'Login')));
		
		//#000 
		$this->checkBruteforce = CConfig::get('validation.bruteforce.enable');
		$this->redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
		$this->badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);		

		$admin = new Admins();				

		// perform auto-login "remember me"
        if(!CAuth::isLoggedIn()){
            parse_str(A::app()->getCookie()->get('auth'));
            if(!empty($usr) && !empty($hash)){
                $username = $usr;
                $password = CHash::decrypt($hash, CConfig::get('password.hashKey'));
                if($admin->login($username, $password)){
                    $this->redirect('backend/index');				
                }
            }            
        }
		
		$cRequest = A::app()->getRequest();
		$this->_view->username = $cRequest->getPost('username');
		$this->_view->password = $cRequest->getPost('password');
        $this->_view->remember = $cRequest->getPost('remember');
		$msg = A::t('app', 'Login Message');
        $msgType = '';
		
		if($cRequest->getPost('act') == 'send'){			
            // perform login form validation
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
				if($admin->login($this->_view->username, $this->_view->password)){
                    if($this->_view->remember){
                        $passwordHash = CHash::encrypt($this->_view->password, CConfig::get('password.hashKey'));
                        A::app()->getCookie()->set('auth', 'usr='.$this->_view->username.'&hash='.$passwordHash, time() + 3600 * 24 * 14);
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
     */
    public function logoutAction()
	{
        A::app()->getSession()->endSession();
		A::app()->getCookie()->remove('auth');
        // clear cache
        if(CConfig::get('cache.enable')) CFile::emptyDirectory('protected/tmp/cache/');
        $this->redirect('backend/login');        
    }    

}