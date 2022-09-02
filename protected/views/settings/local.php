<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Local Settings')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Local Settings'))
    );    
?>
    
<h1><?= A::t('app', 'Local Settings'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;
        
		$daylightSavingChecked = ($settings->daylight_saving == '1' ? true : false);
		
		echo CWidget::create('CFormView', array(
			'action'=>'settings/local',
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmLocalSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'act' =>array('type'=>'hidden', 'value'=>'send'),
	          	'separatorDateTimeFormats' =>array(
					'separatorInfo'  => array('legend'=>'Date & Time Formats'),
					'timeFormat'	 => array('type'=>'textbox', 'value'=>$settings->time_format, 'title'=>A::t('app', 'Time Format'), 'tooltip'=>A::t('app', 'Time Format Tooltip'), 'mandatoryStar'=>false, 'prependCode'=>$timeFormatsList, 'appendCode'=>' &nbsp;'.$timeFormatSample, 'htmlOptions'=>array('maxLength'=>'5', 'style'=>'width:60px')),
					'dateFormat'	 => array('type'=>'textbox', 'value'=>$settings->date_format, 'title'=>A::t('app', 'Date Format'), 'tooltip'=>A::t('app', 'Date Format Tooltip'), 'mandatoryStar'=>false, 'prependCode'=>$dateFormatsList, 'appendCode'=>' &nbsp;'.$dateFormatSample, 'htmlOptions'=>array('maxLength'=>'10', 'style'=>'width:90px')),
					'dateTimeFormat' => array('type'=>'textbox', 'value'=>$settings->datetime_format, 'title'=>A::t('app', 'Date Time Format'), 'tooltip'=>A::t('app', 'Date Time Format Tooltip'), 'mandatoryStar'=>false, 'prependCode'=>$dateTimeFormatsList, 'appendCode'=>' &nbsp;'.$dateTimeFormatSample, 'htmlOptions'=>array('maxLength'=>'16', 'style'=>'width:120px')),
					'weekStartDay'	 => array('type'=>'select', 'value'=>$settings->week_startday, 'title'=>A::t('app', 'First Day of Week'), 'tooltip'=>A::t('app', 'First Day of Week Tooltip'), 'data'=>$weekdaysList, 'htmlOptions'=>array()),
					'timeZone'		 => array('type'=>'select', 'value'=>$settings->time_zone, 'title'=>A::t('app', 'Time Zone'), 'tooltip'=>A::t('app', 'Time Zone Tooltip'), 'data'=>$timeZonesList, 'appendCode'=>' &nbsp;&nbsp;'.$utcTime, 'htmlOptions'=>array()),
					'daylightSaving' => array('type'=>'checkbox', 'viewType'=>'custom', 'value'=>$settings->daylight_saving, 'title'=>A::t('app', 'Observe Daylight Saving'), 'tooltip'=>A::t('app', 'Daylight Saving Tooltip'), 'checked'=>$daylightSavingChecked, 'htmlOptions'=>array()),
				),
	          	'separatorNumberFormats' =>array(
	                'separatorInfo'  => array('legend'=>'Number Formats'),
					'numberFormat'	 => array('type'=>'select', 'value'=>$settings->number_format, 'title'=>A::t('app', 'Number Format'), 'tooltip'=>A::t('app', 'Number Format Tooltip'), 'data'=>$numberFormatsList, 'htmlOptions'=>array()),
				)
			),
			'buttons' => Admins::hasPrivilege('site_settings', 'edit') ? 
				array(
					'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Update')),
					'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','settings/local');")))
				: array(),
			'events' => array(
				'focus'	=> array('field'=>$errorField)
			),
			'return' => true,
		));
	?>
	</div>
</div>   
