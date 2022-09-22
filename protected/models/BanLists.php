<?php
/**
 * BanLists model 
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations                 
 * model (static)          _customFields
 * isBanned
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
	
	/**
	 * Checks if visitor is banned by different parameters
	 * @param $itemType
	 * @param $itemValue
	 * @return bool
	 */
	public function isBanned($itemType = '', $itemValue = '')
	{
		switch($itemType){
			
			// @domain.com
			case 'email_domain':
				$itemValueAsParam = '%'.$itemValue.'%';	
				break;

			// 127.0.0.1
			// username
			// username@domain.com
			case 'ip_address':
			case 'username':
			case 'email_address':
			default:
				$itemValueAsParam = $itemValue;
				break;
		}
		
		$isBanned = $this->_db->count(
			"item_type = '".$itemType."' AND
			item_value = :item_value AND
			is_active = 1 AND
			(expires_at IS NULL OR expires_at > :expires_at)",
			array(
				':item_value' => $itemValue,
				':expires_at' => LocalTime::currentDateTime()
			)
		);

		//$msg = A::t('app', 'This IP address is banned.');
		//$msg = A::t('app', 'This username is banned.');
		//$msg = A::t('app', 'This email is banned.');
		//$msg = A::t('app', 'This username, email or IP address is banned.');
		
		return $isBanned;
	}
   
}
