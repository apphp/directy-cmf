<?php

/**
 * ErrorController
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
			A::app()->view->setTemplate('backend');        	
		}		

        $this->view->render('error/'.$redirectCode);
    }
	
}