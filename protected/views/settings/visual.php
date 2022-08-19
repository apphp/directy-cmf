<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Visual Settings')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Visual Settings'))
    );    
?>
    
<h1><?php echo A::t('app', 'Visual Settings'); ?></h1>

<div class="bloc">

	<?php echo $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;
		
		$languageEvent = (Admins::hasPrivilege('site_settings', 'edit')) ? '$(this).closest("form").find("input[name=act]").val("changeLang");$(this).closest("form").attr("action","settings/visual");$(this).closest("form").submit();' : false;

		echo CWidget::create('CFormView', array(
			'action'=>'settings/visual',
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmVisualSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'act'     	=>array('type'=>'hidden', 'value'=>'send'),
				'siteDescId'=>array('type'=>'hidden', 'value'=>$siteInfo->id),
				'selectedLanguage' =>array('type'=>'select', 'value'=>$selectedLanguage, 'title'=>A::t('app', 'Language'), 'tooltip'=>A::t('app', 'Language Tooltip'), 'data'=>$langList, 'htmlOptions'=>array('submit'=>$languageEvent)),
				'separatorHeadersFooters' => array(
					'separatorInfo' => array('legend'=>A::t('app', 'Headers & Footers')),
					'siteHeader'	=> array('type'=>'textbox', 'value'=>$siteInfo->header, 'title'=>A::t('app', 'Header Text'), 'tooltip'=>A::t('app', 'Header Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'text_header')),
					'slogan'		=> array('type'=>'textarea', 'value'=>$siteInfo->slogan, 'title'=>A::t('app', 'Slogan'), 'tooltip'=>A::t('app', 'Slogan Tooltip'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxlength'=>'250', 'class'=>'small')),
					'footer'		=> array('type'=>'textarea', 'value'=>$siteInfo->footer, 'title'=>A::t('app', 'Footer Text'), 'tooltip'=>A::t('app', 'Footer Tooltip'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxlength'=>'250', 'class'=>'middle')),
				),
				'separatorMetaTags' => array(
					'separatorInfo' 	=> array('legend'=>A::t('app', 'META Tags')),
					'metaTagTitle'		=> array('type'=>'textarea', 'value'=>$siteInfo->meta_title, 'title'=>CHtml::encode(A::t('app', 'Tag TITLE')), 'tooltip'=>A::t('app', 'Tag TITLE Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'250', 'class'=>'small')),
					'metaTagKeywords'	=> array('type'=>'textarea', 'value'=>$siteInfo->meta_keywords, 'title'=>CHtml::encode(A::t('app', 'Meta Tag KEYWORDS')), 'tooltip'=>A::t('app', 'Meta Tag KEYWORDS Tooltip'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxlength'=>'250', 'class'=>'middle')),
					'metaTagDescription'=> array('type'=>'textarea', 'value'=>$siteInfo->meta_description, 'title'=>CHtml::encode(A::t('app', 'Meta Tag DESCRIPTION')), 'tooltip'=>A::t('app', 'Meta Tag DESCRIPTION Tooltip'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxlength'=>'250', 'class'=>'middle')),
				),
			),
			'buttons' =>
				Admins::hasPrivilege('site_settings', 'edit') ? 
				array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')), 'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','settings/visual');"))) :
				array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
					
	?>
	</div>
</div>   

