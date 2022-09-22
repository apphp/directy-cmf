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

class Search extends CModel
{
	/** @var object */    
    private static $_instance;

    /** @var string */    
    protected $_tableCategories = 'search_categories';
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
	 * Returns the static model of the current class
	 */
	public static function model()
	{
		if(self::$_instance == null){
			self::$_instance = new self();
		}
		
		return self::$_instance;    		
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
		return $this->_db->select('SELECT * FROM '.CConfig::get('db.prefix').$this->_tableCategories.' WHERE is_active = 1');
	}
	
	/** 
	 * Returns required categories
	 * $param mixed $category
	 * @return boolean
	 */
	public function getCategory($category)
	{
		$result = $this->_db->select(
			'SELECT * FROM '.CConfig::get('db.prefix').$this->_tableCategories.' WHERE category_code = :categoryCode LIMIT 0,1',
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
				// For PHP_VERSION | phpversion() >= 5.3.0 you may use
				// $callbackClass = $val['callback_class']::model()->search();
				if($callbackClass = @call_user_func_array($val['callback_class'].'::model', array())){
					if($categoryResult = @call_user_func_array(array($callbackClass, $val['callback_method']), array($keywords, ($singleCategory ? 1000 : $val['items_count'])))){
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
		}
		
		return $result;
	}
	
}
