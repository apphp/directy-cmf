<?php
/**
 * Template of ReportsEntitiesItems model
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


class ReportsEntityItems extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'reports_entity_items';
    protected $_tableEntities = 'reports_entities';
    protected $_tableFields = 'reports_type_items';

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

}
