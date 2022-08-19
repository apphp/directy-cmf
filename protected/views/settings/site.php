<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Site Info')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Site Info'))
    );    
?>
    
<h1><?php echo A::t('app', 'Site Info'); ?></h1>

<div class="bloc">
	<?php echo $tabs; ?>

	<div class="content">
	<?php echo $actionMessage; ?>
	
	<fieldset>
		<legend><?php echo A::t('app', 'Domain Info'); ?>:</legend>
		<ul>
			<li><b><?php echo A::t('app', 'Site Domain'); ?>:</b> <i><?php echo $domain; ?></i></li>
		</ul>		
	</fieldset>
	<fieldset>
		<legend><?php echo A::t('app', 'Site Ranks'); ?>:</legend>
		<ul>
			<li><b><?php echo A::t('app', 'Google Rank'); ?>:</b> <i><?php echo ($settings->google_rank == '' ? A::t('app', 'Unknown') : $settings->google_rank); ?></i></li>
			<li><b><?php echo A::t('app', 'Alexa Rank'); ?>:</b> <i><?php echo ($settings->alexa_rank == '' ? A::t('app', 'Unknown') : $settings->alexa_rank); ?></i></li>
		</ul>		
	</fieldset>
	
		
	<?php
		echo CWidget::create('CFormView', array(
			'action'=>'settings/site',
			'method'=>'post',
			'fields'=>array(
				'act' => array('type'=>'hidden', 'value'=>'send'),
			),
			'buttons'=>array(
				'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'disabled'=>!Admins::hasPrivilege('site_settings', 'edit')),
			),
		));
	?>
		
	</div>
</div>   

