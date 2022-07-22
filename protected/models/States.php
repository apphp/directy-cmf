<?php
/**
 * States model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 * getError                _afterDelete
 * 
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */

class States extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'states';
    /** @var string */
    protected $_tableTranslation = 'state_translations';
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
   	public static function model($className = __CLASS__)
   	{
		return parent::model($className);
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
				'state_id',
				'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array('name'=>'state_name')
			),
		);
	}	
	
	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	protected function _afterDelete($id = 0)
	{
		$this->_isError = false;
		// delete state names from translation table
		if(false === $this->_db->delete($this->_tableTranslation, 'state_id="'.$id.'"')){
			$this->_isError = true;
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
