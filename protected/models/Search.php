<?php
/**
 * Search model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_relations
 * model (static)
 * getAllCategories
 * getCategory
 * findResults
 * getError                
 * 
 */

class Search extends CActiveRecord
{
    /** @var string */    
    protected $_table = 'search_categories';
    /** @var string */
    protected $_tableWords = 'search_words';
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
	 * Returns all categories
	 * @return boolean
	 */
	public function getAllCategories()
	{
		return $this->_db->select('SELECT * FROM '.CConfig::get('db.prefix').$this->_table.' WHERE is_active = 1 ORDER BY sort_order ASC');
	}
	
	/** 
	 * Returns required categories
	 * $param mixed $category
	 * @return boolean
	 */
	public function getCategory($category)
	{
		$result = $this->_db->select(
			'SELECT *
			 FROM '.CConfig::get('db.prefix').$this->_table.'
			 WHERE category_code = :categoryCode AND is_active = 1
			 LIMIT 0,1',
			array(':categoryCode' => $category)
		);
		
		return isset($result[0]) ? $result[0] : NULL;
	}
	
	/** 
	 * Returns search results
	 * @param mixed $searchCategory
	 * @param mixed $keywords
	 * @param bool $searchAll
	 * @return mixed 
	 */
	public function findResults($searchCategory, $keywords = '', $searchAll = false)
	{
		$categories = array();
		$result = array();
		$singleCategory = true;

		if($searchAll || empty($searchCategory)){
			$categories = $this->getAllCategories();
			$singleCategory = false;
		}else{
			$categories[0] = $this->getCategory($searchCategory);
		}
		
		if(is_array($categories) && !empty($keywords)){
			foreach($categories as $key => $val){
				// For classes with namespaces callback_class must be:
				// $val['callback_class'] = 'Namespace\Namespace\Namespace\Class';
				if(empty($val['module_code']) || (!empty($val['module_code']) && Modules::model()->isInstalled($val['module_code']))){
					$categoryResult = $val['callback_class']::model()->search($keywords, ($singleCategory ? 1000 : $val['items_count']));
				}

				if(!empty($categoryResult)){
					// Clean result from {module:...} placeholders
					if(is_array($categoryResult[0])){
						foreach($categoryResult[0] as $crKey => $crResult){
							$categoryResult[0][$crKey]['content'] = preg_replace('/{module:(.*?)}/i', '', $categoryResult[0][$crKey]['content']);
							$categoryResult[0][$crKey]['content'] = str_ireplace(array('&nbsp;'), '', $categoryResult[0][$crKey]['content']);
						}
					}

					$result[$val['category_code']] = array(
						'category_name' => A::t($val['module_code'], $val['category_name']),
						'result' 		=> isset($categoryResult[0]) ? $categoryResult[0] : array(),
						'total' 		=> isset($categoryResult[1]) ? $categoryResult[1] : 0
					);					
				}else{
					CDebug::addMessage('errors', 'missing-callback-method', A::t('core', 'Component or method does not exist: {component}', array('{component}'=>$val['callback_class'].'::'.$val['callback_method'].'()')), 'session');
				}					
			}
		}
		
		return $result;
	}
	
}
