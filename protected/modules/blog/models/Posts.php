<?php
/**
 * Posts model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct
 * model (static)
 * search
 *
 */


namespace Modules\Blog\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
    \CHtml,
	\CConfig;

// Directy
use \Website;


class Posts extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'blog_posts';

    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Returns the static model of the specified AR class
	 */
	public static function model()
	{
		return parent::model(__CLASS__);
	}
	
	/**
	 * Performs search in posts
	 * @param string $keywords
	 * @param mixed $itemsCount
	 * @return array array('0'=>array(posts), '1'=>total)
	 */	
	public function search($keywords = '', $itemsCount = 10)
	{
		$result = array();
		
		if($keywords !== ''){
			
			$limit = !empty($itemsCount) ? '0, '.(int)$itemsCount : '';
			$condition = CConfig::get('db.prefix').$this->_table.'.publish_status = 1 AND ('.
				CConfig::get('db.prefix').$this->_table.'.post_text LIKE :keywords OR '.
				CConfig::get('db.prefix').$this->_table.'.post_header LIKE :keywords)';
			
			// Count total items in result
			$total = $this->count(array('condition'=>$condition), array(':keywords'=>'%'.$keywords.'%'));
			
			// Prepare posts result
			$posts = $this->findAll(array('condition'=>$condition, 'limit' => $limit), array(':keywords'=>'%'.$keywords.'%'));
			foreach($posts as $key => $val){
				$result[0][] = array(
					'date'			=> $val['created_at'],
					'title' 		=> $val['post_header'],
					'intro_image'	=> (!empty($val['post_image']) ? '<img class="intro-thumb posts-intro-thumb" src="assets/modules/blog/images/intro_images/'.CHtml::encode($val['post_image']).'" alt="posts intro" />' : ''),
					'content' 		=> $val['post_text'],
					'link' 			=> Website::prepareLinkByFormat('cms', 'post_link_format', $val['id'], $val['post_header'])
				);
			}
			
			$result[1] = $total;
		}
		
		return $result;
	}
	
}


