<?php
/**
 * Template of ReportsEntityComments model
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

// Directy
use \LocalTime;


class ReportsEntityComments extends CActiveRecord
{

    /** @var string */
    protected $_table = 'reports_entities_comments';
    protected $_tableAdmins = 'admins';

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
			'author' => array(
				self::BELONGS_TO,
				$this->_tableAdmins,
				'id',
				'condition'=>'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array('display_name'=>'author_name')
			),
        );
    }

    /**
     * This method is invoked after saving a record successfully
     * @param int $id
     */
    protected function _afterSave($id = 0)
    {
        if(!$this->isNewRecord()){
        	CDatabase::init()->update($this->_table, array('changed_date' => LocalTime::currentDate()), 'id = '.(int)$id);
        }

        return true;
    }

}
