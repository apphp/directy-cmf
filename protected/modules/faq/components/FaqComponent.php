<?php
/**
 * Faq component
 *
 * PUBLIC (static):         PRIVATE:
 * ---------------         	---------------				
 * init
 * prepareTab
 * drawShortcode
 * 
 */

namespace Modules\Faq\Components;

// Modules
use \Modules\Faq\Models\FaqCategories,
	\Modules\Faq\Models\FaqCategoryItems;

// Global
use \A,
	\CWidget,
	\CComponent,
	\CHtml;

// CMF
use \Website,
	\Bootstrap,
	\ModulesSettings;

class FaqComponent extends CComponent
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
     * Prepares faq module tabs
     * @param string $activeTab
     */    
    public static function prepareTab($activeTab = 'faq')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
            'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
            'contentWrapper'=>array(),
            'contentMessage'=>'',
            'tabs'=>array(
                A::t('faq', 'Settings') => array('href'=> Website::getBackendPath().'modules/settings/code/faq', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
                A::t('faq', 'FAQ Categories')  => array('href'=>'faqCategories/manage', 'id'=>'tabFaq', 'content'=>'', 'active'=>(($activeTab == 'faq' || $activeTab == 'faqitems') ? true : false)),
            ),
            'events'=>array(
                
            ),
            'return'=>true,
        ));
    }
    
    /**
     * Draws FAQ shortcode output
     * @param array $params
     */
    public static function drawShortcode($params = array())
    {
        $output = '';
        $totalFaqCategories = 0;
   
        $faqCategories = FaqCategories::model()->findAll(array(
            'condition'=>'is_active = 1',       
            'order'=>'sort_order ASC'
        ));
        $totalFaqCategories = count($faqCategories);

        $faqItems = array();
        for($i = 0; $i < $totalFaqCategories; $i++){
            $faqItems[$faqCategories[$i]['id']] = FaqCategoryItems::model()->findAll(array(
                'condition' => 'faq_category_id = :faqCatId AND is_active = 1',
                'order' => 'sort_order ASC'),
                array(':faqCatId' => $faqCategories[$i]['id'])
            );
        }       
       
        if(!$totalFaqCategories){
            $output .= A::t('faq', 'Wrong parameter passed or there are stili no FAQ!');
        }else{
            $output .= '<div id="faq-block" class="panel-group">';
			$itemsCount = 0;
            for($i=0; $i < $totalFaqCategories; $i++){
                if($totalFaqCategories > 1) $output .= '<h2>'.$faqCategories[$i]['category_name'].'</h2>';
                if(key_exists($faqCategories[$i]['id'], $faqItems)){
                    $faqItem = $faqItems[$faqCategories[$i]['id']];
                    foreach ($faqItem as $key => $value){
						$output .= '<div class="panel panel-default">';
						$output .= '<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" href="#faq-item-'.$i.'-'.$key.'"'.($itemsCount == 0 ? ' aria-expanded="true"' : '').'>
									<i class="fa fa-star-o"></i>
									'.$value['faq_question'].'
								</a>
							</h4>
						</div>
						<div id="faq-item-'.$i.'-'.$key.'" class="accordion-body collapse'.($itemsCount == 0 ? ' in' : '').'"'.($itemsCount == 0 ? ' aria-expanded="true"' : '').'>
							<div class="panel-body">
								<p>'.$value['faq_answer'].'</p>
							</div>
						</div>';
						$output .= '</div>';
						$itemsCount++;
                    }
                }
            }
            $output .= '</div>';
        }        

//        A::app()->getClientScript()->registerScript(
//            'viewFaq',
//            'jQuery(document).ready(function() {
//				jQuery("#faq-block h4").each(function() {
//					var tis = jQuery(this),
//						state = false,
//						answer = answer = jQuery(tis.find("a").attr("href")).hide().css("height","auto").slideUp();
//					tis.click(function(e) {
//						e.preventDefault();
//						state = !state;
//						answer.slideToggle(state);
//						tis.toggleClass("active",state);
//					});
//				});
//                // Show first node opened
//				jQuery(".accordion-body").first().slideDown();
//                jQuery("#faq-block h4").first().toggleClass("active",true);
//                jQuery("#faq-block h4").first().next("div").show().css("height","auto").slideDown();                
//            });
//            ',
//            2
//        );

        return $output;
    
    }

}  
