<?php
/**
 * Admins model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations                 _loadPrivileges
 * model                   _customFields
 * login                   _afterSave               
 * getErrorDescription
 * hasPrivilege            
 * hasPrivilegeByCollect
 * hasPrivilegeByDb
 * privilegeExists 
 * privilegeExistsByCollect
 * privilegeExistsByDb
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
    private $_errorDescription = '';


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
   	public static function model($className = __CLASS__)
   	{
		return parent::model($className);
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
    		// update admin
			if($this->birth_date == '') $this->birth_date = '0000-00-00';
        	$this->updated_at = LocalTime::currentDateTime();
        }else{
        	// insert new admin
			if($this->birth_date == '') $this->birth_date = '0000-00-00';
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
		// refresh logged-in session variables
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
     */
    public function login($username, $password)
    {
        $this->_db->cacheOff();
        if($result = $this->find(
            'username = :username AND password = :password',
            array(':username' => $username, ':password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey')) : $password))            
        )){
            if($result->is_active){
                $session = A::app()->getSession();
                $session->set('loggedIn', true);
                $session->set('loggedId', $result->id);
                $session->set('loggedName', ($result->display_name ? $result->display_name : $result->username));
                $session->set('loggedAvatar', ($result->avatar ? $result->avatar : 'no_image.png'));
                $session->set('loggedEmail', $result->email);
                $session->set('loggedLastVisit', $result->last_visited_at);
                $session->set('loggedLanguage', ($result->language_code ? $result->language_code : Languages::getDefaultLanguage()));
                $session->set('loggedRole', $result->role);
                
                // set current language
                if($resultLang = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$result->language_code))){
                    $params = array(
                        'locale' => $resultLang->lc_time_name,
                        'direction' => $resultLang->direction
                    );
                    A::app()->setLanguage($result->language_code, $params);
                }
    
                // update admin's last visit time
                $this->_db->update($this->_table, array('last_visited_at' => LocalTime::currentDateTime()), 'id = '.(int)CAuth::getLoggedId());
                return true;
            }else{
                $this->_errorDescription = A::t('app', 'Login Inactive Message');            
            }
        }else{
            $this->_errorDescription = A::t('app', 'Login Error Message');            
        }
        return false;        
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
		if($result = Privileges::model()->find(
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
		
		if($privilege = Privileges::model()->find(
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
		$privileges = Privileges::model()->findAll(
			CConfig::get('db.prefix').'roles.code = :role_code', 
			array(':role_code'=>$adminRole)				
		);
		foreach($privileges as $key => $val){			
			self::$_arrAdminPrivileges[$val['privilege_category']][$val['privilege_code']] = $val['is_active'];		
		}
	}


}
