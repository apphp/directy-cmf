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
 * changeStatusAction
 * deleteAction
 *
 */

class LocationsController extends CController
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

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Set meta tags according to Location
		Website::setMetaTags(array('title'=>A::t('app', 'Locations')));
		// Set backend mode
		Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		$this->_view->backendPath = $this->_backendPath;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }
        
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect($this->_backendPath.'locations/manage');
    }

    /**
     * Manage locations action handler
     */
	public function manageAction()
	{			
		// Block access if admin has no active privilege to manage locations
		if(!Admins::hasPrivilege('locations', array('view', 'edit'))){
			$this->redirect($this->_backendPath.'dashboard/index');
		}
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->subLocationCounts = $this->_prepareSubLocationCounts();
		$this->_view->render($this->_backendPath.'locations/manage');
    }

    /**
     * Add new location (country) action handler
     */
	public function addAction()
	{		
		// Block access if admin has no active privilege to add locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
     	
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Add Locations')));

		$this->_view->render($this->_backendPath.'locations/add');
	}

	/**
	 * Edit location (country) action handler
	 * @param int $id the country id 
	 */
	public function editAction($id = 0)
	{		
		// Block access if admin has no active privilege to edit locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
		
     	$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect($this->_backendPath.'locations/manage');
		}
        
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Edit Locations')));

		$this->_view->country = $country;		
		$this->_view->render($this->_backendPath.'locations/edit');
	}

    /**
     * Change status location action handler
     * @param int $id 		the location ID
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 0)
    {
		// Block access if admin has no active privilege to edit locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
		
		$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect($this->_backendPath.'locations/manage');
		}
		
		if(Countries::model()->updateByPk($id, array('is_active'=>($country->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
		}else{
			$alert = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
		}
		 
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'locations/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
	/**
	 * Delete location (country) action handler
	 * @param int $id the country id 
	 */
	public function deleteAction($id = 0)
	{
		// Block access if admin has no active privilege to delete locations
        Website::prepareBackendAction('edit', 'locations', 'locations/manage');
     	
		$country = Countries::model()->findByPk((int)$id);
		if(!$country){
			$this->redirect($this->_backendPath.'locations/manage');
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
		
		$this->redirect($this->_backendPath.'locations/manage');
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
