<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Error 500')));
	$this->_pageTitle = A::t('app', 'Error 500');
?>

<?php if(A::app()->view->getTemplate() != 'backend'){ ?>
	<h1 class="title title-error"><?= A::t('app', 'Error 500'); ?></h1>
<?php } ?>

<div class="block-body">                       
	<div id="error-page">
		<h2 class="error-title"><?= A::t('app', 'Error 500 Title'); ?></h2>
		<div class="error-description">    
			<?php
				if(!empty($errorDescription)){
					echo $errorDescription;
				}else{
					echo A::t('app', 'Error 500 Description');
				}
			?>				
			<br><br>
			<?= A::t('app', 'Error 500 Troubleshooting'); ?>        
		</div>
	</div>    
</div>
