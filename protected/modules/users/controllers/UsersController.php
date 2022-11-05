<?php 
/**
 * UsersController Controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------              ------------------
 * __construct              _checkAccountsAccess
 * indexAction              _checkUsersAccess
 * manageAction             _prepareAccountFields
 * addAction                _logout
 * editAction
 * changeStatusAction
 * deleteAction
 * loginAction
 * logoutAction
 * registrationAction
 * restorePasswordAction
 * confirmRegistrationAction
 * dashboardAction
 * myAccountAction
 * editAccountAction
 * removeAccountAction
 * 
 */

namespace Modules\Users\Controllers;

// Modules
use \Modules\Users\Components\UsersComponent,
	\Modules\Users\Models\UserGroups,
	\Modules\Users\Models\Users;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CDatabase,
	\CFile,
	\CHash,
	\CLoader,
	\CLocale,
	\CTime,
	\CValidator,
	\CWidget;

// Directy
use \Accounts,
	\Countries,
	\Languages,
	\LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\ModulesSettings,
	\SocialLogin,
	\States;


class UsersController extends CController
{
	private $_checkBruteforce;
	private $_redirectDelay;
	private $_badLogins;		
	private $_backendPath = '';

    /**
	 * Class default constructor
     */
    public function __construct()
	{            
        parent::__construct();
        
		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

        // Block access if the module is not installed
        if(!Modules::model()->isInstalled('users')){
        	if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
        	}else{
        		$this->redirect(Website::getDefaultPage());
        	}
        }

        // Set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('users', 'Users Management')));

        $this->_settings = Bootstrap::init()->getSettings();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
        $this->_view->backendPath = $this->_backendPath;

