<?php
/**
 * EmailTemplates model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations
 * model (model)           	_afterDelete
 * getError                
 * getTemplate
 *
 */

class EmailTemplates extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'email_templates';
	/** @var string */
	protected $_tableTranslation = 'email_template_translations';
		
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
				'template_code',
				'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array(
                    'template_name'=>'template_name',
                    'template_subject'=>'template_subject',
                    'template_content'=>'template_content'
                )
			),
		);
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $pk
	 */
	protected function _afterDelete($pk = '')
	{
		$this->_isError = false;
		// Delete records from translation table
		if(false === $this->_db->delete($this->_tableTranslation, 'template_code = :template_code', array(':template_code'=>$this->code))){
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

	/**
	 * Returns template according to given code
	 * @param string $templateCode
	 * @param string $languageCode
	 */
	public function getTemplate($templateCode = '', $languageCode = '')
	{
        if(empty($languageCode)) $languageCode = A::app()->getLanguage();        
        if($result = $this->_db->select('
            SELECT *
            FROM '.CConfig::get('db.prefix').$this->_tableTranslation.'
            WHERE template_code = :template_code AND language_code = :language_code',
            array(':template_code'=>$templateCode, ':language_code'=>$languageCode))
        ){
            return isset($result[0]) ? $result[0] : '';
        }
        return '';		
	}
    
}
