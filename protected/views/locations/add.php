<?php
    $this->_activeMenu = 'locations/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Locations'), 'url'=>'locations/manage'),
		array('label'=>A::t('app', 'Add New Country')),
    );    
?>

<h1><?php echo A::t('app', 'Locations Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Add New Country'); ?></div>
    <div class="content">        
	<?php
		echo CWidget::create('CDataForm', array(
			'model'=>'Countries',
			///'primaryKey'=>0,
			'operationType'=>'add',
			'action'=>'locations/add',
			'successUrl'=>'locations/manage/msg/added',
			'cancelUrl'=>'locations/manage',
			'passParameters'=>false,
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmLocationAdd',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'code'       => array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'alpha', 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
				'is_active'  => array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array()),
				'is_default' => array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array()),                    
				'sort_order' => array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
			),
			'translationInfo' => array('relation'=>array('code', 'country_code'), 'languages'=>Languages::model()->findAll('is_active = 1')),
			'translationFields' => array(
				'name' => array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>70), 'htmlOptions'=>array('title'=>'')),
			),
			'buttons'=>array(
			   'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'=>'core',
			'return'=>true,
		));		                
	?>    
    </div>
</div>
