<?php
/**
 * GalleryAlbums model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct             	_relations
 * model (static)			_afterSave
 * setTranslationsArray    	_afterDelete
 * selectTranslations      	
 *
 */

namespace Modules\Gallery\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class GalleryAlbums extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'gallery_albums';
    /** @var string */
    protected $_tableTranslation = 'gallery_album_translations';
	protected $_columnsTranslation = array('title', 'description');
    protected $_tableItems = 'gallery_album_items';
    
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
                'gallery_album_id',
                'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('title'=>'album_title', 'description'=>'album_description')
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
					$insertFields['gallery_album_id'] = $id;
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
					if(!$this->_db->update($this->_tableTranslation, $updateFields, 'gallery_album_id = :gallery_album_id AND language_code = :language_code', array(':gallery_album_id'=>$id, ':language_code'=>$lang))){
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
		// Delete records from translation table
		if(false === $this->_db->delete($this->_tableTranslation, "gallery_album_id = '".$id."'")){
			$this->_isError = true;
		}

		// Delete album items in loop to force call to afterDelete for each album item to delete translations
		$albumItems = $this->_db->select(
            'SELECT * FROM '.CConfig::get('db.prefix').$this->_tableItems.' WHERE gallery_album_id = :galleryAlbumId',
            array(':galleryAlbumId' => (int)$id)
        );
        
        if(is_array($albumItems)){
			foreach($albumItems as $albumItem){
                if($albumItem['item_file'] != '') CFile::deleteFile('assets/modules/gallery/images/items/'.$albumItem['item_file']);
                if($albumItem['item_file_thumb'] != '') CFile::deleteFile('assets/modules/gallery/images/items/'.$albumItem['item_file_thumb']);
				if(!GalleryAlbumItems::model()->deleteByPk($albumItem['id'])) $this->_isError = true;
			}
		}
	}
	
	public function setTranslationsArray($inputArray)
	{
		$this->_translationsArray = $inputArray;
	}
	
	public function selectTranslations()
	{
		return $this->getTranslations(array('key'=>'gallery_album_id', 'value'=>$this->id, 'fields'=>$this->_columnsTranslation));
	}
	
}
