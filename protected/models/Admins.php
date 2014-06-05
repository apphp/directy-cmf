<?php
/**
 * Admins model
 *
 * PUBLIC:                 PRIVATE
 * -----------             ------------------
 * __construct             
 * relations
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * model                                          loadPrivileges
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
	private static $privilegeProcessingType = 'collect'; /* collect |db */
	/** @var array */    
	private static $arrAdminPrivileges = array();

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
	public function customFields()
	{
		return array(
			'CONCAT(first_name, " ", last_name)' => 'fullname'
		);
	}

	/**
     * Defines relations between different tables in database and current $_table
	 */
	public function relations()
	{
		return array();
	}
	
	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	public function beforeSave($id = 0)
	{
    	if($id > 0){
    		// update admin
			if($this->birth_date == '') $this->birth_date = '0000-00-00';
        	$this->updated_at = date('Y-m-d H:i:s');
        }else{
        	// insert new admin
			if($this->birth_date == '') $this->birth_date = '0000-00-00';
        	$this->created_at = date('Y-m-d H:i:s');
        }
		return true;
	}
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	public function afterSave($id = 0)
	{
		// refresh logged-in session variables
		if($id == CAuth::getLoggedId()){
			$session = A::app()->getSession();
			$session->set('loggedName', ($this->display_name != '' ? $this->display_name : $this->username));
			$session->set('loggedAvatar', ($this->avatar != '' ? $this->avatar : 'no_image.png'));
		
			//if($this->_oldLanguage != $this->language_code){
				// reset site language
				A::app()->setLanguage($this->language_code);
			//}
		}	
	}
	
	/**
	 * Checks if a specific privilege exists
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function privilegeExists($privilegeCategory = '', $privilegeCode = '')
	{
		if(self::$privilegeProcessingType == 'collect'){
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
		if(empty(self::$arrAdminPrivileges)) self::loadPrivileges();
		
		return isset(self::$arrAdminPrivileges[$privilegeCategory][strtolower($privilegeCode)]) ? true : false;
	}

	/**
	 * Checks if a specific privilege exists
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function privilegeExistsByDb($privilegeCategory = '', $privilegeCode = '')
	{
		$result = Privileges::model()->find(
		    CConfig::get('db.prefix').'privileges.category = :privilege_category AND
		  '.CConfig::get('db.prefix').'privileges.code = :privilege_code', 
			array(
				':privilege_category'=>$privilegeCategory,
				':privilege_code'=>$privilegeCode
			)				
		);
		return !empty($result) ? true : false;
	}

	/**
	 * Checks if a given admin has specific privilege
	 * @param mixed $privilegeCategory the privilege category
	 * @param mixed $privilegeCode the privilege code
	 * @return boolean
	 */
	public static function hasPrivilege($privilegeCategory = '', $privilegeCode = '')
	{
		if(self::$privilegeProcessingType == 'collect'){
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
		if(empty(self::$arrAdminPrivileges)) self::loadPrivileges();
		
		$privilegeCodeString = '';
		if(is_array($privilegeCode)){
			foreach($privilegeCode as $key => $val){
				if(isset(self::$arrAdminPrivileges[$privilegeCategory][$val]) && (bool)self::$arrAdminPrivileges[$privilegeCategory][$val] == true){
					return true;			
				}
			}
		}else{
			$privilegeCode = strtolower($privilegeCode);
			if(isset(self::$arrAdminPrivileges[$privilegeCategory][$privilegeCode]) && (bool)self::$arrAdminPrivileges[$privilegeCategory][$privilegeCode] == true){
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
		
		$privilege = Privileges::model()->find(
			CConfig::get('db.prefix').'roles.code = :role_code AND
		  '.CConfig::get('db.prefix').'privileges.category = :privilege_category AND
		  '.CConfig::get('db.prefix').'privileges.code IN('.$privilegeCodeString.')', 
			array(':role_code'=>$adminRole, ':privilege_category'=>$privilegeCategory)				
		);
		return !empty($privilege) ? $privilege[0]['is_active'] : false;
	}

	/**
	 * Loads all admin privileges 1 time
	 */
	private static function loadPrivileges()
	{
		$adminRole = CAuth::getLoggedRole();
		$privileges = Privileges::model()->findAll(
			CConfig::get('db.prefix').'roles.code = :role_code', 
			array(':role_code'=>$adminRole)				
		);
		foreach($privileges as $key => $val){			
			self::$arrAdminPrivileges[$val['privilege_category']][$val['privilege_code']] = $val['is_active'];		
		}
	}
}
