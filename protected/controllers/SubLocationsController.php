<?php
/**
 * SubLocations controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 *
 */

class SubLocationsController extends CController
{
	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to access sub-locations
		if(!Admins::hasPrivilege('locations', array('view', 'edit'))){
			$this->redirect('backend/index');
		}
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Sub-Locations')));
        // Set backend mode
        Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->countryId = '';
		$this->_view->countryCode = '';
	}
        
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('subLocations/manage');    
    }

    /**
     * Manage states action handler
     * @param int $country The country id
     */
	public function manageAction($country = 0)
	{
		// Block access if admin has no active privilege to manage sub-locations
        Website::prepareBackendAction('view', 'locations', 'backend/index');

		$selectedCountry = Countries::model()->findByPk((int)$country);
		if(!$selectedCountry){
			$this->redirect('locations/manage');
		}
		$this->_view->selectedCountry = $selectedCountry;
		$this->_view->countryId = $country;
			
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->render('subLocations/manage');    
    }

    /**
     * Add new state action handler
     * @param int $country The country id
     */
	public function addAction($country = 0)
	{		
		// Block access if admin has no active privilege to add sub-locations
		Website::prepareBackendAction('edit', 'locations', 'subLocations/manage');		
		
     	$selectedCountry = Countries::model()->findByPk((int)$country);
		if(!$selectedCountry){
			$this->redirect('locations/manage');
		}
		$this->_view->selectedCountry = $selectedCountry;		
    	$this->_view->render('sublocations/add');        
	}
		
	/**
	 * Edit state action handler
	 * @param int $id The state id
	 */
	public function editAction($id = 0)
	{		
		// Block access if admin has no active privilege to edit sub-locations
		Website::prepareBackendAction('edit', 'locations', 'subLocations/manage');
		
     	$state = States::model()->findByPk((int)$id);
		if(!$state){
			$this->redirect('sublocations/manage');
		}
		$this->_view->id = $state->id;
        $this->_view->countryId = '';
        $this->_view->countryName = '';	   		                        
		if($selectedCountry = Countries::model()->find('code = :code', array(':code'=>$state->country_code))){
            $this->_view->countryId = $selectedCountry->id;	    		
            $this->_view->countryName = $selectedCountry->country_name;	    		            
        }
        $this->_view->render('sublocations/edit');        
	}

    /**
     * Change status sub-location action handler
     * @param int $id 	the sub-location ID
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 0)
    {
		// Block access if admin has no active privilege to edit sub-locations
		Website::prepareBackendAction('edit', 'locations', 'subLocations/manage');
		
		$countryId = 0;
		
     	$state = States::model()->findByPk((int)$id);
		if(!$state){
			$this->redirect('subLocations/manage');
		}else{
			$country = Countries::model()->find('code = :code', array('s:code'=>$state->country_code));
			if(!$country){
				$this->redirect('subLocations/manage');
			}
			$countryId = $country->id;
		}	
		
		if(States::model()->updateByPk($id, array('is_active'=>($state->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
		}else{
			$alert = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
		}
		 
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect('subLocations/manage/country/'.$countryId.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
	/**
	 * Delete state action handler
	 * @param int $id The state id
	 */
	public function deleteAction($id = 0)
	{
		// Block access if admin has no active privilege to delete sub-locations
		Website::prepareBackendAction('edit', 'locations', 'subLocations/manage');
		
		$state = States::model()->findByPk((int)$id);
		if(!$state){
			$this->redirect('subLocations/manage');
		}

        $selectedCountry = Countries::model()->find('code = :code', array(':code'=>$state->country_code));
		if(!$selectedCountry){
            $this->redirect('subLocations/manage');
        }
		
    	$alert = '';
    	$alertType = '';
	
		// Delete states names from translation table
		if($state->delete()){				
			if($state->getError()){
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
				$alert = $state->getError() ? $state->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect('subLocations/manage/country/'.$selectedCountry->id);
	}

}
