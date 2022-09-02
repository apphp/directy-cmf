<?php
/**
 * MailingLog model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	
 * model (model)           	
 *
 */

class MailingLog extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'mailing_log';
	/** @var bool */
    private $_isError = false;
	
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
    	
}
