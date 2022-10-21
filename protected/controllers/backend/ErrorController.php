<?php
/**
 * Error controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * indexAction
 * viewErrorLogAction
 * deleteAllAction
 * 
 */

class ErrorController extends CController
{

	private $_backendPath = '';

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Set backend mode
		Website::setBackend();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();
		$this->_view->actionMessage = '';
		$this->_view->backendPath = $this->_backendPath;
	}

	/**
	 * Controller default action handler
	 * @param string $code
	 */
	public function indexAction($code = '404')
	{
        if(in_array($code, array('404', '500'))){
            $redirectCode = $code;
        }elseif(strtolower($code) == 'no-privileges'){
			$redirectCode = 'noprivileges';
        }else{
            $redirectCode = 'index';
        }

		if(CAuth::isLoggedInAsAdmin()){
            // Set backend mode
            Website::setBackend();
		}else{
			if(!A::app()->isSetup()){
				// Set frontend mode
				Website::setFrontend();
			}
        }

		// Display error description
		$this->_view->errorDescription = '';
		if(APPHP_MODE == 'debug'){
			$this->_view->errorDescription = A::app()->getSession()->getFlash('error500');
		}

        $this->_view->render($this->_backendPath.'error/'.$redirectCode);
    }

	/**
	 * Shows content of error log file on the screen.
	 */	
	public function viewErrorLogAction()
	{
		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage(), 'owner,mainadmin');

		// Set backend mode
		Website::setBackend();

		$this->_view->fileContent = CFile::getFileContent('protected/tmp/logs/error.log');
		
		if($this->_view->fileContent == ''){
			$this->_view->actionMessage = CWidget::create(
				'CMessage', array('warning', A::t('app', 'Errors log file is empty'), array('button'=>false))
			);
		}
		
		$this->_view->render($this->_backendPath.'error/errorLog');
	}
	
	/**
	 * Clears error log file
	 */	
	public function deleteAllAction()
	{
		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage(), 'owner,mainadmin');

		// Set backend mode
		Website::setBackend();

		if(APPHP_MODE == 'demo'){
			$this->_view->actionMessage = CWidget::create(
				'CMessage', array('warning', A::t('core', 'This operation is blocked in Demo Mode!'), array('button' => false))
			);
		}else{
			$result = CFile::writeToFile('protected/tmp/logs/error.log', '');
			if($result){
				$this->_view->actionMessage = CWidget::create(
					'CMessage', array('success', A::t('app', 'Errors log file has been successfully cleaned!'), array('button' => false))
				);
				$this->_view->fileContent = '';
			}else{
				$this->_view->actionMessage = CWidget::create(
					'CMessage', array('success', A::t('app', 'Errors Log Clear Error Message'), array('button' => true))
				);
				$this->_view->fileContent = CFile::getFileContent('protected/tmp/logs/error.log');
			}
		}

		$this->_view->render($this->_backendPath.'error/errorLog');
	}
	
}