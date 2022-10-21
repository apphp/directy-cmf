<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Common Error')));
    $this->_pageTitle = A::t('app', 'Common Error');
?>

<?php if(A::app()->view->getTemplate() != 'backend'){ ?>
	<h3 class="title title-error"><?= A::t('app', 'Common Error'); ?></h3>
<?php } ?>

<div class="block-body">                       
    <div id="error-page">
        <h2 class="error-title"><?= A::t('app', 'Common Error Title'); ?></h2>
        <div class="error-description">    
            <?= A::t('app', 'Common Error Description'); ?>        
            <br><br>
            <?= A::t('app', 'Common Error Troubleshooting'); ?>        
        </div>
    </div>
</div>