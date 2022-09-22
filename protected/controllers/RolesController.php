<?php
/**
 * Roles controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
 *
 */

class RolesController extends CController
{
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
        
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
			
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Roles Management')));
        // Set backend mode
        Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		$this->_view->rolesCondition = '';
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';        
	}	
		
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
    	$this->redirect('roles/manage');
	}
	
	/**
	 * Manage roles action handler
	 */
	public function manageAction()
	{		
		// This "main admin" can view and edit only "simple admin" role
		if(CAuth::isLoggedInAs('mainadmin')){
        	$this->_view->rolesCondition = 'code = "admin"';
        }

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->render('roles/manage');
	}
	
	/**
	 * Add new role action handler
	 */
	public function addAction()
	{
		// Only site owner can add new roles
		if(!CAuth::isLoggedInAs('owner')){
			$this->redirect('roles/manage');
        }
		
    	$this->_view->render('roles/add');
	}

	/**
	 * Edit role action handler
	 * @param int $id The role id
	 */
	public function editAction($id = 0)
	{
		$roleModel = Roles::model()->findByPk((int)$id);
		if(!$roleModel){
			$this->redirect('roles/manage');
		}

		// This "main admin" can view and edit only "simple admin" role
		if(CAuth::isLoggedInAs('mainadmin') && $roleModel->code != 'admin'){
			$this->redirect('roles/manage');
        }

		$this->_view->id = (int)$id;
    	$this->_view->render('roles/edit');
	}
  
	/**
	 * Delete role action handler
	 * @param int $id The role id 
	 */
	public function deleteAction($id = 0)
	{
		$roleModel = Roles::model()->findByPk((int)$id);
		if(!$roleModel){
			$this->redirect('roles/manage');
		}

		$alert = '';
		$alertType = '';

		// Check if this role is a system role
		if($roleModel->is_system){
			$alert = A::t('app', 'Delete System Role Alert');
			$alertType = 'error';
		}elseif($roleModel->delete()){				
			if($roleModel->getError()){
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
				$alert = $roleModel->getError() ? $roleModel->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}

		if(!empty($alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
		}
        
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect('roles/manage');
	}

}
