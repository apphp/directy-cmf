<?php
/**
* BackendMenusController
*
* PUBLIC:                  PRIVATE
* -----------              ------------------
* __construct              
* indexAction
* manageAction
* editAction
*
*/
class BackendMenusController extends CController
{
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
        
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');			
		        
        // block access if admin has no active privilege to view backend menu
        if(!Admins::hasPrivilege('backend_menu', array('view','edit'))){
        	$this->redirect('backend/index');
        }
		
        // set meta tags according to active language
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Backend Menu Management')));

        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = '';        
	}	
		
	/**
	 * Controller default action handler
     */
	public function indexAction()
	{
    	$this->redirect('backendMenus/manage');
	}
	
	/**
	 * Backend menu manage handler
	 * @param int $pid the id of the parent menu, if $pid == 0 views up level menu items.
     * @param string $msg 
	 */
	public function manageAction($pid = 0, $msg = '')
	{
		$this->view->parentId = 0;
		$this->view->parentName = '';
		$this->view->parentIcon = 'no-image.png';
		$parentMenu = BackendMenus::model()->findByPk((int)$pid);
		if(!empty($parentMenu)){
			$this->view->parentId = (int)$pid;
			$this->view->parentName = $parentMenu->menu_name;
		}
		
	    switch($msg){
        	case 'updated': 
				$message = A::t('core', 'The updating operation has been successfully completed!');
				break;						
			default:
				$message = '';						
        }
        if(!empty($message)){
    		$this->view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
    	}
		$this->view->render('backendMenus/manage');
	}
	
	/**
	 * Backend menu edit action handler
	 * @param int $id the menu id 
	 */
	public function editAction($id = 0)
	{
	    // block access if admin has no active privilege to edit backend menu
     	if(!Admins::hasPrivilege('backend_menu', 'edit')){
     		$this->redirect('backend/index');
     	}
		$this->view->parentId = 0;
		$this->view->parentName = A::t('app', 'Top Level Menu');
		$menu = BackendMenus::model()->findbyPk((int)$id);
        if(!empty($menu)){
        	$parentMenu = BackendMenus::model()->findbyPk((int)$menu->parent_id);
        	if(!empty($parentMenu)){
				$this->view->parentId = $parentMenu->id;
				$this->view->parentName = $parentMenu->menu_name;
			}
        }
    	$this->view->id = (int)$id;
    	$this->view->render('backendMenus/edit');
	}
  
}