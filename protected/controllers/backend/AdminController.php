<?php
/**
 * Admin controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct
 * indexAction
 * loginAction
 * logoutAction
 * resetPasswordAction
 * restorePasswordAction
 *
 */

class AdminController extends CController
{
	private $_checkBruteforce;
	private $_redirectDelay;
	private $_badLogins;
	private $_badRestores;
	private $_badResetPasswords;
	private $_backendPath = '';

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Admin Panel')));
        // Set backend mode
        Website::setBackend();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
        $this->_view->backendPath = $this->_backendPath;

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		//#000
		$this->_checkBruteforce = CConfig::get('validation.bruteforce.enable');
		$this->_redirectDelay = (int)CConfig::get('validation.bruteforce.redirectDelay', 3);
		$this->_badLogins = (int)CConfig::get('validation.bruteforce.badLogins', 5);

		$this->_badResetPasswords = 10;
		$this->_badRestores = 10;
	}

	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect($this->_backendPath.'admin/login');
    }

    /**
     * Admin login action handler
     */
	public function loginAction()
	{
        // Redirect logged in admins
		CAuth::handleLoggedIn($this->_backendPath.'dashboard');

        // Set default language for backend
        Website::setDefaultLanguage();

		$this->_view->username = $this->_cRequest->getPost('username');
		$this->_view->password = $this->_cRequest->getPost('password');
        $this->_view->remember = $this->_cRequest->getPost('remember');
		$alert = A::t('app', 'Login Message');
        $alertType = '';
		$errors = array();

		$admin = new Admins();

		// Check if access is blocked to this IP address
		$ipBanned = Website::checkBan('ip_address', $this->_cRequest->getUserHostAddress(), $errors);
        if($ipBanned){
			// do nothing
			$this->_view->actionMessage = CWidget::create('CMessage', array($errors['alertType'], $errors['alert']));
		}else{
			// -------------------------------------------------
			// Perform auto-login "remember me"
			// --------------------------------------------------
			if(!CAuth::isLoggedIn()){
				parse_str(A::app()->getCookie()->get('auth'), $output);
				if(!empty($output['usr']) && !empty($output['hash'])){
					$username = CHash::decrypt($output['usr'], CConfig::get('password.hashKey'));
					$password = $output['hash'];

					// Check if access is blocked to this username
					$usernameBanned = Website::checkBan('username', $username);
					if($usernameBanned){
						// do nothing
					}elseif(!$usernameBanned && $admin->login($username, $password, true, true)){
						$this->redirect($this->_backendPath.'dashboard/index');
					}
				}
			}

			// -------------------------------------------------
			// Handle form submission
			// --------------------------------------------------
			if($this->_cRequest->getPost('act') == 'send'){
				// Perform login form validation
				$result = CWidget::create('CFormValidation', array(
					'fields'=>array(
						'username'=>array('title'=>A::t('app', 'Username'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>32)),
						'password'=>array('title'=>A::t('app', 'Password'), 'validation'=>array('required'=>true, 'type'=>'any', 'minLength'=>4, 'maxLength'=>25)),
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
								$httpUserAgent = $this->_cRequest->getUserAgent();
								$passwordHash = CHash::create(CConfig::get('password.encryptAlgorithm'), $admin->id.$admin->username.$admin->getPasswordSalt().$httpUserAgent);
								A::app()->getCookie()->set('auth', 'usr='.$usernameHash.'&hash='.$passwordHash, (time() + 3600 * 24 * 14));
							}
							//#001 clean login attempts counter
							if($this->_checkBruteforce){
								A::app()->getSession()->remove('loginAttempts');
								A::app()->getCookie()->remove('loginAttemptsAuth');
							}
							$this->redirect($this->_backendPath.'dashboard');
						}else{
							$alert = $admin->getErrorDescription();
							$alertType = 'error';
							$this->_view->errorField = 'username';
						}
					}
				}

				if(!empty($alert)){
					$this->_view->username = $this->_cRequest->getPost('username');
					$this->_view->password = $this->_cRequest->getPost('password');
					$this->_view->remember = $this->_cRequest->getPost('remember', 'string');
					$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));

					//#002 increment login attempts counter
					if($this->_checkBruteforce && $this->_view->username != '' && $this->_view->password != ''){
						$logAttempts = A::app()->getSession()->get('loginAttempts', 1);
						if($logAttempts < $this->_badLogins){
							A::app()->getSession()->set('loginAttempts', $logAttempts+1);
						}else{
							A::app()->getCookie()->set('loginAttemptsAuth', md5(uniqid('', true)));
							sleep($this->_redirectDelay);
							$this->redirect($this->_backendPath.'admin/login');
						}
					}
				}
			}else{
				//#003 validate login attempts cookie
				if($this->_checkBruteforce){
					$logAttempts = A::app()->getSession()->get('loginAttempts', 1);
					$logAttemptsAuthCookie = A::app()->getCookie()->get('loginAttemptsAuth');
					$logAttemptsAuthPost = $this->_cRequest->getPost('loginAttemptsAuth');
					if($logAttempts >= $this->_badLogins){
						if($logAttemptsAuthCookie != '' && $logAttemptsAuthCookie == $logAttemptsAuthPost){
							A::app()->getSession()->remove('loginAttempts');
							A::app()->getCookie()->remove('loginAttemptsAuth');
							$this->redirect($this->_backendPath.'admin/login');
						}
					}
				}

				$this->_view->actionMessage = CWidget::create('CMessage', array('info', $alert));
			}
		}

		$this->_view->render($this->_backendPath.'admin/login');
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
		if(CConfig::get('cache.db.enable')){
			CFile::emptyDirectory(CConfig::get('cache.db.path'), array('index.html'));
		}

		$this->redirect($this->_backendPath.'admin/login');
	}

    /**
     * Admin password reset action handler
     * @param string $hash
     * @return void
     */
    public function resetPasswordAction($hash = '')
    {
        // Redirect logged in admins
		CAuth::handleLoggedIn($this->_backendPath.'dashboard');
		// Set backend mode
        Website::setBackend();

        if(CConfig::get('restoreAdminPassword.restoreType') !== 'reset') {
			$this->redirect($this->_backendPath.'admin/login');
		}

		$alertType = 'info';
		$alert = A::t('app', 'Enter a new password and confirm the new password.');

		$this->_view->id = '';
		$this->_view->urlParams = '';
		$this->_view->showForm = true;
		$this->_view->password = '';
		$this->_view->confirmPassword = '';

		$hashDescrypted = str_replace(array('-', '_', ','), array('+', '/', '='), CHash::decrypt($hash, CConfig::get('password.hashKey'), 'aes-128-cbc'));
		$hashParts	 = explode('|', $hashDescrypted);
		$id = !empty($hashParts[0]) ? $hashParts[0] : '';
		$hashCode = !empty($hashParts[1]) ? $hashParts[1] : '';

		// Validate parameters
		if(empty($id) || empty($hashCode)){
			$alertType = 'error';
			$alert = A::t('app', 'Wrong parameter passed! Please try again later.');
			$this->_view->showForm = false;
		}elseif(!CValidator::isInteger($id) || !CValidator::isAlphaNumeric($hashCode) || CString::length($hashCode) != 20){
			$alertType = 'error';
			$alert = A::t('app', 'Wrong parameter passed! Please try again later.');
			$this->_view->showForm = false;
		}else{
			// Check if such admin exists
			$admin = Admins::model()->find('id = :id AND password_restore_hash = :password_hash', array(':id'=>$id, ':password_hash'=>$hashCode));
			if(empty($admin)){
				$alertType = 'error';
				$alert = A::t('app', 'Sorry, but we were not able to find any admin with that login information!');
				$this->_view->showForm = false;
			}else{
				$this->_view->id = $id;
				$this->_view->urlParams = 'h/'.$hash;
				$urlParams = $this->_view->urlParams;

				// -------------------------------------------------
				// Handle form submission
				// --------------------------------------------------
				if($this->_cRequest->getPost('act') == 'send'){

					// Form Validation
					$result = CWidget::create('CFormValidation', array(
						'fields'=>array(
							'password'		   => array('title'=>A::t('app', 'New Password'), 'validation'=>array('required'=>true, 'type'=>'password', 'minLength'=>6, 'maxLength'=>25, 'simplePassword'=>false)),
							'password_confirm' => array('title'=>A::t('app', 'Confirm New Password'), 'validation'=>array('required'=>true, 'type'=>'confirm', 'confirmField'=>'password')),
						),
					));

					if($result['error']){
						$alertType = 'validation';
						$alert = $result['errorMessage'];
						$this->_cSession->setFlash('errorField', $result['errorField']);

						//#002 increment reset password attempts counter
						if($this->_checkBruteforce){
							$resetAttempts = A::app()->getSession()->get('resetAttempts', 1);
							if($resetAttempts < $this->_badResetPasswords){
								A::app()->getSession()->set('resetAttempts', $resetAttempts+1);
							}else{
								A::app()->getCookie()->set('resetAttemptsAuth', md5(uniqid('', true)));
								sleep($this->_redirectDelay);
								$this->redirect($this->_backendPath.'admin/resetPassword/'.$urlParams);
							}
						}
					}else {
						// Change passwords
						$password = $this->_cRequest->getPost('password');
						if(CConfig::get('password.encryption')){
							$admin->salt = CConfig::get('password.encryptSalt') ? CHash::salt() : '';
							$admin->password = CHash::create(CConfig::get('password.encryptAlgorithm'), $password, $admin->salt);
						}else{
							$admin->password = $password;
						}
						$admin->password_restore_hash = '';

						$alertType = 'error';
						$alert = A::t('app', 'An error occurred while password reset process! Please try again later.');
						if($admin->save()){
							$alertType = 'success';
							$alert = A::t('app', 'Your password was successfully changed! You may now sign in with your new password.', array('{SITE_BO_URL}'=>Website::getBackendPath()));

							//#001 clean reset password attempts counter
							if($this->_checkBruteforce){
								A::app()->getSession()->remove('resetAttempts');
								A::app()->getCookie()->remove('resetAttemptsAuth');
							}
						}
					}

					$this->_cSession->setFlash('alert', $alert);
					$this->_cSession->setFlash('alertType', $alertType);

					$this->redirect($this->_backendPath.'admin/resetPassword/'.$urlParams);
				}else{
					//#003 validate reset password attempts cookie
					if($this->_checkBruteforce){
						$resetAttempts = A::app()->getSession()->get('resetAttempts', 1);
						$resetAttemptsAuthCookie = A::app()->getCookie()->get('resetAttemptsAuth');
						$resetAttemptsAuthPost = $this->_cRequest->getPost('resetAttemptsAuth');
						if($resetAttempts >= $this->_badResetPasswords){
							if($resetAttemptsAuthCookie != '' && $resetAttemptsAuthCookie == $resetAttemptsAuthPost){
								A::app()->getSession()->remove('resetAttempts');
								A::app()->getCookie()->remove('resetAttemptsAuth');
								$this->redirect($this->_backendPath.'admin/resetPassword/'.$urlParams);
							}
						}
					}

					$this->_view->actionMessage = CWidget::create('CMessage', array('info', $alert));
				}

			}
		}

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->showForm = false;
			if($alertType == 'validation'){
				$this->_view->showForm = true;
				$this->_view->errorField = $this->_cSession->getFlash('errorField');
			}
		}

		if(!empty($alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>false)));
		}

		$this->_view->render($this->_backendPath.'admin/resetPassword');
	}

    /**
     * Admin restore password action handler
     * @return void
     */
    public function restorePasswordAction()
    {
        // Redirect logged in admins
		CAuth::handleLoggedIn($this->_backendPath.'dashboard');
        // Set default language for backend
        Website::setDefaultLanguage();

        // Check if action allowed
        if(!CConfig::get('restoreAdminPassword.enable')){
            $this->redirect($this->_backendPath);
        }

		$this->_view->email = $this->_cRequest->getPost('email');
		$alert = '';
        $alertType = '';
		$errors = array();

		if($this->_cRequest->getPost('act') == 'send'){

			// Check if access is blocked to this IP address
			$ipBanned = Website::checkBan('ip_address', $this->_cRequest->getUserHostAddress(), $errors);
			if($ipBanned){
				$alert = $errors['alert'];
				$alertType = $errors['alertType'];
			}else{
				$email = $this->_cRequest->getPost('email');
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
						$alert = A::t('app', 'The field email cannot be empty!');
					}elseif(!empty($email) && !CValidator::isEmail($email)){
						$alertType = 'validation';
						$alert = A::t('app', 'You must provide a valid email address!');
					}elseif(APPHP_MODE == 'demo'){
						$alertType = 'warning';
						$alert = A::t('core', 'This operation is blocked in Demo Mode!');
					}else{
						$admin = Admins::model()->find("role IN('owner','mainadmin','admin') AND email = :email", array(':email'=>$email));
						if(empty($admin)){
							$alertType = 'error';
							$alert = A::t('app', 'Sorry, but we were not able to find any admin with that login information!');
						}else{
							$username = $admin->username;
							$password = '';
							$preferredLang = $admin->language_code;

					        // Check if recovery is allowed or direct sending of password
							if(CConfig::get('restoreAdminPassword.restoreType') === 'reset'){
								$restoreHashCode = CHash::getRandomString(20);
								$admin->password_restore_hash = $restoreHashCode;
								if(!$admin->save()){
									$alertType = 'error';
									$alert = A::t('app', 'An error occurred while password reset process! Please try again later.');
								}
							}else{
								// Generate new password
								if(CConfig::get('password.encryption')){
									$password = CHash::getRandomString(8);
									$admin->password = CHash::create(CConfig::get('password.encryptAlgorithm'), $password, $admin->salt);
									if(!$admin->save()){
										$alertType = 'error';
										$alert = A::t('app', 'An error occurred while password recovery process! Please try again later.');
									}
								}else{
									$password = $admin->password;
								}
							}

							if(empty($alert)){
								// Check if password restore is allowed
								if(CConfig::get('restoreAdminPassword.restoreType') == 'reset'){
									$result = Website::sendEmailByTemplate(
										$email,
										'bo_admin_password_forgotten_reset',
										$preferredLang,
										array(
											'{USERNAME}' => $username,
											'{HASH_CODE}' => 'h/'.str_replace(array('+', '/', '='), array('-', '_', ','), CHash::encrypt($admin->id.'|'.$restoreHashCode, CConfig::get('password.hashKey'), 'aes-128-cbc'))
										)
									);
								}else{
									$result = Website::sendEmailByTemplate(
										$email,
										'bo_admin_password_forgotten_renew',
										$preferredLang,
										array(
											'{USERNAME}' => $username,
											'{PASSWORD}' => $password
										)
									);
								}

								if($result){
									$alertType = 'success';
									$alert = A::t('app', 'A new password has been sent! Check your e-mail address linked to the account for the confirmation link, including the spam or junk folder.');
									$this->_view->email = '';

									//#001 clean restore attempts counter
									if($this->_checkBruteforce){
										A::app()->getSession()->remove('restoreAttempts');
										A::app()->getCookie()->remove('restoreAttemptsAuth');
									}
								}else{
									$alertType = 'error';
									$alert = A::t('app', 'An error occurred while password reset process! Please try again later.');
								}
							}
						}

						if(!empty($alert)){
							//#002 increment restore attempts counter
							if($this->_checkBruteforce){
								$restoreAttempts = A::app()->getSession()->get('restoreAttempts', 1);
								if($restoreAttempts < $this->_badRestores){
									A::app()->getSession()->set('restoreAttempts', $restoreAttempts+1);
								}else{
									A::app()->getCookie()->set('restoreAttemptsAuth', md5(uniqid('', true)));
									sleep($this->_redirectDelay);
									$this->redirect($this->_backendPath.'admin/restorePassword');
								}
							}
						}
					}
				}
			}
		}else{
			//#003 validate restore attempts cookie
			if($this->_checkBruteforce){
				$restoreAttempts = A::app()->getSession()->get('restoreAttempts', 1);
				$restoreAttemptsAuthCookie = A::app()->getCookie()->get('restoreAttemptsAuth');
				$restoreAttemptsAuthPost = $this->_cRequest->getPost('restoreAttemptsAuth');
				if($restoreAttempts >= $this->_badRestores){
					if($restoreAttemptsAuthCookie != '' && $restoreAttemptsAuthCookie == $restoreAttemptsAuthPost){
						A::app()->getSession()->remove('restoreAttempts');
						A::app()->getCookie()->remove('restoreAttemptsAuth');
						$this->redirect($this->_backendPath.'admin/restorePassword');
					}
				}
			}
		}

		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);
			$this->redirect($this->_backendPath.'admin/restorePassword');
		}else{
			if($this->_cSession->hasFlash('alert')){
				$alert = $this->_cSession->getFlash('alert');
				$alertType = $this->_cSession->getFlash('alertType');
			}else{
				$alert = A::t('app', 'Password Restore Message');
				$alertType = 'info';
			}
			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>false)));
		}

        $this->_view->render($this->_backendPath.'admin/restorePassword');
    }

}