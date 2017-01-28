<?php
/**
 * Settings model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */

class Settings extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'settings';

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
    	
}
