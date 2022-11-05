<?php 
	Website::setMetaTags(array('title'=>$newsHeader));

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = $newsHeader;

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('news', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('news', 'All News'), 'url'=>'news/viewAll'),
		array('label'=>$newsHeader),
	);
?>

<?= $actionMessage; ?>

<h1 class="title"><?= $newsHeader; ?></h1>
<span><?= A::t('news', 'Viewed').' ('.$hits.')'; ?></span>
<div class="block-body">	
    <div class="news-text">
		<?= !empty($introImage) ? '<img class="news-intro-image" src="assets/modules/news/images/intro_images/'.CHtml::encode($introImage).'" alt="news intro" /><br><br>' : '' ; ?>		
		<?= $newsText; ?>
	</div>
    <div class="news-info"><?= A::t('news', 'Published at').': '.$datePublished; ?></div>
</div>

