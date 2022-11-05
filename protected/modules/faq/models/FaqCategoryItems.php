<?php
/**
 * FaqCategoryItems model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct             	_relations
 * model (static)			_afterSave
 * setTranslationsArray    	_afterDelete
 * selectTranslations      	
 *                         
 */

namespace Modules\Faq\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class FaqCategoryItems extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'faq_category_items';
    /** @var string */
    public $_tableTranslation = 'faq_category_item_translations';
    protected $_columnsTranslation = array('faq_question','faq_answer');
    
    
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
                'faq_category_item_id',
                'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('faq_question'=>'faq_question','faq_answer'=>'faq_answer')
            ),
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
                    $insertFields['faq_category_item_id'] = $id;
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
                    if(!$this->_db->update($this->_tableTranslation, $updateFields, 'faq_category_item_id = :faq_category_item_id AND language_code = :language_code', array(':faq_category_item_id'=>$id, ':language_code'=>$lang))){
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
        if(false === $this->_db->delete($this->_tableTranslation, 'faq_category_item_id = \''.$id.'\'')){
            $this->_isError = true;
        }
    }
    
    public function setTranslationsArray($inputArray)
    {
        $this->_translationsArray = $inputArray;
    }
    
    public function selectTranslations()
    {
        return $this->getTranslations(array('key'=>'faq_category_item_id', 'value'=>$this->id, 'fields'=>$this->_columnsTranslation));
    }  
    
}
