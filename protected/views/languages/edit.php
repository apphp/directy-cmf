<?php
    $this->_activeMenu = 'languages/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Languages Settings'), 'url'=>'languages/'),
        array('label'=>A::t('app', 'Languages'), 'url'=>'languages/manage'),
		array('label'=>A::t('app', 'Edit Language')),
    );    
?>

<h1><?php echo A::t('app', 'Languages Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Edit Language'); ?></div>
    <div class="content">
    <?php
		echo CWidget::create('CDataForm', array(
			'model'=>'Languages',
			'primaryKey'=>$language->id,
			'operationType'=>'edit',
			'action'=>'languages/edit/id/'.$language->id,
			'successUrl'=>'languages/manage/msg/updated',
			'cancelUrl'=>'languages/manage',
			'passParameters'=>false,
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmLanguageEdit',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fieldSetType'=>'frameset',
			'fields'=>array(
				'name_native'=>array('type'=>'textbox', 'title'=>A::t('app', 'Language Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
				'name'		=>array('type'=>'textbox', 'title'=>A::t('app', 'Language Name (English)'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'32')),
				'code'		=>array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'validation'=>array('required'=>true, 'type'=>'alpha', 'minLength'=>2, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small', 'readonly'=>true)),
				'lc_time_name' =>array('type'=>'select', 'title'=>A::t('app', 'Server Locale'), 'data'=>$localesList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($localesList))),
				'direction' =>array('type'=>'select', 'title'=>A::t('app', 'Text Direction'), 'data'=>$directionsList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($directionsList))),
				'icon' =>array(
					'type'          => 'imageupload',
					'title'         => A::t('app', 'Icon Image Label'),
					'validation'    => array('required'=>false, 'type'=>'image', 'maxSize'=>'100k', 'targetPath'=>'images/flags/', 'mimeType'=>'image/jpeg, image/png, image/gif, image/jpg', 'fileName'=>''),
					'imageOptions'  => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imagePath'=>'images/flags/', 'imageClass'=>'icon'),
					'deleteOptions' => array('showLink'=>true, 'linkUrl'=>'languages/edit/id/'.$language->id.'/icon/delete', 'linkText'=>A::t('app', 'Delete')),
					'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
				),
				'sort_order' =>array('type'=>'textbox', 'title'=>A::t('app', 'Order'), 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
				'used_on' 	 =>array('type'=>'select', 'title'=>A::t('app', 'Used On'), 'data'=>$usedOnList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($usedOnList))),
				'is_default' =>array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($language->is_default ? array('disabled'=>'disabled', 'uncheckValue'=>$language->is_default) : '')),
				'is_active'  =>array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($language->is_default ? array('disabled'=>'disabled', 'uncheckValue'=>$language->is_active) : '')),
			),
			'buttons'=>array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'=>'core',
			'showAllErrors'=>false,
			'return'=>true,
		));		                
	?>    
    </div>
</div>
