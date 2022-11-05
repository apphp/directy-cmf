<?php
	Website::setMetaTags(array('title'=>A::t('events', 'Events Calendar')));

	// Define active menu
	$this->_activeMenu = 'events/showCalendar';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('events', 'Events Calendar');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('events', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('events', 'Events Calendar')),
	);
?>

<div class="block-body v-blog-items-wrap blog-mini">
	<?= \Modules\Events\Components\EventsComponent::drawCalendar($currentLangCode, $categories, $url, $timezone); ?>
</div>

	