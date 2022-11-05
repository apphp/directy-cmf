<?php
/**
* TicketsComponent
*
* PUBLIC:                   PRIVATE
* -----------               ------------------
* prepareTab
* prepareSubTab
* sortArr
*
* STATIC
* -------------------------------------------
* init
*
*/

namespace Modules\Tickets\Components;

// Modules
use \Modules\Tickets\Models\Tickets,
	\Modules\Tickets\Models\TicketReplies;

// Global
use \A,
	\CWidget,
	\CComponent,
	\CHtml;

// CMF
use \Website,
	\Bootstrap,
	\ModulesSettings;

class TicketsComponent extends CComponent{
    
    const NL = "\n";

    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Prepares Tickets module sub tabs
     * @param string $parentTab
     * @param string $activeSubTab
     * @return html
     */
    public static function prepareSubTab($parentTab = 'tickets', $activeSubTab = '', $additionText = '')
    {
        $output = '';
        $activeSubTab = strtolower($activeSubTab);

        $arrPrepareTabs = array(
            'tickets' => array(
                array('title'=>'', 'url'=>'tickets/manage/status/all', 'text'=>A::t('tickets', 'All')),
                array('title'=>'opened', 'url'=>'tickets/manage/status/opened', 'text'=>A::t('tickets', 'Opened')),
                array('title'=>'waitingreply', 'url'=>'tickets/manage/status/waitingreply', 'text'=>A::t('tickets', 'Waiting Reply')),
                array('title'=>'answered', 'url'=>'tickets/manage/status/answered', 'text'=>A::t('tickets', 'Answered')),
                array('title'=>'closed', 'url'=>'tickets/manage/status/closed', 'text'=>A::t('tickets', 'Closed')),
            ),
        );

        if(isset($arrPrepareTabs[$parentTab])){
            foreach($arrPrepareTabs[$parentTab] as $tab){
                $output .= '<a class="sub-tab'.($activeSubTab == $tab['title'] ? ' active' : ' previous').'" href="'.$tab['url'].'">'.$tab['text'].'</a>';
                $output .= $activeSubTab == $tab['title'] && !empty($additionText) ? '» <a class="sub-tab active"><b>'.$additionText.'</b></a>&nbsp;' : '';
            }
        }

        return $output;
    }

	/**
	 * Prepares tickets module tabs
	 * @param string $activeTab : default - tickets
	 */
	public static function prepareTab($activeTab = 'tickets')
	{
		return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('tickets', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/tickets', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('tickets', 'Tickets Management')  	=> array('href'=>'tickets/manage', 'id'=>'tabTickets', 'content'=>'', 'active'=>($activeTab == 'tickets' ? true : false)),
			),
			'events'=>array(

			),
			'return'=>true,
		));
	}

	/**
	 * We sort the multidimensional array by the value of the nested array
	 * @param $array array multidimensional array that sorts
	 * @param $field string the name of the field of the nested array to sort by
	 * @return array sorted multidimensional array
	 */
	public static function sortArr($array, $field){
		$sortArr = array();
		foreach($array as $key=>$val){
			$sortArr[$key] = $val[$field];
		}
		array_multisort($sortArr,$array);

		return $array;
	}

}
