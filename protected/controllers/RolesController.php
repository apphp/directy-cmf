<?php
/**
* RolesController
*
* PUBLIC:                  PRIVATE
* -----------              ------------------
* __construct              
* indexAction
* manageAction
* editAction
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
        
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
			
        if(!CAuth::isLoggedInAs('owner', 'mainadmin')){
        	$this->redirect('backend/index');
        }
        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Roles Management')));

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
	 * Manage roles action handler
     * @param string $msg 
	 */
	public function manageAction($msg = '')
	{
		$this->view->rolesCondition = '';
		// "main admin" can view and edit only "simple admin" role
		if(CAuth::isLoggedInAs('mainadmin')){
        	$this->view->rolesCondition = 'code = "admin"';
        }
		if($msg == 'updated'){
			$this->view->actionMessage = CWidget::create('CMessage', array('success', A::t('core', 'The updating operation has been successfully completed!'), array('button'=>true)));
		}
		$this->view->render('roles/manage');
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
		$this->view->id = (int)$id;
    	$this->view->render('roles/edit');
	}
  
}