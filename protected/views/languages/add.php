<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Add New Language')));
	
	$this->_activeMenu = 'languages/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Language Settings'), 'url'=>'languages/'),
        array('label'=>A::t('app', 'Languages'), 'url'=>'languages/manage'),
		array('label'=>A::t('app', 'Add New Language')),
    );    
?>

<h1><?= A::t('app', 'Languages Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Add New Language'); ?></div>
    <div class="content">        
    <?php
		echo CWidget::create('CDataForm', array(
			'model'				=> 'Languages',
			///'primaryKey'		=> 0,
			'operationType'		=> 'add',
			'action'			=> 'languages/add/',
			'successUrl'		=> 'languages/manage',
			'cancelUrl'			=> 'languages/manage',
			'passParameters'	=> false,
			'method'			=> 'post',
			'htmlOptions'		=> array(
				'name'				=> 'frmLanguageAdd',
				'enctype'			=> 'multipart/form-data',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert' => true,
			'fields'			=> array(
				'name_native'		=> array('type'=>'textbox', 'title'=>A::t('app', 'Language Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32', 'autofocus'=>true)),
				'name'		 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Language Name (English)'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'32')),
				'code'		 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'validation'=>array('required'=>true, 'type'=>'alpha', 'minLength'=>2, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
				'lc_time_name' 		=> array('type'=>'select', 'title'=>A::t('app', 'Server Locale'), 'default'=>'en_US', 'data'=>$localesList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($localesList))),
				'direction'  		=> array('type'=>'select', 'title'=>A::t('app', 'Text Direction'), 'default'=>'ltr', 'data'=>$directionsList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($directionsList))),
				'icon' 			=> array(
					'type'        	=> 'imageupload',
					'title'		  	=> A::t('app', 'Icon Image Label'),
					'validation'  	=> array('required'=>false, 'type'=>'image', 'maxSize'=>'100k', 'targetPath'=>'images/flags/', 'mimeType'=>'image/jpeg, image/png, image/gif, image/jpg', 'fileName'=>''),
					'fileOptions' 	=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
				),					
				'sort_order' 	=> array('type'=>'textbox', 'title'=>A::t('app', 'Order'), 'default'=>$sortOrder, 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
				'used_on' 	 	=> array('type'=>'select', 'title'=>A::t('app', 'Used On'), 'default'=>'global', 'data'=>$usedOnList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($usedOnList))),
				'is_default' 	=> array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
				'is_active'  	=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
			),
			'buttons'			=> array(
			   'submit' 			=> array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Language')),
            'return'            => true,
		));		                
    ?>    
    </div>
</div>
