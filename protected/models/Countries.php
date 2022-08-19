<?php
/**
 * Countries model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations
 * model (static)		   	_beforeDelete
 * getError                	_beforeSave
 *                         	_afterSave
 *                         	_afterDelete
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
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
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
     * Used to define custom fields
	 */
	protected function _customFields()
	{
		return array('code'=>'country_code');
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
		// If country is default - it must be active
		if($this->is_default) $this->is_active = 1;
		return true;
	}
	
	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
		// If this country is default - remove default flag in all other countries
		if($this->is_default){
			if(!$this->_db->update($this->_table, array('is_default'=>0), 'id != :id', array(':id'=>(int)$id))){
				$this->_isError = true;
			}
		}
	}

	/**
	 * This method is invoked before deleting a record (after validation, if any)
	 * @param string $pk
	 * @return boolean
	 */
	protected function _beforeDelete($pk = '')
	{        
        if($this->count() > 1){
            return true;
        }else{
            $this->_isError = true;
            $this->_errorMessage = A::t('core', 'You cannot delete the last remaining record in table {table}!', array('{table}'=>'<b>'.ucfirst($this->_table).'</b>'));
            return false;    
        }
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $pk
	 */
	protected function _afterDelete($pk = '')
	{
		$this->_isError = false;
		// Delete country names from translation table
		if(false === $this->_db->delete($this->_tableTranslation, 'country_code = :country_code', array(':country_code'=>$this->code))){
			$this->_isError = true;
		}
		
		// Delete states in loop to force call to afterDelete for each state to delete states translations 
		$states = States::model()->findAll('country_code = :countryCode', array(':countryCode'=>$this->code));
		if(is_array($states)){
			foreach($states as $state){
				if(!States::model()->deleteByPk($state['id'])) $this->_isError = true;
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
