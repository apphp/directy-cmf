<?php
/**
 * CMS component
 *
 * PUBLIC (static):         PRIVATE:
 * ---------------         	---------------
 * init
 * prepareTab
 *
 */

namespace Modules\Cms\Components;

// Modules
use \Modules\Cms\Models\Pages;

// Global
use \A,
	\CWidget,
	\CComponent,
	\CFile,
	\CHtml;

// CMF
use \Website,
	\Bootstrap,
	\ModulesSettings;


class CmsComponent extends CComponent
{

	const NL = "\n";

	/**
	 *	Returns the instance of object
	 *	@return current class
	 */
	public static function init()
	{
		return parent::init(__CLASS__);
	}

	/**
	 * Prepares cms module tabs
	 * @param string $activeTab
	 * @param int $menuCatId
	 * @param int $menuCatItemId
	 */
	public static function prepareTab($activeTab = 'info', $menuCatId = '', $menuCatItemId = '')
	{
		return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('cms', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/cms', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('cms', 'Pages')  => array('href'=>'pages/index', 'id'=>'tabInfo', 'content'=>'', 'active'=>($activeTab == 'pages' ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	}


}