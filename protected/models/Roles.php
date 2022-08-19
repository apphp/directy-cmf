<?php
/**
 * Roles model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations
 * model (static)			_afterSave
 * 							_afterDelete
 *
 */

class Roles extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'roles';
	protected $_tablePrivileges = 'privileges';
	protected $_tableRolesPrivileges = 'role_privileges';

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
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{
		return array();
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
		
		$privileges = Privileges::model()->findAll();
		foreach($privileges as $key => $val){
			$data = array(
				'role_id' 		=> $id,
				'privilege_id' 	=> $val['id'],
				'is_active' 	=> 1
			);
			
			$privileges = RolePrivileges::model()->addPrivilages($data);
			if(!$privileges){
				$this->_isError = true;
			}
		}		
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param int $pk
	 */
	protected function _afterDelete($pk = '')
	{
		// Remove role privileges		
		RolePrivileges::model()->deleteAll('role_id = :role_id', array(':role_id' => $pk));
	}
	
}
