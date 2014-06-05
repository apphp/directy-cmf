<?php
/**
 * Countries model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * relations
 * beforeSave
 * afterSave
 * afterDelete
 * setTranslationsArray
 * selectTranslations
 * getError
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */
class Countries extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'countries';
	/** @var string */
	protected $_tableTranslation = 'country_translations';
	protected $_columnsTranslation = array('name');
		
	private $_translationsArray;
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
			'code' => array(
				self::HAS_MANY,
				$this->_tableTranslation,
				'country_code',
				'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array('name'=>'country_name')
			),
		);
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	public function beforeSave($id = 0)
	{
		// if country is default - it must be active
		if($this->is_default) $this->is_active = 1;
		return true;
	}
	
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	public function afterSave($id = 0)
	{
		$this->isError = false;
		// if this country is default - remove default flag in all other countries
		if($this->is_default){
			if(!$this->db->update($this->_table, array('is_default'=>0), 'id != '.$id)){
				$this->isError = true;
			}
		}
	}
	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	public function afterDelete($id = 0)
	{
		$this->isError = false;
		// delete country names from translation table
		if(false === $this->db->delete($this->_tableTranslation, 'country_code="'.$this->code.'"')){
			$this->isError = true;
		}
		
		// delete states in loop to force call to afterDelete for each state to delete states translations 
		$states = States::model()->findAll('country_code = :countryCode', array(':countryCode'=>$this->code));
		if(is_array($states)){
			foreach($states as $state){
				if(!States::model()->deleteByPk($state['id'])) $this->isError = true;
			}
		}
	}
	
}
