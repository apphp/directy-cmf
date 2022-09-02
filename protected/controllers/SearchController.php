<?php
/**
 * Search controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct
 * indexAction
 * findAction
 *
 */

class SearchController extends CController
{	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // Set frontend mode
        Website::setFrontend();
	}
	
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('search/find');
	}
	
	/**
	 * Find result according to search criteria default action handler
	 */
	public function findAction()
	{
		$cRequest = A::app()->getRequest();
		
		$searchCategory = $cRequest->getQuery('search_category');		

		// Check if keywords is string
		$keywords = $cRequest->getQuery('keywords');
		if(is_string($keywords)){
			$keywords = trim(substr($keywords, 0, 1024));		
			// Filter unexpected characters
			$keywords = str_replace(array('%', '_'), '', $keywords);			
		}else{
			$keywords = '';
		}

		$this->_view->keywords = $keywords;
		$this->_view->searchCategories = Search::model()->getAllCategories();
		$this->_view->results = Search::model()->findResults($searchCategory, $keywords);
		$this->_view->highlightResults = Bootstrap::init()->getSettings()->search_is_highlighted;
		
		$currentCategory = Search::model()->getCategory($searchCategory);
		$this->_view->currentCategory = isset($currentCategory['category_name']) ? $currentCategory['category_name'] : '';		
		
		$this->_view->render('search/result');	
	}
	
}
