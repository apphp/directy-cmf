<?php

/**
 * EventsCategories model
 *
 * PUBLIC:                 			PROTECTED                  PRIVATE
 * ---------------         			---------------            ---------------
 * __construct             			_customFields
 * setTranslationsArray    			_relations
 * selectTranslations      			_afterSave
 * model (static)          			_afterDelete
 * loadEventsCategories (static)
 *
 */

namespace Modules\Events\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class EventsCategories extends CActiveRecord {

    /** @var string */
    protected $_table = 'events_categories';

    /** @var string */
    protected $_tableTranslation = 'events_categories_translations';
    protected $_tableCategoryItems = 'events';
    protected $_tableCategoryItemsTranslation = 'events_translations';
    protected $_columnsTranslation = array('event_category_name', 'event_category_description');
    private $_translationsArray;

    /** @var bool */
    private $_isError = false;

    /**
     * Class default constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Defines relations between different tables in database and current $_table
     */
    protected function _relations() {
        return array(
            'id' => array(
                self::HAS_MANY,
                $this->_tableTranslation,
                'event_category_id',
                'condition' => 'language_code = \'' . A::app()->getLanguage() . '\'',
                'joinType' => self::LEFT_OUTER_JOIN,
                'fields' => array('event_category_name' => 'event_category_name',
                    'event_category_description' => 'event_category_description')
            ),
        );
    }

    /**
     * Used to define custom fields
     * This method should be overridden
     * Usage: 'CONCAT(first_name, " ", last_name)' => 'fullname'
     */
    protected function _customFields() {
        return array(
            '(SELECT COUNT(*)
              FROM ' . CConfig::get('db.prefix') . $this->_tableCategoryItems . ' eci
              WHERE eci.event_category_id	= ' . CConfig::get('db.prefix') . $this->_table . '.id)' => 'events_count'
        );
    }

    /**
     * This method is invoked after saving a record successfully
     * @param string $pk
     */
    protected function _afterSave($id = 0) {
        $this->_isError = false;

        // Insert/update data in translation table
        if (is_array($this->_translationsArray)) {
            if ($this->isNewRecord()) {
                foreach ($this->_translationsArray as $lang => $translations) {
                    $insertFields['event_category_id'] = $id;
                    $insertFields['language_code'] = $lang;
                    foreach ($this->_columnsTranslation as $columnTransKey) {
                        $insertFields[$columnTransKey] = $translations[$columnTransKey];
                    }
                    if (!$this->_db->insert($this->_tableTranslation, $insertFields)) {
                        $this->_isError = true;
                    }
                }
            } else {
                foreach ($this->_translationsArray as $lang => $translations) {
                    $updateFields = array();
                    foreach ($this->_columnsTranslation as $columnTransKey) {
                        $updateFields[$columnTransKey] = $translations[$columnTransKey];
                    }
                    if (!$this->_db->update($this->_tableTranslation, $updateFields, 'event_category_id = :event_category_id  AND language_code = :language_code', array(':event_category_id' => $id, ':language_code' => $lang))) {
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
    protected function _afterDelete($id = 0) {
        $this->_isError = false;
        
		// Delete event categories from translation table
        if (false === $this->_db->delete($this->_tableTranslation, 'event_category_id = '.(int)$id)){
            $this->_isError = true;
        }
		
		// Delete cetegory items in loop to force call to afterDelete for each item to delete translations
		$categoryItems = $this->_db->select(
            'SELECT * FROM '.CConfig::get('db.prefix').$this->_tableCategoryItems.' WHERE event_category_id = :eventCategoryId',
            array(':eventCategoryId' => (int)$id)
        );
        
        if(is_array($categoryItems)){
			foreach($categoryItems as $categoryItem){
				
				// Delete events translation table
				if (false === $this->_db->delete($this->_tableCategoryItemsTranslation, 'event_id = '.(int)$categoryItem['id'])){
					$this->_isError = true;
				}

				// Delete events table
				if (false === $this->_db->delete($this->_tableCategoryItems, 'event_category_id = '.(int)$id)){
					$this->_isError = true;
				}
			}			
		}

    }

    public function setTranslationsArray($inputArray) {
        $this->_translationsArray = $inputArray;
    }

    public function selectTranslations() {
        return $this->getTranslations(array('key' => 'event_category_id', 'value' => $this->id, 'fields' => $this->_columnsTranslation));
    }

    /**
     * List of categories
     */
    public static function loadEventsCategories() {
        $eventsCategory = self::model()->findAll(array(
            'condition' => "event_category_is_active = 1",
            'orderBy' => "event_category_sort_order"
        ));

        $options = array(0 => '');
        foreach ($eventsCategory as $c) {
            if($c['events_count']>0){
                $options[$c['id']] = $c['event_category_name'];
            }
        }
        return $options;
    }

}
