<?php
/**
 * AdminMenu - component for building backend menu dynamically
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 *
 * STATIC
 * -------------------------------------------
 * draw						getMenu
 * 							
 *
 */

class AdminMenu extends CComponent
{
	/**
	 * Returns array of the visible menu items sorted by sort_order
	 * @param int $parentId The id of the parent menu. 
	 */
	private static function getMenu($parentId = 0)
	{
		$i = 0;
		$items = array();
		$menuItems = BackendMenus::model()->findAll(array('condition'=>'parent_id = :parentID AND is_visible=1', 'order'=>'sort_order ASC'), array(':parentID'=>(int)$parentId));
		if(is_array($menuItems)){
			foreach($menuItems as $item){
				
				// draw modules management and modules menu
				if($item['icon'] == 'modules.png' && !Admins::hasPrivilege('modules', 'view')) continue;
				if($item['module_code'] != '' && !Admins::hasPrivilege('modules', 'view')) continue;				
				
				$subItems = ($parentId == 0 ? self::getMenu($item['id']) : '') ;
				$imagePath = (preg_match('/\//', $item['icon'])) ? '' : 'templates/backend/images/icons/';
				$image = ($item['icon'] == '' ? '' : '<img src="'.$imagePath.$item['icon'].'" alt="">'); 				
				$show = true;

				// check if admin has privileges to access specific menus items
				switch($item['url']){
					case 'settings/':
						if(!Admins::hasPrivilege('site_settings', 'view')) $show = false; 
						break;
					case 'backendMenus/':
						if(!Admins::hasPrivilege('backend_menu', 'view')) $show = false; 
						break;
					case 'frontendMenus/':
						if(!Admins::hasPrivilege('frontend_menu', 'view')) $show = false; 
						break;
					case 'locations/':
						if(!Admins::hasPrivilege('locations', 'view')) $show = false; 
						break;
					case 'currencies/':
						if(!Admins::hasPrivilege('currencies', 'view')) $show = false; 
						break;
					case 'admins/':
						if(!CAuth::isLoggedInAs('owner','mainadmin')) $show = false; 
						break;
					case 'roles/':
						if(!CAuth::isLoggedInAs('owner','mainadmin')) $show = false; 
						break;					
					case 'languages/':
						if(!Admins::hasPrivilege('languages', 'view')) $show = false; 
						break;					
					case 'vocabulary/':
						if(!Admins::hasPrivilege('vocabulary', 'view')) $show = false; 
						break;					
					case 'modules/':
						if(!Admins::hasPrivilege('modules', 'view')) $show = false; 
						break;					
				}
				
				if($show){
					$items[$i] = array(
						'label' => $image.$item['menu_name'],
						'url' 	=> empty($item['url']) ? 'javascript:void(0)' : $item['url'],
						'id' 	=> 'menu-'.$parentId.$i,
						'items' => $subItems,
					);					
				}
				
				// add menu items for installed modules under Modules menu
				if(strpos($item['url'], 'modules') !== false){
					$modules = Modules::model()->findAll('is_installed = 1');
					if(is_array($modules)){
						foreach($modules as $module){
							$items[] = array(
								'label' => '<img src="images/modules/'.$module['code'].'/'.$module['icon'].'" style="height:16px;margin-top:1px;" /> '.$module['name'],
								'url' 	=> 'modules/settings/code/'.$module['code']
							);
						}
					}						
				}			
				$i++;
			}
		}
		return $items;
	}
	
	/**
	 * Draws backend menu from database
	 * @param string $activeMenu
	 */
	public static function draw($activeMenu = '')
	{
		return CWidget::create('CMenu', array(
			'type'=>'vertical',
			'items'=>self::getMenu(),
			'selected'=>$activeMenu,
			'return'=>true
		));
	}
}