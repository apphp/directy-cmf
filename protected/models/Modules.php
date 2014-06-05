<?php
/**
 * Modules model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * relations
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */

class Modules extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'modules';

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
	public function relations()
	{

	}	
}
