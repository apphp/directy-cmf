<?php
/**
 * RolePrivileges controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 *
 */

class RolePrivilegesController extends CController
{
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
        
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
			
		// Allow access only to site owner 
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Privileges Management')));
        // Set backend mode
        Website::setBackend();

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
	 * Manage privileges action handler
	 * @param int $roleID the role id
     * @param string $msg 
	 */
	public function manageAction($roleID = 0, $msg = '')
	{
		$this->_view->id = (int)$roleID;
		$cRequest = A::app()->getRequest();
		
		$roleModel = Roles::model()->findByPk((int)$roleID);
		if(!$roleModel){
			$this->redirect('roles/manage');
		}
		// This "main admin" can view and edit only "simple admin" role
		if(CAuth::isLoggedInAs('mainadmin') && $roleModel->code != 'admin'){
			$this->redirect('roles/manage');
        }
		$this->_view->role = $roleModel;
		
		if($cRequest->getPost('act') == 'send'){
			if(APPHP_MODE == 'demo'){
				$this->_view->actionMessage = CWidget::create('CMessage', array('warning', A::t('core', 'This operation is blocked in Demo Mode!'), array('button'=>true)));
		   	}else{
				// Get current privileges from database			
                $privileges = RolePrivileges::model()->findAll(
					array(
						'condition'=>'role_id='.$this->_view->id,
						'order'=>'module_code ASC, privilege_category ASC',
						'limit'=>'0, 1000'
					)			
				);
				
				// Update only privileges which value is changed
				foreach($privileges as $key => $val){
					if($val['privilege_category'] != '' && $val['privilege_code'] != ''){					
						$activity = $cRequest->getPost($val['privilege_category'].'#'.$val['privilege_code']);
						if($val['is_active'] != $activity){
							RolePrivileges::model()->updatePrivilege($roleID, $val['privilege_id'], $activity);
						}					
					}
				}
				$this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
			}			
		}

		$this->_view->privileges = RolePrivileges::model()->findAll(
			array(
				'condition'=>'role_id='.$this->_view->id,
				'order'=>'module_code ASC, privilege_category ASC',
				'limit'=>'0, 1000'
			)			
		);
	
		$this->_view->render('rolePrivileges/manage');
	}
	
}