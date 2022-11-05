<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'User Registration')));

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('users', 'User Registration');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('users', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('users', 'User Registration')),
	);
?>
<h1 class="title"><?= A::t('users', 'User Registration'); ?></h1>
<div class="block-body">
    <?= $actionMessage; ?>
</div>