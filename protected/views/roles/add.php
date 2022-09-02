<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Add New Role')));
	
	$this->_activeMenu = 'roles/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Roles & Privileges'), 'url'=>'roles/manage'),
		array('label'=>A::t('app', 'Add New Role')),
    );    
?>

<h1><?= A::t('app', 'Roles Management'); ?></h1>

<div class="bloc">
	<div class="title"><?= A::t('app', 'Add New Role')?></div>
    <div class="content">    
    <?php
        echo CWidget::create('CDataForm', array(
            'model'				=> 'Roles',
            ///'primaryKey'		=> 0,
            'operationType'		=> 'add',
            'action'			=> 'roles/add',
            'successUrl'		=> 'roles/manage',
            'cancelUrl'			=> 'roles/manage',
			'passParameters'	=> false,
			'method'			=> 'post',
            'htmlOptions'		=> array(
                'name'				=> 'frmRoleEdit',
                'autoGenerateId'	=> true
            ),
            'requiredFieldsAlert' => true,
            'fields'			=> array(
                'code' 		  		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'alphanumeric', 'unique'=>true, 'maxlength'=>'20'), 'htmlOptions'=>array('maxlength'=>'20')),
                'name' 		  		=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'50')),
                'description' 		=> array('type'=>'textarea', 'title'=>A::t('app', 'Description'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('maxlength'=>'255')),
            ),
            'buttons' 			=> array(
				'submit' 			=> array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
				'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),                
            'events' 			=> array(
                'focus' 			=> array('field'=>$errorField)
            ),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Role')),
            'return'            => true,
        ));				
    ?>        	
    </div>
</div>
