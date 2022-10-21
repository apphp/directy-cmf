<?php
    Website::setMetaTags(array('title'=>A::t('app', 'General Settings')));
	
	$this->_activeMenu = $backendPath.'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>$backendPath.'settings/general'),
		array('label'=>A::t('app', 'General Settings'))
    );
    
    A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
?>
    
<h1><?= A::t('app', 'General Settings'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;
        
		echo CWidget::create('CFormView', array(
			'action'=>$backendPath.'settings/general',
			'method'=>'post',			
			'htmlOptions'=>array(
				'name'=>'frmGeneralSettings',
                'id'=>'frmGeneralSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=> array(
				'act' => array('type'=>'hidden', 'value'=>'send'),
	        	'separatorDashboard' =>array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'Dashboard Settings')),
                    'dashboardHotkeys'		=> array('type'=>'checkbox', 'viewType'=>'custom', 'title'=>A::t('app', 'Dashboard (hotkeys)'), 'tooltip'=>A::t('app', 'Dashboard (hotkeys) Tooltip'), 'mandatoryStar'=>false, 'value'=>'1', 'checked'=>($settings->dashboard_hotkeys ? true : false), 'htmlOptions'=>array()),
                    'dashboardNotifications'=> array('type'=>'checkbox', 'viewType'=>'custom', 'title'=>A::t('app', 'Dashboard Notifications'), 'tooltip'=>A::t('app', 'Dashboard Notifications Tooltip'), 'mandatoryStar'=>false, 'value'=>'1', 'checked'=>($settings->dashboard_notifications ? true : false), 'htmlOptions'=>array()),
                    'dashboardStatistics'	=> array('type'=>'checkbox', 'viewType'=>'custom', 'title'=>A::t('app', 'Dashboard Statistics'), 'tooltip'=>A::t('app', 'Dashboard Statistics Tooltip'), 'mandatoryStar'=>false, 'value'=>'1', 'checked'=>($settings->dashboard_statistics ? true : false), 'htmlOptions'=>array()),
                ),
	        	'separatorOffline' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'Offline Settings')),
                    'isOffline' 			=> array('type'=>'checkbox', 'viewType'=>'custom', 'title'=>A::t('app', 'Site Offline'), 'tooltip'=>A::t('app', 'Site Offline Tooltip'), 'mandatoryStar'=>false, 'value'=>$settings->is_offline, 'checked'=>($settings->is_offline ? true : false), 'htmlOptions'=>array()),
                    'offlineMsg' 			=> array('type'=>'textarea', 'value'=>$settings->offline_message, 'title'=>A::t('app', 'Offline Message'), 'tooltip'=>A::t('app', 'Offline Message Tooltip'), 'htmlOptions'=>array('maxLength'=>'255')),
                ),
	        	'separatorSsl' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'SSL Settings')),
                    'sslMode' 				=> array('type'=>'select', 'value'=>$settings->ssl_mode, 'title'=>A::t('app', 'Force SSL Mode'), 'tooltip'=>A::t('app', 'Force SSL Mode Tooltip'), 'data'=>$sslModesList, 'htmlOptions'=>array()), 
                ),
	        	'separatorRss' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'RSS Settings')),
                    'rssFeedType' 			=> array('type'=>'select', 'value'=>$settings->rss_feed_type, 'title'=>A::t('app', 'RSS Feed Type'), 'tooltip'=>A::t('app', 'RSS Feed Type Tooltip'), 'data'=>$rssFeedTypesList, 'htmlOptions'=>array()),
					'rssItemsPerFeed' 		=> array('type'=>'select', 'value'=>$settings->rss_items_per_feed, 'title'=>A::t('app', 'RSS Feed Items'), 'tooltip'=>A::t('app', 'RSS Feed Items Default Number Tooltip'), 'data'=>$rssItemsPerFeed, 'htmlOptions'=>array()),
                    'rssFeadPath' 			=> array('type'=>'label', 'title'=>A::t('app', 'RSS Fead Path'), 'tooltip'=>A::t('app', 'RSS Feed Path Tooltip'), 'value'=>$rssFeedPath, 'definedValues'=>array(), 'format'=>''),
                ),
			),
			'buttons' =>
                Admins::hasPrivilege('site_settings', 'edit') ? 
				array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')), 'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','".$backendPath."settings/general');"))) :
                array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	?>
	</div>
</div>   

