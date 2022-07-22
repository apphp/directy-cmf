<?php
/**
 * Error controller
 *
 * PUBLIC:                 PRIVATE
 * -----------             ------------------
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
        }else{
            $redirectCode = 'index';
        }

		if(CAuth::isLoggedInAsAdmin()){
            // set backend mode
            Website::setBackend();
		}else{
            // set frontend mode
            Website::setFrontend();
        }

        $this->_view->render('error/'.$redirectCode);
    }
	
}