<?php
/**
 * Error controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * indexAction
 *
 */

class ErrorController extends CController
{
    
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
		
		$this->_view->actionMessage = '';
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

        $this->_view->render('error/'.$redirectCode);
    }


}