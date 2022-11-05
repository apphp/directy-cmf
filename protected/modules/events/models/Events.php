<?php
/**
 * Events model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct             _relations
 * setTranslationsArray    _afterSave
 * selectTranslations      _afterDelete
 * model (static)
 *
 */

namespace Modules\Events\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class Events extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'events';
    /** @var string */
    public $_tableTranslation = 'events_translations';
    protected $_columnsTranslation = array('event_name','event_description');
    
    protected $_tableCategory = 'events_categories_translations';
    
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
                'event_id',
                'condition'=>CConfig::get('db.prefix').$this->_tableTranslation.'.language_code = \''.A::app()->getLanguage().'\'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('event_name'=>'event_name','event_description'=>'event_description')
            ),
            'event_category_id' => array(
                self::HAS_ONE,
                $this->_tableCategory,
                'event_category_id',
                'condition'=>CConfig::get('db.prefix').$this->_tableCategory.'.language_code = \''.A::app()->getLanguage().'\'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('event_category_name'=>'event_category_name')
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
                    $insertFields['event_id'] = $id;
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
                    if(!$this->_db->update($this->_tableTranslation, $updateFields, 'event_id = :event_id AND language_code = :language_code', array(':event_id'=>$id, ':language_code'=>$lang))){
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
        // Delete event from translation table
        if(false === $this->_db->delete($this->_tableTranslation, 'event_id = '.(int)$id)){
            $this->_isError = true;
        }
    }
    
    public function setTranslationsArray($inputArray)
    {
        $this->_translationsArray = $inputArray;
    }
    
    public function selectTranslations()
    {
        return $this->getTranslations(array('key'=>'event_id', 'value'=>$this->id, 'fields'=>$this->_columnsTranslation));
    }  
    
}
