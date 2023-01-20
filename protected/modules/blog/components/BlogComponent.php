<?php
/**
 * Blog component
 *
 * PUBLIC (static):         PRIVATE:
 * ---------------         	---------------				
 * init
 * prepareTab
 * drawShortcode
 * drawLastPostsBlock
 * drawRecentPostsBlock
 * 
 */

namespace Modules\Blog\Components;

// Models
use \Modules\Blog\Models\Posts;

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


class BlogComponent extends CComponent
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
     * Prepares blog module tabs
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
				A::t('blog', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/blog', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('blog', 'Posts')  => array('href'=>'posts/index', 'id'=>'tabInfo', 'content'=>'', 'active'=>($activeTab == 'blogposts' ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
    	));
    }

	/**
	 * Draws shortcode output
	 * @param array $params
	 */
	public static function drawShortcode($params = array())
	{
		$output = ''; 
    }
    
    /**
     * Draws web form
     * @param string $title
     */
    public static function drawLastPostsBlock($title = '')
    {
        $output = '';
        $headerLength = 250;
        $testimonialsCount = 2;

        $postsCount = ModulesSettings::model()->param('blog', 'posts_count');
        $postsItemShowCount = ModulesSettings::model()->param('blog', 'posts_show_item');
        $postsItemSwitchTime = ModulesSettings::model()->param('blog', 'posts_show_time');
        $postsPagination = ModulesSettings::model()->param('blog', 'posts_pagination') ? 'yes' : 'no';
        $postsArrows = ModulesSettings::model()->param('blog', 'posts_arrows') ? 'yes' : 'no';
        $viewAllPostsLink = ModulesSettings::model()->param('blog', 'view_all_link');
        $moduleLink = ModulesSettings::model()->param('blog', 'modulelink');

        $settings = Bootstrap::init()->getSettings();
        $dateFormat = $settings->date_format;
        $dateTimeFormat = $settings->datetime_format;

        $posts = Posts::model()->findAll(
            array('condition'=>'publish_status = :publish_status', /*'order'=>'sort_order ASC',*/ 'limit'=>'0, '.(int)$postsCount),
            array(':publish_status'=>1)
        );
		
        // Draw all posts button, if access granted by settings
        $viewAllPosts = $viewAllPostsLink ? '<a href="posts/viewAll" class="btn btn-sm btn-default pull-right">'.A::t('blog', 'All Posts').'</a>'  : '' ;

        $output .= '<section class="listing-block latest-news">';
        $count = 0;

        if(is_array($posts) && count($posts) > 0){

            $output .= '<section class="widget v-recent-entry-widget">';
            $output .= '<ul>';

            foreach($posts as $key => $val){
                $link = Website::prepareLinkByFormat('blog', 'post_link_format', $val['id'], CHtml::encode($val['post_header']));
                
				$output.= '<li><a href="'.$link.'">'.strip_tags(CHtml::encode($val['post_header'])).'</a></li>';

//                $output.= '<li class="item">
//								<div class="post-block format-standard">
//									<a href="'.$link.'" class="media-box post-image"><img style="height:215px;" src="'.CHtml::encode($imagePath).'" alt=""></a>
//									<div class="post-actions">
//										<div class="post-date">'.date($dateFormat, strtotime($val['created_at'])).'</div>
//									</div>
//									<h3 class="post-title"><a href="'.$link.'">'.strip_tags(CHtml::encode($val['post_header'])).'</a></h3>
//									<div class="post-content">';
//											$postText = strip_tags($val['post_text']);
//											$postText = preg_replace('/{module:(.*?)}/i', '', $postText);
//										$output.= '<p>'.CString::substr($postText, $headerLength, '', true).'"&nbsp;</p>
//									</div>
//								</div>
//							</li>';
//                $count++;
            }

            $output.= '</ul>';
			$output.= '</section>';

        }else{
            $output .= A::t('blog', 'Did not match any posts');
        }
        $output .= '</section>';

        return $output;
    }
	
	/**
	 * Draws Recent Posts
	 */
	public static function drawRecentPostsBlock()
	{
		$output = '';
		
		$settings = Bootstrap::init()->getSettings();
		$dateFormat = $settings->date_format;
		
		$posts = Posts::model()->findAll(
			array('condition'=>'publish_status = :publish_status', /*'order'=>'sort_order ASC',*/ 'limit'=>'0, 3', 'orderBy'=>'created_at DESC'),
			array(':publish_status'=>1)
		);
		$countPosts = Posts::model()->count('publish_status = 1');
		
		if(is_array($posts) && count($posts) > 0){
			$output .= '<ul>';
			
			foreach($posts as $key => $val){
				$link = Website::prepareLinkByFormat('blog', 'post_link_format', $val['id'], CHtml::encode($val['post_header']));
				
				$output.= '<li>';
				$output.= '<a href="'.$link.'">'.strip_tags(CHtml::encode($val['post_header'])).'</a>';
				$output.= '<span class="post-date">'.date($dateFormat, strtotime($val['created_at'])).'</span>';
				$output.= '</li>';
			}
			
			$output.= '</ul>';
			
			if($countPosts > 3){
				// Draw all posts button
				$output .= '<a href="posts/viewAll">'.A::t('blog', 'All Posts').' →</a>';
			}
		}else{
			$output .= A::t('blog', 'Did not match any posts');
		}
		
		return $output;
	}
}