        $this->_view->tabs = UsersComponent::prepareTab('users');
	}
    
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        if(CAuth::isLoggedInAs('user')){
            $this->redirect('users/dashboard');	   	
        }else{
            $this->redirect('users/manage');	   	
        }		
    }
        
    /**
     * Manage users action handler
     * @param string $alert
     */
    public function manageAction($alert = '')
    {
        Website::prepareBackendAction('manage', 'users', 'users/manage', false);
        $this->_prepareAccountFields();

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
        
        $this->_view->render('users/manage');
    }	
  
    /**
     * Add user action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'users', 'users/manage', false);
        $this->_prepareAccountFields();
        
        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            $this->_view->countryCode = $cRequest->getPost('country_code');             
            $this->_view->stateCode = $cRequest->getPost('state');
        }else{
            $this->_view->countryCode = $this->_view->defaultCountryCode;             
            $this->_view->stateCode = '';
        }
        
		// Prepare salt
		$this->_view->salt = '';
		if(A::app()->getRequest()->getPost('password') != ''){
			$this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
		}

        $this->_view->render('users/add');
    }
    
    /**
     * User edit action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {      
        Website::prepareBackendAction('edit', 'users', 'users/manage', false);
        $user = $this->_checkUsersAccess($id);
        $this->_prepareAccountFields();

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            $this->_view->countryCode = $cRequest->getPost('country_code');             
            $this->_view->stateCode = $cRequest->getPost('state');
        }else{
            $this->_view->countryCode = $user->country_code;
            $this->_view->stateCode = $user->state;
        }
        
        $this->_view->id = $user->id;          
        // Fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
    
		// Prepare salt
		$this->_view->salt = '';
		if(A::app()->getRequest()->getPost('password') != ''){
			$this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
			A::app()->getRequest()->setPost('salt', $this->_view->salt);
		}

	    $this->_view->render('users/edit');
    }   
	
	/**
     * Change user state method
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 1)
    {
        Website::prepareBackendAction('edit', 'users', 'users/manage', false);
        $user = $this->_checkUsersAccess($id);

		$changeResult = Accounts::model()->updateByPk($user->account_id, array('is_active'=>($user->is_active == 1 ? '0' : '1')));

        if ($changeResult) {
            $alert = A::t('users', 'User status has been successfully changed!');
            $alertType = 'success';
        }else{
			$alert = APPHP_MODE == 'demo' ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('users', 'An error occurred while changing user status!');
			$alertType = APPHP_MODE == 'demo' ? 'warning' : 'error';
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
     
        $this->redirect('users/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
    /**
     * Delete user action handler
     * @param int $id the user id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'users', 'users/manage', false);
        $user = $this->_checkUsersAccess($id);
        $this->_prepareAccountFields();
        if($this->_view->removalType == 'logical') $this->redirect('users/manage');

        $alert = '';
        $alertType = '';
    
        if($user->delete()){             
            $alert = A::t('users', 'User has been successfully deleted!');
            $alertType = 'success'; 
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('users', 'User deleting error');
                $alertType = 'error';
            }           
        }
        
		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);
        }
        
		$this->redirect('users/manage');
    }
   
    /**
     * User login action handler
     * @param string $type
     * @return void
     */
    public function loginAction($type = '')
    {
        // Redirect logged in users
		CAuth::handleLoggedIn('users/dashboard', 'user');

        // Check if login action is allowed
        if(!ModulesSettings::model()->param('users', 'allow_login')){
            $this->redirect(Website::getDefaultPage());
        }

        // Social login
        if(!empty($type)){
			if(APPHP_MODE == 'demo'){
				A::app()->getSession()->setFlash(
					'msgLoggedOut',
					CWidget::create('CMessage', array('warning', A::t('core', 'This operation is blocked in Demo Mode!')))
				);
				$this->redirect('users/login');
			}
			
            $config = array();
            $config['returnUrl'] = 'users/login';
            $config['model'] = 'Users';

			SocialLogin::config($config);
			SocialLogin::login(strtolower($type));
        }
		
        // Set frontend mode
        Website::setFrontend();
       
        $this->_view->allowRememberMe = ModulesSettings::model()->param('users', 'allow_remember_me');
        $this->_view->allowRegistration = ModulesSettings::model()->param('users', 'allow_registration');
        $this->_view->allowResetPassword = ModulesSettings::model()->param('users', 'allow_restore_password');
        
		//#000
        $this->_checkBruteforce = CConfig::get('validation.bruteforce.enable');
		$this->_redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
		$this->_badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);		
		$alert = A::t('app', 'Login Message');
        $alertType = '';
		$errors = array();
		$cRequest = A::app()->getRequest();

        $user = new Accounts();
         
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
				if($this->_view->allowRememberMe){
					parse_str(A::app()->getCookie()->get('userAuth'), $output);
					if(!empty($output['usr']) && !empty($output['hash'])){
						$username = CHash::decrypt($output['usr'], CConfig::get('password.hashKey'));
						$password = $output['hash'];

						// Check if access is blocked to this username 
						$usernameBanned = Website::checkBan('username', $username);
						if($usernameBanned){
							// do nothing
						}elseif($user->login($username, $password, 'user', true, true)){
							$this->redirect('users/dashboard');
						}
					}
				}
			}
	
			$this->_view->username = $cRequest->getPost('login_username');
			$this->_view->password = $cRequest->getPost('login_password');
			$this->_view->remember = $cRequest->getPost('remember');
			
			// -------------------------------------------------
			// Handle form submission
			// --------------------------------------------------
			if($cRequest->getPost('act') == 'send'){			
				// Perform login form validation
				$fields = array();
				$fields['login_username'] = array('title'=>A::t('app', 'Username'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>25));
				$fields['login_password'] = array('title'=>A::t('app', 'Password'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>20));
				if($this->_view->allowRememberMe) $fields['remember'] = array('title'=>A::t('app', 'Remember me'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)));
				$result = CWidget::create('CFormValidation', array(
					'fields' => $fields
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
						if($user->login($this->_view->username, $this->_view->password, 'user', false, ($this->_view->allowRememberMe && $this->_view->remember))){
							if($this->_view->allowRememberMe && $this->_view->remember){
								// Username may be decoded
								$usernameHash = CHash::encrypt($this->_view->username, CConfig::get('password.hashKey'));
								// Password cannot be decoded, so we save ID + username + salt + HTTP_USER_AGENT
								$httpUserAgent = A::app()->getRequest()->getUserAgent();
								$passwordHash = CHash::create(CConfig::get('password.encryptAlgorithm'), $user->id.$user->username.$user->getPasswordSalt().$httpUserAgent);
								A::app()->getCookie()->set('userAuth', 'usr='.$usernameHash.'&hash='.$passwordHash, (time() + 3600 * 24 * 14));
							}
							//#001 clean login attempts counter
							if($this->_checkBruteforce){
								A::app()->getSession()->remove('userLoginAttempts');
								A::app()->getCookie()->remove('userLoginAttemptsAuth');
							}
		
							// Save user role ID
							$usr = Users::model()->find('account_id = :account_id', array(':account_id' => (int)$user->id));
							if($usr){
								A::app()->getSession()->set('loggedRoleId', $usr->id);
							}

							$lastVisitedPage = Website::getLastVisitedPage();
							$defaultPage = str_replace('/', '\/', Website::getDefaultPage());
							if(!empty($lastVisitedPage) && !preg_match('/(login|registration|Home\/index|index\/index'.(!empty($defaultPage) ? '|'.$defaultPage : '').')/i', $lastVisitedPage)){
								$this->redirect($lastVisitedPage, true);
							}
							$this->redirect('users/dashboard');
						}else{
							$alert = $user->getErrorDescription();
							$alertType = 'error';
							$this->_view->errorField = 'username';
						}
					}
				}
	
				if(!empty($alert)){				
					$this->_view->username = $cRequest->getPost('login_username');
					$this->_view->password = $cRequest->getPost('login_password');
					if($this->_view->allowRememberMe) $this->_view->remember = $cRequest->getPost('remember', 'string');
					$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
	
					//#002 increment login attempts counter
					if($this->_checkBruteforce && $this->_view->username != '' && $this->_view->password != ''){
						$logAttempts = A::app()->getSession()->get('userLoginAttempts', 1);
						if($logAttempts < $this->_badLogins){
							A::app()->getSession()->set('userLoginAttempts', $logAttempts+1);
						}else{
							A::app()->getCookie()->set('userLoginAttemptsAuth', md5(uniqid()));
							sleep($this->_redirectDelay);
							$this->redirect('users/login');
						}					
					}
				}
			}else{
				//#003 validate login attempts coockie
				if($this->_checkBruteforce){
					$logAttempts = A::app()->getSession()->get('userLoginAttempts', 1);
					$logAttemptsAuthCookie = A::app()->getCookie()->get('userLoginAttemptsAuth');
					$logAttemptsAuthPost = $cRequest->getPost('userLoginAttemptsAuth');
					if($logAttempts >= $this->_badLogins){
						if($logAttemptsAuthCookie != '' && $logAttemptsAuthCookie == $logAttemptsAuthPost){
							A::app()->getSession()->remove('userLoginAttempts');
							A::app()->getCookie()->remove('userLoginAttemptsAuth');
							$this->redirect('users/login');
						}
					}elseif($logAttempts == 0 && !empty($logAttemptsAuthPost)){
						// If the lifetime of the session ended, and confirm the button has not been pressed
						A::app()->getCookie()->remove('userLoginAttemptsAuth');
						$this->redirect('users/login');
					}
				}
				$this->_view->actionMessage = CWidget::create('CMessage', array('info', $alert));
			}
		}
        
        $this->_view->buttons = SocialLogin::drawButtons(array(
            'facebook'=>'users/login/type/facebook',
            'twitter'=>'users/login/type/twitter',
            'google'=>'users/login/type/google')
        );

		// Logged out messages
		if(A::app()->getSession()->hasFlash('msgLoggedOut')){
			$this->_view->actionMessage = A::app()->getSession()->getFlash('msgLoggedOut');	
		}
		
        $this->_view->render('users/login');
    }

    /**
     * User logout action handler
     */
    public function logoutAction()
    {
        if(CAuth::isLoggedInAs('user')){
			$this->_logout();			
			$this->_cSession->startSession();		
			$this->_cSession->setFlash('msgLoggedOut', CWidget::create('CMessage', array('info', A::t('users', 'You have been successfully logged out. We hope to see you again soon.'))));
		}
		
		$this->redirect('users/login');
    } 

    /**
     * User registration action handler
     * @return void
     */
    public function registrationAction()
    {        
        // Redirect logged in users
		CAuth::handleLoggedIn('users/dashboard', 'user');

        // Check if action allowed
        if(!ModulesSettings::model()->param('users', 'allow_registration')){
            $this->redirect(Website::getDefaultPage());
        }
        
        // Set frontend mode
        Website::setFrontend();
        
        $this->_prepareAccountFields();
        $cRequest = A::app()->getRequest();
        $approvalType = ModulesSettings::model()->param('users', 'approval_type');
        $arr = array();
		$errors = array();
        
		if($cRequest->isPostRequest()){

			// Check if access is blocked to this IP address
			$ipBanned = Website::checkBan('ip_address', $cRequest->getUserHostAddress(), $errors);
			if($ipBanned){
				// Do nothing			
				$arr[] = '"status": "0"';
				$arr[] = '"error": "'.$errors['alert'].'"';
			}else{			
				if($cRequest->getPost('act') != 'send'){            
					$this->redirect(CConfig::get('defaultController').'/');
				}elseif(APPHP_MODE == 'demo'){
					$arr[] = '"status": "0"';
				}else{            
					// Perform registration form validation
					$fields = array();            
					if($this->_view->fieldFirstName !== 'no') $fields['first_name'] = array('title'=>A::t('app', 'First Name'), 'validation'=>array('required'=>($this->_view->fieldFirstName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32));
					if($this->_view->fieldLastName !== 'no') $fields['last_name'] = array('title'=>A::t('app', 'Last Name'), 'validation'=>array('required'=>($this->_view->fieldLastName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32));
					if($this->_view->fieldBirthDate !== 'no') $fields['birth_date'] = array('title'=>A::t('app', 'Birth Date'), 'validation'=>array('required'=>($this->_view->fieldBirthDate == 'allow-required' ? true : false), 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>date('Y-m-d')));
					if($this->_view->fieldWebsite !== 'no') $fields['website'] = array('title'=>A::t('app', 'Website'), 'validation'=>array('required'=>($this->_view->fieldWebsite == 'allow-required' ? true : false), 'type'=>'url', 'maxLength'=>255));
					if($this->_view->fieldCompany !== 'no') $fields['company'] = array('title'=>A::t('app', 'Company'), 'validation'=>array('required'=>($this->_view->fieldCompany == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>128));
					if($this->_view->fieldPhone !== 'no') $fields['phone'] = array('title'=>A::t('app', 'Phone'), 'validation'=>array('required'=>($this->_view->fieldPhone == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32));
					if($this->_view->fieldFax !== 'no') $fields['fax'] = array('title'=>A::t('app', 'Fax'), 'validation'=>array('required'=>($this->_view->fieldFax == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32));
					if($this->_view->fieldEmail !== 'no') $fields['email'] = array('title'=>A::t('app', 'Email'), 'validation'=>array('required'=>($this->_view->fieldEmail == 'allow-required' ? true : false), 'type'=>'email', 'maxLength'=>100));
					if($this->_view->fieldAddress !== 'no') $fields['address'] = array('title'=>A::t('app', 'Address'), 'validation'=>array('required'=>($this->_view->fieldAddress == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
					if($this->_view->fieldAddress2 !== 'no') $fields['address_2'] = array('title'=>A::t('app', 'Address (line 2)'), 'validation'=>array('required'=>($this->_view->fieldAddress2 == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
					if($this->_view->fieldCity !== 'no') $fields['city'] = array('title'=>A::t('app', 'City'), 'validation'=>array('required'=>($this->_view->fieldCity == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
					if($this->_view->fieldZipCode !== 'no') $fields['zip_code'] = array('title'=>A::t('app', 'Zip Code'), 'validation'=>array('required'=>($this->_view->fieldZipCode == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32));
					if($this->_view->fieldCountry !== 'no') $fields['country_code'] = array('title'=>A::t('app', 'Country'), 'validation'=>array('required'=>($this->_view->fieldCountry == 'allow-required' ? true : false), 'type'=>'set', 'source'=>array_keys($this->_view->countries)));
					if($this->_view->fieldState !== 'no') $fields['state'] = array('title'=>A::t('app', 'State/Province'), 'validation'=>array('required'=>($this->_view->fieldState == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64));
					$fields['username'] = array('title'=>A::t('app', 'Username'), 'validation'=>array('required'=>true, 'type'=>'login', 'minLength'=>6, 'maxLength'=>25));
					$fields['password'] = array('title'=>A::t('app', 'Password'), 'validation'=>array('required'=>true, 'type'=>'password', 'minLength'=>6, 'maxLength'=>20));
					if($this->_view->fieldConfirmPassword !== 'no') $fields['confirm_password'] = array('title'=>A::t('app', 'Confirm Password'), 'validation'=>array('required'=>true, 'type'=>'confirm', 'confirmField'=>'password', 'minLength'=>6, 'maxLength'=>20));
					$captcha = $cRequest->getPost('captcha');
	
					$result = CWidget::create('CFormValidation', array(
						'fields' => $fields
					));
					if($result['error']){
						$arr[] = '"status": "0"';
						$arr[] = '"error": "'.$result['errorMessage'].'"';
					}elseif($this->_view->fieldCaptcha !== 'no' && $captcha === ''){
						$arr[] = '"status": "0"';
						$arr[] = '"error_field": "captcha_validation"';
						$arr[] = '"error": "'.A::t('users', 'The field captcha cannot be empty!').'"';            
					}elseif($this->_view->fieldCaptcha !== 'no' && $captcha != A::app()->getSession()->get('captchaResult')){
						$arr[] = '"status": "0"';
						$arr[] = '"error_field": "captcha_validation"';
						$arr[] = '"error": "'.A::t('users', 'Sorry, the code you have entered is invalid! Please try again.').'"';
					// These operations is done in model class
					//}elseif(Accounts::model()->count('role = :role AND email = :email', array(':role'=>'user', ':email'=>$cRequest->getPost('email')))){
					//    $arr[] = '"status": "0"';
					//    $arr[] = '"error_field": "email"';
					//    $arr[] = '"error": "'.A::t('users', 'User with such email already exists!').'"'; 
					//}elseif(Accounts::model()->count('role = :role AND username = :username', array(':role'=>'user', ':username'=>$cRequest->getPost('username')))){
					//    $arr[] = '"status": "0"';
					//    $arr[] = '"error_field": "username"';
					//    $arr[] = '"error": "'.A::t('users', 'User with such username already exists!').'"';
					}else{ 
						$username = $cRequest->getPost('username');
						$password = $cRequest->getPost('password');

						// Check if access is blocked to this username 
						$usernameBanned = Website::checkBan('username', $username, $errors);
						if($usernameBanned){
							// Do nothing			
							$arr[] = '"status": "0"';
							$arr[] = '"error": "'.$errors['alert'].'"';
						}else{
							// Password encryption 
							if(CConfig::get('password.encryption')){
								$encryptAlgorithm = CConfig::get('password.encryptAlgorithm');
								$hashKey = CConfig::get('password.hashKey');
								$passwordEncrypted = CHash::create($encryptAlgorithm, $password, $hashKey);
							}else{
								$passwordEncrypted = $password;
							}
		
							$user = new Users();
							$user->group_id = (int)UserGroups::model()->findPk('is_default = 1');
							$user->first_name = $cRequest->getPost('first_name');
							$user->last_name = $cRequest->getPost('last_name');
							$user->birth_date = $cRequest->getPost('birth_date');     
							$user->website = $cRequest->getPost('website');
							$user->company = $cRequest->getPost('company');            
							$user->phone = $cRequest->getPost('phone');
							$user->fax = $cRequest->getPost('fax');                        
							$user->address = $cRequest->getPost('address');
							$user->address_2 = $cRequest->getPost('address_2');            
							$user->city = $cRequest->getPost('city');
							$user->zip_code = $cRequest->getPost('zip_code');
							$user->country_code = $cRequest->getPost('country_code');             
							$user->state = $cRequest->getPost('state');
							
							$accountCreated = false;
							if($user->save()){
								$user = $user->refresh();
								
								// Update accounts table
								$account = Accounts::model()->findByPk((int)$user->account_id);
								if($approvalType == 'by_admin'){
									$account->registration_code = CHash::getRandomString(20);
									$account->is_active = 0;
								}elseif($approvalType == 'by_email'){
									$account->registration_code = CHash::getRandomString(20);
									$account->is_active = 0;
								}else{
									$account->registration_code = '';
									$account->is_active = 1;
								}
								if($account->save()){
									$accountCreated = true;
								}
							}
							
							if(!$accountCreated){
								$arr[] = '"status": "0"';
								if(APPHP_MODE == 'demo'){
									$arr[] = '"error": "'.A::t('core', 'This operation is blocked in Demo Mode!').'"';
								}else{
									$arr[] = '"error": "'.(($user->getError() != '') ? $user->getError() : A::t('users', 'An error occurred while creating user account! Please try again later.')).'"';    
									$arr[] = '"error_field": "'.$user->getErrorField().'"';
								}                           
							}else{
								$firstName = $user->first_name;
								$lastName = $user->last_name;
								if($user->first_name == '' && $user->last_name == '') $lastName = A::t('users', 'user');
								$userEmail = $cRequest->getPost('email');
								$emailResult = true;
		
								// Send notification to admin about new registration
								if(ModulesSettings::model()->param('users', 'new_registration_alert')){
									$adminLang = '';
									if($defaultLang = Languages::model()->find('is_default = 1')){
										$adminLang = $defaultLang->code;
									}
									$emailResult = Website::sendEmailByTemplate(
										$this->_settings->general_email,
										'users_account_created_notify_admin',
										$adminLang,
										array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USER_EMAIL}' => $userEmail, '{USERNAME}' => $username)
									);
								}                        
								
								// Send email to user according to approval type
								if(!empty($userEmail)){
									if($approvalType == 'by_admin'){
										// Approval by admin
										$emailResult = Website::sendEmailByTemplate(
											$userEmail,
											'users_account_created_admin_approval',
											A::app()->getLanguage(),
											array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USERNAME}' => $username, '{PASSWORD}' => $password)
										);
									}elseif($approvalType == 'by_email'){
										// Confirmation by email                            
										$emailResult = Website::sendEmailByTemplate(
											$userEmail,
											'users_account_created_email_confirmation',
											A::app()->getLanguage(),
											array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USERNAME}' => $username, '{PASSWORD}' => $password, '{REGISTRATION_CODE}' => $account->registration_code)
										);
									}else{
										// Auto approval
										$emailResult = Website::sendEmailByTemplate(
											$userEmail,
											'users_account_created_auto_approval',
											A::app()->getLanguage(),
											array('{FIRST_NAME}' => $firstName, '{LAST_NAME}' => $lastName, '{USERNAME}' => $username, '{PASSWORD}' => $password)
										);
									}                                                    
								}
								if(!$emailResult){
									$arr[] = '"status": "1"';
									$arr[] = '"error": "'.A::t('users', 'Your account has been successfully created, but email not sent! Please try again later.').'"';
								}else{
									$arr[] = '"status": "1"';							
								}
							}
						}
					}
				}
			}

            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
            header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
            header('Pragma: no-cache'); // HTTP/1.0
            header('Content-Type: application/json');
    
            echo '{';
            echo implode(',', $arr);
            echo '}';
        
            exit;
        }else{            
            if($approvalType == 'by_admin'){
                $messageSuccess = A::t('users', 'Account successfully created! Admin approval required.');                
            }elseif($approvalType == 'by_email'){
                $messageSuccess = A::t('users', 'Account successfully created! Email confirmation required.');
            }else{
                $messageSuccess = A::t('users', 'Account successfully created!');                
            }
            $this->_view->messageSuccess = $messageSuccess;
        }
        
        $this->_view->render('users/registration');
    }    

    /**
     * User restore password action handler
     * @return void
     */
    public function restorePasswordAction()
    {
        // Redirect logged in users
		CAuth::handleLoggedIn('users/dashboard', 'user');

        // Check if action allowed
        if(!ModulesSettings::model()->param('users', 'allow_restore_password')){
            $this->redirect(Website::getDefaultPage());
        }
        
        // Set frontend mode
        Website::setFrontend();

		$errors = array();
        $cRequest = A::app()->getRequest();
		
		if($cRequest->getPost('act') == 'send'){
            
			// Check if access is blocked to this IP address
			$ipBanned = Website::checkBan('ip_address', $cRequest->getUserHostAddress(), $errors);
			if($ipBanned){
				$alert = $errors['alert'];
				$alertType = $errors['alertType'];
			}else{
				$email = $cRequest->getPost('email');
				$alertType = '';
				$alert = '';
				
				// Check if access is blocked to this email
				$emailBanned = Website::checkBan('email_address', $email, $errors);
				if($emailBanned){
					// do nothing			
					$alert = $errors['alert'];
					$alertType = $errors['alertType'];
				}else{
					if(empty($email)){    
						$alertType = 'validation';
						$alert = A::t('users', 'The field email cannot be empty!');
					}elseif(!empty($email) && !CValidator::isEmail($email)){
						$alertType = 'validation';
						$alert = A::t('users', 'You must provide a valid email address!');
					}elseif(APPHP_MODE == 'demo'){
						$alertType = 'warning';
						$alert = A::t('core', 'This operation is blocked in Demo Mode!');
					}else{
						$account = Accounts::model()->find('role = :role AND email = :email', array(':role'=>'user', ':email'=>$email));
						if(empty($account)){    
							$alertType = 'error';                
							$alert = A::t('users', 'Sorry, but we were not able to find a user with that login information!');
						}else{
							$username = $account->username;
							$preferredLang = $account->language_code;
							// Generate new password
							if(CConfig::get('password.encryption')){
								$password = CHash::getRandomString(8);
								$account->password = CHash::create(CConfig::get('password.encryptAlgorithm'), $password, $account->salt);
								if(!$account->save()){
									$alertType = 'error';                
									$alert = A::t('users', 'An error occurred while password recovery process! Please try again later.');
								}
							}else{
								$password = $account->password;
							}
		
							if(!$alert){                        
								$result = Website::sendEmailByTemplate(
									$email,
									'users_password_forgotten',
									$preferredLang,
									array(
										'{USERNAME}' => $username,
										'{PASSWORD}' => $password
									)
								);
								if($result){
									$alertType = 'success';                
									$alert = A::t('users', 'A new password has been sent! Check your e-mail address linked to the account for the confirmation link, including the spam or junk folder.');
								}else{
									$alertType = 'error';
									$alert = A::t('users', 'An error occurred while password recovery process! Please try again later.');
								}                        
							}
						}
					}					
				}
			}
            
            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
            }
        }
        
        $this->_view->render('users/restorePassword');        
    }
    
    /**
     * User confirm registration action handler
     * @param string $code
     */
    public function confirmRegistrationAction($code)
    {
        // Redirect logged in users
		CAuth::handleLoggedIn('users/dashboard', 'user');

        if($user = Accounts::model()->find('is_active = 0 AND registration_code = :code', array(':code'=>$code))){
            $user->is_active = 1;
            $user->registration_code = '';
            if($user->save()){
                $alertType = 'success';
                $alert = A::t('users', 'Account registration confirmed');                
            }else{
                if(APPHP_MODE == 'demo'){
                    $alertType = 'warning';
                    $alert = CDatabase::init()->getErrorMessage();
                }else{
                    $alertType = 'error';
                    $alert = A::t('users', 'Account registration error');
                }           
            }
        }else{
            $alertType = 'warning';
            $alert = A::t('users', 'Account registration wrong code');
        }
        
        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }
        $this->_view->render('users/confirmRegistration');
    }

    /**
     * Dashboard action handler
     */
  	public function dashboardAction()
	{
        // Block access to this controller for not-logged users
		CAuth::handleLogin('users/login', 'user');
		// Set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('users', 'Dashboard')));
        // Set frontend settings
        Website::setFrontend();         
        
        $this->_view->render('users/dashboard');        
    }
        
    /**
     * User edit Account action handler
     */
    public function myAccountAction()
    {
        // Block access to this controller for not-logged users
		CAuth::handleLogin('users/login', 'user');
		// Set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('users', 'My Account')));
        // Set frontend settings
        Website::setFrontend();         
         
        $user = $this->_checkAccountsAccess(A::app()->getSession()->get('loggedId'));             
        $this->_prepareAccountFields(); 
        // Fetch datetime format from settings table
        $dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
        $dateFormat = Bootstrap::init()->getSettings('date_format');

        $this->_view->user = $user;
        // Prepare some fields
        $this->_view->countryName = $user->country_code;
        $this->_view->stateName = $user->state;
        $this->_view->langName = $user->language_code;
        $this->_view->notifications = ($user->notifications) ? A::t('users', 'Yes') : A::t('users', 'No');
        $this->_view->birthDate = (!CTime::isEmptyDate($user->birth_date)) ? date($dateFormat, strtotime($user->birth_date)) : '';
        $this->_view->createdAt = ($user->created_at) ? date($dateTimeFormat, strtotime($user->created_at)) : '';
        $this->_view->lastVisitedAt = ($user->last_visited_at) ? date($dateTimeFormat, strtotime($user->last_visited_at)) : '';
        if($country = Countries::model()->find('code = :code', array(':code'=>$user->country_code))){
            $this->_view->countryName = $country->country_name;
        }        
        if($state = States::model()->find('country_code = :country_code AND code = :code', array(':country_code'=>$user->country_code, ':code'=>$user->state))){
            $this->_view->stateName = $state->state_name;
        }
        if($language = Languages::model()->find('code = :code', array(':code'=>$user->language_code))){
            $this->_view->langName = $language->name;
        }

        $this->_view->render('users/myAccount');
    }           

    /**
     * User edit Account action handler
     */
    public function editAccountAction()
    {
        // Block access to this controller for not-logged users
		CAuth::handleLogin('users/login', 'user');
		// Set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('users', 'Edit Account')));
        // Set frontend settings
        Website::setFrontend();
        
        $user = $this->_checkAccountsAccess(A::app()->getSession()->get('loggedId'));             
        $this->_prepareAccountFields();

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            $this->_view->countryCode = $cRequest->getPost('country_code');             
            $this->_view->stateCode = $cRequest->getPost('state');
        }else{
            $this->_view->countryCode = $user->country_code;
            $this->_view->stateCode = $user->state;
        }
        
		// Prepare salt
		$this->_view->salt = '';
		if(A::app()->getRequest()->getPost('password') != ''){
			$this->_view->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
			A::app()->getRequest()->setPost('salt', $this->_view->salt);
		}		

        $this->_view->id = $user->id;          
        // Fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
        $this->_view->render('users/editAccount');
    }
    
    /**
     * User remove account action handler
     */
    public function removeAccountAction()
    {
        // Block access to this controller for not-logged users
		CAuth::handleLogin('users/login', 'user');
		// Set meta tags according to active language
		Website::setMetaTags(array('title'=>A::t('users', 'Remove Account')));
        // Set frontend settings
        Website::setFrontend();

        $loggedId = A::app()->getSession()->get('loggedId');
        $user = $this->_checkAccountsAccess($loggedId);             
        $alertType = '';
        $alert = '';
        $this->_view->accountRemoved = false;

        $cRequest = A::app()->getRequest();
        if($cRequest->isPostRequest()){
            if($cRequest->getPost('act') != 'send'){            
                $this->redirect('users/myAccount');
            }elseif(APPHP_MODE == 'demo'){
                $alertType = 'warning';
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
            }else{
                
                // Add removing account here
                $removalType = ModulesSettings::model()->param('users', 'removal_type');
                $this->_view->accountRemoved = true;
                if($removalType == 'logical' || $removalType == 'physical_or_logical'){
                    if(!Accounts::model()->updateByPk($loggedId, array('is_removed'=>1, 'is_active'=>0))){
                        $this->_view->accountRemoved = false;
                    }
                }elseif($removalType == 'physical'){
                    if(!Accounts::model()->deleteByPk($loggedId)){
                        $this->_view->accountRemoved = false;
                    }
                }
        
                if($this->_view->accountRemoved){
                    $alertType = 'success';                            
                    $alert = A::t('users', 'Your account has been successfully removed!');

                    $result = Website::sendEmailByTemplate(
                        CAuth::getLoggedEmail(),
                        'users_account_removed_by_user',
                        CAuth::getLoggedLang(),
                        array('{USERNAME}' => CAuth::getLoggedName())
                    );

                    $this->_logout();
                }else{
                    $alertType = 'error';
                    $alert = A::t('users', 'An error occurred while deleting your account! Please try again later.');
                }
            }           
        }           

        if(!empty($alert)){				
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }
        $this->_view->render('users/removeAccount');
    }
    
    /**
     * Check if passed Account ID is valid
     * @param int $id
     */
    private function _checkAccountsAccess($id = 0)
    {
		// TODO? '.CConfig::get('db.prefix').'accounts.is_active = 1
        $users = Users::model()->find('account_id = :account_id AND is_active = 1', array('i:account_id'=>$id));		
        if(!$users){
            $this->redirect('users/manage');
        }    
        return $users;
    } 

    /**
     * Check if passed User ID is valid
     * @param int $id
     */
    private function _checkUsersAccess($id = 0)
    {        
        $users = Users::model()->findByPk($id);
        if(!$users){
            $this->redirect('users/manage');
        }    
        return $users;
    } 
    
    /**
     * Prepares account fields 
     */
    private function _prepareAccountFields()
    {
        $this->_view->fieldFirstName = ModulesSettings::model()->param('users', 'field_first_name');
        $this->_view->fieldLastName = ModulesSettings::model()->param('users', 'field_last_name');
        $this->_view->fieldBirthDate = ModulesSettings::model()->param('users', 'field_birth_date');
        $this->_view->fieldWebsite = ModulesSettings::model()->param('users', 'field_website');
        $this->_view->fieldCompany = ModulesSettings::model()->param('users', 'field_company');

        $this->_view->fieldPhone = ModulesSettings::model()->param('users', 'field_phone');
        $this->_view->fieldFax = ModulesSettings::model()->param('users', 'field_fax');
        $this->_view->fieldEmail = ModulesSettings::model()->param('users', 'field_email');
        
        $this->_view->fieldAddress = ModulesSettings::model()->param('users', 'field_address');
        $this->_view->fieldAddress2 = ModulesSettings::model()->param('users', 'field_address_2');
        $this->_view->fieldCity = ModulesSettings::model()->param('users', 'field_city');
        $this->_view->fieldZipCode = ModulesSettings::model()->param('users', 'field_zip_code');
        $this->_view->fieldCountry = ModulesSettings::model()->param('users', 'field_country');
        $this->_view->fieldState = ModulesSettings::model()->param('users', 'field_state');
       
        $this->_view->fieldNotifications = ModulesSettings::model()->param('users', 'field_notifications'); 
        $this->_view->fieldUsername = ModulesSettings::model()->param('users', 'field_username');
        $this->_view->fieldPassword = ModulesSettings::model()->param('users', 'field_password');
        $this->_view->fieldConfirmPassword = ModulesSettings::model()->param('users', 'field_confirm_password');
        $this->_view->fieldCaptcha = ModulesSettings::model()->param('users', 'field_captcha');
        
        $this->_view->removalType = ModulesSettings::model()->param('users', 'removal_type');
        $this->_view->changePassword = ModulesSettings::model()->param('users', 'change_user_password');
        
        // Prepare groups 
        $groups = array();
        $groupsResult = UserGroups::model()->findAll();
        $this->_view->defaultGroupId = 0;
        if(is_array($groupsResult)){
            if(count($groupsResult)) $groups = array(''=>'');
            foreach($groupsResult as $key => $val){
                $groups[$val['id']] = $val['name'];
                if($val['is_default']) $this->_view->defaultGroupId = $val['id'];
            }
        }
        $this->_view->groups = $groups;

        // Prepare countries
        $countries = array(''=>A::t('users', '- select -'));
        $countriesResult = Countries::model()->findAll(array('condition'=>'is_active = 1', 'order'=>'sort_order DESC, country_name ASC'));
        $this->_view->defaultCountryCode = '';
        if(is_array($countriesResult)){
            foreach($countriesResult as $key => $val){
                $countries[$val['code']] = $val['country_name'];
                if($val['is_default']) $this->_view->defaultCountryCode = $val['code'];
            }
        }
        $this->_view->countries = $countries;                

        // Prepare languages
        $langList = array();
        $languagesResult = Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'));
		if(is_array($languagesResult)){
			foreach($languagesResult as $lang){
	        	$langList[$lang['code']] = $lang['name_native'];
	        }
	  	}
	  	$this->_view->langList = $langList;

    }
    
    /**
     * User logout 
     */
    private function _logout()
    {        
        A::app()->getSession()->endSession();
        A::app()->getCookie()->remove('userAuth');
        // Clear cache
        if(CConfig::get('cache.enable')) CFile::emptyDirectory(CConfig::get('cache.path'));
    }
   
}