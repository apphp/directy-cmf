<?php
/**
 * BackendMenu - component for building backend menu dynamically
 *
 * PUBLIC (static):			PRIVATE (static):
 * -----------              ------------------
 * init						_getMenu 
 * drawSideMenu			    
 * drawProfileMenu
 * 							
 *
 */

class BackendMenu extends CComponent
{

	/**
     *	Returns the instance of object
     *	@return current class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}

	/**
	 * Returns array of the visible menu items sorted by sort_order
	 * @param int $parentId The id of the parent menu. 
	 */
	private static function _getMenu($parentId = 0)
	{
		$i = 0;
		$items = array();
		$menuItems = BackendMenus::model()->findAll(array('condition'=>'parent_id = :parentID AND is_visible=1', 'order'=>'sort_order ASC'), array(':parentID'=>(int)$parentId));
		if(is_array($menuItems)){
			foreach($menuItems as $item){
				
				// Get opened meni ID
				$openedMenu = A::app()->getCookie()->get('isOpened');

				// Draw modules management and modules menu
				if($item['icon'] == 'modules.png' && !Admins::hasPrivilege('modules', 'view') && !Admins::hasPrivilege('modules', 'view_management')) continue;
				if($item['module_code'] != '' && !Admins::hasPrivilege('modules', 'view')) continue;				
				
				$subItems = ($parentId == 0 ? self::_getMenu($item['id']) : '') ;
                // Don't show parent menu if it has no child items
                if($parentId == 0 && is_array($subItems) && !count($subItems)) continue;

				$imagePath = (preg_match('/\//', $item['icon'])) ? '' : 'templates/backend/images/icons/';
				$image = ($item['icon'] == '' ? '' : '<img src="'.$imagePath.$item['icon'].'" alt="icon" class="'.($parentId ? 'sub-' : '').'menu-icon" />'); 				
				$target = '';
				$show = true;

				// Preview link
				if($item['url'] == 'index/'){
					$item['url'] = Website::getDefaultPage();
					$target = '_blank';
				}else{					
					// Check other links - if admin has privileges to access specific menus items
					switch($item['url']){
						case 'settings/':
							if(!Admins::hasPrivilege('site_settings', 'view')) $show = false; break;
						case 'socialNetworks/':
							if(!Admins::hasPrivilege('social_networks', 'view')) $show = false; break;
						case 'backendMenus/':
							if(!Admins::hasPrivilege('backend_menu', 'view')) $show = false; break;
						case 'frontendMenus/':
							if(!Admins::hasPrivilege('frontend_menu', 'view')) $show = false; break;
						case 'locations/':
							if(!Admins::hasPrivilege('locations', 'view')) $show = false; break;
						case 'currencies/':
							if(!Admins::hasPrivilege('currencies', 'view')) $show = false; break;
						case 'paymentProviders/':
							if(!Admins::hasPrivilege('payment_providers', 'view')) $show = false; break;
						case 'banLists/':
							if(!Admins::hasPrivilege('ban_lists', 'view')) $show = false; break;
						case 'admins/':
							if(!CAuth::isLoggedInAs('owner','mainadmin')) $show = false; break;
						case 'roles/':
							if(!CAuth::isLoggedInAs('owner','mainadmin')) $show = false; break;					
						case 'languages/':
							if(!Admins::hasPrivilege('languages', 'view')) $show = false; break;					
						case 'vocabulary/':
							if(!Admins::hasPrivilege('vocabulary', 'view')) $show = false; break;					
						case 'emailTemplates/':
							if(!Admins::hasPrivilege('email_templates', 'view')) $show = false; break;
						case 'mailingLog/':
							if(!Admins::hasPrivilege('mailing_log', 'view')) $show = false; break;
						case 'modules/':
							if(!Admins::hasPrivilege('modules', 'view_management')) $show = false; break;
					}
				}
				
				if($show){
					
					// Preview
					if($item['id'] == 15){
						$url = A::app()->getRequest()->getBaseUrl();
					}else{
						$url = ! empty($item['url']) ? $item['url'] : 'javascript:void(0)';	
					}
					
					$items[$i] = array(
						'label'  => $image.(!empty($item['module_code']) ? A::t($item['module_code'], $item['menu_name']) : $item['menu_name']),
						'url' 	 => $url,
						'id' 	 => 'menu-'.$parentId.$i,
						'target' => $target,
						'class'	 => ('menu-'.$parentId.$i === $openedMenu || $openedMenu == 'all') ? 'active' : '',
						'items'  => $subItems,						
					);					
				}
				
				// Add menu items for installed modules under Modules menu
				if($item['url'] === 'modules/'){
					$modules = Modules::model()->findAll(array('condition'=>'is_installed = 1', 'orderBy'=>'sort_order ASC'));
					if(is_array($modules)){
						foreach($modules as $module){
							if(Admins::privilegeExists($module['code'], 'view') && !Admins::hasPrivilege($module['code'], 'view')){
								// Do nothing - don't show this module in menu (only for modules which has "view" privilege)
							}else{
								$backendDefaultUrl = CConfig::get('modules.'.$module['code'].'.backendDefaultUrl');
								$items[] = array(
									'label' => '<img src="assets/modules/'.$module['code'].'/images/'.$module['icon'].'" class="sub-menu-icon" />'.$module['name'],
									'url' 	=> (!empty($backendDefaultUrl) ? $backendDefaultUrl : 'modules/settings/code/'.$module['code'])
								);								
							}
						}
					}						
				}			
				$i++;
			}
		}
		return $items;
	}
	
