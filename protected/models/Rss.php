<?php
/**
 * RSS model
 *
 * PUBLIC:                 	PRIVATE
 * -----------             	------------------
 * __construct
 * model (static)
 *
 */

class Rss extends CActiveRecord
{

    /** @var string */
    protected $_table = 'rss_channels';

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