<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Admin')));
	
	$this->_activeMenu = 'admins/'.($isMyAccount ? 'myAccount' : '');
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Admins'), 'url'=>'admins/manage'),
		array('label'=>($isMyAccount ? A::t('app', 'My Account') : A::t('app', 'Edit Admin'))),
    );    
?>

<h1><?= A::t('app', 'Admins Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= ($isMyAccount ? A::t('app', 'My Account') : A::t('app', 'Edit Admin')); ?></div>
    <div class="content">
    
    <?= $actionMessage; ?>

    <?php
		echo CWidget::create('CDataForm', array(
			'model'				=> 'Admins',
			'primaryKey'		=> $admin->id,
			'operationType'		=> 'edit',
			'action'			=> 'admins/edit/id/'.$admin->id,
			'successUrl'		=> $isMyAccount ? '' : 'admins/manage',
			'cancelUrl'			=> 'admins/manage',
			'method'			=> 'post',
			'passParameters'	=> $isMyAccount ? false : true,
			'htmlOptions'		=> array(
				'name'				=> 'frmAdminEdit',
				'enctype'			=> 'multipart/form-data',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert' => true,
			'fieldSets'			=> array('type'=>'frameset'),
			'fields'			=> array(
				'separatorPersonal' => array(
					'separatorInfo' 	=> array('legend'=>A::t('app', 'Personal Information')),
					'first_name'    	=> array('type'=>'textbox', 'title'=>A::t('app', 'First Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
					'last_name'     	=> array('type'=>'textbox', 'title'=>A::t('app', 'Last Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
					'display_name'  	=> array('type'=>'textbox', 'title'=>A::t('app', 'Display Name'), 'validation'=>array('required'=>false, 'type'=>'mixed', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50')),
					'birth_date'    	=> array('type'=>'datetime', 'title'=>A::t('app', 'Birth Date'), 'defaultEditMode'=>null, 'validation'=>array('required'=>false, 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>date('Y-m-d')), 'maxDate'=>'1', 'yearRange'=>'-100:+0', 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px')),
					'language_code' 	=> array('type'=>'select', 'title'=>A::t('app', 'Preferred Language'), 'data'=>$langList, 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($langList))),
					'avatar' 		=> array(
						'type'          => 'imageupload',
						'title'         => A::t('app', 'Avatar'),
						'validation'    => array('required'=>false, 'type'=>'image', 'maxSize'=>'150k', 'maxWidth'=>'200px', 'maxHeight'=>'200px', 'targetPath'=>'templates/backend/images/accounts/', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>'adm_'.CHash::getRandomString(10)),
						'imageOptions'  => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'showImageDimensions'=>true, 'imageClass'=>'avatar'),
						'deleteOptions' => array('showLink'=>true, 'linkUrl'=>'admins/edit/id/'.$admin->id.'/avatar/delete', 'linkText'=>A::t('app', 'Delete')),
						'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'templates/backend/images/accounts/')
					),
					'personal_info'	=> array('type'=>'textarea', 'title'=>A::t('app', 'Personal Information'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
				),
				'separatorContact' 	=> array(
					'separatorInfo' 	=> array('legend'=>A::t('app', 'Contact Information')),
					'email'				=> array('type'=>'textbox', 'title'=>A::t('app', 'Email'), 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'email', 'autocomplete'=>'off')),
				),
				'separatorAccount' => array(
					'separatorInfo' => array('legend'=>A::t('app', 'Account Information')),
					'role'			=> array('type'=>'select', 'title'=>A::t('app', 'Account Type'), 'data'=>($isMyAccount ? $allRolesList : $rolesList), 'mandatoryStar'=>true, 'htmlOptions'=>($isMyAccount ? array('disabled'=>'disabled') : array()), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>($isMyAccount ? array('owner') : array_keys($rolesList)))),
					'username'		=> array('type'=>'label', 'title'=>A::t('app', 'Username'), 'tooltip'=>'', 'htmlOptions'=>array()),
					'password'		=> array('type'=>'password', 'title'=>A::t('app', 'Password'), 'validation'=>array('required'=>false, 'type'=>'password', 'minLength'=>6, 'maxlength'=>20, 'simplePassword'=>(APPHP_MODE == 'debug' ? true : false)), 'encryption'=>array('enabled'=>CConfig::get('password.encryption'), 'encryptAlgorithm'=>CConfig::get('password.encryptAlgorithm'), 'encryptSalt'=>$salt), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;')),
					'salt'			=> array('type'=>'data', 'default'=>$salt, 'disabled'=>$saltDisabled),
					///'passwordRetype' => array('type'=>'password', 'title'=>A::t('app', 'Retype Password'), 'validation'=>array('required'=>false, 'type'=>'confirm', 'confirmField'=>'password', 'minLength'=>6, 'maxlength'=>20), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;')),
					'is_active' 	=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($isMyAccount ? array('disabled'=>'disabled', 'uncheckValue'=>1) : array()), 'viewType'=>'custom'),
				),
				'separatorOther' 	=> array(
					'separatorInfo' 	=> array('legend'=>A::t('app', 'Other')),
					'created_at'	  	=> array('type'=>'label', 'title'=>A::t('app', 'Date Created'), 'definedValues'=>array(null=>A::t('app', 'Unknown')), 'format'=>$dateTimeFormat),
					'updated_at'	  	=> array('type'=>'label', 'title'=>A::t('app', 'Last Changed'), 'definedValues'=>array(null=>A::t('app', 'Never')), 'format'=>$dateTimeFormat),
					'password_changed_at' => array('type'=>'label', 'title'=>A::t('app', 'Last Password Changed'), 'definedValues'=>array(null=>A::t('app', 'Never')), 'format'=>$dateTimeFormat),
					'last_visited_at' 	=> array('type'=>'label', 'title'=>A::t('app', 'Last Visit'), 'definedValues'=>array(null=>A::t('app', 'Never')), 'format'=>$dateTimeFormat),
	            ),
			),
			'buttons'	=>	array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose'), 'disabled'=>($isMyAccount ? true : false)),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white'), 'disabled'=>($isMyAccount ? true : false)),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Admin account')),
            'return'            => true,
		));
    ?>
    </div>
</div>
