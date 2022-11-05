<?php
	Website::setMetaTags(array('title'=>$title));

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = $title;;

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('cms', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>$title),
	);
?>
<h1 class="title"><?= $title; ?></h1>
<div class="block-body">
    <?= $text; ?>                    
</div>
