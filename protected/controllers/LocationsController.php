<?php
/**
 * Locations controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
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
        
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }
        
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect('locations/manage');    
    }

    /**
     * Manage locations action handler
     * @param string $msg 
     */
	public function manageAction($msg = '')
	{			
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');

		// block access if admin has no active privilege to manage locations
		if(!Admins::hasPrivilege('locations', array('view', 'edit'))){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Locations Management')));
        // set backend mode
        Website::setBackend();
		
		switch($msg){
			case 'added':
				$message = A::t('core', 'The adding operation has been successfully completed!');
				break;
			case 'updated':
				$message = A::t('core', 'The updating operation has been successfully completed!');
				break;
			default:
				$message = '';
		}
		if(!empty($message)){
			$this->_view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
		}
		$this->_view->render('locations/manage');    
    }

    /**
     * Add new location (country) action handler
     */
	public function addAction()
	{		
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to add locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
     	
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Add Locations')));
        // set backend mode
        Website::setBackend();

		$this->_view->render('locations/add');        
	}

	/**
	 * Edit location (country) action handler
	 * @param int $id the country id 
	 */
	public function editAction($id = 0)
	{		
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to edit locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
		
     	$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect('locations/manage');
		}
        
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Edit Locations')));
        // set backend mode
        Website::setBackend();

		$this->_view->country = $country;		
		$this->_view->render('locations/edit');        
	}

	/**
	 * Delete location (country) action handler
	 * @param int $id the country id 
	 */
	public function deleteAction($id = 0)
	{
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');

		// block access if admin has no active privilege to delete locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
     	
		$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect('locations/manage');
		}
		
		$msg = '';
		$msgType = '';
	
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Delete Locations')));
        // set backend mode
        Website::setBackend();

		// check if the country is default
		if($country->is_default){
			$msg = A::t('app', 'Delete Default Alert');
			$msgType = 'error';
		}else if($country->delete()){				
			if($country->getError()){
				$msg = A::t('app', 'Delete Warning Message');
				$msgType = 'warning';
			}else{		
				$msg = A::t('app', 'Delete Success Message');
				$msgType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$msgType = 'warning';
		   	}else{
				$msg = $country->getError() ? $country->getErrorMessage() : A::t('app', 'Delete Error Message');
				$msgType = 'error';
		   	}			
		}
		
		if(!empty($msg)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		}
		
		// block access if admin has no active privilege to view locations
		if(Admins::hasPrivilege('locations', array('view'))){
			$this->_view->render('locations/manage');
		}else{
			$this->redirect('locations/manage');
		}		
		
	}

	/**
	 * Returns sub-locations (states) by given location (country)
	 * @return JSON array
	 */
	public function getSubLocationsAction()
	{
        $arr = array();
        $cRequest = A::app()->getRequest();
        
        if($cRequest->getPost('act') == 'send'){        
            $arr[] = '{"status": "1"}';
            $result = States::model()->findAll('country_code = :code', array('s:code'=>$cRequest->getPost('country_code')));
            foreach($result as $key => $val){
                $arr[] = '{"code": "'.$val['code'].'", "name": "'.$val['state_name'].'"}';  
            }            
        }else{
            $arr[] = '{"status": "0"}';            
        }
        
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-Type: application/json');

        echo '[';
        echo implode(',', $arr);
        echo ']';
    
        exit;
    }

}
