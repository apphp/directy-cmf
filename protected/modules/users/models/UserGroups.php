<?php
/**
 * UserGroups model
 *
 * PUBLIC:                PROTECTED:              PRIVATE:
 * ---------------        ---------------         ---------------
 * __construct            _afterSave
 * model (static)
 *
 */

namespace Modules\Users\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig,
	\CHash;


class UserGroups extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'users_groups';
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
       
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
		
		// If this group is default - remove default flag in all other languages
		if($this->is_default){
        	if(!$this->_db->update($this->_table, array('is_default'=>0), 'id != :id', array(':id'=>$id))){
        		$this->_isError = true;
        	}
		}
    }		
    
}
