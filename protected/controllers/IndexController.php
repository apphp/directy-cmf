<?php
/**
 * Index controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct
 * indexAction
 * clearAction
 *
 */

class IndexController extends CController
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
	 * @return void
	 */
	public function indexAction()
	{
        $this->_view->title = '';
        $this->_view->text = '';
		
		$controller = CConfig::get('defaultController');
		$action = CConfig::get('defaultAction');
		$renderPath = strtolower($controller.'/'.$action);
		
		if(in_array($renderPath, array('/', 'index', 'index/', 'index/index'))){
			//$this->_view->setLayout('wide');
			$this->_view->render('index/index');	
		}else{
			$this->redirect($controller.'/'.$action);	
		}		
	}

	/**
	 * Controller clear action handler
	 * @param string $type
	 * @return void
	 */
	public function clearAction($type = '')
	{
		if(APPHP_MODE == 'debug'){
			// Clear session and cookies
			if($type == 'session_and_cookie'){
				A::app()->getSession()->removeAll();
				A::app()->getCookie()->clearAll();
			}elseif($type == 'cache_and_minified'){
				CFile::emptyDirectory(CConfig::get('cache.db.path'), array('index.html'));
					CFile::emptyDirectory(CConfig::get('compression.css.path'), array('index.html'));
				CFile::emptyDirectory(CConfig::get('compression.js.path'), array('index.html'));
			}

			$this->redirect(Website::getDefaultPage());
		}
	}

}
