<?php
/**
 * News model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct             	_relations
 * model (static)           _afterSave
 * search  					_afterDelete
 *                         	
 */

namespace Modules\News\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig,
	\CHtml;

// Directy
use \Website;

class News extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'news';
    /** @var string */
    protected $_tableTranslation = 'news_translations';
	protected $_columnsTranslation = array('news_header', 'news_text');
    
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
                'news_id',
                'condition'=>'language_code = \''.A::app()->getLanguage().'\'',
                'joinType'=>self::LEFT_OUTER_JOIN,
                'fields'=>array('news_header'=>'news_header', 'news_text'=>'news_text')
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
					$insertFields['news_id'] = $id;
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
					if(!$this->_db->update($this->_tableTranslation, $updateFields, 'news_id = :news_id AND language_code = :language_code', array(':news_id'=>$id, ':language_code'=>$lang))){
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
		// Delete news from translation table
		if(false === $this->_db->delete($this->_tableTranslation, 'news_id = \''.$id.'\'')){
			$this->_isError = true;
		}
	}
	
	public function setTranslationsArray($inputArray)
	{
		$this->_translationsArray = $inputArray;
	}
	
	public function selectTranslations()
	{
		return $this->getTranslations(array('key'=>'news_id', 'value'=>$this->id, 'fields'=>$this->_columnsTranslation));
	}

	/**
	 * Performs search in news
	 * @param string $keywords
	 * @param mixed $itemsCount
	 * @return array array('0'=>array(news), '1'=>total)
	 */	
	public function search($keywords = '', $itemsCount = 10)
	{
		$result = array();
		
		if($keywords !== ''){
			
			$limit = !empty($itemsCount) ? '0, '.(int)$itemsCount : '';
			$condition = CConfig::get('db.prefix').$this->_table.'.is_published = 1 AND ('.
				CConfig::get('db.prefix').$this->_tableTranslation.'.news_text LIKE :keywords OR '.
				CConfig::get('db.prefix').$this->_tableTranslation.'.news_header LIKE :keywords)';
			
			// Count total items in result
			$total = $this->count(array('condition'=>$condition), array(':keywords'=>'%'.$keywords.'%'));
			
			// Prepare news result
			$news = $this->findAll(array('condition'=>$condition, 'limit' => $limit), array(':keywords'=>'%'.$keywords.'%'));
			foreach($news as $key => $val){
				$result[0][] = array(
					'date'			=> $val['created_at'],
					'title' 		=> $val['news_header'],
					'intro_image'	=> (!empty($val['intro_image']) ? '<img class="intro-thumb news-intro-thumb" src="assets/modules/news/images/intro_images/'.CHtml::encode($val['intro_image']).'" alt="news intro" />' : ''),
					'content' 		=> $val['news_text'],
					'link' 			=> Website::prepareLinkByFormat('news', 'news_link_format', $val['id'], $val['news_header'])
				);
			}
			
			$result[1] = $total;
		}
		
		return $result;
	}
	
}
