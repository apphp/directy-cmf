<?php
    $this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'General Settings'))
    );    
?>
    
<h1><?php echo A::t('app', 'General Settings'); ?></h1>

<div class="bloc">

	<?php echo $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;
		
		echo CWidget::create('CFormView', array(
			'action'=>'settings/general',
			'method'=>'post',			
			'htmlOptions'=>array(
				'name'=>'frmGeneralSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'act'     	=>array('type'=>'hidden', 'value'=>'send'),
				'isOffline'	=>array('type'=>'select', 'value'=>$settings->is_offline, 'title'=>A::t('app', 'Site Offline'), 'tooltip'=>A::t('app', 'Site Offline Tooltip'), 'data'=>$noYesArray),
				'offlineMsg'=>array('type'=>'textarea', 'value'=>$settings->offline_message, 'title'=>A::t('app', 'Offline Message'), 'tooltip'=>A::t('app', 'Offline Message Tooltip'), 'htmlOptions'=>array('maxLength'=>'255')),
				'rssFeedType'=>array('type'=>'select', 'value'=>$settings->rss_feed_type, 'title'=>A::t('app', 'RSS Feed Type'), 'tooltip'=>A::t('app', 'RSS Feed Type Tooltip'), 'data'=>$rssFeedTypesList),
				'cachingAllowed'=>array('type'=>'radioButtonList', 'checked'=>$settings->caching_allowed, 'title'=>A::t('app', 'Caching'), 'tooltip'=>A::t('app', 'Caching Tooltip'), 'data'=>$yesNoArray),
			),
			'buttons'=> Admins::hasPrivilege('site_settings', 'edit') ? 
				array(
					'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')),
					'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','settings/general');")))
				: array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	?>
	</div>
</div>   

