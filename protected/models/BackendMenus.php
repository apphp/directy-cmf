<?php
/**
 * BackendMenus model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * relations
 * afterSave
 * afterDelete
 * getError
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */
class BackendMenus extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'backend_menus';
    /** @var string */
    protected $_tableTranslation = 'backend_menu_translations';

    private $isError = false;
	
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
   	public static function model($className = __CLASS__)
   	{
		return parent::model($className);
   	}
    	
	/**
     * Defines relations between different tables in database and current $_table
	 */
	public function relations()
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
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	public function afterDelete($id = 0)
	{
		$this->isError = false;
		// delete menu names from translation table
		if(false === $this->db->delete($this->_tableTranslation, 'menu_id="'.$id.'"')){
			$this->isError = true;
		}
	}
	
	/** 
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->isError;
	}
		
}
