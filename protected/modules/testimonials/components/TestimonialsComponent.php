<?php
/**
 * Testimonials component for drawing testimonials
 *
 * PUBLIC (static):         PRIVATE:
 * ---------------         	---------------				
 * init
 * drawTestimonialsBlock
 * drawShortcode
 * prepareTab
 *
 */

namespace Modules\Testimonials\Components;

// Models
use \Modules\Testimonials\Models\Testimonials;

// Global
use \A,
	\CAuth,
	\CWidget,
	\CComponent,
	\CConfig,
	\CHtml,
	\CLoader,
	\CString,
	\CTime;

// CMF
use \Website,
	\Bootstrap,
	\LocalTime,
	\ModulesSettings,
	\SocialLogin;

class TestimonialsComponent extends CComponent
{
	
	const NL = "\n";
	
	/**
     *	Returns the instance of object
     *	@return self current class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}

	/**
	 * Draws web form
	 * @param string $title
	 */
	public static function drawTestimonialsBlock($title = '')
	{
		$output = '';
		$headerLength = 250;

		$testimonialsCount = ModulesSettings::model()->param('testimonials', 'testimonials_count');
		$viewAll = ModulesSettings::model()->param('testimonials', 'view_all_link');
		$moduleLink = ModulesSettings::model()->param('testimonials', 'modulelink');
		
		$testimonials = Testimonials::model()->findAll(
			array('condition'=>'is_active = :isActive', 'order'=>'sort_order ASC', 'limit'=>'0, '.(int)$testimonialsCount),
			array(':isActive'=>1)
		);
		
 		$output .= '<div class="side-panel-block testimonials-block">';
		$output .= '<h3 class="title">'.A::t('testimonials', 'Testimonials').'</h3>';
		$count = 0;
		$showViewAll = false;
		if(is_array($testimonials) && count($testimonials) > 0){
			foreach($testimonials as $key => $val){
				$country = ($val['author_country'] != '') ? $val['author_country'] : '';
				$city = ($val['author_city'] != '') ? ', '.$val['author_city'] : '';
				$company = ($val['author_company'] != '') ? ' &laquo;'.$val['author_company'].'&raquo;' : '';
				$author_position = ($val['author_position'] != '') ? ', '.$val['author_position'] : '';
				
				$output .= '<div class="block-body">					
				<p class="testimonials-info">"'.CString::substr($val['testimonial_text'], $headerLength).'"&nbsp;</p>
				<p class="testimonials-author-info">
					'.$val['author_name'].$company.$author_position.'<br />
					'.$country.$city.'
				</p>					
				</div>';
				$count++;
			}
			if($viewAll == 'always'){
				$showViewAll = true;
			}elseif(in_array($viewAll, array('show-after_1_item','show-after_2_items','show-after_3_items','show-after_4_items','show-after_5_items')) && ($count >= preg_replace('/[^0-9]/', '', $viewAll))){
				$showViewAll = true;
			}
			if($showViewAll) $output .= '<a class="testimonials-all" href="'.$moduleLink.'">'.A::t('testimonials', 'View All').'</a>';
		}else{
			$output .= A::t('testimonials', 'No Testimonials');
		}
		$output .= '</div>';

		return $output;
    }    

	/**
	 * Draws testimonials
	 * @param array $params
	 */
	public static function drawShortcode($params = array())
	{
		$output = '';
        
        $currentPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
        if($currentPage <= 0) $currentPage = 1;
        $pageSize = ModulesSettings::model()->param('testimonials', 'testimonials_per_page');
		$totalTestimonials = Testimonials::model()->count();
	
		$testimonials = Testimonials::model()->findAll(array(
			'limit'=>(($currentPage - 1) * $pageSize).', '.$pageSize,
			'order'=>'created_at DESC'
		));
        $showTestimonials = count($testimonials);
		
		if(!$showTestimonials){
			$output .= A::t('testimonials', 'Wrong parameter passed or there are no testimonials!');
		}else{
            for($i=0; $i < $showTestimonials; $i++){
                $country = ($testimonials[$i]['author_country'] != '') ? ', '.$testimonials[$i]['author_country'] : '';
                $city = ($testimonials[$i]['author_city'] != '') ? ', '.$testimonials[$i]['author_city'] : '';
                $company = ($testimonials[$i]['author_company'] != '') ? ' &laquo;'.$testimonials[$i]['author_company'].'&raquo;' : '';
                $author_position = ($testimonials[$i]['author_position'] != '') ? ', '.$testimonials[$i]['author_position'] : '';
        
                $output .= '<p class="testimonials-text"><q>'.$testimonials[$i]['testimonial_text'].'</q></p>';
                $output .= '<p class="testimonials-author-info">'.$testimonials[$i]['author_name'].$company.$author_position.$city.$country.'</p>';
                $output .= '<div class="testimonials-divider"></div>';
            }        
            
            if($totalTestimonials > 1){
                $output .= CWidget::create('CPagination', array(
                    'actionPath'   => A::app()->router->getCurrentUrl(),
                    'currentPage'  => $currentPage,
                    'pageSize'     => $pageSize,
                    'totalRecords' => $totalTestimonials,
                    'showResultsOfTotal' => false,
                    'linkType' => 0,
                    'paginationType' => 'justNumbers'
                ));            
            }
        }        
        
		return $output;        
    }

    /**
     * Prepares Testimonials module tabs
     * @param string $activeTab
     */
    public static function prepareTab($activeTab = 'testimonials')
    {
    	return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('testimonials', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/testimonials', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('testimonials', 'Testimonials') => array('href'=>'testimonials/manage', 'id'=>'tabTestimonials', 'content'=>'', 'active'=>($activeTab == 'testimonials' ? true : false)),
			),
			'events'=>array(),
			'return'=>true,
    	));
    }
}