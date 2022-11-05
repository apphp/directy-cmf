<?php
/**
* AdvertisementsComponent
*
* PUBLIC: (statis)              PRIVATE
* -----------                   ------------------
* init
* prepareTab
* drawShortcode
* drawAdsBlock
*
*/

namespace Modules\Advertisements\Components;

// Modules
use \Modules\Advertisements\Models\Advertisements;

// Global
use \A,
    \CWidget,
    \CComponent,
    \CConfig,
    \CHtml;

// CMF
use \Website,
    \ModulesSettings;

class AdvertisementsComponent extends CComponent
{
    private static $countBlock = 0;
    private static $ads = null;

    /**
     * Initializes the class
     */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Prepares Advertisements module tabs
     * @param string $activeTab
     */
    public static function prepareTab($activeTab = 'info')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper'       => array('tag'=>'div', 'class'=>'title'),
            'tabsWrapperInner'  => array('tag'=>'div', 'class'=>'tabs'),
            'contentWrapper'    => array(),
            'contentMessage'    => '',
            'tabs'              => array(
                A::t('advertisements', 'Settings')       => array('href'=>Website::getBackendPath().'modules/settings/code/advertisements', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
                A::t('advertisements', 'Advertisements') => array('href'=>'advertisements/manage', 'id'=>'tabInfo', 'content'=>'', 'active'=>($activeTab == 'advertisements' ? true : false)),
            ),
            'events'            => array(
                //'click' => array('field'=>$errorField)
            ),
            'return'            => true,
        ));
    }
    
    /**
     * Draws shortcode output.
     * @param array $params
     */
    public static function drawShortcode($params = array())
    {
        $id = isset($params[0]) ? (int)$params[0] : 0;
        return self::drawAdsBlock('', '', $id);
    }
    
    /**
     * Draws ads side block
     * @param string $title
     * @param string $urlPage
     * @param int $id
     */
    public static function drawAdsBlock($title = '', $urlPage = '', $id = 0)
    {
        if(!ModulesSettings::model()->param('advertisements', 'allow_ads')){
            return '';
        }
        if((int)$id == 0){
            $ads = self::getActualAds(self::$countBlock++);
        }else{
            $tableAds = CConfig::get('db.prefix').Advertisements::model()->getTableName();
            $ads = Advertisements::model()->findByPk((int)$id, array(
                'condition'=>$tableAds.'.is_active = 1',
                'order'=>'sort_order DESC, created_at DESC',
            ));
            $ads = !empty($ads) ? $ads->getFieldsAsArray() : array();
        }
        if(empty($ads) || (empty($ads['image']) && empty($ads['title']) && empty($ads['text']))){
            return '';
        }

        $output = '';
        $content = '';

        if(!empty($ads['image'])){
            $content .= '<img src="assets/modules/advertisements/images/items/'.(!empty($ads['image']) ? $ads['image'] : 'no_image.png').'" style="width:'.(!empty($ads['width']) ? CHtml::encode($ads['width']) : '100%').';height:'.(!empty($ads['height']) ? CHtml::encode($ads['height']) : '100%').';">';
        }

        $content .= $ads['text'];

        if(!empty($ads['url'])){
            $content = '<a target="_blank" rel="noopener noreferrer" href="'.CHtml::encode($ads['url']).'">'.$content.'</a>';
        }

        $output = '<div class="side_block" id="ads-'.($id == 0 ? self::$countBlock : 'id-'.$id).'" style="'.(!empty($ads['width']) ? 'width:'.CHtml::encode($ads['width']).';' : '').(!empty($ads['height']) ? 'height:'.CHtml::encode($ads['height']).';' : '').(!empty($ads['background_color']) ? 'background:'.CHtml::encode($ads['background_color']).';' : '').'">
            <h4 class="v-heading"'.(!empty($ads['color_text']) ? ' style="color:'.CHtml::encode($ads['color_text']).';"' : '').'><span>'.htmlentities($ads['title']).'</span></h4>
            <div'.(!empty($ads['color_text']) ? ' style="color:'.CHtml::encode($ads['color_text']).';"' : '').'>'.$content.'</div>
        </div>';

        return $output;
    }

    /**
     * Prepare Advertisements
     * @param int $number
     * @return array
     */
    private static function getActualAds($number = 0)
    {
        if(self::$ads === null){
            // An array which will contain all the ads from the comparison type equal "Equally"
            $arrEqually = array();
            // An array which will contain all the ads from the comparison type equal "Substring"
            $arrSubstring = array();
            // The array that will contain all the ads in the correct order "Equally" then "Substring"
            $ads = array();

            $tableAds = CConfig::get('db.prefix').Advertisements::model()->getTableName();
            $allAds = Advertisements::model()->findAll(array(
                'condition'=>$tableAds.'.is_active = 1',
                'order'=>'sort_order ASC, created_at DESC',
            ));

            if(!empty($allAds) && is_array($allAds)){
                // If URL == "http://localhost/mblog/posts/viewAll/"
                // $baseUrl == "mblog/"
                $basePath = ltrim(A::app()->getRequest()->getBasePath(), '/');
                // $currentPath == "mblog/posts/viewAll"
                $currentPath = trim(A::app()->getRequest()->getRequestUri(), '/');
                // $relativePath == "posts/viewAll"
                $relativePath = substr($currentPath, strlen($basePath));
                if(empty($relativePath)){
                    $relativePath = trim(Website::getDefaultPage(), '\\/');
                }

                foreach($allAds as $advertisement){
                    if(!empty($advertisement['instruction'])){
                        $adsInstruction = trim($advertisement['instruction'],'/');
                        if($advertisement['type_comparison'] == 0){
                            // Equally
                            if(strnatcasecmp($adsInstruction, $relativePath) == 0){
                                $arrEqually[] = $advertisement;
                            }
                        }elseif($advertisement['type_comparison'] == 1){
                            // Substring
                            if(stristr($relativePath, $adsInstruction) !== false){
                                $arrSubstring[] = $advertisement;
                            }
                        }
                    }else{
                        // If you specify a null value, such a ads is for any page
                        $ads[] = $advertisement;
                    }
                }

                // First "Equally" then "Substring" at the end an advertisement with a empty value
                $ads = array_merge($arrEqually, $arrSubstring, $ads);
            }

            self::$ads = $ads;
        }


        if(isset(self::$ads[$number])){
            return self::$ads[$number];
        }else{
            return null;
        }
    }
    
}
