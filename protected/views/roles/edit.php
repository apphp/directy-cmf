<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Role')));
	
	$this->_activeMenu = 'roles/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Roles & Privileges'), 'url'=>'roles/manage'),
		array('label'=>A::t('app', 'Edit Role')),
    );    
?>

<h1><?= A::t('app', 'Roles Management'); ?></h1>

<div class="bloc">
	<div class="title"><?= A::t('app', 'Edit Role')?></div>
    <div class="content">    
    <?php
        echo CWidget::create('CDataForm', array(
            'model'				=> 'Roles',
            'operationType'		=> 'edit',
            'primaryKey'		=> $id,
            'action'			=> 'roles/edit/id/'.$id,
            'successUrl'		=> 'roles/manage',
            'cancelUrl'			=> 'roles/manage',
            'requiredFieldsAlert' => true,
            'return'			=> true,
            'htmlOptions'		=> array(
                'name'				=> 'frmRoleEdit',
                'autoGenerateId'	=> true
            ),
            'fields'			=> array(
                'code' 		  		=> array('type'=>'label', 'title'=>A::t('app', 'Code')),
                'name'        		=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'50')),
                'description' 		=> array('type'=>'textarea', 'title'=>A::t('app', 'Description'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('maxlength'=>'255')),
            ),
            'buttons' 			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
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
