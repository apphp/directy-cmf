<?php
/**
 * BannersComponent
 *
 * PUBLIC (static):         PRIVATE:
 * -----------         		------------------
 * init
 * prepareTab				
 * drawSlider
 * drawShortcode
 * getBanner
 * 
 */

namespace Modules\Banners\Components;

// Modules
use \Modules\Banners\Models\Banners;

// Global
use \A,
	\CWidget,
	\CComponent,
	\CFile,
	\CHtml;

// CMF
use \Website,
	\ModulesSettings;


class BannersComponent extends CComponent
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
     * Prepares Banners module tabs
     * @param string $activeTab
     * @param int $menuCatId
     * @param int $menuCatItemId
	 * @return bool
     */
    public static function prepareTab($activeTab = 'info', $menuCatId = '', $menuCatItemId = '')
    {
    	return CWidget::create('CTabs', array(
			'tabsWrapper' => array('tag' => 'div', 'class' => 'title'),
			'tabsWrapperInner'=>array('tag' => 'div', 'class' => 'tabs'),
			'contentWrapper' => array(),
			'contentMessage' => '',
			'tabs'=>array(
				A::t('banners', 'Settings') => array('href' => Website::getBackendPath().'modules/settings/code/banners', 'id' => 'tabSettings', 'content' => '', 'active' => false, 'htmlOptions' => array('class' => 'modules-settings-tab')),
				A::t('banners', 'Banners Management') => array('href' => 'Banners/index', 'id' => 'tabBanners', 'content' => '', 'active' => ($activeTab == 'banners' ? true : false)),
			),
			'events' => array(
			),
			'return' => true,
    	));
    }
		
	/**
	 * Draws banner slider
	 * @param array $params		Values: 'type', 'width', 'height', 'navigation', 'delay',
	 * @return string $output
	 */
	public static function drawSlider($params = array())
	{
		// Module settings
		$rotationDelay = (int)ModulesSettings::model()->param('banners', 'rotation_delay'); 
		$viewerMode = ModulesSettings::model()->param('banners', 'viewer_type');
		$output = '';
		
		$type = isset($params['type']) ? $params['type'] : '';	/* 'coin' or 'revolution' */
		$width = isset($params['width']) ? (int)$params['width'] : '670';
		$height = isset($params['height']) ? (int)$params['height'] : '445';
		$navigation = isset($params['navigation']) ? (string)$params['navigation'] : 'true';
		$paramsDelay = isset($params['delay']) ? (int)$params['delay'] : '';
		
		// Calculate a delay in milliseconds
		$delay = !empty($paramsDelay) ? $paramsDelay : $rotationDelay * 1000;
		
		// Setup visability state for banners
		$sliderVisible = true;
		switch($viewerMode){
			case 'visitors only':
				$sliderVisible = CAuth::isLoggedIn() ? false : true;
				break;
			case 'registered only':
				$sliderVisible = CAuth::isLoggedIn() ? true : false;
				break;
			default:
				$sliderVisible = true;
				break;
		}

		if($sliderVisible){
				
			$bannersData = Banners::model()->findAll(array(
				'condition' => 'is_active = 1',
				'order' => 'sort_order ASC'
			));
			
			$countData = count($bannersData);
			
			if($countData){
				if($type == 'revolution' || $type == 'rev'){
					// Draw Revolution slider
					A::app()->getClientScript()->registerCssFile('assets/vendors/rs-slider/css/settings.css');
					A::app()->getClientScript()->registerCssFile('assets/vendors/rs-slider/css/custom-captions.css');
					
					A::app()->getClientScript()->registerScriptFile('assets/vendors/rs-slider/js/jquery.themepunch.tools.min.js', 2);
					A::app()->getClientScript()->registerScriptFile('assets/vendors/rs-slider/js/jquery.themepunch.revolution.min.js', 2);

					A::app()->getClientScript()->registerScript(
						'viewMenuCategory',
						'jQuery(document).ready(function() {
							jQuery("div#rev-slider").each(function () {
								var slider = jQuery(this);				
								var defaults = {				
									dottedOverlay: "none",
									delay: '.$delay.',
									startwidth: '.$width.',
									startheight: '.$height.',
									hideThumbs: 200,
					
									thumbWidth: 100,
									thumbHeight: 50,
									thumbAmount: 5,
					
									navigationType: "bullet",
									navigationArrows: "'.($navigation ? 'solo' : 'off').'",
									navigationStyle: "preview4",
					
									touchenabled: "on",
									onHoverStop: "on",
					
									swipe_velocity: 0.7,
									swipe_min_touches: 1,
									swipe_max_touches: 1,
									drag_block_vertical: false,
					
									parallax: "mouse",
									parallaxBgFreeze: "on",
									parallaxLevels: [7, 4, 3, 2, 5, 4, 3, 2, 1, 0],
					
									keyboardNavigation: "off",
					
									navigationHAlign: "center",
									navigationVAlign: "bottom",
									navigationHOffset: 0,
									navigationVOffset: 20,
					
									soloArrowLeftHalign: "left",
									soloArrowLeftValign: "center",
									soloArrowLeftHOffset: 20,
									soloArrowLeftVOffset: 0,
					
									soloArrowRightHalign: "right",
									soloArrowRightValign: "center",
									soloArrowRightHOffset: 20,
									soloArrowRightVOffset: 0,
					
									shadow: 0,
									fullWidth: "on",
									fullScreen: "off",
					
									spinner: "spinner4",
					
									stopLoop: "off",
									stopAfterLoops: -1,
									stopAtSlide: -1,
					
									shuffle: "off",
					
									autoHeight: "off",
									forceFullWidth: "off",
									 
									hideThumbsOnMobile: "off",
									hideNavDelayOnMobile: 1500,
									hideBulletsOnMobile: "off",
									hideArrowsOnMobile: "off",
									hideThumbsUnderResolution: 0,
					
									hideSliderAtLimit: 0,
									hideCaptionAtLimit: 0,
									hideAllCaptionAtLilmit: 0,
									startWithSlide: 0,
									videoJsPath: "",
									fullScreenOffsetContainer: ""
								}
					
								var config = jQuery.extend({}, defaults, slider.data("slider-options"));
					
								// Initialize Slider
								slider.show().revolution(config);
							});
						});
						',
						2
					);
					
					$output = CHtml::openTag('div', array('id'=>'rev-slider')).self::NL;
					$output .= '<ul>';
					for ($i = 0; $i < $countData; $i++){
						// Slide
						$output .= '<li data-transition="fade" data-slotamount="7" data-masterspeed="1000">';
						$baseUrl = A::app()->getRequest()->getBaseUrl();
						
						$imageFile = CFile::fileExists('assets/modules/banners/images/items/'.$bannersData[$i]['image_file']) ? $bannersData[$i]['image_file'] : 'no_banner.png';
						$output .= '<img src="'.$baseUrl.'assets/modules/banners/images/items/'.$imageFile.'" alt="fullslide6" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat">';

						$output .= '<!-- LAYER NR. 1 -->
						<div class="tp-caption light_heavy_70_shadowed lfb ltt tp-resizeme"
							 data-x="60"
							 data-y="110"
							 data-speed="600"
							 data-start="800"
							 data-easing="Power4.easeOut"
							 data-splitin="none"
							 data-splitout="none"
							 data-elementdelay="0.01"
							 data-endelementdelay="0.1"
							 data-endspeed="500"
							 data-endeasing="Power4.easeIn"
							 style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;">
							'.$bannersData[$i]['banner_title'].'
						</div>';
	
						if(!empty($bannersData[$i]['banner_description'])){
							$output .= '<!-- LAYER NR. 2 -->
							<div class="tp-caption light_medium_30 lfb ltt tp-resizeme"
								 data-x="60"
								 data-y="200"
								 data-speed="600"
								 data-start="900"
								 data-easing="Power4.easeOut"
								 data-splitin="none"
								 data-splitout="none"
								 data-elementdelay="0.01"
								 data-endelementdelay="0.1"
								 data-endspeed="500"
								 data-endeasing="Power4.easeIn"
								 style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;">
								'.$bannersData[$i]['banner_description'].'
							</div>';
						}
						
						if(!empty($bannersData[$i]['link_url'])){
							//data-x="center" data-hoffset="0"
							$output .= '<div class="tp-caption lfb stb"
								 data-x="60"
								 data-y="bottom" data-voffset="-70"
								 data-speed="700"
								 data-start="1700"
								 data-easing="Circ.easeInOut"
								 data-splitin="none"
								 data-splitout="none"
								 data-elementdelay="0"
								 data-endelementdelay="0"
								 data-endspeed="600">
								<a href="'.$bannersData[$i]['link_url'].'" class="btn v-btn v-second-light">'.(!empty($bannersData[$i]['banner_button']) ? $bannersData[$i]['banner_button'] : A::t('app', 'Find out more')).'</a>
							</div>';
						}
						
						$output .= '</li>';
					}
					
					$output .= '</ul>';
					$output .= CHtml::closeTag('div').self::NL;
	
				}else{
					// Draw default slider			
					A::app()->getClientScript()->registerScriptFile('assets/vendors/jquery/jquery'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.js', 2);
					A::app()->getClientScript()->registerScriptFile('assets/vendors/coin-slider/coin-slider.min.js', 2);
					A::app()->getClientScript()->registerCssFile('assets/vendors/coin-slider/coin-slider'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.css');
					A::app()->getClientScript()->registerScript(
						'viewMenuCategory',
						'jQuery(document).ready(function() {
							if(jQuery("#coin-slider").length > 0){
								jQuery("#coin-slider").coinslider({ width: '.$width.', height: '.$height.', navigation: '.$navigation.', delay: '.$delay.', });
							}
						});
						',
						2
					);					
		
					$output = CHtml::openTag('div', array('id' => 'coin-slider')).self::NL;
					for ($i = 0; $i < $countData; $i++){
						$bannerItem = CHtml::image('assets/modules/banners/images/items/'.$bannersData[$i]['image_file'], CHtml::encode($bannersData[$i]['banner_description'])).CHtml::tag('span', '', $bannersData[$i]['banner_description']);
						if($bannersData[$i]['link_url'] != ''){
							$output .= CHtml::link(
								$bannerItem,
								$bannersData[$i]['link_url'],
								array('target'=>'_blank')
							);					
						}else{
							$output .= $bannerItem;
						}
					}
					$output .= CHtml::closeTag('div').self::NL;
				}
			}
		}
		
		return $output;		
	}

	/**
	 * Draws shortcode output
	 * @param array $params
	 */
	public static function drawShortcode($params = array())
	{
		return self::drawSlider($params);
    }
	
    /**
     * Get banner by ID
     * @param int $bannerId
     * @return html
     */
    public static function getBanner($bannerId)
    {
        $banner = Banners::model()->findByPk($bannerId, 'is_active = 1');
        if(empty($banner)){
            return '';
        }

        $link = ($banner->link_url ? $banner->link_url : '');
        $src = ($banner->image_file ? $banner->image_file : 'no_image.png');

        if(empty($link)){
            $output  = CHtml::openTag('div', array('class'=>'banner'));
            $output .= CHtml::image('assets/modules/banners/images/items/'.$src, A::t('banners', 'Banner'), array('title'=>CHtml::encode($banner->banner_description)));
            $output .= CHtml::closeTag('div');
        }else{
            $output  = CHtml::openTag('a', array('class'=>'banner', 'href'=>$link, 'target'=>'_blank'));
            $output .= CHtml::image('assets/modules/banners/images/items/'.$src, A::t('banners', 'Banner'), array('title'=>CHtml::encode($banner->banner_description)));
            $output .= CHtml::closeTag('a');
        }

        return $output;
    }
}
