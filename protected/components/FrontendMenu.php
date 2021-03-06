<?php
/**
 * FrontendMenu - component for building site menu dynamically
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 *
 * STATIC
 * -------------------------------------------
 * draw						_getMenu
 * 							
 *
 */

class FrontendMenu extends CComponent
{
	/**
	 * Returns array of the menu items with links to published pages sorted by sort_order
	 * @param string $placement left|right|top|bottom|hidden
	 * @param string $activeMenu
	 * @param array $params: "separator", "menuClass", "subMenuClass", "dropdownItemClass", "drawMenuCaption"
	 * @return HTML code
	 */
	private static function _getMenu($placement, $activeMenu = '', $params = array())
	{
		$output = '';
        $separator = isset($params['separator']) ? $params['separator'] : '';
        $menuClass = isset($params['menuClass']) ? $params['menuClass'] : '';
        $subMenuClass = isset($params['subMenuClass']) ? $params['subMenuClass'] : '';
        $dropdownItemClass = isset($params['dropdownItemClass']) ? $params['dropdownItemClass'] : '';
        $drawMenuCaption = isset($params['drawMenuCaption']) ? (bool)$params['drawMenuCaption'] : true;

		$items = array();
        $menuItems = FrontendMenus::model()->findAll(array('condition'=>'placement = :placement AND parent_id=0', 'order'=>'sort_order ASC'), array(':placement'=>$placement));
        if(is_array($menuItems)){
          	foreach($menuItems as $item){
				if($placement == 'left' || $placement == 'right') $items = array();

				if($item['menu_type'] == 'moduleblock'){
					$output .= (!empty($item['link_url'])) ? @call_user_func_array($item['module_code'].'Component::'.$item['link_url'], array($item['menu_name'], $activeMenu)) : '';
				}else{
					if($item['access_level'] == 'registered' && !CAuth::isLoggedIn()) continue;
					$subItems = '';
					$subMenuItems = FrontendMenus::model()->findAll(array('condition'=>'parent_id = :parent_id', 'order'=>'sort_order ASC'), array(':parent_id'=>$item['id']));
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
					);					
				}
				
				if($placement == 'left' || $placement == 'right'){
					$output .= CWidget::create('CMenu', array(
						'type'		=> 'vertical',
						'items'		=> $items,
						'selected'	=> $activeMenu,
						'separator'	=> $separator,
						'id'		=> 'side-menu',
                        'class'     => $menuClass,
                        'subMenuClass' => $subMenuClass,
                        'dropdownItemClass' => $dropdownItemClass,
						'return'	=> true
					));											
				}				
        	} // foreach			

			if($placement == 'top' || $placement == 'bottom'){
				$output .= CWidget::create('CMenu', array(
					'type'		=> 'horizontal',
					'items'		=> $items,
					'selected'	=> $activeMenu,
					'separator'	=> $separator,
					'id'		=> $placement.'-menu',
                    'class'     => $menuClass,
                    'subMenuClass' => $subMenuClass,
                    'dropdownItemClass' => $dropdownItemClass,
					'return'	=> true
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
	 * @return HTML code
	 */
	public static function draw($placement, $activeMenu = '', $params = array())
	{
        return self::_getMenu($placement, $activeMenu, $params);
	}
}