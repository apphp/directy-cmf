<?php
/**
 * Modules model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations
 * model (static)          	_afterSave
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
    public static function model()
    {
        return parent::model(__CLASS__);
    }
    	
	/**
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{

	}
    
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $pk
	 * You may override this method
	 */
	protected function _afterSave($pk = '')
	{
        // $pk - key used for saving operation
        if(BackendMenus::model()->exists('module_code = :moduleCode', array(':moduleCode'=>$this->_columns['code']))){
            if($this->_columns['show_in_menu'] == 1){
                // Do nothing    
            }else{
                BackendMenus::model()->deleteMenu($this->_columns['code']);
            }                
        }else if($this->_columns['show_in_menu'] == 1){            
            BackendMenus::model()->addMenu($this->_columns['code'], $this->_columns['name']);
        }
	}
    
}
