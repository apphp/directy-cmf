<?php
/**
 * Template of ReportsEntities model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct				_relations
 * model (static)
 *
 */

namespace Modules\Reports\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class ReportsEntities extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'reports_entities';
    protected $_tableProjects = 'reports_projects';
    protected $_tableReportTypes = 'reports_types';


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
		return array(
            'type_id' => array(
                self::BELONGS_TO,
                $this->_tableReportTypes,
                'id',
                'condition'=>'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('name'=>'type_name')
			),
		);
	}
	
}
