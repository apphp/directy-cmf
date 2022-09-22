<?php
/**
 * CController base class file
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2016 ApPHP Framework
 * @license http://www.apphpframework.com/license/ 
 * 
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct                                          _getCalledClass
 * testAction
 * errorAction
 * redirect
 * 
 */	  

class CController
{
	/** @var string */		
    protected $_view;

	/**
	 * Class constructor
	 * @return void
	 */
	function __construct()
	{
		$this->_view = A::app()->view;
	}
    
	/**
	 * Renders test action
	 */
	public function testAction()
	{
		if(APPHP_MODE == 'test'){            
			$controller = $this->_getCalledClass();
			if($controller.'/index' == $this->_view->render($controller.'/index')){
				return true; 
			}else{
				return false; 
			}
		}else{
			$this->redirect('error/index');
		}
    }

	/**
	 * Renders error 404 view
	 */
	public function errorAction()
	{
        $this->_view->header = 'Error 404';
        $this->_view->text = '';

        $errors = CDebug::getMessage('errors', 'action');
        if(is_array($errors)){
			foreach($errors as $error){
				$this->_view->text .= $error;		    
			}        
		}
        $this->_view->render('error/index');        
    }

	/**
	 * Redirects to another controller
	 * Parameter may consist from 2 parts: controller/action or just controller name
	 * @param string $path			Redirect path
	 * @param int $code				HTTP Response status code
	 * @param bool $isDirectUrl
	 */
    public function redirect($path, $isDirectUrl = false, $code = '')
	{
		if(APPHP_MODE == 'test') return true;
        
		if(!$isDirectUrl){
			$paramsParts = explode('/', $path);
			$calledController = str_replace('controller', '', strtolower($this->_getCalledClass()));
			$params = '';
			$baseUrl = A::app()->getRequest()->getBaseUrl();
			
			// Set controller and action according to given parameters
			if(!empty($path)){
				$parts = count($paramsParts);
				if($parts == 1){
					$controller = $calledController;
					$action = isset($paramsParts[0]) ? $paramsParts[0] : '';
				}elseif($parts == 2){
					$controller = isset($paramsParts[0]) ? $paramsParts[0] : $calledController;
					$action = isset($paramsParts[1]) ? $paramsParts[1] : '';
				}elseif($parts > 2){
					$controller = isset($paramsParts[0]) ? $paramsParts[0] : $calledController;
					$action = isset($paramsParts[1]) ? $paramsParts[1] : '';
					for($i=2; $i<$parts; $i++){
						$params .= (isset($paramsParts[$i]) ? '/'.$paramsParts[$i] : '');
					}
				}
			}
			
			$newLocation = $baseUrl.$controller.'/'.$action.$params;
		}else{
			$newLocation = $path;
		}
        
		// Prepare redirection code
		// 301 - Moved Permanently
		// 303 - See Other (since HTTP/1.1)
		// 307 - Temporary Redirect (since HTTP/1.1)
		// 302 - Found
		if(empty($code) || !is_numeric($code)){
			if(isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1'){
				// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
				$code = ($_SERVER['REQUEST_METHOD'] !== 'GET') ? 303 : 307;
			}else{
				$code = 302;
			}
		}

        // Close the session with user data
        A::app()->getSession()->closeSession();

        // Perform redirection
        header('location: '.$newLocation, true, $code);
        exit;
    }
 
	/**
	 * Returns the name of called class
	 * @return string|bool
	 */
	private function _getCalledClass()
	{
		if(function_exists('get_called_class')) return get_called_class();
		$bt = debug_backtrace();
		if(!isset($bt[1])){
			// Cannot find called class -> stack level too deep
			return false; 
		}elseif(!isset($bt[1]['type'])){
			// Type not set
			return false; 
		}else switch ($bt[1]['type']) { 
			case '::': 
				$lines = file($bt[1]['file']); 
				$i = 0; 
				$callerLine = ''; 
				do{ 
					$i++; 
					$callerLine = $lines[$bt[1]['line']-$i] . $callerLine; 
				}while (stripos($callerLine,$bt[1]['function']) === false); 
				preg_match('/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/', $callerLine, $matches); 
				if(!isset($matches[1])){
					// Could not find caller class: originating method call is obscured
					return false; 
				}
				return $matches[1]; 
				break;
			case '->': switch ($bt[1]['function']) { 
					case '__get': 
						// Edge case -> get class of calling object 
						if(!is_object($bt[1]['object'])){
							// Edge case fail. __get called on non object
							return false; 
						}
						return get_class($bt[1]['object']); 
					default: return $bt[1]['class']; 
				}
				break;
			default:
				// Unknown backtrace method type
				return false;
				break;
		}
		return false;
	}	
    
}