<?php
/**
 * FrontendMenus controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _getPlacementsList
 * indexAction				_getAccessLevelsList
 * manageAction				_getLinkTargetsList
 * changeStatusAction		_getMenuTypesList 	
 * addAction         		_getPagesLinks
 * editAction               _getModulesLinks
 * deleteAction             _getModulesBlock
 *                          _getDialogContent
 *                          _getModulesList
 *                          _prepareSubMenuCounts
 *
 */

class FrontendMenusController extends CController
{
    /**
	 * Class default constructor
     */
    public function __construct()
	{
        parent::__construct();
        
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());
        
        // Block access if admin has no active privilege to access frontend menu
        if(!Admins::hasPrivilege('frontend_menu', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
       
        // Set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('app', 'Frontend Menu Management')));
        // Set backend mode
        Website::setBackend();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    	$this->_view->parentId = 0;
		$this->_view->parentName = '';
		$this->_view->moduleCode = '';
        $this->_view->menuType = '';
		
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		// Show all menu placements, available for current Frontend template
    	$this->_view->placementsList = $this->_getPlacementsList();
		// Show all possible menu placements
		$this->_view->placementsFilterList = $this->_getPlacementsList('all');
		$this->_view->modulesFilterList = $this->_getModulesList();
    	$this->_view->accessLevelsList = $this->_getAccessLevelsList();
		$this->_view->linkTargetsList = $this->_getLinkTargetsList();
		$this->_view->menuTypesList = $this->_getMenuTypesList();
		$this->_view->pagesList = $this->_getPagesLinks();
		$this->_view->modulesList = $this->_getModulesLinks();
		$this->_view->modulesBlock = $this->_getModulesBlock();
	}
	
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect('frontendMenus/manage');	   	
    }	  
    
    /**
     * Manage menus action handler
	 * @param int $pid the ID of the parent menu, if $pid == 0 views up level menu items.
     */
    public function manageAction($pid = 0)
    {
	    // Block access if admin has no active privilege to manage backend menus
		Website::prepareBackendAction('view', 'frontend_menu', 'backend/index');

		$this->_view->parentId = 0;
		$menu = FrontendMenus::model()->findByPk((int)$pid);
		if(!empty($menu)){
			$this->_view->parentId = (int)$pid;
			$this->_view->parentName = $menu->menu_name;
			$this->_view->menuType = $menu->menu_type;
		}

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->subMenuCounts = $this->_prepareSubMenuCounts();
		$this->_view->render('frontendMenus/manage');	   	
    }
    
    /**
     * Change status menu action handler
     * @param int $id the menu ID
     * @param int $pid the ID of the parent menu, if $pid == 0 views up level menu items.
     */
    public function changeStatusAction($id, $pid = 0)
    {
		// Block access if admin has no active privilege to edit frontend menus
		Website::prepareBackendAction('edit', 'frontend_menu', 'frontendMenus/manage');		
		
		$parentMenuPart = '';

		$menu = FrontendMenus::model()->findbyPk($id);
		if(!empty($menu)){
			if(FrontendMenus::model()->updateByPk($id, array('is_active'=>($menu->is_active == 1 ? '0' : '1')))){
				$alert = A::t('app', 'Status has been successfully changed!');
				$alertType = 'success';
			}else{
				$alert = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
				$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
			}
			 
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);

			if($parentMenu = FrontendMenus::model()->findbyPk($pid)){
				$parentMenuPart = '/pid/'.(int)$pid;
			}
		}
		
