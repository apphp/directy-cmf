<?php
/**
 * AjaxHandler controller
 * This controller is intended for both Backend and Frontend modes
 *
 * PUBLIC:                  				PRIVATE
 * -----------              				------------------
 * __construct              				_output
 * indexAction
 * getLocationsAction
 *
 */

class AjaxController extends CController
{

    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

		// Block access if this is not AJAX request
		$this->_cRequest = A::app()->getRequest();
		if(!$this->_cRequest->isAjaxRequest()){
			$this->redirect(Website::getDefaultPage());
		}
	}	

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->_output();
    }

    /**
     * Returns locations 
     * @return json
     */
    public function getLocationsAction()
    {
		$arr = array();
		
		if(CAuth::isLoggedInAsAdmin()){
			$search = $this->_cRequest->getPost('search');
			$act = $this->_cRequest->getPost('act');
			
 			if($act == 'send' && !empty($search)){
				$locations = Countries::model()->findAll(array('condition'=>CConfig::get('db.prefix').'country_translations.name LIKE :name', 'order'=>'sort_order ASC'), array(':name'=>$search.'%'));
				if($locations){
					$arr[] = '{"status": "1"}';
					foreach($locations as $key => $location){
						$arr[] = '{"id": "'.htmlentities($location['country_name']).'", "label": "'.htmlentities($location['country_name']).'"}';
						//$arr[] = '{"id": "'.$location['id'].'", "label": "'.htmlentities($location['country_name']).'"}';
					}									
				}				
			}			
		}

		if(empty($arr)){
			$arr[] = '{"status": "0"}';
		}
		
		$this->_output($arr);
	}

	/**
	 * Outputs data to browser
	 * @param $array $data
	 * @param string $returnArray
	 */
	private function _output($data = array(), $returnArray = true)
	{
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Pragma: no-cache'); // HTTP/1.0
		header('Content-Type: application/json');

		if($returnArray){
			echo '[';
			echo array($data) ? implode(',', $data) : '';
			echo ']';
		}else{
            echo '{';
            echo implode(',', $data);
            echo '}';
		}
		
		exit;
	}
	
}
