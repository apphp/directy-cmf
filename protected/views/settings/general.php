<?php
    Website::setMetaTags(array('title'=>A::t('app', 'General Settings')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
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
			'action'=>'settings/general',
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
	        	'separatorSearch' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'Search Settings')),
                    'searchItemsPerPage' 	=> array('type'=>'select', 'value'=>$settings->search_items_per_page, 'title'=>A::t('app', 'Search Results Page Size'), 'tooltip'=>A::t('app', 'Items Count On Search Result Page Tooltip'), 'data'=>$searchItemsPerPage, 'htmlOptions'=>array()),
                    'searchIsHighlighted' 	=> array('type'=>'checkbox', 'viewType'=>'custom', 'title'=>A::t('app', 'Highlight Results'), 'tooltip'=>A::t('app', 'Highlight Search Results Tooltip'), 'mandatoryStar'=>false, 'value'=>$settings->search_is_highlighted, 'checked'=>($settings->search_is_highlighted ? true : false), 'htmlOptions'=>array()),
                ),
	        	'separatorCache' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'Cache Settings')),
                    'cacheAllowed' 			=> array('type'=>'label', 'title'=>A::t('app', 'Enabled'), 'tooltip'=>A::t('app', 'Cache Tooltip'), 'value'=>$cacheEnable, 'definedValues'=>array(''=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'format'=>''),
					'cacheType' 			=> array('type'=>'label', 'title'=>A::t('app', 'Cache Type'), 'tooltip'=>A::t('app', 'Cache Type Tooltip'), 'value'=>$cacheType, 'definedValues'=>array('auto'=>A::t('app', 'Auto'), 'manual'=>A::t('app', 'Manual')), 'format'=>''),
                    'cacheLifetime' 		=> array('type'=>'label', 'title'=>A::t('app', 'Cache Lifetime'), 'tooltip'=>A::t('app', 'Cache Lifetime Tooltip'), 'value'=>$cacheLifetime.' '.A::t('app', 'minute/s'), 'definedValues'=>array(), 'format'=>''),
                    'cachePath' 			=> array('type'=>'label', 'title'=>A::t('app', 'Cache Path'), 'tooltip'=>A::t('app', 'Cache Path Tooltip'), 'value'=>$cachePath, 'definedValues'=>array(), 'format'=>''),
                    'cacheDeleteLink' =>
                        !CFile::isDirectoryEmpty('protected/tmp/cache/') ? 
                        array('type'=>'link', 'title'=>A::t('app', 'Clear Cache'), 'tooltip'=>A::t('app', 'Clear Cache Tooltip'), 'linkUrl'=>'settings/general/task/clearCache', 'linkText'=>A::t('app', 'Clear Cache'), 'htmlOptions'=>array('class'=>'settings-link', 'onclick'=>'javascript:return clearCacheAlert()'), 'prependCode'=>'[ ', 'appendCode'=>' ]') :
                        array('type'=>'label', 'title'=>A::t('app', 'Clear Cache'), 'tooltip'=>A::t('app', 'Clear Cache Tooltip'), 'value'=>A::t('app', 'No cache found'), 'definedValues'=>array(), 'format'=>''),
	            ),
			),
			'buttons' =>
                Admins::hasPrivilege('site_settings', 'edit') ? 
				array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')), 'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','settings/general');"))) :
                array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	?>
	</div>
</div>   

<?php
	A::app()->getClientScript()->registerScript(
		'cache-alert',
		'function clearCacheAlert(){
			if(!confirm("'.CHtml::encode(A::t('app', 'Are you sure you want to clear cache?')).'")) return false;
            return true;
		};',
		0
	);
?>
	
