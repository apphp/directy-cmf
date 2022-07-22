<?php
/**
 * Index controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
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

        // set frontend mode
        Website::setFrontend();
	}
	
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->_view->title = '';
        $this->_view->text = '';

        // check if CMS module installed and redirect to view pages
        if(Modules::model()->exists("code = 'cms' AND is_installed = 1")){        
            $this->redirect('pages/view');
        }else{
            $this->_view->render('index/index');	
        }
	}	
}