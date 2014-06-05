<?php
/**
* FrontendMenusController
*
* PUBLIC:                  	PRIVATE
* -----------              	------------------
* __construct              	getPlacementsList
* indexAction				getAccessLevelsList
* manageAction				getLinkTargetsList
* addAction                 getMenuTypesList 
* editAction                getPagesLinks
* deleteAction              getModulesLinks
*                           getModulesBlock
*                           getDialogContent 
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
        
        // block access to this controller for not-logged users
        CAuth::handleLogin('backend/login');
        
        // block access if admin has no active privilege to view backend menu
        if(!Admins::hasPrivilege('frontend_menu', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
       
        // set meta tags according to active language
        SiteSettings::setMetaTags(array('title'=>A::t('app', 'Frontend Menu Management')));
        
        A::app()->view->setTemplate('backend');
        $this->view->actionMessage = '';
        $this->view->errorField = '';
    	$this->view->parentId = 0;
		$this->view->parentName = '';
		$this->view->moduleCode = '';
        $this->view->menuType = '';
		
    	$this->view->placementsList = $this->getPlacementsList();
		$this->view->placementsFilterList = $this->getPlacementsList('all');
    	$this->view->accessLevelsList = $this->getAccessLevelsList();
		$this->view->linkTargetsList = $this->getLinkTargetsList();
		$this->view->menuTypesList = $this->getMenuTypesList();
		$this->view->pagesList = $this->getPagesLinks();
		$this->view->modulesList = $this->getModulesLinks();
		$this->view->modulesBlock = $this->getModulesBlock();
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
     * @param string $msg 
     */
    public function manageAction($pid = 0, $msg = '')
    {
		$this->view->parentId = 0;
		$menu = FrontendMenus::model()->findByPk((int)$pid);
		if(!empty($menu)){
			$this->view->parentId = (int)$pid;
			$this->view->parentName = $menu->menu_name;
			$this->view->menuType =	$menu->menu_type;
		}

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
		$this->view->render('frontendMenus/manage');	   	
    }
    
    /**
     * Add new menu action handler
	 * @param int $pid the ID of the parent menu, if $pid == 0 views up level menu items.
     */
    public function addAction($pid = 0)
    {
        // block access if admin has no active privilege to add menus
        if(!Admins::hasPrivilege('frontend_menu', 'edit')){
        	$this->redirect('frontendMenus/manage');
        }

		$this->view->parentId = 0;
		$this->view->parentName = A::t('app', 'Top Level Menu');
		$this->view->menuType = '';
		$menu = FrontendMenus::model()->findbyPk($pid);
		if(!empty($menu)){
			$this->view->parentId = $pid;
			$this->view->parentName = $menu->menu_name;
		}

		// prepare menu type
		if(A::app()->getRequest()->getPost('APPHP_FORM_ACT') == 'change'){
			$this->view->menuType =	A::app()->getRequest()->getPost('menu_type');
			A::app()->getRequest()->setPost('link_url', '');
			A::app()->getRequest()->setPost('link_target', '');
			A::app()->getRequest()->setPost('module_code', '');
		}

		$dialog = $this->getDialogContent($this->view->menuType, 'Add');
		$this->view->dialogTitle = $dialog['title'];
		$this->view->dialogContent = $dialog['content'];

    	$this->view->render('frontendMenus/add');
    }    
  
    /**
     * Menu edit action handler
     * @param int $id the menu id
     */
    public function editAction($id = 0)
    {
        // block access if admin has no active privilege to edit menus
        if(!Admins::hasPrivilege('frontend_menu', 'edit')){
        	$this->redirect('frontendMenus/manage');
        }
		
		$this->view->parentId = 0;
		$this->view->parentName = A::t('app', 'Top Level Menu');
		$this->view->moduleCode = '';
		$this->view->menuType = '';
		$menu = FrontendMenus::model()->findByPk($id);
        if(!empty($menu)){
			$this->view->moduleCode = $menu->module_code;
			$this->view->menuType =	$menu->menu_type;
        	$parentMenu = FrontendMenus::model()->findbyPk($menu->parent_id);
        	if(!empty($parentMenu)){
				$this->view->parentName = $parentMenu->menu_name;
				$this->view->parentId = $parentMenu->id;
				$this->view->moduleCode = '';
			}
        }

		// prepare menu type
		if(A::app()->getRequest()->getPost('APPHP_FORM_ACT') == 'change'){
			$this->view->menuType =	A::app()->getRequest()->getPost('menu_type');
			A::app()->getRequest()->setPost('link_url', '');
			A::app()->getRequest()->setPost('link_target', '');
			A::app()->getRequest()->setPost('module_code', '');
		}		

		$dialog = $this->getDialogContent($this->view->menuType, 'Edit');
		$this->view->dialogTitle = $dialog['title'];
		$this->view->dialogContent = $dialog['content'];
    	$this->view->id = (int)$id;
		
    	$this->view->render('frontendMenus/edit');
    }    
  
    /**
     * Delete menu action handler
     * @param int $id the menu id
     * @param int $pid the ID of the parent menu, if $pid == 0 views up level menu items.
     */
    public function deleteAction($id = 0, $pid = 0)
    {
    	$msg = '';
    	$errorType = '';
    
		$menu = FrontendMenus::model()->findByPk($id);
		if(!$menu){
			$this->redirect('frontendMenus/manage');
		}		
        // block access if admin has no active privilege to delete menus
        if(!Admins::hasPrivilege('frontend_menu', 'edit')){
        	$this->redirect('frontendMenus/manage');
        }else if($menu->delete()){
			$msg = A::t('app', 'Delete Success Message');
			$errorType = 'success';	
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
			$this->view->parentId = $pid;
		}
		$this->view->render('frontendMenus/manage');
    }

    /**
     * Returns an array with available menu placements
     * @param string $type
     */
    private function getPlacementsList($type = '')
    {
    	$placementsList = array(			
			'left' 	=> A::t('app', 'Left'),
			'top' 	=> A::t('app', 'Top'),
			'right' => A::t('app', 'Right'),
			'bottom'=> A::t('app', 'Bottom'),
    	);
		
		if($type != 'all'){
			// remove menu placements that are not allowed by active template
			$template = Settings::model()->findByPk(1)->template;
			// load data from XML file
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
    private function getAccessLevelsList()
    {
    	return array(
			'public' 	 => A::t('app', 'Public'),
			'registered' => A::t('app', 'Registered'),
		);
    }
	
    /**
     * Returns an array with available menu access levels
     */
    private function getLinkTargetsList()
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
    private function getMenuTypesList()
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
    private function getPagesLinks()
    {
		$result = array();
        if(Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>'cms'))){
			$pages = Pages::model()->findAll('publish_status = 1'); 
			foreach($pages as $key => $val){
				$result[$val['id']] = array('link'=>'pages/view/id/'.$val['id'], 'title'=>$val['page_header']);
			}
        }		
		return $result;
    }	
	
    /**
     * Returns an array with available modules links
     */
    private function getModulesLinks()
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
    private function getModulesBlock()
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
     * Returns a dialog content according to selected menu type
     */
    private function getDialogContent($menuType = '', $viewType = 'Add')
    {
		$output = array('content'=>'', 'title'=>'');
		if($menuType == 'modulelink'){
			$modulesList = $this->view->modulesList;
			$output['title'] = 'Module Links';
			if(count($modulesList) > 0){
				$output['content'] = '<p>'.A::t('app', 'Click on the Module Link below to add a module link URL').'</p><br>';
				foreach($modulesList as $key => $val){
					$output['content'] .= '&bull; <a href="javascript:void(0)" onclick="javascript:setMenuType(\''.$viewType.'\',\''.$val['link'].'\',\''.$val['module'].'\');" title="'.$val['tooltip'].'">'.$val['title'].'</a><br>';
				}
			}else{
				$output['content'] = A::t('app', 'No module links available');
			}			
		}else if($menuType == 'moduleblock'){
			$modulesBlock = $this->view->modulesBlock;
			$output['title'] = 'Module Blocks';
			if(count($modulesBlock) > 0){				
				$output['content'] = '<p>'.A::t('app', 'Click on the Module Block below to add a module block link').'</p><br>';
				foreach($modulesBlock as $key => $val){
					$output['content'] .= '&bull; <a href="javascript:void(0)" onclick="javascript:setMenuType(\''.$viewType.'\',\''.$val['link'].'\',\''.$val['module'].'\');" title="'.$val['tooltip'].'">'.$val['title'].'</a><br>';
				}
			}else{
				$output['content'] = A::t('app', 'No module blocks available');
			}
		}else{
			// 'pagelink'
			$pagesList = $this->view->pagesList;
			$output['title'] = 'Page Links';
			if(count($pagesList) > 0){
				$output['content'] = '<p>'.A::t('app', 'Click on the Page Name below to add a page link URL').'</p><br>';
				foreach($pagesList as $key => $val){
					$output['content'] .= '&bull; <a href="javascript:void(0)" onclick="javascript:setMenuType(\''.$viewType.'\',\''.$val['link'].'\',\'\');">'.$val['title'].'</a><br>';
				}
			}else{
				$output['content'] = A::t('app', 'No page links available');
			}
		}
		return $output;
	}	
}