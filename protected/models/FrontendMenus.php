<?php
/**
 * FrontendMenus model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_afterDelete
 * model (model)           	_relations
 * getError     			_customFields
 *
 */

class FrontendMenus extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'frontend_menus';
    /** @var string */
    protected $_tableTranslation = 'frontend_menu_translations';
    
    /** @var bool */
    private $_isError = false;
	private $_parentId = '';
    
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
		return array(
			'id' => array(
				self::HAS_MANY,
				$this->_tableTranslation,
				'menu_id',
				'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array('name'=>'menu_name')
			),
		);
	}
	
	/**
     * Used to define custom fields
	 */
	protected function _customFields()
	{
		return array(CConfig::get('db.prefix').$this->_table.'.id'=>'menu_id');
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	protected function _afterDelete($id = 0)
	{
		$this->_isError = false;
		// Delete menu names from translation table
		if(!$this->_db->delete($this->_tableTranslation, 'menu_id = :menu_id', array(':menu_id'=>$id))){
			$this->_isError = true;
		}
		
		// Delete sub-menus 
		if(!$this->_db->delete($this->_tableTranslation, 'WHERE menu_id IN (SELECT id FROM '.CConfig::get('db.prefix').$this->_table.' WHERE parent_id = :parent_id)', array(':parent_id'=>$id))){
			$this->_isError = true;
		}else{
			if(!$this->_db->delete($this->_table, 'parent_id = :parent_id', array(':parent_id'=>$id))){
				$this->_isError = true;
			}
		}
	}
	
	/**
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->_isError;
	}
	
}
