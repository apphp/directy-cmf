<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Social Login')));

    $this->_activeMenu = $backendPath.'socialNetworks/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard/'),
        array('label'=>A::t('app', 'Social Settings'), 'url'=>$backendPath.'socialNetworks/manage'),
        array('label'=>A::t('app', 'Social Login Management'), 'url'=>$backendPath.'socialNetworks/login'),
        array('label'=>A::t('app', 'Edit Social Login'))
    );
?>

<h1><?= A::t('app', 'Edit Social Login'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="content">
        <?= $actionMessage; ?>

        <?php if(!empty($application_id) || !empty($application_secret)): ?>
            <a href="<?=$backendPath?>socialNetworks/clearData/id/<?=$id?>" class="delete-all prompt-delete align-right" data-prompt-message="<?= A::te('app', 'Are you sure you want to clear all data for this record?'); ?>"><?= A::t('app', 'Clear Data'); ?></a>
        <?php endif; ?>


        <div class="message-box message-info mb20 align-left">
            <b><?= strtoupper(A::t('app', 'important'));?>:</b><br><br>
            <p><?= A::t('app', 'Social Network Configuration Message')?></p>
            <p>
				<?php
                    $loginUrl = $callbackUrl = '';
				    $domain = A::app()->getRequest()->getBaseUrl();
                    switch($type){
						case 'google':
							$loginUrl = 'https://developers.google.com';
							$callbackUrl = $domain.'socialNetworks/oauth/type/google/cb/oauth2callback';
							break;
						case 'facebook':
							$loginUrl = 'https://developers.facebook.com';
							$callbackUrl = $domain.'socialNetworks/oauth/type/facebook/cb/oauth2callback';
							break;
						case 'twitter':
							$loginUrl = 'https://developer.twitter.com';
							$callbackUrl = $domain.'socialNetworks/oauth/type/twitter/cb/oauth2callback';
							break;
						case 'linkedin':
							$loginUrl = 'https://www.linkedin.com/developers';
							$callbackUrl = $domain.'socialNetworks/oauth/type/linkedin/cb/oauth2callback';
							break;
                        default:
							break;
                    }
                    echo A::t('app', 'Social Network Configuration Text', array('{login_url}'=>$loginUrl, '{callback_url}'=>$callbackUrl));
                ?>
            </p>
        </div>

        <?php
            echo CWidget::create('CDataForm', array(
                'model'         => 'SocialNetworksLogin',
                'primaryKey'    => $id,
                'operationType' => 'edit',
                'action'        => $backendPath.'socialNetworks/loginEdit/id/'.$id,
                'successUrl'    => $backendPath.'socialNetworks/login',
                'cancelUrl'     => $backendPath.'socialNetworks/login',
                'passParameters'=> false,
                'method'=>'post',
                'htmlOptions'=>array(
                    'id'     =>'frmSocialLoginEdit',
                    'name'   =>'frmSocialLoginEdit',
                    'enctype'=>'multipart/form-data',
                    'autoGenerateId'=>true
                ),
                'requiredFieldsAlert'=>true,
                'fields'=>array(
                    'name'               => array('type'=>'label', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>32), 'htmlOptions'=>array('maxLength'=>50)),
                    'application_id'     => array('type'=>'textbox', 'title'=>A::t('app', ($type == 'google' ? 'Client ID' : 'Application ID')), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('class'=>'large', 'maxLength'=>255)),
                    'application_secret' => array('type'=>'password', 'title'=>A::t('app', ($type == 'google' ? 'Client Secret' : 'Application Secret')), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('class'=>'large', 'maxLength'=>255)),
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
