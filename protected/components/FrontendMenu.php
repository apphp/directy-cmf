<?php
/**
 * FrontendMenu - component for building site menu dynamically
 *
 * PUBLIC (static):         PRIVATE (static):
 * -----------              ------------------
 * init						_getMenu
 * draw
 *
 */

class FrontendMenu extends CComponent
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
	 * Returns array of the menu items with links to published pages sorted by sort_order
	 * @param string $placement left|right|top|bottom|hidden
	 * @param string $activeMenu
	 * @param array $params: "parentId" (ID of top menu section), "separator", "menuId", "menuClass", "subMenuClass", "dropdownItemClass", "dropdownItemLinkClass", "dropdownItemLinkDataToggle", "drawMenuCaption", "activeItemClass", "itemInnerTag"
	 * Ex.:
	 * <ul id="menuId" class="menuClass">
	 *      <li>Link</li>
	 *      <li class="dropdownItemClass">
	 *          <a href="#" class="dropdownItemLinkClass" data-toggle="dropdownItemLinkDataToggle"><itemInnerTag>Link</itemInnerTag></a>
	 *          <ul class="subMenuClass">
	 *              <li><itemInnerTag>Link</itemInnerTag></li>
	 *              <li><itemInnerTag>Link</itemInnerTag></li>
	 *              <li><itemInnerTag>Link</itemInnerTag></li>
	 *          </ul>
	 *      </li>
	 *      <li class="menuItemClass">Link</li>
	 * </ul>
	 * @return HTML code
	 */
	private static function _getMenu($placement, $activeMenu = '', $params = array())
	{
		$output = '';
        $separator = isset($params['separator']) ? $params['separator'] : '';
        $menuId = isset($params['menuId']) ? $params['menuId'] : '';
        $menuClass = isset($params['menuClass']) ? $params['menuClass'] : '';
		$menuItemClass = isset($params['menuItemClass']) ? $params['menuItemClass'] : '';
        $subMenuClass = isset($params['subMenuClass']) ? $params['subMenuClass'] : '';
        $dropdownItemClass = isset($params['dropdownItemClass']) ? $params['dropdownItemClass'] : '';        
		$dropdownItemLinkClass = isset($params['dropdownItemLinkClass']) ? $params['dropdownItemLinkClass'] : '';
		$dropdownItemLinkDataToggle = isset($params['dropdownItemLinkDataToggle']) ? $params['dropdownItemLinkDataToggle'] : '';
        $activeItemClass = isset($params['activeItemClass']) ? $params['activeItemClass'] : 'active';
        $drawMenuCaption = isset($params['drawMenuCaption']) ? (bool)$params['drawMenuCaption'] : true;
        $itemInnerTag = isset($params['itemInnerTag']) ? $params['itemInnerTag'] : '';
		$parentId = isset($params['parentId']) ? $params['parentId'] : '';

		$items = array();
        $menuItems = FrontendMenus::model()->findAll(array('condition'=>'placement = :placement AND parent_id = 0 '.(!empty($parentId) ? ' AND '.CConfig::get('db.prefix').'frontend_menus.id = '.(int)$parentId : '').' AND is_active = 1', 'order'=>'sort_order ASC'), array(':placement'=>$placement));
        if(is_array($menuItems)){
          	foreach($menuItems as $item){
				if($placement == 'left' || $placement == 'right') $items = array();

				if($item['menu_type'] == 'moduleblock'){
					// Find component class
					$componentClass = '';
					$components = CConfig::get('components');
					if(is_array($components)){
						foreach($components as $key => $component){
							if(strtolower($key) == strtolower($item['module_code'].'Component')){
								$componentClass = A::app()->mapAppModuleClass($item['module_code'].'Component');
								if(empty($componentClass)){
									$componentClass = !empty($component['class']) ? $component['class'] : '';	
								}
								break;
							}
						}
					}
					
					// Call component methods
					if(!empty($item['link_url'])){
						if(APPHP_MODE == 'debug'){
							$output .= call_user_func_array($componentClass.'::'.$item['link_url'], array($item['menu_name'], $activeMenu));	
						}else{
							$output .= @call_user_func_array($componentClass.'::'.$item['link_url'], array($item['menu_name'], $activeMenu));
						}
					}
					// Old code
					// $output .= (!empty($item['link_url'])) ? @call_user_func_array($item['module_code'].'Component::'.$item['link_url'], array($item['menu_name'], $activeMenu)) : '';
				}else{
					if($item['access_level'] == 'registered' && !CAuth::isLoggedIn()) continue;
					$subItems = array();
					$subMenuItems = FrontendMenus::model()->findAll(array('condition'=>'parent_id = :parent_id AND is_active = 1', 'order'=>'sort_order ASC'), array(':parent_id'=>$item['id']));
					if(is_array($subMenuItems)){
						foreach($subMenuItems as $subMenuItem){
							if($subMenuItem['access_level'] == 'registered' && !CAuth::isLoggedIn()) continue;
							$subItems[] = array(
								'label'  => $subMenuItem['menu_name'],
								'url' 	 => (($subMenuItem['link_url'] != '') ? $subMenuItem['link_url'] : 'javascript:void(0)'),
								'target' => (($subMenuItem['link_target'] != '') ? $subMenuItem['link_target'] : ''),
							);
						}
					}
					$items[] = array(
						'label'  => ($drawMenuCaption) ? $item['menu_name'] : '',
						'url' 	 => (($item['link_url'] != '') ? $item['link_url'] : 'javascript:void(0)'),
						'target' => (($item['link_target'] != '') ? $item['link_target'] : ''),
						'items'  => $subItems,
						'class'	 => (empty($subItems) ? $menuItemClass : ''),
					);					
				}
				
				if($placement == 'left' || $placement == 'right'){
					$output .= CWidget::create('CMenu', array(
						'type'              => 'vertical',
						'items'             => $items,
						'selected'          => $activeMenu,
						'separator'         => $separator,
						'itemInnerTag'      => $itemInnerTag,
						'id'                => $menuId ? $menuId : 'side-menu',
						'class'             => $menuClass,
						'subMenuClass'      => $subMenuClass,
						'dropdownItemClass' => $dropdownItemClass,
						'dropdownItemLinkClass' => $dropdownItemLinkClass,
						'dropdownItemLinkDataToggle' => $dropdownItemLinkDataToggle,
						'activeItemClass'   => $activeItemClass,
						'return'            => true
					));											
				}				
        	} // Foreach			

			if($placement == 'top' || $placement == 'bottom'){
				$output .= CWidget::create('CMenu', array(
					'type'              => 'horizontal',
					'items'             => $items,
					'selected'          => $activeMenu,
					'separator'         => $separator,
					'itemInnerTag'      => $itemInnerTag,
					'id'                => $menuId ? $menuId : $placement.'-menu',
					'class'             => $menuClass,
					'subMenuClass'      => $subMenuClass,
					'dropdownItemClass' => $dropdownItemClass,
					'dropdownItemLinkClass' => $dropdownItemLinkClass,
					'dropdownItemLinkDataToggle' => $dropdownItemLinkDataToggle,
					'activeItemClass'   => $activeItemClass,
					'return'            => true
				));
			}
        }
		return $output;           
	}
	
	/**
	 * Draws menu from database
	 * @param string $placement left|right|top|bottom|hidden
	 * @param string $activeMenu
	 * @param array $params
	 * @SEE self::_getMenu() 
	 * @return HTML code
	 */
	public static function draw($placement, $activeMenu = '', $params = array())
	{
        return self::_getMenu($placement, $activeMenu, $params);
	}
}
