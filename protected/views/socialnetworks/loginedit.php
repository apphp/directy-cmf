<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Social Login')));

    $this->_activeMenu = 'socialNetworks/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Social Settings'), 'url'=>'socialNetworks/manage'),
        array('label'=>A::t('app', 'Social Login Management'), 'url'=>'socialNetworks/login'),
        array('label'=>A::t('app', 'Edit Social Login'))
    );

    $spinnersCount = 0;
?>

<h1><?= A::t('app', 'Edit Social Login'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        echo CWidget::create('CDataForm', array(
            'model'=>'SocialNetworksLogin',
            'primaryKey'=>$id,
            'operationType'=>'edit',
            'action'=>'socialNetworks/loginEdit/id/'.$id,
            'successUrl'=>'socialNetworks/login',
            'cancelUrl'=>'socialNetworks/login',
            'passParameters'=>false,
            'method'=>'post',
            'htmlOptions'=>array(
                'id'     =>'frmSocialLoginEdit',
                'name'   =>'frmSocialLoginEdit',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'name'               => array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>50)),
                'application_id'     => array('type'=>'textbox', 'title'=>A::t('app', 'Application ID'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
                'application_secret' => array('type'=>'password', 'title'=>A::t('app', 'Application Secret'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
                'sort_order'         => array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>false, 'maxLength'=>1, 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>1, 'class'=>'small')),
                'is_active'          => array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>1, 'validation'=>array('type'=>'set', 'source'=>array(0, 1)), 'viewType'=>'custom'),
            ),
            'buttons'=>array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'=>'core',
            'alerts'=>array('type'=>'flash'),
            'return'=>true,
        ));
    ?>
    </div>
</div>
