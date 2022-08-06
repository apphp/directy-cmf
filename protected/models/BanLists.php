<?php
/**
 * BanLists model 
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations                 
 * model (static)          _customFields
 *
 *
 */

class BanLists extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'ban_lists';


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
        return array();
    }
    
    /**
     * Used to define custom fields
     * This method should be overridden
     * Usage: 'CONCAT(first_name, " ", last_name)' => 'fullname'
     */
    protected function _customFields()
    {
        return array();
    }    
    
}