        $this->redirect('frontendMenus/manage'.$parentMenuPart);        
    }

    /**
     * Add new menu action handler
	 * @param int $pid the ID of the parent menu, if $pid == 0 views up level menu items.
     */
    public function addAction($pid = 0)
    {
		// Block access if admin has no active privilege to add frontend menus
		Website::prepareBackendAction('edit', 'frontend_menu', 'frontendMenus/manage');		

		$this->_view->parentId = 0;
		$this->_view->parentName = A::t('app', 'Top Level Menu');
		$this->_view->menuType = '';
		$menu = FrontendMenus::model()->findbyPk($pid);
		if(!empty($menu)){
			$this->_view->parentId = $pid;
			$this->_view->parentName = $menu->menu_name;
		}

		// Prepare menu type
		if(A::app()->getRequest()->isPostRequest()){
			$this->_view->menuType = A::app()->getRequest()->getPost('menu_type');            
        }
		if(A::app()->getRequest()->getPost('APPHP_FORM_ACT') == 'change'){
			A::app()->getRequest()->setPost('link_url', '');
			A::app()->getRequest()->setPost('link_target', '');
			A::app()->getRequest()->setPost('module_code', '');
		}

		$dialog = $this->_getDialogContent($this->_view->menuType, 'Add');
		$this->_view->dialogTitle = $dialog['title'];
		$this->_view->dialogContent = $dialog['content'];

        $sortOrder = FrontendMenus::model()->count();
        $this->_view->sortOrder = ($sortOrder < 999) ? $sortOrder + 1 : 999;        		        
    	$this->_view->render('frontendMenus/add');
    }    
  
    /**
     * Menu edit action handler
     * @param int $id the menu id
     */
    public function editAction($id = 0)
    {
		// Block access if admin has no active privilege to edit frontend menus
		Website::prepareBackendAction('edit', 'frontend_menu', 'frontendMenus/manage');		
		
		$this->_view->parentId = 0;
		$this->_view->parentName = A::t('app', 'Top Level Menu');
		$this->_view->moduleCode = '';
		$this->_view->menuType = '';
		$this->_view->isActive = '';
		
		$menu = FrontendMenus::model()->findByPk($id);
        if(!$menu){
			$this->redirect('frontendMenus/manage');			
		}
		
		$this->_view->moduleCode = $menu->module_code;
		$this->_view->menuType = $menu->menu_type;
		$this->_view->isActive = $menu->is_active;
		$this->_view->menuName = $menu->menu_name;

		$parentMenu = FrontendMenus::model()->findbyPk($menu->parent_id);
		if(!empty($parentMenu)){
			$this->_view->parentName = $parentMenu->menu_name;
			$this->_view->parentId = $parentMenu->id;
			$this->_view->moduleCode = '';
		}

		// Prepare menu type
		if(A::app()->getRequest()->isPostRequest()){
			$this->_view->menuType = A::app()->getRequest()->getPost('menu_type');            
        }
		if(A::app()->getRequest()->getPost('APPHP_FORM_ACT') == 'change'){
			A::app()->getRequest()->setPost('link_url', '');
			A::app()->getRequest()->setPost('link_target', '');
			A::app()->getRequest()->setPost('module_code', '');
		}		

		$dialog = $this->_getDialogContent($this->_view->menuType, 'Edit');
		$this->_view->dialogTitle = $dialog['title'];
		$this->_view->dialogContent = $dialog['content'];
    	$this->_view->id = (int)$id;
		
    	$this->_view->render('frontendMenus/edit');
    }    
  
    /**
     * Delete menu action handler
     * @param int $id the menu id
     * @param int $pid the ID of the parent menu, if $pid == 0 views up level menu items.
     */
    public function deleteAction($id = 0, $pid = 0)
    {
		// Block access if admin has no active privilege to delete frontend menus
		Website::prepareBackendAction('edit', 'frontend_menu', 'frontendMenus/manage');		

    	$alert = '';
    	$alertType = '';
    
		$menu = FrontendMenus::model()->findByPk($id);
		if(!$menu){
			$this->redirect('frontendMenus/manage');
		}
		
		if($menu->delete()){
			$alert = A::t('app', 'Delete Success Message');
			$alertType = 'success';	
		}else{
			if(APPHP_MODE == 'demo'){
				$alert = CDatabase::init()->getErrorMessage();
				$alertType = 'warning';	
		   	}else{
				$alert = $menu->getError() ? $menu->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect('frontendMenus/manage'.(!empty($pid) ? '/pid/'.(int)$pid : ''));
    }

    /**
     * Returns an array with available menu placements
     * @param string $type
     */
    private function _getPlacementsList($type = '')
    {
    	$placementsList = array(			
			'left' 	=> A::t('app', 'Left'),
			'top' 	=> A::t('app', 'Top'),
			'right' => A::t('app', 'Right'),
			'bottom'=> A::t('app', 'Bottom'),
    	);
		
		if($type != 'all'){
			// Remove menu placements that are not allowed by active template
			$template = Bootstrap::init()->getSettings('template');
			// Load data from XML file
			if(@file_exists('templates/'.$template.'/info.xml')) {
				$xml = simplexml_load_file('templates/'.$template.'/info.xml');		 
				if(isset($xml->menus->menu)){
					$templateMenusArray = array();
					foreach($xml->menus->menu as $menu){
						$templateMenusArray[] = strtolower($menu);
					}
					foreach($placementsList as $key => $value){
						if(!in_array($key, $templateMenusArray)) unset($placementsList[$key]);
				   }				
				}
			}
		}
		
		$placementsList['hidden'] = A::t('app', 'Hidden');
		return $placementsList;
    }
    
    /**
     * Returns an array with available menu access levels
     */
    private function _getAccessLevelsList()
    {
    	return array(
			'public' 	 => A::t('app', 'Public'),
			'registered' => A::t('app', 'Registered'),
		);
    }
	
    /**
     * Returns an array with available menu access levels
     */
    private function _getLinkTargetsList()
    {
    	return array(
			''  	 => '',
			'_self'  => '_self',
			'_blank' => '_blank',
		);
    }
	
    /**
     * Returns an array with available menu types
     */
    private function _getMenuTypesList()
    {
    	return array(
			'pagelink'    => A::t('app', 'Page Link'),
			'modulelink'  => A::t('app', 'Module Link'),
			'moduleblock' => A::t('app', 'Module Block'),
		);
    }
	
    /**
     * Returns an array with available pages links
     */
    private function _getPagesLinks()
    {
		$result = array();
        if(Modules::model()->isInstalled('cms')){
			$pages = Pages::model()->findAll(array('condition'=>'publish_status = 1', 'order'=>'sort_order ASC')); 
			foreach($pages as $key => $val){
				$result[$val['id']] = array('id'=>$val['id'], 'link'=>'pages/view/id/'.$val['id'], 'title'=>$val['page_header']);
			}
        }		
		return $result;
    }	
	
    /**
     * Returns an array with available modules links
     */
    private function _getModulesLinks()
    {
		$result = array();
		if($modules = ModulesSettings::model()->findAll(array('condition'=>'property_key = :property_key', 'order'=>'id ASC'), array(':property_key'=>'modulelink'))){
			foreach($modules as $key => $val){
				$result[$val['id']] = array('link'=>$val['property_value'], 'title'=>$val['name'], 'module'=>$val['module_code'], 'tooltip'=>$val['description']);
			}
        }
		return $result;
    }	
	
    /**
     * Returns an array with available modules blocks
     */
    private function _getModulesBlock()
    {
		$result = array();
		if($modules = ModulesSettings::model()->findAll(array('condition'=>'property_key = :property_key', 'order'=>'id ASC'), array(':property_key'=>'moduleblock'))){
			foreach($modules as $key => $val){
				$result[$val['id']] = array('link'=>$val['property_value'], 'title'=>$val['name'], 'module'=>$val['module_code'], 'tooltip'=>$val['description']);
			}
        }
		return $result;
    }	

    /**
     * Returns an array with available modules
     */
    private function _getModulesList()
    {
		$result = array();
		if($modules = Modules::model()->findAll(array('order'=>'name ASC'))){
			foreach($modules as $key => $val){
				$result[$val['code']] = $val['name'];
			}
        }
		return $result;
    }	

    /**
     * Returns a dialog content according to selected menu type
     * @param string $menuType
     * @param string $viewType
     */
    private function _getDialogContent($menuType = '', $viewType = 'Add')
    {
		$output = array('content'=>'', 'title'=>'');
		if($menuType == 'modulelink'){
			$modulesList = $this->_view->modulesList;
			$output['title'] = 'Module Links';
			if(count($modulesList) > 0){
				$output['content'] = '<p>'.A::t('app', 'Click on the Module Link below to add a module link URL').'</p><br>';
				foreach($modulesList as $key => $val){
					$output['content'] .= '&bull; <a href="javascript:void(0)" onclick="javascript:setMenuType(\''.$viewType.'\',\''.$val['link'].'\',\''.$val['module'].'\');" title="'.$val['tooltip'].'">'.strip_tags($val['title']).'</a><br>';
				}
			}else{
				$output['content'] = A::t('app', 'No module links available');
			}			
		}elseif($menuType == 'moduleblock'){
			$modulesBlock = $this->_view->modulesBlock;
			$output['title'] = 'Module Blocks';
			if(count($modulesBlock) > 0){				
				$output['content'] = '<p>'.A::t('app', 'Click on the Module Block below to add a module block link').'</p><br>';
				foreach($modulesBlock as $key => $val){
					$output['content'] .= '&bull; <a href="javascript:void(0)" onclick="javascript:setMenuType(\''.$viewType.'\',\''.$val['link'].'\',\''.$val['module'].'\');" title="'.$val['tooltip'].'">'.strip_tags($val['title']).'</a><br>';
				}
			}else{
				$output['content'] = A::t('app', 'No module blocks available');
			}
		}else{
			// 'pagelink'
			$pagesList = $this->_view->pagesList;
			$output['title'] = 'Page Links';
			if(count($pagesList) > 0){
				$output['content'] = '<p>'.A::t('app', 'Click on the Page Name below to add a page link URL').'</p><br>';
				foreach($pagesList as $key => $val){
                    $pageLink = Website::prepareLinkByFormat('cms', 'page_link_format', $val['id'], $val['title']);                     
					$output['content'] .= '&bull; <a href="javascript:void(0)" onclick="javascript:setMenuType(\''.$viewType.'\',\''.$pageLink.'\',\'\');">'.strip_tags($val['title']).'</a><br>';
				}
			}else{
				$output['content'] = A::t('app', 'No page links available');
			}
		}
		return $output;
	}	

    /**
     * Prepares array of total counts for each sub-menu
     * @return array
     */
    private function _prepareSubMenuCounts()
    {
		$result = FrontendMenus::model()->count(array('condition'=>'is_active = 1', 'select'=>CConfig::get('db.prefix').'frontend_menus.parent_id', 'count'=>'*', 'groupBy'=>'parent_id', 'allRows'=>true));
		$subMenuCounts = array();
		foreach($result as $key => $model){
			$subMenuCounts[$model['parent_id']] = $model['cnt'];
		}
		
		return $subMenuCounts;
	}
	
}
