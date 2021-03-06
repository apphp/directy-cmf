<?php
/**
 * Privileges model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations 
 * updatePrivilege
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */

class Privileges extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'role_privileges';

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
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{
		return array(
			'privilege_id' => array(
				self::BELONGS_TO,
				'privileges',
				'id',
				'condition'=>'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array(
                    'module_code'=>'module_code',
					'category'=>'privilege_category',
					'code'=>'privilege_code',
					'name'=>'privilege_name',
					'description'=>'privilege_description'
				)
			),
			'role_id' => array(
				self::BELONGS_TO,
				'roles',
				'id',
				'condition'=>'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array('code'=>'role_code', 'name'=>'role_name')
			),
		);
	}

	/**
     * Updates activity for given role and privilege
     * $roleId int
     * $privilegeId int
     * $activity bool
	 */
	public function updatePrivilege($roleId = 0, $privilegeId = 0, $activity = 1)
	{
		return $this->_db->update('role_privileges', array('is_active'=>(int)$activity), 'role_id = '.(int)$roleId.' AND privilege_id = '.(int)$privilegeId);
	}


}
