<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Common Error')));
    $this->_pageTitle = A::t('app', 'Common Error');
?>

<?php if(A::app()->view->getTemplate() != 'backend'){ ?>
	<h3 class="title"><?php echo A::t('app', 'Common Error'); ?></h3>
<?php } ?>

<div class="block-body">                       
    <div id="error-page">
        <h2 class="error-title"><?php echo A::t('app', 'Common Error Title'); ?></h2>
        <div class="error-description">    
            <?php echo A::t('app', 'Common Error Description'); ?>        
            <br><br>
            <?php echo A::t('app', 'Common Error Troubleshooting'); ?>        
        </div>
    </div>
</div>