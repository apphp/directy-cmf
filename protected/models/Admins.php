<?php
/**
 * Admins model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations                 	_loadPrivileges (static)
 * model (static)          	_customFields
 * login                   	_afterSave
 * getPasswordSalt
 * getErrorDescription
 * hasPrivilege (static)
 * hasPrivilegeByCollect (static)
 * hasPrivilegeByDb (static)
 * privilegeExists (static)
 * privilegeExistsByCollect (static)
 * privilegeExistsByDb (static)
 *
 */

class Admins extends CActiveRecord
{
    /** @var string */    
    protected $_table = 'admins';
	/** @var string */    
	private static $_privilegeProcessingType = 'collect'; /* collect |db */
	/** @var array */    
	private static $_arrAdminPrivileges = array();
    /** @var string */    
    private $_passwordSalt = '';
    /** @var string */    
    private $_errorDescription = '';
    /** @var bool */    
	private $_isLoginProcess = false;


    /**
	 * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }
	
	/**
     * Used to define custom fields
	 * This method should be overridden
	 */
	protected function _customFields()
	{
		return array(
			'CONCAT(first_name, " ", last_name)' => 'fullname'
		);
	}

	/**
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{
		return array();
	}
	
	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
    	if($id > 0){
    		// Update admin
			if($this->birth_date == ''){
				$this->birth_date = null;
			}
			if(!$this->_isLoginProcess){
				if($this->password !== '' && A::app()->getRequest()->getPost('password') !== ''){
					$this->password_changed_at = LocalTime::currentDateTime();
				}
				$this->updated_at = LocalTime::currentDateTime();				
			}
        }else{
        	// Insert new admin
			if($this->birth_date == '') $this->birth_date = null;
        	$this->created_at = LocalTime::currentDateTime();
        }
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		// Refresh logged-in session variables
		if($id == CAuth::getLoggedId()){
			$session = A::app()->getSession();
			$session->set('loggedName', ($this->display_name != '' ? $this->display_name : $this->username));
			$session->set('loggedAvatar', ($this->avatar != '' ? $this->avatar : 'no_image.png'));
		
    		// reset site language
            if($result = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$this->language_code))){
                $params = array(
                    'locale' => $result->lc_time_name,
                    'direction' => $result->direction
                );
                A::app()->setLanguage($this->language_code, $params);
                $session->set('loggedLanguage', $this->language_code);
			}
		}	
	}
	
    /**
     * Admin login
     * @param string $username
     * @param string $password
     * @param bool $checkRememberMe
     * @param bool $saveTokenExpires
     * @retun bool
     */
    public function login($username, $password, $checkRememberMe = false, $saveTokenExpires = false)
    {
        $this->_db->cacheOff();

		$admin = $this->find('username = :username', array(':username' => $username));
        
		if($admin){
			$this->_isLoginProcess = true;
			
			$isBanned = BanLists::model()->count(
				"item_type = 'email' AND item_value = :item_value AND is_active = 1 AND (expires_at IS NULL OR expires_at > :expires_at)",
				array(':item_value' => $admin->email, ':expires_at' => LocalTime::currentDateTime())
			);		

			if($isBanned){
				$this->_errorDescription = A::t('app', 'This email is banned.');
			}else{
				if($admin->is_active){					
					// Prepare password for check
					if($checkRememberMe){
						// Check if token is not expired
						if($admin->token_expires_at != '' && time() > $admin->token_expires_at){
							$savedPassword = true;
							$checkPassword = false;
							
							// Update token expires date in admin record
							$admin->token_expires_at = '';
							$admin->save();						
						}else{
							if(CConfig::get('password.encryption')){
								// We use ID + username + salt + HTTP_USER_AGENT
								$checkSalt = CConfig::get('password.encryptSalt') ? $admin->salt : '';
								$httpUserAgent = A::app()->getRequest()->getUserAgent();
								$checkPassword = CHash::create(CConfig::get('password.encryptAlgorithm'), $admin->id.$admin->username.$checkSalt.$httpUserAgent);
							}else{
								$checkPassword = $password;
							}
							$savedPassword = $password;						
						}					
					}else{					
						if(CConfig::get('password.encryption')){
							$checkSalt = CConfig::get('password.encryptSalt') ? $admin->salt : '';
							$checkPassword = CHash::create(CConfig::get('password.encryptAlgorithm'), $password, $checkSalt);
						}else{
							$checkPassword = $password;
						}
						$savedPassword = $admin->password;
					}
					
					if(CHash::equals($savedPassword, $checkPassword)){
						$session = A::app()->getSession();
						$session->set('loggedIn', true);
						$session->set('loggedId', $admin->id);
						$session->set('loggedName', ($admin->display_name ? $admin->display_name : $admin->username));
						$session->set('loggedAvatar', ($admin->avatar ? $admin->avatar : 'no_image.png'));
						$session->set('loggedEmail', $admin->email);
						$session->set('loggedLastVisit', $admin->last_visited_at);
						$session->set('loggedLanguage', ($admin->language_code ? $admin->language_code : Languages::model()->getDefaultLanguage()));
						$session->set('loggedRole', $admin->role);
						
						// We don't want to save this data in session - just in this object to minimum use
						$this->_passwordSalt = $admin->salt;
						
						// Set current language
						if($adminLang = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$admin->language_code))){
							$params = array(
								'locale' => $adminLang->lc_time_name,
								'direction' => $adminLang->direction
							);
							A::app()->setLanguage($admin->language_code, $params);
						}
			
						// Update last visited and token expires dates in admin record
						$admin->last_visited_at = LocalTime::currentDateTime();
						$admin->token_expires_at = ($saveTokenExpires ? (time() + 3600 * 24 * 14) : '');
						$admin->save();						
						
						return true;
					}else{
						$this->_errorDescription = A::t('app', 'Login Error Message');
					}			
				}else{
					$this->_errorDescription = A::t('app', 'Login Inactive Message');            
				}				
			}
        }else{
            $this->_errorDescription = A::t('app', 'Login Error Message');
        }
        
		return false;        
    }
	
	/** 
	 * Returns admin password salt
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

	/**
	 * Checks if a specific privilege exists
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function privilegeExists($privilegeCategory = '', $privilegeCode = '')
	{
		if(self::$_privilegeProcessingType == 'collect'){
			return self::privilegeExistsByCollect($privilegeCategory, $privilegeCode);
		}else{
			return self::privilegeExistsByDb($privilegeCategory, $privilegeCode);
		}
	}

	/**
	 * Checks if a specific privilege exists
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function privilegeExistsByCollect($privilegeCategory = '', $privilegeCode = '')
	{
		if(empty($privilegeCode)) return false;
		if(empty(self::$_arrAdminPrivileges)) self::_loadPrivileges();
		
		return isset(self::$_arrAdminPrivileges[$privilegeCategory][strtolower($privilegeCode)]) ? true : false;
	}

	/**
	 * Checks if a specific privilege exists
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function privilegeExistsByDb($privilegeCategory = '', $privilegeCode = '')
	{
		if($result = RolePrivileges::model()->find(
		    CConfig::get('db.prefix').'privileges.category = :privilege_category AND
		  '.CConfig::get('db.prefix').'privileges.code = :privilege_code', 
			array(':privilege_category'=>$privilegeCategory, ':privilege_code'=>$privilegeCode)
		)){
            return true;    
        }else{
            return false;    
        }
		
	}

	/**
	 * Checks if a given admin has specific privilege
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function hasPrivilege($privilegeCategory = '', $privilegeCode = '')
	{
		if(self::$_privilegeProcessingType == 'collect'){
			return self::hasPrivilegeByCollect($privilegeCategory, $privilegeCode);
		}else{
			return self::hasPrivilegeByDb($privilegeCategory, $privilegeCode);
		}
	}
	
	/**
	 * Checks if a given admin has specific privilege
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function hasPrivilegeByCollect($privilegeCategory, $privilegeCode = '')
	{
 		if(empty($privilegeCode)) return false;
		if(empty(self::$_arrAdminPrivileges)) self::_loadPrivileges();
		
		$privilegeCodeString = '';
		if(is_array($privilegeCode)){
			foreach($privilegeCode as $key => $val){
				if(isset(self::$_arrAdminPrivileges[$privilegeCategory][$val]) && (bool)self::$_arrAdminPrivileges[$privilegeCategory][$val] == true){
					return true;			
				}
			}
		}else{
			$privilegeCode = strtolower($privilegeCode);
			if(isset(self::$_arrAdminPrivileges[$privilegeCategory][$privilegeCode]) && (bool)self::$_arrAdminPrivileges[$privilegeCategory][$privilegeCode] == true){
				return true;			
			}
		}
		return false;
	}	

	/**
	 * Checks if a given admin role has specific privilege
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @param string $adminRole the admin role code
	 * @return boolean
	 */
	public static function hasPrivilegeByDb($privilegeCategory, $privilegeCode = '',  $adminRole = '')
	{
		if(empty($adminRole)) $adminRole = CAuth::getLoggedRole();
		if(empty($privilegeCode)) return false;
		
		$privilegeCodeString = '';
		if(is_array($privilegeCode)){
			foreach($privilegeCode as $key => $val){
				$privilegeCodeString .= (($privilegeCodeString != '') ? ',' : '').'"'.$val.'"'; 
			}
		}else{
			$privilegeCodeString = '"'.$privilegeCode.'"';
		}
		
		if($privilege = RolePrivileges::model()->find(
			CConfig::get('db.prefix').'roles.code = :role_code AND
		  '.CConfig::get('db.prefix').'privileges.category = :privilege_category AND
		  '.CConfig::get('db.prefix').'privileges.code IN('.$privilegeCodeString.')', 
			array(':role_code'=>$adminRole, ':privilege_category'=>$privilegeCategory)
		)){
            return $privilege->is_active;
        }else{
            return false;
        }		
	}

	/**
	 * Loads all admin privileges 1 time
	 */
	private static function _loadPrivileges()
	{
		$adminRole = CAuth::getLoggedRole();
		$privileges = RolePrivileges::model()->findAll(
			CConfig::get('db.prefix').'roles.code = :role_code', 
			array(':role_code'=>$adminRole)				
		);
		foreach($privileges as $key => $val){			
			self::$_arrAdminPrivileges[$val['privilege_category']][$val['privilege_code']] = $val['is_active'];		
		}
	}


}
