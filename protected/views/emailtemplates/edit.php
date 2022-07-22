<?php
    $this->_activeMenu = 'emailTemplates/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Email Templates'), 'url'=>'emailTemplates/manage'),
		array('label'=>A::t('app', 'Edit Template')),
    );    
?>

<h1><?php echo A::t('app', 'Email Templates Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Edit Template'); ?></div>
    <div class="content">        
	<?php
		echo CWidget::create('CDataForm', array(
			'model'=>'EmailTemplates',
            'primaryKey'=>$template->id,
			'operationType'=>'edit',
			'action'=>'emailTemplates/edit/id/'.$template->id,
			'successUrl'=>'emailTemplates/manage/msg/updated',
			'cancelUrl'=>'emailTemplates/manage',
			'passParameters'=>false,
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmEmailTemplatesEdit',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
            //'fieldSetType'=>'tabs',
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'code'        => array('type'=>'label', 'title'=>A::t('app', 'Code'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>'', 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
				'module_code' => array('type'=>'label', 'title'=>A::t('app', 'Module'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>'', 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
				'is_system'   => array('type'=>'label',  'title'=>A::t('app', 'System Template'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('0'=>A::t('app', 'No'), '1'=>A::t('app', 'Yes')), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
			),
			'translationInfo' => array('relation'=>array('code', 'template_code'), 'languages'=>Languages::model()->findAll('is_active = 1')),
			'translationFields' => array(
                'template_name' => array('type'=>'textbox', 'title'=>A::t('app', 'Template Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>125), 'htmlOptions'=>array('maxLength'=>'125', 'class'=>'middle')),
                'template_subject' => array('type'=>'textbox', 'title'=>A::t('app', 'Template Subject'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('maxLength'=>'125', 'class'=>'large')),
                'template_content' => array('type'=>'textarea', 'title'=>A::t('app', 'Template Content'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>5000), 'htmlOptions'=>array('maxLength'=>'5000', 'class'=>'full')),
			),
			'buttons'=>array(
                'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'=>'core',
			'return'=>true,
		));		                
	?>
    <br>
    
    <fieldset>
    <legend><?php echo A::t('app', 'Email Variables'); ?></legend>
    <div class="fieldset-content">
        <p><?php echo A::t('app', 'Email Variables Notice'); ?></p>
        <b><?php echo A::t('app', 'Base Variables'); ?>:</b>
        <ul>
            <li>{SITE_URL} - <?php echo A::t('app', 'website base URL'); ?></li>
            <li>{WEB_SITE} - <?php echo A::t('app', 'website name'); ?></li>
            <li>{YEAR} - <?php echo A::t('app', 'current year in YYYY format'); ?></li>
        </ul>
    </div>
    </fieldset>

    </div>
</div>
