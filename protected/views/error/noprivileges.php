<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Error No Privileges')));
	$this->_pageTitle = A::t('app', 'Error No Privileges');
?>

<?php if(A::app()->view->getTemplate() != 'backend'){ ?>
	<h1 class="title title-error"><?= A::t('app', 'Error No Privileges'); ?></h1>
<?php } ?>

<div class="block-body">                       
    <div id="error-page">
        <h2 class="error-title"><?= A::t('app', 'Error No Privileges Title'); ?></h2>
        <div class="error-description">    
            <?= A::t('app', 'Error No Privileges Description'); ?>
            <br><br>
            <?= A::t('app', 'Error No Privileges Troubleshooting'); ?>        
        </div>
    </div>    
</div>

