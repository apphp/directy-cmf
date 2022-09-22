<?php
/**
 * SocialNetworksLogin model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct              _relations
 * model (static)
 *
 */
class SocialNetworksLogin extends CActiveRecord
{

    /** @var string */
    protected $_table = 'social_networks_login';

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
        return array();
    }
}
