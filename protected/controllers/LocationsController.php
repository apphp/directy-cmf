<?php
/**
 * Locations controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _prepareSubLocationCounts
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
        $this->redirect('locations/manage');    
    }

    /**
     * Manage locations action handler
     */
	public function manageAction()
	{			
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Block access if admin has no active privilege to manage locations
		if(!Admins::hasPrivilege('locations', array('view', 'edit'))){
			$this->redirect('backend/index');
		}
		
        // Set backend mode
        Website::setBackend();
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->subLocationCounts = $this->_prepareSubLocationCounts();
		$this->_view->render('locations/manage');    
    }

    /**
     * Add new location (country) action handler
     */
	public function addAction()
	{		
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to add locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
     	
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Add Locations')));
        // Set backend mode
        Website::setBackend();

		$this->_view->render('locations/add');        
	}

	/**
	 * Edit location (country) action handler
	 * @param int $id the country id 
	 */
	public function editAction($id = 0)
	{		
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to edit locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
		
     	$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect('locations/manage');
		}
        
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Edit Locations')));
        // Set backend mode
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
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Block access if admin has no active privilege to delete locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
     	
		$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect('locations/manage');
		}
		
    	$alert = '';
    	$alertType = '';
	
		// Check if the country is default
		if($country->is_default){
			$alert = A::t('app', 'Delete Default Alert');
			$alertType = 'error';
		}elseif($country->delete()){				
			if($country->getError()){
				$alert = A::t('app', 'Delete Warning Message');
				$alertType = 'warning';
			}else{		
				$alert = A::t('app', 'Delete Success Message');
				$alertType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$alert = CDatabase::init()->getErrorMessage();
				$alertType = 'warning';
		   	}else{
				$alert = $country->getError() ? $country->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect('locations/manage');
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

    /**
     * Prepares array of total counts for each sub-location
     * @return array
     */
    private function _prepareSubLocationCounts()
    {
		$result = States::model()->count(array('condition'=>'is_active = 1', 'select'=>CConfig::get('db.prefix').'states.country_code', 'count'=>'*', 'groupBy'=>'country_code', 'allRows'=>true));
		$subLocationCounts = array();
		foreach($result as $key => $model){
			$subLocationCounts[$model['country_code']] = $model['cnt'];
		}
		
		return $subLocationCounts;
	}

}
