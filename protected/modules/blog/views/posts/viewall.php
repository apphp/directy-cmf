<?php 
	Website::setMetaTags(array('title'=>A::t('blog', 'Posts')));
	
	// Define active menu
	$this->_activeMenu = 'posts/viewAll';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('blog', 'Posts');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('blog', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('blog', 'Posts')),
	);
?>

<h1 class="title"><?=  A::t('blog', 'Our Blog'); ?></h1>

<div class="block-body v-blog-items-wrap blog-mini">
<ul class="v-blog-items row mini-items clearfix"> 
<?php
if($actionMessage != ''):
	echo $actionMessage;
else:
	$showPosts = count($posts);
	for($i=0; $i < $showPosts; $i++){
		// Find first image and redo it to be a thumbnail
		preg_match_all('/<img[^>]+>/i', $posts[$i]['post_text'], $images);
		$postThumbnail = isset($images[0][0]) ? $images[0][0] : '';
		$postThumbnail = preg_replace('/(width|height|style)="*"/', '', $postThumbnail);
		$postThumbnail = preg_replace('/<img/', '<img class="blog-post-thumbnail"', $postThumbnail);
		
		$postText = strip_tags($posts[$i]['post_text']);
		$postText = preg_replace('/{module:(.*?)}/i', '', $postText);
		$postLink = Website::prepareLinkByFormat('blog', 'post_link_format', $posts[$i]['id'], $posts[$i]['post_header']);
?>	
	<li class="v-blog-item col-sm-12">
		<div class="mini-v-blog-item-wrap">
			<figure class="animated-overlay overlay-alt">
				<?= $postThumbnail; ?>
				<a href="<?= $postLink; ?>" class="link-to-post"></a>
				<figcaption>
					<div class="thumb-info thumb-info-v2"><i class="fa fa-angle-right"></i></div>
				</figcaption>
			</figure>
			<div class="blog-v-blog-item-info">
				<h3><a href="<?= $postLink; ?>"><?= $posts[$i]['post_header']; ?></a></h3>
				<div class="v-blog-item-details">
					<?= A::t('blog', 'Posted by'); ?> <?= $posts[$i]['created_by']; ?>
					<?= A::t('blog', 'at').' '.CLocale::date($dateTimeFormat, $posts[$i]['created_at']); ?>
				</div>
				<div class="v-blog-item-description">
					<p><?= CString::substr($postText, $postLength, '', true); ?></p>
				</div>
				<a class="btn v-btn v-third-dark" href="<?= $postLink; ?>"><?= A::t('blog', 'Read more');?></a>
				<div class="like-info">
					<div class="comments-wrapper">
						<a href="<?= $postLink; ?>">
							<span>
							<?php
								if($posts[$i]['views'] == 0):
									echo A::t('blog', 'not viewed yet');
								elseif($posts[$i]['views'] == 1):
									echo '<i class="fa fa-eye"></i>'.$posts[$i]['views'].' '.A::t('blog', 'view');
								else:
									echo '<i class="fa fa-eye"></i>'.$posts[$i]['views'].' '.A::t('blog', 'views');
								endif;
							?>
							</span>
						</a>
					</div>
					<!--<div class="like-info-wrap"><span class="like-count"></span>-->
				</div>
			</div>
		</div>
	</li>
    <!--<div class="posts-divider"></div>-->
<?php } ?>
</ul>

<?php 
	if($totalPosts > 1):
		echo CWidget::create('CPagination', array(
			'actionPath'   => 'posts/viewAll',
			'currentPage'  => $currentPage,
			'pageSize'     => $pageSize,
			'totalRecords' => $totalPosts,
			'showEmptyLinks' => false,
			'showResultsOfTotal' => false,
			'linkType' => 0,
			'linkNames' =>array('previous'=>A::t('blog', 'Newer Posts'), 'next'=>A::t('blog', 'Older Posts')),
			'paginationType' => 'olderNewer' 
		));            
	endif;
endif;
?>    
</div>
