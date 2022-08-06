<?php
/**
 * SystemNotifications model
 *
 * PUBLIC:                 	PRIVATE
 * -----------             	------------------
 * __construct
 * model (static)
 *
 */

class SystemNotifications extends CActiveRecord
{

    /** @var string */
    protected $_table = 'system_notifications';

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