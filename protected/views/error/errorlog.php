<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Errors Log')));
	
	$this->_activeMenu = 'backend/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Dashboard'), 'url'=>'backend/'),
		array('label'=>A::t('app', 'Errors Log')),
    );    
?>

<h1><?= A::t('app', 'Errors Log'); ?></h1>

<div class="bloc">
    <div class="title">
		<?= A::t('app', 'Errors Log Content'); ?>
	</div>
    <div class="content">
		
		<? if(!empty($fileContent)): ?>
			<a href="error/deleteAll" class="delete-all"><?= A::t('app', 'Delete All'); ?></a>
			<br><br>
		<? endif; ?>
		
		<?= $actionMessage; ?>
		
		<?= nl2br($fileContent); ?>
    </div>
</div>

<?php
	A::app()->getClientScript()->registerScript(
		'delete-all-errors',
		'jQuery(".delete-all").click(function(){
			return confirm("'.A::te('app', 'Are you sure you want to clear error log file?').'");
		});'
		,
		2
	);				
?>	