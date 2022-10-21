<?php
/**
 * Backend Index controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              	
 * indexAction
 */

class IndexController extends CController
{
	private $_checkBruteforce;
	private $_redirectDelay;
	private $_badLogins;
	private $_badRestores;
	private $_backendPath = '';

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access to this controller to non-logged visitors
		CAuth::handleLogin($this->_backendPath.'admin/login');

		// Set backend mode
        Website::setBackend();
    }
        
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect($this->_backendPath.'dashboard');
    }

}