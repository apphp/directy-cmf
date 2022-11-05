<?php
    Website::setMetaTags(array('title'=>A::t('users', 'Add User Group')));
	
    $this->_activeMenu = 'users/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('users', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('users', 'Users'), 'url'=>$backendPath.'modules/settings/code/users'),
        array('label'=>A::t('users', 'User Groups Management')),
        array('label'=>A::t('users', 'Add User Group')),
    );
?>

<h1><?= A::t('users', 'Users Management')?></h1> 

<div class="bloc">
    <?= $tabs; ?>
        
    <div class="sub-title"><?= A::t('users', 'Add User Group'); ?></div>
    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
            'model'         => 'Modules\Users\Models\UserGroups',
            'operationType' => 'add',
            'action'        => 'userGroups/add/',
            'successUrl'    => 'userGroups/manage',
            'cancelUrl'     => 'userGroups/manage',
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmUserGroupAdd',                
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'name'        => array('type'=>'textbox', 'title'=>A::t('users', 'Group Name'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50')),
                'description' => array('type'=>'textarea', 'title'=>A::t('users', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
				'is_default'  => array('type'=>'checkbox', 'title'=>A::t('users', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
            ),
            'buttons'=>array(
                'submit' => array('type'=>'submit', 'value'=>A::t('users', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel' => array('type'=>'button', 'value'=>A::t('users', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource' 	=> 'core',
			'showAllErrors'     => false,
			'alerts'            => array('type'=>'flash', 'itemName'=>A::t('users', 'User Group')),
			'return'            => true,
        ));
    ?>
    </div>
</div>
