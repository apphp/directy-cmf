<?php
/**
 * SiteInfo model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct
 * model (static)
 *
 */

class SiteInfo extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'site_info';

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
