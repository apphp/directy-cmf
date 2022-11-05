<?php
/**
 * Gallery component for drawing gallery
 *
 * PUBLIC (static):         PRIVATE
 * -----------              ------------------
 * init
 * prepareTab
 * drawShortcode
 * drawRecentImages
 *
 */

namespace Modules\Gallery\Components;

// Modules
use \Modules\Gallery\Models\GalleryAlbums,
	\Modules\Gallery\Models\GalleryAlbumItems;

// Global
use \A,
	\CWidget,
	\CComponent,
	\CHtml,
	\CConfig;

// CMF
use \Website,
	\Bootstrap,
	\ModulesSettings;

class GalleryComponent extends CComponent
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
     * Prepares gallery module tabs
     * @param string $activeTab
     * @param int $albumId
     */
    public static function prepareTab($activeTab = 'info', $albumId = '')
    {
    	return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('gallery', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/gallery', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('gallery', 'Gallery Albums')  => array('href'=>'galleryAlbums/manage', 'id'=>'tabGallery', 'content'=>'', 'active'=>(($activeTab == 'gallery' || $activeTab == 'galleryitems') ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
    	));
    }

	/**
	 * Draws testimonials
	 * @param array $params
	 */
	public static function drawShortcode($params = array())
	{
        $id = isset($params[0]) ? (int)$params[0] : 0;
        $opened = isset($params[1]) && $params[1] == 'opened' ? true : false;

		$output = '';

        //<!-- register fancybox files -->
        A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.mousewheel.pack.js', 2);
        A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.fancybox.pack'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.js', 2);
        A::app()->getClientScript()->registerCssFile('assets/vendors/fancybox/jquery.fancybox'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.css');

		if($opened){
			A::app()->getClientScript()->registerCss(
				'gallery',
				'.album-wrapper{float:left;display:inline-block;width:100%;}
				.link-gallery-albums{float:left;display:inline-block;margin-right:10px;}'
			);
		}else{
			A::app()->getClientScript()->registerCss(
				'gallery',
				'.album-wrapper,.album-image{float:left;display:inline-block;width:150px;}'
			);
		}
        
		$galleryAlbums = GalleryAlbums::model()->findAll(array(
            'condition'=>'is_active = 1'.(!empty($id) ? ' AND '.CConfig::get('db.prefix').'gallery_albums.id = '.$id : ''),
			'order'=>'sort_order ASC'
		));
		
        $showAlbums = count($galleryAlbums);

		if(!$showAlbums){
			$output .= A::t('gallery', 'Wrong parameter passed or there are no available albums!');
		}else{
            $output .= CHtml::openTag('div', array('class'=>'gallery-wrapper')).self::NL;
            for($i=0; $i < $showAlbums; $i++){
                
                $galleryAlbumItems = GalleryAlbumItems::model()->findAll(array(
                    'condition'=>CConfig::get('db.prefix').'gallery_album_items.gallery_album_id = :galleryAlbumId AND '.CConfig::get('db.prefix').'gallery_album_items.is_active = 1',
                    'order'=>CConfig::get('db.prefix').'gallery_album_items.sort_order ASC'),
                    array(':galleryAlbumId'=>$galleryAlbums[$i]['id'])
                );
                
                $outputAbumImages = '';
                $albumCoverImage = '';
                $albumCoverThumb = '';
                $albumCoverTitle = '';
                $showAlbumItems = count($galleryAlbumItems);
                for($j=0; $j < $showAlbumItems; $j++){
                    $albumItemName = ($galleryAlbumItems[$j]['album_item_name'] != '') ? trim($galleryAlbumItems[$j]['album_item_name'], '.') : '';
                    
					if($opened){
						// Show all album items
						$titleAttr = $albumItemName;
						if(!empty($galleryAlbumItems[$j]['album_item_description'])){
							$titleAttr .= !empty($albumItemName) ? '. ' : '';
							$titleAttr .= $galleryAlbumItems[$j]['album_item_description'];
						}
						$itemFile = $galleryAlbumItems[$j]['item_file_thumb'];
						$itemImage = '<img src="assets/modules/gallery/images/'.($itemFile != '' ? 'items/'.$itemFile : 'empty_album.png').'" alt="'.CHtml::encode($galleryAlbums[$i]['album_title']).'" />';
						
						$outputAbumImages .= CHtml::link($itemImage, 'assets/modules/gallery/images/items/'.$galleryAlbumItems[$j]['item_file'], array('class'=>'link-gallery-albums', 'title'=>CHtml::encode($titleAttr), 'rel'=>'album-'.$galleryAlbums[$i]['id'])).self::NL;
						
						// For bootsbox template
						//$outputAbumImages .= '<div class="v-single-img v-animation borderframe col-sm-6" data-animation="fade-in" data-delay="200" style="opacity: 1;">
						//	<figure class="lightbox animated-overlay overlay-alt clearfix">
						//		'.$itemImage.'
						//		<a class="view" href="assets/modules/gallery/images/'.($galleryAlbumItems[$j]['item_file'] != '' ? 'items/'.$galleryAlbumItems[$j]['item_file'] : 'empty_album.png').'" rel="album-'.$galleryAlbums[$i]['id'].'"></a>
						//		<figcaption>
						//			<div class="thumb-info">
						//				<h4>'.CHtml::encode($titleAttr).'</h4>
						//				<i class="fa fa-eye" style="visibility: visible; opacity: 1; transition-duration: 0.3s; transform: scale(0.5) rotate(-90deg);"></i>
						//			</div>
						//		</figcaption>
						//	</figure>
						//</div>';
					}else{
						// Show the cover only, all items via fancybox
						if($galleryAlbumItems[$j]['item_file'] != '' && empty($albumCoverImage)){
							$albumCoverImage = $galleryAlbumItems[$j]['item_file'];
							$albumCoverTitle = $albumItemName;
							if(!empty($galleryAlbumItems[$j]['album_item_description'])){
								$albumCoverTitle .= !empty($albumItemName) ? '. ' : '';
								$albumCoverTitle .= $galleryAlbumItems[$j]['album_item_description'];
							}
							$albumCoverThumb = ($galleryAlbumItems[$j]['item_file_thumb'] != '') ? $galleryAlbumItems[$j]['item_file_thumb'] : $albumCoverImage;
						}else{
							$titleAttr = $albumItemName;
							if(!empty($galleryAlbumItems[$j]['album_item_description'])){
								$titleAttr .= !empty($albumItemName) ? '. ' : '';
								$titleAttr .= $galleryAlbumItems[$j]['album_item_description'];
							}
							$outputAbumImages .= CHtml::link('', 'assets/modules/gallery/images/items/'.$galleryAlbumItems[$j]['item_file'], array('class'=>'link-gallery-albums', 'title'=>CHtml::encode($titleAttr), 'rel'=>'album-'.$galleryAlbums[$i]['id'])).self::NL;
						}
					}
                }

                $output .= CHtml::openTag('div', array('class'=>'album-wrapper')).self::NL;
                $output .= CHtml::openTag('div', array('class'=>'album-image')).self::NL;

				// Show cover image for album
				if(!$opened){
					if($showAlbumItems) $output .= CHtml::openTag('a', array('class'=>'link-gallery-albums', 'title'=>CHtml::encode($albumCoverTitle), 'rel'=>'album-'.$galleryAlbums[$i]['id'], 'href'=>'assets/modules/gallery/images/'.($albumCoverImage != '' ? 'items/'.$albumCoverImage : 'empty_album.png'))).self::NL;
					$output .= '<img src="assets/modules/gallery/images/'.($albumCoverThumb != '' ? 'items/'.$albumCoverThumb : 'empty_album.png').'" alt="'.CHtml::encode($galleryAlbums[$i]['album_title']).'" />';
					if($showAlbumItems) $output .= CHtml::closeTag('a').self::NL; /* album-wrapper */
				}

                $output .= CHtml::closeTag('div').self::NL; /* album-image */
                $output .= '<div class="album-title" title="'.CHtml::encode($galleryAlbums[$i]['album_description']).'">'.strip_tags($galleryAlbums[$i]['album_title']).'</div>'.self::NL;
                $output .= $outputAbumImages;                
                $output .= CHtml::closeTag('div').self::NL; /* album-wrapper */
            }            
            $output .= CHtml::closeTag('div').self::NL; /* gallery-wrapper */
        }
        
        A::app()->getClientScript()->registerScript(
            'viewMenuCategory',
            'jQuery(document).ready(function(){
                jQuery("a.link-gallery-albums").fancybox({
                    "opacity"		: true,
                    "overlayShow"	: false,
                    "overlayColor"	: "#000",
                    "overlayOpacity": 0.5,
                    "titlePosition"	: "inside", /* outside, inside or float */
                    "cyclic" : true,
                    "transitionIn"	: "elastic", /* fade or none*/
                    "transitionOut"	: "fade"
                });
            });	
            ',
            2
        );
        
		return $output;        
    }	
	
	/**
	 * Draws Recently added imaged to the footer of the page
	 * @param int $count
	 * @return HTML
	 */
	public static function drawRecentImages($count = 6)
	{
		$output = '';
		$baseUrl = A::app()->getRequest()->getBaseUrl();
		
		$galleryItems = GalleryAlbumItems::model()->findAll(array(
			'condition'	=> CConfig::get('db.prefix').'gallery_album_items.is_active = 1',
			'order'		=> CConfig::get('db.prefix').'gallery_album_items.sort_order ASC',
			'limit'		=> '0,'.(int)$count)
		);
		
		if(!empty($galleryItems)){		
			$output .= '<ul class="portfolio-grid">';
			foreach($galleryItems as $key => $galleryItem){
				if(!empty($galleryItem['item_file'])){
					$output .= '<li>';
					$output .= '<a href="'.$baseUrl.'assets/modules/gallery/images/items/'.$galleryItem['item_file'].'" class="grid-img-wrap">';
					$output .= '<img src="'.$baseUrl.'assets/modules/gallery/images/items/'.$galleryItem['item_file_thumb'].'" />';
					$output .= '<span class="tooltip">'.CHtml::encode($galleryItem['album_item_name']).'<span class="arrow"></span></span>';
					$output .= '</a>';
					$output .= '</li>';
				}
			}
			$output .= '<ul>';
		}
		
		return $output;
	}
    
}