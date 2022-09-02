<?php
/**
 * Accounts model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct											_selfModel (static)
 * __callStatic											_parentModel (static)
 * model (static)
 * login
 * getPasswordSalt
 * getErrorDescription
 * registrationSocial
 *
 */

class Accounts extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'accounts';
    /** @var bool */
    private $_isError = false;    
    /** @var string */    
    private $_passwordSalt = '';
    /** @var string */    
    private $_errorDescription = '';

    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Triggered when invoking inaccessible methods in an object context
	 * We use this method to avoid calling model($className = __CLASS__) in derived class
	 * @param string $method
	 * @param array $args
	 * @version PHP 5.3.0 or higher
	 * @return mixed
	 */
	public static function __callStatic($method, $args)
	{
        if(strtolower($method) == 'model'){
            if(count($args) == 0){
				return self::_selfModel();
			}else{
				return self::_parentModel($args[0]);
            }
		}		
	}

    ///**
    // * Returns the static model of the specified AR class
	// * @version PHP 5.2.x 
    // */
    //public static function model($class = __CLASS__)
    //{
    //    return parent::model($class);
    //}
    
    /**
     * Account login
     * @param string $username
     * @param string $password
     * @param mixed $roles
     * @param bool $checkRememberMe
     * @param bool $saveTokenExpires
     */
    public function login($username, $password, $roles = '', $checkRememberMe = false, $saveTokenExpires = false)
    {
        $this->_db->cacheOff();
		
		if(!empty($roles)){
			if(is_array($roles)){
				$roles_in = array('-1');
				foreach($roles as $role){
					$roles_in[] = "'$role'";
				}
				$account = $this->find('role IN('.implode(',', $roles_in).') AND username = :username', array(':username' => $username));	
			}else{
				$account = $this->find('role = :role AND username = :username', array(':role' => $roles, ':username' => $username));	
			}
			
		}else{
			$account = $this->find('username = :username', array(':username' => $username));
		}

        if($account){
            if($account->is_active){
				
				// Prepare password for check
				if($checkRememberMe){
					// Check if token is not expired
					if($account->token_expires_at != '' && time() > $account->token_expires_at){
						$savedPassword = true;
						$checkPassword = false;
						
						// Update token expires date in account record
						$account->token_expires_at = '';
						$account->save();						
					}else{
						if(CConfig::get('password.encryption')){
							// We use ID + username + salt + HTTP_USER_AGENT
							$checkSalt = CConfig::get('password.encryptSalt') ? $account->salt : '';
							$httpUserAgent = A::app()->getRequest()->getUserAgent();
							$checkPassword = CHash::create(CConfig::get('password.encryptAlgorithm'), $account->id.$account->username.$checkSalt.$httpUserAgent);
						}else{
							$checkPassword = $password;
						}
						$savedPassword = $password;						
					}					
				}else{					
					if(CConfig::get('password.encryption')){
						$checkSalt = CConfig::get('password.encryptSalt') ? $account->salt : '';
						$checkPassword = CHash::create(CConfig::get('password.encryptAlgorithm'), $password, $checkSalt);
					}else{
						$checkPassword = $password;
					}
					$savedPassword = $account->password;
				}
				
				if(CHash::equals($savedPassword, $checkPassword)){
					$session = A::app()->getSession();
					$session->set('loggedIn', true);
					$session->set('loggedId', $account->id);
					$session->set('loggedName', $account->username);
					$session->set('loggedEmail', $account->email);
					$session->set('loggedLastVisit', $account->last_visited_at);
					if($account->isColumnExists('avatar')) $session->set('loggedAvatar', $account->avatar);
					$session->set('loggedLanguage', ($account->language_code ? $account->language_code : Languages::model()->getDefaultLanguage()));
					$session->set('loggedRole', $account->role);
							  
					// We don't want to save this data in session - just in this object to minimum use
					$this->_passwordSalt = $account->salt;

					// Set current language
					if($accountLang = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$account->language_code))){
						$params = array(
							'locale' => $accountLang->lc_time_name,
							'direction' => $accountLang->direction
						);
						A::app()->setLanguage($account->language_code, $params);
					}
	
					// Update last visited and token expires dates in account record
					$account->last_visited_at = LocalTime::currentDateTime();
					$account->last_visited_ip = A::app()->getRequest()->getUserHostAddress();
					$account->token_expires_at = ($saveTokenExpires ? (time() + 3600 * 24 * 14) : '');
					$account->save();						

					return true;
				}else{
					$this->_errorDescription = A::t('app', 'Login Error Message');
				}			
            }else{
                if($account->registration_code != ''){
                    $this->_errorDescription = A::t('app', 'Login Not Approval Message');
                }else{
                    $this->_errorDescription = A::t('app', 'Login Inactive Message');
                }
            }
        }else{
            $this->_errorDescription = strip_tags(A::t('app', 'Login Error Message'));            
        }
		
        return false;        
    } 
    
	/** 
	 * Returns account password salt
	 * @return boolean
	 */
	public function getPasswordSalt()
	{
		return $this->_passwordSalt;
	}

	/** 
	 * Returns error description
	 * @return boolean
	 */
	public function getErrorDescription()
	{
		return $this->_errorDescription;
	}

    /*
     * Social Registration
     * @param array $params
     * @return void
     */
    public function registrationSocial($params = array())
    {
        
    }

    /**
     * Returns the static model of the specified AR class
     */
    public static function _selfModel()
    {
        return parent::model(__CLASS__);
    }

	/**
	 * Returns the static model of the specified AR class
	 * @param string $className
	 */
	private static function _parentModel($className = __CLASS__)
	{
		return parent::model($className);
    }
	
}
