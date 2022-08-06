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
        
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
			
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Roles Management')));
        // set backend mode
        Website::setBackend();

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
     * @param string $msg 
	 */
	public function manageAction($msg = '')
	{		
		// "main admin" can view and edit only "simple admin" role
		if(CAuth::isLoggedInAs('mainadmin')){
        	$this->_view->rolesCondition = 'code = "admin"';
        }
		if($msg == 'updated'){
			$this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
		}
		$this->_view->render('roles/manage');
	}
	
	/**
	 * Add new role action handler
	 */
	public function addAction()
	{
		// only site owner can add new roles
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

		// "main admin" can view and edit only "simple admin" role
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

		$msg = '';
		$msgType = '';

		// check if this role is a system role
		if($roleModel->is_system){
			$msg = A::t('app', 'Delete System Role Alert');
			$msgType = 'error';
		}else if($roleModel->delete()){				
			if($roleModel->getError()){
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
				$msg = $roleModel->getError() ? $roleModel->getErrorMessage() : A::t('app', 'Delete Error Message');
				$msgType = 'error';
		   	}			
		}

		if(!empty($msg)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		}
        
		// block access if admin has no active privilege to view roles
		if(CAuth::isLoggedInAs('owner')){
			// "main admin" can view and edit only "simple admin" role
			if(CAuth::isLoggedInAs('mainadmin')){
				$this->_view->rolesCondition = 'code = "admin"';
			}		
			$this->_view->render('roles/manage');
		}else{
			$this->redirect('roles/manage');
		}		
	}

}