<?php
	Website::setMetaTags(array('title'=>$postHeader));

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = $postHeader;

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('blog', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('blog', 'Posts'), 'url'=>'posts/viewAll'),
		array('label'=>$postHeader),
	);
?>

<?= $actionMessage; ?>

<article>
	<h1 class="title"><?= $postHeader; ?></h1>
    
	<div class="post-info clearfix">
		<span class="vcard author">
			<?= A::t('blog', 'Posted by').' '.$createdBy; ?>
			<?= A::t('blog', 'at').' '.$createdAt; ?>
		</span>
		
		<div class="like-info">
		<?php
			if($views == 0):
				echo A::t('blog', 'not viewed yet');
			elseif($views == 1):
				echo '<i class="fa fa-eye"></i> '.$views.' '.A::t('blog', 'view');
			else:
				echo '<i class="fa fa-eye"></i> '.$views.' '.A::t('blog', 'views');
			endif;
		?>
		</div>
    </div>

	<section class="article-body-wrap">
		<div class="body-text clearfix"><?= $postText; ?></div>
	</section>

<!--	<div class="tags-link-wrap clearfix">
		<div class="tags-wrap">
			Tags:<span class="tags">
			<a href="#" rel="tag">Tag A</a>,
			<a href="#" rel="tag">Tag B</a>
			</span>
		</div>
	</div>	
-->

	<?php
		$socialNetworks = SocialNetworks::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'), array(), 'social-networks');
		if(!empty($socialNetworks)):
	?>
	<div class="share-links curved-bar-styling clearfix">
		<div class="share-text"><?= A::t('blog', 'Share this article');?>:</div>
		<ul class="social-icons">
			<?php
				foreach($socialNetworks as $key => $socialNetwork):
					$networkCode = str_replace('-', '', $socialNetwork['code']);
					echo '<li class="'.$networkCode.'"><a href="'.$socialNetwork['link'].'" class="post_share_'.$networkCode.'" target="_blank" rel="noopener noreferrer"><i class="fa fa-'.$socialNetwork['code'].'"></i><i class="fa fa-'.$socialNetwork['code'].'"></i></a></li>';
				endforeach;				
			?>
		</ul>
	</div>
	<?php endif; ?>

</article>

