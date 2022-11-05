<?php
    Website::setMetaTags(array('title'=>A::t('polls', 'Polls Archive')));

    // Register polls main css file
    A::app()->getClientScript()->registerCssFile('assets/modules/polls/css/polls.css');

	// Define active menu
	$this->_activeMenu = 'polls/showAll';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('polls', 'Polls Archive');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('polls', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('polls', 'All Polls'), 'url'=>'polls/showAll'),
	);
?>

<h1 class="title"><?= A::t('polls', 'Polls Archive'); ?></h1>

<div class="block-body">
        <div class="polls-previous">
        <?php if ($previousPolls): ?>
            <ul>
            <?php foreach ($previousPolls as $poll): ?>
                <li><a href="polls/show/id/<?= $poll['id'] ?>"><?= $poll['poll_question']; ?></a></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        </div>
</div>
