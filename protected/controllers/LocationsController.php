<?php
/**
* LocationsController
*
* PUBLIC:                  PRIVATE
* -----------              ------------------
* __construct              
* indexAction
* manageAction
* addAction
* editAction
* deleteAction
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

        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view locations
		if(!Admins::hasPrivilege('locations', 'view','edit')){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Locations Management')));
		
        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = '';
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
		$this->view->render('locations/manage');    
    }

    /**
     * Add new location (country) action handler
     */
	public function addAction()
	{		
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('locations', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
		$this->view->render('locations/add');        
	}

	/**
	 * Edit location (country) action handler
	 * @param int $id The country id 
	 */
	public function editAction($id = 0)
	{		
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('locations', 'edit')){
     		$this->redirect('backend/index');
     	}
		
     	$country = Countries::model()->findByPk($id);
		if(!$country){
			$this->redirect('locations/manage');
		}
		$this->view->country = $country;		
		$this->view->render('locations/edit');        
	}

	/**
	 * Delete location (country) action handler
	 * @param int $id the country id 
	 */
	public function deleteAction($id = 0)
	{
		// block access if admin has no active privilege to edit locations
     	if(!Admins::hasPrivilege('locations', 'edit')){
     		$this->redirect('backend/index');
     	}
     	
		$msg = '';
		$errorType = '';
	
		$country = Countries::model()->findByPk($id);
		if(!$country){
			$this->redirect('locations/manage');
		}
		
		// check if the country is default
		if($country->is_default){
			$msg = A::t('app', 'Delete Default Alert');
			$errorType = 'error';
		}else if($country->delete()){				
			if($country->getError()){
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
		$this->view->render('locations/manage');
	}

}
