<?php
/**
 * SubLocations controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * deleteAction
 * editAction
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

        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view locations
		if(!Admins::hasPrivilege('locations', 'view')){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Sub-Locations')));
        // set backend mode
        Website::setBackend();

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
        $this->redirect('sublocations/manage');    
    }

    /**
     * Manage states action handler
     * @param int $country The country id
     * @param string $msg 
     */
	public function manageAction($country = 0, $msg = '')
	{
		$selectedCountry = Countries::model()->findByPk((int)$country);
		if(!$selectedCountry){
			$this->redirect('locations/manage');
		}
		$this->_view->selectedCountry = $selectedCountry;
			
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
		$this->_view->render('sublocations/manage');    
    }

    /**
     * Add new state action handler
     * @param int $country The country id
     */
	public function addAction($country = 0)
	{		
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('locations', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$selectedCountry = Countries::model()->findByPk((int)$country);
		if(!$selectedCountry){
			$this->redirect('locations/manage');
		}
		$this->_view->selectedCountry = $selectedCountry;		
    	$this->_view->render('sublocations/add');        
	}
		
	/**
	 * Delete state action handler
	 * @param int $id The state id
	 */
	public function deleteAction($id = 0)
	{
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('locations', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$msg = '';
		$msgType = '';
	
		$state = States::model()->findByPk((int)$id);
		if(!$state){
			$this->redirect('subLocations/manage');
		}
        $selectedCountry = Countries::model()->find('code = :code', array(':code'=>$state->country_code));
		if($selectedCountry){
            $this->_view->selectedCountry = $selectedCountry;
        }else{
            $this->redirect('subLocations/manage');
        }
				
		// delete states names from translation table
		if($state->delete()){				
			if($state->getError()){
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
				$msg = A::t('app', 'Delete Error Message');
				$msgType = 'error';
		   	}			
		}
		if(!empty($msg)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		}
		$this->_view->render('sublocations/manage');
	}
	
	/**
	 * Edit state action handler
	 * @param int $id The state id
	 */
	public function editAction($id = 0)
	{		
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('locations', 'edit')){
     		$this->redirect('backend/index');
     	}
		
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
}
