<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Home Page')));
	$this->_pageTitle = '';
    
    if(!isset($title)) $title = '';
    if(!isset($text)) $text = '';
?>

<h1 class="title"><?php echo $title; ?></h1>
<div class="block-body">                       
    <?php echo $text; ?>                    
</div>
