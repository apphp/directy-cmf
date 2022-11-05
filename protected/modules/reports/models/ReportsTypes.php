<?php
/**
 * Template of ReportsTypes model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct				_relations
 * model (static)			_afterSave
 * 							_beforeDelete
 *
 */

namespace Modules\Reports\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class ReportsTypes extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'reports_types';
    protected $_tablePrivileges = 'privileges';
    protected $_tableRolesPrivileges = 'role_privileges';
    protected $_tableEntities = 'reports_entities';
    protected $_tableEntityComments = 'reports_entities_comments';
    protected $_tableEntityItems = 'reports_entity_items';
    protected $_tableRoles = 'roles';

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
     * @param int $pk
     * You may override this method
     */
    protected function _afterSave($id = 0)
    {
        $this->_isError = false;

        $reportType = $this->_db->select('SELECT code FROM '.CConfig::get('db.prefix').$this->_table.' WHERE id = :id ', array(':id' => $id));
        $roles = $this->_db->select('SELECT id FROM '.CConfig::get('db.prefix').$this->_tableRoles.' WHERE id NOT IN (1,2,3)');
        $rolesCount = count($roles);
        if($this->isNewRecord()){

            // View Privileges
			$insertFields = array(
                'id'            => NULL,
                'module_code'   => 'reports',
                'category'      => $reportType[0]['code'],
                'code'	        => 'view',
                'name'	        => 'View Reports - '.$reportType[0]['code'],
                'description'	=> 'View Reports - '.$reportType[0]['code']
            );
            if(!$result = $this->_db->insert(CConfig::get('db.prefix').$this->_tablePrivileges, $insertFields)){
                $this->_isError = true;
            }

            for($role = 0; $role < $rolesCount; $role++){
                $insertFields = array(
                    'id'            => NULL,
                    'role_id'       => $roles[$role]['id'],
                    'privilege_id'	=> $result,
                    'is_active'	    => 1,
                );
                if(!$this->_db->insert(CConfig::get('db.prefix').$this->_tableRolesPrivileges, $insertFields)){
                    $this->_isError = true;
                }
            }

			// Edit Privileges
            $insertFields = array(
                'id'            => NULL,
                'module_code'   => 'reports',
                'category'      => $reportType[0]['code'],
                'code'	        => 'edit',
                'name'	        => 'Edit Reports - '.$reportType[0]['code'],
                'description'	=> 'Edit Reports - '.$reportType[0]['code']
            );
            if(!$result = $this->_db->insert(CConfig::get('db.prefix').$this->_tablePrivileges, $insertFields)){
                $this->_isError = true;
            }

            for($role = 0; $role < $rolesCount; $role++){
                $insertFields = array(
                    'id'            => NULL,
                    'role_id'       => $roles[$role]['id'],
                    'privilege_id'	=> $result,
                    'is_active'	    => 0,
                );

                if(!$this->_db->insert(CConfig::get('db.prefix').$this->_tableRolesPrivileges, $insertFields)){
                    $this->_isError = true;
                }
            }

            // Approve Privileges
			$insertFields = array(
                'id'            => NULL,
                'module_code'   => 'reports',
                'category'      => $reportType[0]['code'],
                'code'	        => 'approve',
                'name'	        => 'Approve Reports - '.$reportType[0]['code'],
                'description'	=> 'Approve Reports - '.$reportType[0]['code']
            );
            if(!$result = $this->_db->insert(CConfig::get('db.prefix').$this->_tablePrivileges, $insertFields)){
                $this->_isError = true;
            }

            for($role = 0; $role < $rolesCount; $role++){
                $insertFields = array(
                    'id'                => NULL,
                    'role_id'           => $roles[$role]['id'],
                    'privilege_id'	    => $result,
                    'is_active'	        => 0,
                );

                if(!$this->_db->insert(CConfig::get('db.prefix').$this->_tableRolesPrivileges, $insertFields)){
                    $this->_isError = true;
                }
            }
        }
    }

    /**
     * This method is invoked before deleting a record (after validation, if any)
     * You may override this method
     * @param int $id
     * @return boolean
     */
    protected function _beforeDelete($id = 0)
    {
        if(isset($id)){
            $id = (int)$id;
            $reportType = $this->_db->select('SELECT code FROM '.CConfig::get('db.prefix').$this->_table.' WHERE id = :id ',array(':id' => $id));
            $reportTypePrivilegeRole = $this->_db->delete(CConfig::get('db.prefix').$this->_tableRolesPrivileges, ' WHERE privilege_id IN (SELECT id FROM '.CConfig::get('db.prefix').'.privileges WHERE category = :category AND module_code = :module_code) ',array(':category' => $reportType[0]['code'], ':module_code' => 'reports'));
            $reportTypePrivilege = $this->_db->delete(CConfig::get('db.prefix').$this->_tablePrivileges, ' WHERE category = :category AND module_code = :module_code ',array(':category' => $reportType[0]['code'], ':module_code' => 'reports'));

            $reportEntities = $this->_db->select('SELECT id FROM '.CConfig::get('db.prefix').$this->_tableEntities.' WHERE type_id = :type_id ',array(':type_id' => $id));
            $entitiesString = array();
            if(isset($reportEntities) && is_array($reportEntities)){
                foreach($reportEntities as $entities){
                    $entitiesString[] = $entities['id'];
                }
                $entitiesList = implode(',', $entitiesString);

                $reportTypeEntityItems = $this->_db->delete(CConfig::get('db.prefix').$this->_tableEntityItems, ' WHERE entity_id IN ('.$entitiesList.')');
                $reportTypeEntityComments = $this->_db->delete(CConfig::get('db.prefix').$this->_tableEntityComments, ' WHERE entity_id IN ('.$entitiesList.')');
				
                $reportTypeEntities = $this->_db->delete(CConfig::get('db.prefix').$this->_tableEntities, ' WHERE type_id = :type_id', array(':type_id' => $id));
            }
        }

        return true;
    }
	
}
