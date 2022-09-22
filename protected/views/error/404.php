<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Error 404')));	
	$this->_pageTitle = A::t('app', 'Error 404');
?>

<?php if(A::app()->view->getTemplate() != 'backend'){ ?>
	<h1 class="title title-error"><?= A::t('app', 'Error 404'); ?></h1>
<?php } ?>

<div class="block-body">                       
    <div id="error-page">
        <h2 class="error-title"><?= A::t('app', 'Error 404 Title'); ?></h2>
        <div class="error-description">    
            <?= A::t('app', 'Error 404 Description'); ?>
            <br><br>
            <?= A::t('app', 'Error 404 Troubleshooting'); ?>        
        </div>
    </div>    
</div>

