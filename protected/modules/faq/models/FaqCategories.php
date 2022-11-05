<?php
/**
 * FaqCategories model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct             	_customFields
 * model (static)			_relations
 * setTranslationsArray    	_afterSave
 * selectTranslations      	_afterDelete 
 *
 */

namespace Modules\Faq\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class FaqCategories extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'faq_categories';
    /** @var string */
    protected $_tableTranslation = 'faq_category_translations';
    protected $_tableCategoryItems = 'faq_category_items';
    protected $_tableCategoryItemsTranslation = 'faq_category_items_translation';
    protected $_columnsTranslation = array('category_name');
    
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
            'id' => array(
                self::HAS_MANY,
                $this->_tableTranslation,
                'faq_category_id',
                'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('category_name'=>'category_name')
            ),
        );
    }
    
	/**
     * Used to define custom fields
	 * This method should be overridden
	 * Usage: 'CONCAT(first_name, " ", last_name)' => 'fullname'
	 */
	protected function _customFields()
	{
		return array(
            '(SELECT COUNT(*)
              FROM '.CConfig::get('db.prefix').$this->_tableCategoryItems.' fci
              WHERE fci.faq_category_id	= '.CConfig::get('db.prefix').$this->_table.'.id)' => 'questions_count'
        );
	}

    /**
     * This method is invoked after saving a record successfully
     * @param string $pk
     */
    protected function _afterSave($id = 0)
    {              
        $this->_isError = false;
            
        // Insert/update data in translation table
        if(is_array($this->_translationsArray)){
            if($this->isNewRecord()){
                foreach($this->_translationsArray as $lang => $translations){
                    $insertFields['faq_category_id'] = $id;
                    $insertFields['language_code'] = $lang;
                    foreach($this->_columnsTranslation as $columnTransKey){
                        $insertFields[$columnTransKey] = $translations[$columnTransKey];
                    }
                    if(!$this->_db->insert($this->_tableTranslation, $insertFields)){
                        $this->_isError = true;
                    }
                }
            }else{
                foreach($this->_translationsArray as $lang => $translations){
                    $updateFields = array();
                    foreach($this->_columnsTranslation as $columnTransKey){
                        $updateFields[$columnTransKey] = $translations[$columnTransKey];
                    }
                    if(!$this->_db->update($this->_tableTranslation, $updateFields, 'faq_category_id = :faq_category_id  AND language_code = :language_code', array(':faq_category_id'=>$id, ':language_code'=>$lang))){
                        $this->_isError = true;
                    }
                }
            }
        }
    }

    /**
     * This method is invoked after deleting a record successfully
     * @param string $id
     */
    protected function _afterDelete($id = 0)
    {
        $this->_isError = false;
        // Delete faq from translation table
        if(false === $this->_db->delete($this->_tableTranslation, 'faq_category_id = \''.$id.'\'')){
            $this->_isError = true;
        }
    }
    
    public function setTranslationsArray($inputArray)
    {
        $this->_translationsArray = $inputArray;
    }
    
    public function selectTranslations()
    {
        return $this->getTranslations(array('key'=>'faq_category_id', 'value'=>$this->id, 'fields'=>$this->_columnsTranslation));
    }    
    
}
