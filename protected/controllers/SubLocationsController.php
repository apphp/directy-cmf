<?php
/**
* SubLocationsController
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
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Sub-Locations')));
		
        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = '';
		$this->view->countryId = '';
		$this->view->countryCode = '';
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
		$this->view->selectedCountry = $selectedCountry;
			
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
			$this->view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
		}
		$this->view->render('sublocations/manage');    
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
		$this->view->selectedCountry = $selectedCountry;		
    	$this->view->render('sublocations/add');        
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
		$errorType = '';
	
		$state = States::model()->findByPk((int)$id);
		if(!$state){
			$this->redirect('sublocations/manage');
		}
		$selectedCountry = Countries::model()->find('code = :code', array(':code'=>$state->country_code));
	    $this->view->selectedCountry = SiteSettings::convertToObject(Countries::model(), $selectedCountry[0]);
				
		// delete states names from translation table
		if($state->delete()){				
			if($state->getError()){
				$msg = A::t('app', 'Delete Warning Message');
				$errorType = 'warning';
			}else{		
				$msg = A::t('app', 'Delete Success Message');
				$errorType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$errorType = 'warning';
		   	}else{
				$msg = A::t('app', 'Delete Error Message');
				$errorType = 'error';
		   	}			
		}
		if(!empty($msg)){
			$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
		}
		$this->view->render('sublocations/manage');
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
		$this->view->id = $state->id;
		$selectedCountry = Countries::model()->find('code = :code', array(':code'=>$state->country_code));
	    $this->view->countryId = $selectedCountry[0]['id'];	    		
	    $this->view->countryName = $selectedCountry[0]['country_name'];	    		
	    $this->view->render('sublocations/edit');        
	}
}
