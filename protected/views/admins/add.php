<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Add New Admin')));
	
	$this->_activeMenu = 'admins/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
		array('label'=>A::t('app', 'Admins'), 'url'=>'admins/manage'),
		array('label'=>A::t('app', 'Add New Admin')),
    );    
?>

<h1><?= A::t('app', 'Admins Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Add New Admin'); ?></div>
    <div class="content">
   
    <?= $actionMessage; ?>
    
    <?php
		echo CWidget::create('CDataForm', array(
			'model'=>'Admins',
			'operationType'=>'add',
			'action'=>'admins/add',
			'successUrl'=>'admins/manage',
			'successCallback'=>array('add'=>'sendNewAccountEmail', 'edit'=>''),
			'cancelUrl'=>'admins/manage',
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmAdminAdd',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fieldSetType'=>'frameset',
			'fields'=>array(
				'separatorPersonal' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Personal Information')),
					'first_name'   	=> array('type'=>'textbox', 'title'=>A::t('app', 'First Name'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
					'last_name'    	=> array('type'=>'textbox', 'title'=>A::t('app', 'Last Name'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
					'display_name' 	=> array('type'=>'textbox', 'title'=>A::t('app', 'Display Name'), 'validation'=>array('required'=>false, 'type'=>'mixed', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50')),
					'birth_date'   	=> array('type'=>'datetime', 'title'=>A::t('app', 'Birth Date'), 'validation'=>array('required'=>false, 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>date('Y-m-d')), 'maxDate'=>'1', 'yearRange'=>'-100:+0', 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px')),
					'language_code'	=> array('type'=>'select', 'title'=>A::t('app', 'Preferred Language'), 'data'=>$langList, 'default'=>A::app()->getLanguage(), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($langList))),
					'avatar' =>array(
						'type'          => 'imageupload',
						'title'         => A::t('app', 'Avatar'),
						'validation'    => array('required'=>false, 'type'=>'image', 'maxSize'=>'100k', 'maxWidth'=>'100px', 'maxHeight'=>'100px', 'targetPath'=>'templates/backend/images/accounts/', 'mimeType'=>'image/jpeg, image/png', 'fileName'=>'adm_'.CHash::getRandomString(10)),
						'imageOptions'  => array('showImage'=>false),
						'deleteOptions' => array('showLink'=>false),
						'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
					),
					'personal_info'	=>array('type'=>'textarea', 'title'=>A::t('app', 'Personal Information'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
				),
				'separatorContact' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Contact Information')),
					'email'			=> array('type'=>'textbox', 'title'=>A::t('app', 'Email'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'email', 'autocomplete'=>'off')),
				),
				'separatorAccount' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Account Information')),
					'role'			=> array('type'=>'select', 'title'=>A::t('app', 'Account Type'), 'data'=>$rolesList, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($rolesList))),
					'username'		=> array('type'=>'textbox', 'title'=>A::t('app', 'Username'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'username', 'maxLength'=>25, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'25')),
					'password'		=> array('type'=>'password', 'title'=>A::t('app', 'Password'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'password', 'minLength'=>6, 'maxlength'=>20, 'simplePassword'=>(APPHP_MODE == 'debug' ? true : false)), 'encryption'=>array('enabled'=>CConfig::get('password.encryption'), 'encryptAlgorithm'=>CConfig::get('password.encryptAlgorithm'), 'encryptSalt'=>$salt), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;')),
					'passwordRetype' => array('type'=>'password', 'title'=>A::t('app', 'Retype Password'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'confirm', 'confirmField'=>'password', 'minLength'=>6, 'maxlength'=>20), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;')),
					'salt'			=> array('type'=>'data', 'default'=>$salt),
					'is_active' 	=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>'1', 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
				),
			),
			'buttons'=>array(
			   'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Admin account')),
            'return'            => true,
		));
    ?>    
    </div>
</div>
