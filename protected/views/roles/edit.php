<?php
    $this->_activeMenu = 'roles/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Roles & Privileges'), 'url'=>'roles/manage'),
		array('label'=>A::t('app', 'Edit Role')),
    );    
?>

<h1><?php echo A::t('app', 'Roles Management'); ?></h1>

<div class="bloc">
	<div class="title"><?php echo A::t('app', 'Edit Role')?></div>
    <div class="content">    
		<?php
			echo CWidget::create('CDataForm', array(
				'model'=>'Roles',
				'operationType'=>'edit',
				'primaryKey'=>$id,
				'action'=>'roles/edit/id/'.$id,
				'successUrl'=>'roles/manage/msg/updated',
				'cancelUrl'=>'roles/manage',
				'requiredFieldsAlert'=>true,
				'return'=>true,
				'htmlOptions'=>array(
					'name'=>'frmRoleEdit',
					'autoGenerateId'=>true
				),
				'fields'=>array(
					'id'         => array('type'=>'hidden'),
					'name'       => array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any'), 'htmlOptions'=>array('maxlength'=>'50')),
					'description'=> array('type'=>'textarea', 'title'=>A::t('app', 'Description'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('maxlength'=>'255')),
				),
				'buttons' => array(
				   'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'')),
				   'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
				),                
				'events'=>array(
					'focus'=>array('field'=>$errorField)
				),
			));				
		?>        	
    </div>
</div>
