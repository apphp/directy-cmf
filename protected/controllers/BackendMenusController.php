<?php
/**
 * BackendMenus controller
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
        if(!Admins::hasPrivilege('backend_menu', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
		
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Backend Menu Management')));
        // set backend mode 
        Website::setBackend();
        
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';        
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
		$this->_view->parentId = 0;
		$this->_view->parentName = '';
		$this->_view->parentIcon = 'no-image.png';
		$parentMenu = BackendMenus::model()->findByPk((int)$pid);
		if(!empty($parentMenu)){
			$this->_view->parentId = (int)$pid;
			$this->_view->parentName = $parentMenu->menu_name;
		}
		
	    switch($msg){
        	case 'updated': 
				$message = A::t('core', 'The updating operation has been successfully completed!');
				break;						
			default:
				$message = '';						
        }
        if(!empty($message)){
    		$this->_view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
    	}
		$this->_view->render('backendMenus/manage');
	}
	
	/**
	 * Backend menu edit action handler
	 * @param int $id the menu id
	 * @param int $pid
	 * @param string $icon has value 'delete' in order to delete the icon file
	 */
	public function editAction($id = 0, $pid = 0, $icon = '')
	{
	    // block access if admin has no active privilege to edit backend menu
     	if(!Admins::hasPrivilege('backend_menu', 'edit')){
     		$this->redirect('backend/index');
     	}
		$this->_view->parentId = 0;
		$this->_view->parentName = A::t('app', 'Top Level Menu');
		$menu = BackendMenus::model()->findbyPk((int)$id);
    	if(!$menu){
	  		$this->redirect('backendMenus/manage');
    	}
        
        // delete the icon file
        if($icon === 'delete'){
        	$msg = '';
        	$msgType = '';

            if(!empty($menu)){
                $iconFile = 'templates/backend/images/icons/'.$menu->icon;
                $menu->icon = '';
            
                // save the changes in admins table
                if($menu->parent_id != 0 && $menu->save()){
                    // delete the file
                    if(CFile::deleteFile($iconFile)){
                        $msg = A::t('app', 'Image Delete Success Message');
                        $msgType = 'success';
                    }else{
                        $msg = A::t('app', 'Image Delete Warning');
                        $msgType = 'warning';
                    }        		
                }else{
                    $msg = A::t('app', 'Image Delete Error Message');
                    $msgType = 'error';                
                }
            }
        	if(!empty($msg)){
        		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
        	}
        }

        if(!empty($menu)){
        	$parentMenu = BackendMenus::model()->findbyPk((int)$menu->parent_id);
        	if(!empty($parentMenu)){
				$this->_view->parentId = $parentMenu->id;
				$this->_view->parentName = $parentMenu->menu_name;
			}
        }
    	$this->_view->id = (int)$id;
    	$this->_view->render('backendMenus/edit');
	}
  
}