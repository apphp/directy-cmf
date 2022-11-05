<?php
/**
 * Banners model 
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct             	_relations
 * model (static)
 *
 */

namespace Modules\Banners\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class Banners extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'banners';
	protected $_tableTranslation = 'banners_translations';
	protected $_columnsTranslation = array('banner_text');
	private $_translationsArray;
	
    /** @var bool */
    private $_isError = false;
	

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
			'id'=> array(
				self::HAS_MANY,
				$this->_tableTranslation,
				'banner_id',
				'condition' => "language_code = '".A::app()->getLanguage()."'",
				'joinType' => self::LEFT_OUTER_JOIN,
				'fields' => array(
					'banner_title'	=> 'banner_title',
					'banner_text'	=> 'banner_description',
					'banner_button'	=> 'banner_button'
				)
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
					$insertFields['banner_id'] = $id;
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
					if(!$this->_db->update($this->_tableTranslation, $updateFields, 'banner_id = :banner_id AND language_code = :language_code', array(':banner_id'=>$id, ':language_code'=>$lang))){
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

		// Delete news banners translation table
		if(false === $this->_db->delete($this->_tableTranslation, "banner_id = '".$id."'")){
			$this->_isError = true;
		}
	}
	
	public function setTranslationsArray($inputArray)
	{
		$this->_translationsArray = $inputArray;
	}
	
	public function selectTranslations()
	{
		return $this->getTranslations(array('key' => 'banner_id', 'value' => $this->id, 'fields' => $this->_columnsTranslation));
	}
	
}