	/**
	 * Draws backend side menu from database
	 * @param string $activeMenu
	 */
	public static function drawSideMenu($activeMenu = '')
	{
		return CWidget::create('CMenu', array(
			'type'=>'vertical',
			'items'=>self::_getMenu(),
			'selected'=>$activeMenu,
			'return'=>true
		));
	}
    
	/**
	 * Draws backend profile menu 
	 * @param string $activeMenu
	 */
	public static function drawProfileMenu($activeMenu = '')
	{
        $output = '';
        $output .= CHtml::openTag('div', array('class'=>'logout'));
        $output .= CHtml::link(CHtml::image('templates/backend/images/icons/logout-white.png', 'logout'), 'backend/logout', array('class'=>'tooltip-link', 'title'=>A::t('app', 'Logout')));
        $output .= CHtml::closeTag('div');
        $output .= CHtml::openTag('div', array('id'=>'dd', 'class'=>'wrapper-dropdown'));
        $output .= CHtml::image('templates/backend/images/accounts/'.CAuth::getLoggedAvatar(), 'avatar', array('height'=>'36px'));
        $output .= CHtml::tag('span', array(), A::t('app', 'Hi').', '.CAuth::getLoggedName());
        $output .= CHtml::openTag('ul', array('class'=>'dropdown'));
        $output .= CHtml::tag('li', array('class'=>($activeMenu == 'backend/' ? 'active' : '')), CHtml::link('<i class="icon-dashboard"></i>'.A::t('app', 'Dashboard'), 'backend/dashboard'));
        $output .= CHtml::tag('li', array('class'=>($activeMenu == 'admins/myAccount' ? 'active' : '')), CHtml::link('<i class="icon-account"></i>'.A::t('app', 'My Account'), 'admins/myAccount'));
        $output .= CHtml::tag('li', array('class'=>($activeMenu == 'settings/' ? 'active' : '')), CHtml::link('<i class="icon-settings"></i>'.A::t('app', 'Settings'), 'settings/general'));
        //$output .= CHtml::tag('li', array(), CHtml::link('<i class="icon-logout"></i>'.A::t('app', 'Logout'), 'backend/logout'));
		$output .= CHtml::tag('li', array('class'=>''), CHtml::link('<i class="icon-preview"></i>'.A::t('app', 'Preview'), Website::getDefaultPage(), array('target'=>'_blank')));
		
        $output .= CHtml::closeTag('ul');
        $output .= CHtml::closeTag('div');
        
        return $output;
    }
    
}
