<?php
/**
* BackendController
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

        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = ''; 

		//#000 
		$this->checkBruteforce = CConfig::get('validation.bruteforce.enable');
		$this->redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
		$this->badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);		
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
		SiteSettings::setMetaTags(array('title'=>A::t('app', 'Dashboard')));
		
        $alerts = array();
        if(APPHP_MODE == 'debug') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Debug Mode Alert'));
		if(CConfig::get('cookies.path') == '/' && A::app()->getRequest()->getBasePath() != '/') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Cookies Base Path Alert', array('{path}'=>A::app()->getRequest()->getBasePath())));			 
        if(CAuth::getLoggedEmail() == '' || preg_match('/email.me/i', CAuth::getLoggedEmail())) $alerts[] = array('type'=>'error', 'message'=>A::t('app', 'Default Email Alert'));
        $this->view->alerts = $alerts;
        
        // fetch modules that need to be shown in hotkeys
        $this->view->modulesToShow = Modules::model()->findAll('show_on_dashboard=1 AND is_active=1');

		// fetch datetime format from settings table
    	$settings = Settings::model()->findByPk(1);
    	$dateTimeFormat = $settings->datetime_format;
		
        $this->view->todayDate = date($dateTimeFormat);
        $this->view->lastLoginDate = strtotime(CAuth::getLoggedLastVisit()) > 0 ? date($dateTimeFormat, strtotime(CAuth::getLoggedLastVisit())) : A::t('app', 'Never');
        $this->view->adminsCount = Admins::model()->count();
		$this->view->scriptName = CConfig::get('name');
		$this->view->scriptVersion = CConfig::get('version');

		// fetch last 5 admins
		$lastAdmins = Admins::model()->findAll(array('order'=>'id DESC', 'limit'=>'5'));
		$lastAdminsList = '';
		if(is_array($lastAdmins)){
			foreach($lastAdmins as $admin){
				$lastAdminsList = ($lastAdminsList == '') ? $admin['username'] : $lastAdminsList.', '.$admin['username'];	
			}
		}
		$this->view->lastAdminsList = $lastAdminsList;

		// prepare news 
		$newsContent = array();
		if(Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>'news'))){
			$news = News::model()->findAll('is_published = 1');
			foreach($news as $key => $val){
				$newsContent[$key] = array(
					'date' => $val['created_at'],
					'header' => $val['news_header'],
					//'content' => $val['news_text'],
				);
			}
		}
		$this->view->newsContent = $newsContent;
		
        $this->view->render('backend/dashboard');
    }

    /**
     * Admin login action handler
     */
	public function loginAction()
	{
        // redirect logged in users
		CAuth::handleLoggedIn('backend/dashboard');
        
		// set meta tags according to active language
		SiteSettings::setMetaTags(array('title'=>A::t('app', 'Login')));
		
		$model = new Backend();				

		// perform auto-login "remember me"
		parse_str(A::app()->getCookie()->get('auth'));
		if(!empty($usr) && !empty($hash)){
			$username = $usr;
			$password = CHash::decrypt($hash, CConfig::get('password.hashKey'));
			if($model->login($username, $password)){
				$this->redirect('backend/index');				
			}
		}
		
		$cRequest = A::app()->getRequest();
		$this->view->username = $cRequest->getPost('username');
		$this->view->password = $cRequest->getPost('password');
        $this->view->remember = $cRequest->getPost('remember');
		$msg = A::t('app', 'Login Message');
		
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
				$this->view->errorField = $result['errorField'];
				$messageType = 'validation';                
            }else{
				if($model->login($this->view->username, $this->view->password)){
                    if($this->view->remember){
                        $passwordHash = CHash::encrypt($this->view->password, CConfig::get('password.hashKey'));
                        A::app()->getCookie()->set('auth', 'usr='.$this->view->username.'&hash='.$passwordHash, time() + 3600 * 24 * 14);
                    }
					//#001 clean login attempts counter
					if($this->checkBruteforce){
						A::app()->getSession()->remove('loginAttempts');
						A::app()->getCookie()->remove('loginAttemptsAuth');
					}
					
					$this->redirect('backend/index');	
				}else{
					$msg = A::t('app', 'Login Error Message');
					$this->view->errorField = 'username';
					$messageType = 'error';
				}
			}

			if(!empty($msg)){				
				$this->view->username = $cRequest->getPost('username');
				$this->view->password = $cRequest->getPost('password');
                $this->view->remember = $cRequest->getPost('remember', 'string');
				$this->view->actionMessage = CWidget::create('CMessage', array($messageType, $msg));
				
				//#002 increment login attempts counter
				if($this->checkBruteforce && $this->view->username != '' && $this->view->password != ''){
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
            $this->view->actionMessage = CWidget::create('CMessage', array('info', $msg));
        }
        
		$this->view->render('backend/login');	        
    }

    /**
     * Admin logout action handler
     */
    public function logoutAction()
	{
        A::app()->getSession()->endSession();
		A::app()->getCookie()->remove('auth');
        $this->redirect('backend/login');        
    }    

}