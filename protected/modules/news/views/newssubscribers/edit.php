<?php
	Website::setMetaTags(array('title'=>A::t('news', 'Edit Subscriber')));

    $this->_activeMenu  = $backendPath.'modules/settings/code/news';
    $this->_breadCrumbs = array(
        array('label' => A::t('news', 'Modules'),    'url'=>$backendPath.'modules/'),
        array('label' => A::t('news', 'News'),       'url'=>$backendPath.'modules/settings/code/news'),
        array('label' => A::t('news', 'Subscribers'), 'url'=>'newsSubscribers/manage'),
        array('label' => A::t('news', 'Edit Subscriber')),
    );
        $fields = array();
        if('no' != $typeFirstName) $fields['first_name'] = array('title' => A::t('news', 'First Name'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => 'allow-required' == $typeFirstName ? true : false, 'default' => $firstName, 'htmlOptions' => array('maxLength' => '32', 'placeholder' => '', 'id' => 'news_subscribers_first_name'), 'validation' => array('required' => 'allow-required' == $typeFirstName ? true : false, 'type'=>'text', 'maxLength' => 32, 'unique' => false));
        if('no' != $typeLastName) $fields['last_name'] = array('title' => A::t('news', 'Last Name'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => 'allow-required' == $typeLastName ? true : false, 'default' => $lastName, 'htmlOptions' => array('maxLength' => '32', 'placeholder' => '', 'id' => 'news_subscribers_last_name'), 'validation' => array('required' => 'allow-required' == $typeLastName ? true : false, 'type'=>'text', 'maxLength' => 32, 'unique' => false));
        if('no' != $typeFullName) $fields['full_name'] = array('title' => A::t('news', 'Full Name'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => 'allow-required' == $typeFullName ? true : false, 'default' => $fullName, 'htmlOptions' => array('maxLength' => '64', 'placeholder' => '', 'id' => 'news_subscribers_last_name'), 'validation' => array('required' => 'allow-required' == $typeFullName ? true : false, 'type'=>'text', 'maxLength' => 64, 'unique' => false));
        $fields['email'] = array('title' => A::t('news', 'Email'), 'type' => 'textbox', 'tooltip' => '', 'mandatoryStar' => true, 'value' => $email, 'htmlOptions' => array('maxLength' => '128', 'class' => 'middle', 'placeholder' => '', 'id' => 'news_subscribers_email'), 'validation' => array('required' => true, 'type'=>'email', 'maxLength' => '128', 'autocomplete'=>'', 'unique' => true));
?>
<h1><?= A::t('news', 'Subscribers'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>
    <div class="sub-title"><?= A::t('news', 'Edit Subscriber'); ?></div>
    <div class="content">
        <?php
            $formName = 'frmNewsSubscribersEdit';
            CWidget::create('CDataForm', array(
                'model'         => '\Modules\News\Models\NewsSubscribers',
                'primaryKey'    => $id,
                'operationType' => 'edit',
                'action'        => 'newsSubscribers/edit/id/'.$id,
                'successUrl'    => 'newsSubscribers/manage',
                'cancelUrl'     => 'newsSubscribers/manage',
                'method'        => 'post',
                'htmlOptions'   => array(
                    'id'   => $formName,
                    'name' => $formName,
                    'autoGenerateId' => true
                ),
                'requiredFieldsAlert' => true,
                'fields'    => $fields,
                'buttons'   => array(
                    'submitUpdateClose'=>array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                    'submitUpdate'=>array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                    'cancel' => array('type'=>'button', 'value'=>A::t('news', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'buttonsPosition' => 'bottom',
                'events' => array(
                    'focus' => array('field' => $errorField)
                ),
				'messagesSource' 	=> 'core',
				'showAllErrors'     => false,
				'alerts'            => array('type'=>'flash', 'itemName'=>A::t('news', 'Subscriber')),
				'return'            => false
            ));
        ?>
    </div>
</div>
