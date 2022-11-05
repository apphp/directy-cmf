<?php 
	Website::setMetaTags(array('title'=>A::t('news', 'All News')));

	$this->_activeMenu = 'news/viewAll';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('news', 'All News');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('news', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('news', 'All News')),
	);

	$layout = 'new';
?>

<h1 class="title"><?=  A::t('news', 'All News'); ?></h1>

<?php if($layout == 'old'): ?>

	<div class="block-body">
	<?php
		if($actionMessage != ''){
			echo $actionMessage;
		}else{    
			$showNews = count($news);
			for($i=0; $i < $showNews; $i++){
				$newsLink = Website::prepareLinkByFormat('news', 'news_link_format', $news[$i]['id'], $news[$i]['news_header']);
	?>
			<h3><a href="<?= $newsLink; ?>"><?= $news[$i]['news_header']; ?></a></h3>
			<span><?= A::t('news', 'Viewed').' ('.$news[$i]['hits'].')'; ?></span>
			<div class="news-text">
				<?= !empty($news[$i]['intro_image']) ? '<img class="news-intro-thumb" src="assets/modules/news/images/intro_images/'.CHtml::encode($news[$i]['intro_image']).'" alt="news intro" />' : ''; ?>
				<?= preg_replace('/{module:(.*?)}/i', '', $news[$i]['news_text']); ?>
			</div>    
			<div class="news-info"><?= A::t('news', 'Published at').': '.CLocale::date($dateTimeFormat, $news[$i]['created_at']); ?></div>
			<div class="news-divider"></div>
	<?php
			}
			
			if($totalNews > 1){
				echo CWidget::create('CPagination', array(
					'actionPath'   => 'news/viewAll',
					'currentPage'  => $currentPage,
					'pageSize'     => $pageSize,
					'totalRecords' => $totalNews,
					'showResultsOfTotal' => false,
					'linkType' => 0,
					'paginationType' => 'justNumbers'
				));            
			}
		}
	?>    
	</div>

<?php else: ?>

	<div class="v-blog-items-wrap blog-standard">

		<ul class="v-blog-items row standard-items clearfix">
		<?php
			if($actionMessage != ''):
				echo $actionMessage;
			else:
				$showNews = count($news);
				for($i=0; $i < $showNews; $i++):
					$newsLink = Website::prepareLinkByFormat('news', 'news_link_format', $news[$i]['id'], $news[$i]['news_header']);
		?>

			<li class="v-blog-item col-sm-12">
				<?php				
					if(!empty($news[$i]['intro_image'])):
				?>
					<figure class="animated-overlay overlay-alt">
						<img src="assets/modules/news/images/intro_images/<?= CHtml::encode($news[$i]['intro_image']); ?>" alt="news intro" />
						<a href="<?= $newsLink; ?>" class="link-to-post"></a>
						<figcaption>
							<div class="thumb-info thumb-info-v2"><i class="fa fa-angle-right" style="visibility: visible; opacity: 1; transition-duration: 0.3s; transform: scale(0.5) rotate(-90deg);"></i></div>
						</figcaption>
					</figure>
				<?php endif; ?>
	
				<div class="post-content">	
					<div class="post-inner" style="margin-left:0px;">
						<div class="post-header">
							<h2 class="title"><a href="<?= $newsLink; ?>"><?= $news[$i]['news_header']; ?></a></h2>
							<span><?= A::t('news', 'Viewed').' ('.$news[$i]['hits'].')'; ?></span>
							<div class="post-meta-info">
								<span class="blog-author minor-meta">
									<?= A::t('news', 'Published at').': <span class="entry-author-link">'.CLocale::date($dateTimeFormat, $news[$i]['created_at']).'</span>'; ?>
								</span>
								<!--<span class="text-sep">|</span>-->
							</div>
						</div>
						<div class="v-blog-post-description">
							<?= preg_replace('/{module:(.*?)}/i', '', $news[$i]['news_text']); ?>
						</div>
						<a class="btn v-btn v-third-dark read-more" href="<?= $newsLink; ?>"><?= A::t('news', 'read more'); ?></a>
					</div>
				</div>
			</li>
	
		<?php endfor; ?>
		</ul>
		
		<?php 
			if($totalNews > 1):
				echo CWidget::create('CPagination', array(
					'actionPath'   => 'news/viewAll',
					'currentPage'  => $currentPage,
					'pageSize'     => $pageSize,
					'totalRecords' => $totalNews,
					'showResultsOfTotal' => false,
					'linkType' 		=> 0,
					'paginationType' => 'justNumbers',
				));            
			endif;
		endif;	
		?>    
	
	</div>

<?php endif; ?>
