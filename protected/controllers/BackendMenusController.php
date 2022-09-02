<?php
/**
 * BackendMenus controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
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
        
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		        
        // Block access if admin has no active privileges to access backend menus
        if(!Admins::hasPrivilege('backend_menu', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Backend Menu Management')));
        // Set backend mode 
        Website::setBackend();
        
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';        
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();
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
	 */
	public function manageAction($pid = 0)
	{
	    // Block access if admin has no active privilege to manage backend menus
		Website::prepareBackendAction('view', 'backend_menu', 'backend/index');

		$this->_view->parentId = 0;
		$this->_view->parentName = '';
		$this->_view->parentIcon = 'no_image.png';
		$parentMenu = BackendMenus::model()->findByPk((int)$pid);
		if(!empty($parentMenu)){
			$this->_view->parentId = (int)$pid;
			$this->_view->parentName = $parentMenu->menu_name;
		}
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
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
	    // Block access if admin has no active privilege to edit backend menus
		Website::prepareBackendAction('edit', 'backend_menu', 'backendMenus/manage');

		$this->_view->parentId = 0;
		$this->_view->parentName = A::t('app', 'Top Level Menu');
		$menu = BackendMenus::model()->findbyPk($id);
    	if(!$menu){
	  		$this->redirect('backendMenus/manage');
    	}		
		$this->_view->menuName = $menu->menu_name;
        
        // Delete the icon file
        if($icon === 'delete'){
        	$alert = '';
        	$alertType = '';

            if(!empty($menu)){
                $iconFile = 'templates/backend/images/icons/'.$menu->icon;
                $menu->icon = '';
            
                // Save the changes in admins table
                if($menu->parent_id != 0 && $menu->save()){
                    // Delete the file
                    if(CFile::deleteFile($iconFile)){
                        $alert = A::t('app', 'Image Delete Success Message');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('app', 'Image Delete Warning');
                        $alertType = 'warning';
                    }        		
                }else{
                    $alert = A::t('app', 'Image Delete Error Message');
                    $alertType = 'error';                
                }
            }

			if(!empty($alert)){
				$this->_cSession->setFlash('alert', $alert);
				$this->_cSession->setFlash('alertType', $alertType);
				$this->redirect('backendMenus/edit/id/'.$id.($pid ? '/pid/'.$pid : ''));
			}
        }

		// Prepare alert message
		if(!$this->_cRequest->isPostRequest() && $this->_cSession->hasFlash('alert')){
			$this->_view->actionMessage = CWidget::create('CMessage',
				array($this->_cSession->getFlash('alertType'), $this->_cSession->getFlash('alert'), array('button'=>true))
			);
		}

        // Get parent menu info
		if(!empty($menu)){
        	$parentMenu = BackendMenus::model()->findbyPk($menu->parent_id);
        	if(!empty($parentMenu)){
				$this->_view->parentId = $parentMenu->id;
				$this->_view->parentName = $parentMenu->menu_name;
			}
        }
		
    	$this->_view->id = (int)$id;
    	$this->_view->render('backendMenus/edit');
	}
  
}