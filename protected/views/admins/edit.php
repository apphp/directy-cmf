<?php
    $this->_activeMenu = 'admins/'.($isMyAccount ? 'myAccount' : '');
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Admins'), 'url'=>'admins/manage'),
		array('label'=>($isMyAccount ? A::t('app', 'My Account') : A::t('app', 'Edit Admin'))),
    );    
?>

<h1><?php echo A::t('app', 'Admins Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo ($isMyAccount ? A::t('app', 'My Account') : A::t('app', 'Edit Admin')); ?></div>
    <div class="content">
    
    <?php echo $actionMessage; ?>

    <?php
        //$buttons['submit'] = array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>''));
        //if(!$isMyAccount){
        	//$buttons['cancel'] = array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white'));
        //}
		
		echo CWidget::create('CDataForm', array(
			'model'=>'Admins',
			'primaryKey'=>$admin->id,
			'operationType'=>'edit',
			'action'=>'admins/edit/id/'.$admin->id,
			'successUrl'=>$isMyAccount ? '' : 'admins/manage/msg/updated',
			'cancelUrl'=>'admins/manage',
			'method'=>'post',
			'passParameters'=>$isMyAccount ? false : true,
			'htmlOptions'=>array(
				'name'=>'frmAdminEdit',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fieldSetType'=>'frameset',
			'fields'=>array(
				'separatorPersonal' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Personal Information')),
					'first_name'    => array('type'=>'textbox', 'title'=>A::t('app', 'First Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
					'last_name'     => array('type'=>'textbox', 'title'=>A::t('app', 'Last Name'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
					'display_name'  => array('type'=>'textbox', 'title'=>A::t('app', 'Display Name'), 'validation'=>array('required'=>false, 'type'=>'mixed', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50')),
					'birth_date'    => array('type'=>'datetime', 'title'=>A::t('app', 'Birth Date'), 'defaultEditMode'=>'0000-00-00', 'validation'=>array('required'=>false, 'type'=>'date', 'maxLength'=>10), 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px'), 'definedValues'=>array('0000-00-00'=>'')),
					'language_code' => array('type'=>'select', 'title'=>A::t('app', 'Preferred Language'), 'data'=>$langList, 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($langList))),
					
					'avatar' =>array(
						'type'          => 'imageupload',
						'title'         => A::t('app', 'Avatar'),
						'validation'    => array('required'=>false, 'type'=>'image', 'maxSize'=>'100k', 'maxWidth'=>'100px', 'maxHeight'=>'100px', 'targetPath'=>'templates/backend/images/accounts/', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>'adm_'.CHash::getRandomString(10)),
						'imageOptions'  => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'imagePath'=>'templates/backend/images/accounts/', 'imageClass'=>'avatar'),
						'deleteOptions' => array('showLink'=>true, 'linkUrl'=>'admins/edit/id/'.$admin->id.'/avatar/delete', 'linkText'=>A::t('app', 'Delete')),
						'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
					),
				),
				'separatorContact' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Contact Information')),
					'email'			=>array('type'=>'textbox', 'title'=>A::t('app', 'Email'), 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'email', 'autocomplete'=>'off')),
				),
				'separatorAccount' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Account Information')),
					'role'		=> array('type'=>'select', 'title'=>A::t('app', 'Account Type'), 'data'=>($isMyAccount ? $allRolesList : $rolesList), 'mandatoryStar'=>true, 'htmlOptions'=>($isMyAccount ? array('disabled'=>'disabled') : array()), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>($isMyAccount ? array('owner') : array_keys($rolesList)))),
					'username'	=> array('type'=>'label', 'title'=>A::t('app', 'Username'), 'tooltip'=>'', 'htmlOptions'=>array()),
					'password'	=> array('type'=>'password', 'title'=>A::t('app', 'Password'), 'validation'=>array('required'=>false, 'type'=>'password', 'minLength'=>6, 'maxlength'=>20), 'encryption'=>array('enabled'=>CConfig::get('password.encryption'), 'encryptAlgorithm'=>CConfig::get('password.encryptAlgorithm'), 'hashKey'=>CConfig::get('password.hashKey')), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;')),
					///'passwordRetype' => array('type'=>'password', 'title'=>A::t('app', 'Retype Password'), 'validation'=>array('required'=>false, 'type'=>'confirm', 'confirmField'=>'password', 'minLength'=>6, 'maxlength'=>20), 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;')),
					'is_active' => array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($isMyAccount ? array('disabled'=>'disabled', 'uncheckValue'=>1) : array())),
				),
				'separatorOther' =>array(
					'separatorInfo' => array('legend'=>A::t('app', 'Other')),
					'created_at'	  => array('type'=>'label', 'title'=>A::t('app', 'Time Created'), 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('app', 'Unknown')), 'format'=>$dateTimeFormat),
					'updated_at'	  => array('type'=>'label', 'title'=>A::t('app', 'Last Changed'), 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('app', 'Never')), 'format'=>$dateTimeFormat),
					'last_visited_at' => array('type'=>'label', 'title'=>A::t('app', 'Last Visit'), 'definedValues'=>array('0000-00-00 00:00:00'=>A::t('app', 'Never')), 'format'=>$dateTimeFormat),
	            ),
			),
			'buttons'=>array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose'), 'disabled'=>($isMyAccount ? true : false)),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white'), 'disabled'=>($isMyAccount ? true : false)),
			),
			'messagesSource'=>'core',
			'return'=>true,
		));
    ?>

    </div>
</div>
