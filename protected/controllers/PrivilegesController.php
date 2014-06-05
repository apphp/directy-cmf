<?php
/**
* PrivilegesController
*
* PUBLIC:                  PRIVATE
* -----------              ------------------
* __construct              
* indexAction
* manageAction
*
*/
class PrivilegesController extends CController
{
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
        
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
			
		// allow access only to site owner 
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Privileges Management')));

        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = '';        
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
		$this->view->id = (int)$roleID;
		$cRequest = A::app()->getRequest();
		
		$roleModel = Roles::model()->findByPk((int)$roleID);
		if(!$roleModel){
			$this->redirect('roles/manage');
		}
		// "main admin" can view and edit only "simple admin" role
		if(CAuth::isLoggedInAs('mainadmin') && $roleModel->code != 'admin'){
			$this->redirect('roles/manage');
        }
		$this->view->role = $roleModel;
		
		if($cRequest->getPost('act') == 'send'){
			if(APPHP_MODE == 'demo'){
				$this->view->actionMessage = CWidget::create('CMessage', array('warning', A::t('core', 'This operation is blocked in Demo Mode!'), array('button'=>true)));
		   	}else{
				// get current privileges from database			
				$privileges = Privileges::model()->findAll(
					array(
						'condition'=>'role_id='.$this->view->id,
						'order'=>'privilege_category ASC',
						'limit'=>'0, 1000'
					)			
				);
				
				// update only privileges which value is changed
				foreach($privileges as $key => $val){
					if($val['privilege_category'] != '' && $val['privilege_code'] != ''){					
						$activity = $cRequest->getPost($val['privilege_category'].'#'.$val['privilege_code']);
						if($val['is_active'] != $activity){
							Privileges::model()->updatePrivilege($roleID, $val['privilege_id'], $activity);
						}					
					}
				}
				$this->view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
			}			
		}

		$this->view->privileges = Privileges::model()->findAll(
			array(
				'condition'=>'role_id='.$this->view->id,
				'order'=>'privilege_category ASC',
				'limit'=>'0, 1000'
			)			
		);
	
		$this->view->render('privileges/manage');
	}
	
}