<?php
/**
 * SocialNetworks model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 *
 * STATIC:
 * ---------------------------------------------------------------
 * model
 *
 */
class SocialNetworks extends CActiveRecord
{

    /** @var string */
    protected $_table = 'social_networks';
    /** @var string */
    protected $_tableSite = 'site_info_frontend';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     * @return void
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }

    /**
     * Defines relations between different tables in database and current $_table
     * @return array
     */
    protected function _relations()
    {
        return array(
        );
    }
}
