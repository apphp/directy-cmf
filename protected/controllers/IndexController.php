<?php
/**
 * Index controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct
 * indexAction
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
}
