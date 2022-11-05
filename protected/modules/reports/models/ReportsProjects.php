<?php
/**
 * Template of ReportsProjects model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct				_relations
 * model (static)			_afterSave
 * 							_afterDelete
 *
 */

namespace Modules\Reports\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;


class ReportsProjects extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'reports_projects';
    protected $_tableReports = 'reports_entities';
    protected $_tableReportsTypes = 'reports_types';
    protected $_tableReportItems = 'reports_entity_items';
    protected $_tableReportComments = 'reports_entities_comments';

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
     * @param int $id
     */
    protected function _afterSave($id = 0)
    {
        $this->_isError = false;

        $reportTypes = $this->_db->select('SELECT id FROM '.CConfig::get('db.prefix').$this->_tableReportsTypes);
        $typeCount = count($reportTypes);
        if($this->isNewRecord()){
            for($type = 0; $type < $typeCount; $type++){
				
                $insertFields = array(
                    'project_id' => $id,
                    'type_id'    => $reportTypes[$type]['id'],
                    'sort_order' => $type,
					'is_active'	 => 1
                );
				
                if(!$this->_db->insert(CConfig::get('db.prefix').$this->_tableReports, $insertFields)){
                    $this->_isError = true;
                }
            }
        }
    }

    /**
     * This method is invoked after deleting a record successfully
     * @param int $id
     */
    protected function _afterDelete($id = 0)
    {
        $reports = $this->_db->select('SELECT id FROM '.CConfig::get('db.prefix').$this->_tableReports.' WHERE project_id ='.$id);
        $reportItems = array();
        $reportCount = count($reports);
        if(isset($reports)){
            for($item = 0; $item < $reportCount; $item++){
                $reportItems [] = $reports[$item]['id'];
            }
        }
        $reportList = implode(',', $reportItems);
        $this->_isError = false;
        if(false === $this->_db->delete(CConfig::get('db.prefix').$this->_tableReports, "project_id = '".$id."'")){
            $this->_isError = true;
        }
        if(false === $this->_db->delete(CConfig::get('db.prefix').$this->_tableReportItems, 'entity_id IN('.$reportList.')')){
            $this->_isError = true;
        }
        if(false === $this->_db->delete(CConfig::get('db.prefix').$this->_tableReportComments, 'entity_id IN('.$reportList.')')){
            $this->_isError = true;
        }
    }
	
}
