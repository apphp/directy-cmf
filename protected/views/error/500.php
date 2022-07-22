<?php
    $this->_pageTitle = A::t('app', '500 Error');   
?>

<?php if(A::app()->view->getTemplate() != 'backend'){ ?>
<h1 class="title"><?php echo A::t('app', '500 Error'); ?></h1>
<?php } ?>

<div class="block-body">                       
    <div id="error-page">
        <h2 class="error-title"><?php echo A::t('app', '500 Error Title'); ?></h2>
        <div class="error-description">    
            <?php echo A::t('app', '500 Error Description'); ?>
            <br><br>
            <?php echo A::t('app', '500 Error Troubleshooting'); ?>        
        </div>
    </div>    
</div>