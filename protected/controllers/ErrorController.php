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
	 * Controller default action handler
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
            // Set frontend mode
            Website::setFrontend();
        }

		// Display error description
		$this->_view->errorDescription = '';
		if(APPHP_MODE == 'debug'){
			$this->_view->errorDescription = A::app()->getSession()->getFlash('error500');
		}

        $this->_view->render('error/'.$redirectCode);
    }
	
}