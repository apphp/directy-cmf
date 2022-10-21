<?php
/**
 * Locations frontend controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct
 * indexAction
 * getSubLocationsAction
 *
 */

class LocationsController extends CController
{
	/**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Set backend mode
		Website::setFrontend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }
        
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect(Website::getDefaultPage());
    }

	/**
	 * Returns sub-locations (states) by given location (country)
	 * @return JSON array
	 */
	public function getSubLocationsAction()
	{
        $arr = array();

        if($this->_cRequest->isAjaxRequest() && $this->_cRequest->getPost('act') == 'send'){
            $arr[] = '{"status": "1"}';
            $result = States::model()->findAll('country_code = :code', array('s:code'=>$this->_cRequest->getPost('country_code')));
            foreach($result as $key => $val){
                $arr[] = '{"code": "'.$val['code'].'", "name": "'.$val['state_name'].'"}';  
            }            
        }else{
            $arr[] = '{"status": "0"}';            
        }
        
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-Type: application/json');

        echo '[';
        echo implode(',', $arr);
        echo ']';
    
        exit;
    }

}
