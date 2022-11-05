<?php
    Website::setMetaTags(array('title'=>A::t('users', 'Edit Account')));

    $this->_activeMenu = 'users/editAccount';
    
    A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
    if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');
    A::app()->getClientScript()->registerScriptFile('assets/modules/users/js/users.js'); 

    A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
    A::app()->getClientScript()->registerScriptFile('assets/vendors/jquery/jquery-ui.min.js', 1);

    $breadCrumbs = array(
        array('label'=>A::t('users', 'Dashboard'), 'url'=>'users/dashboard'),
        array('label'=>A::t('users', 'My Account'), 'url'=>'users/myAccount'),
        array('label'=>A::t('users', 'Edit')),
    );   
?>

<h1 class="title"><?= A::t('users', 'Edit Account'); ?></h1>
<div class="block-body">    
<?php
    CWidget::create('CBreadCrumbs', array(
        'links'=>$breadCrumbs,
        'separator'=>'&nbsp;/&nbsp;',
        'return'=>false
    ));
?>

<div id="my-account">
<?php
    $fields = array();        
    
    $fields['separatorPersonal'] = array();
    $fields['separatorPersonal']['separatorInfo'] = array('legend'=>A::t('users', 'Personal Information'));
    if($fieldFirstName !== 'no') $fields['separatorPersonal']['first_name'] = array('type'=>'textbox', 'title'=>A::t('users', 'First Name'), 'default'=>'', 'validation'=>array('required'=>($fieldFirstName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32'));
    if($fieldLastName !== 'no') $fields['separatorPersonal']['last_name'] = array('type'=>'textbox', 'title'=>A::t('users', 'Last Name'), 'default'=>'', 'validation'=>array('required'=>($fieldLastName == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32'));
    if($fieldBirthDate !== 'no') $fields['separatorPersonal']['birth_date'] = array('type'=>'datetime', 'title'=>A::t('users', 'Birth Date'), 'validation'=>array('required'=>($fieldBirthDate == 'allow-required' ? true : false), 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>date('Y-m-d')), 'htmlOptions'=>array('maxLength'=>'10', 'style'=>'width:100px'), 'maxDate'=>-1, 'default'=>null, 'definedValues'=>array());
    if($fieldWebsite !== 'no') $fields['separatorPersonal']['website'] = array('type'=>'textbox', 'title'=>A::t('users', 'Website'), 'default'=>'', 'validation'=>array('required'=>($fieldWebsite == 'allow-required' ? true : false), 'type'=>'url', 'maxLength'=>255, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'255', 'autocomplete'=>'off'));
    if($fieldCompany !== 'no') $fields['separatorPersonal']['company'] = array('type'=>'textbox', 'title'=>A::t('users', 'Company'), 'default'=>'', 'validation'=>array('required'=>($fieldCompany == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>128, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'128', 'autocomplete'=>'off'));
    if(count($fields['separatorPersonal']) == 1) unset($fields['separatorPersonal']);
    
    $fields['separatorContact'] = array();
    $fields['separatorContact']['separatorInfo'] = array('legend'=>A::t('users', 'Contact Information'));
    if($fieldPhone !== 'no') $fields['separatorContact']['phone'] = array('type'=>'textbox', 'title'=>A::t('users', 'Phone'), 'default'=>'', 'validation'=>array('required'=>($fieldPhone == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'32', 'autocomplete'=>'off'));
    if($fieldFax !== 'no') $fields['separatorContact']['fax'] = array('type'=>'textbox', 'title'=>A::t('users', 'Fax'), 'default'=>'', 'validation'=>array('required'=>($fieldFax == 'allow-required' ? true : false), 'type'=>'phoneString', 'maxLength'=>32, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'32', 'autocomplete'=>'off'));
    if(count($fields['separatorContact']) == 1) unset($fields['separatorContact']);
    
    $fields['separatorAddress'] = array();
    $fields['separatorAddress']['separatorInfo'] = array('legend'=>A::t('users', 'Address Information'));
    if($fieldAddress !== 'no') $fields['separatorAddress']['address'] = array('type'=>'textbox', 'title'=>A::t('users', 'Address'), 'default'=>'', 'validation'=>array('required'=>($fieldAddress == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
    if($fieldAddress2 !== 'no') $fields['separatorAddress']['address_2'] = array('type'=>'textbox', 'title'=>A::t('users', 'Address (line 2)'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
    if($fieldCity !== 'no') $fields['separatorAddress']['city'] = array('type'=>'textbox', 'title'=>A::t('users', 'City'), 'default'=>'', 'validation'=>array('required'=>($fieldCity == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
    if($fieldZipCode !== 'no') $fields['separatorAddress']['zip_code'] = array('type'=>'textbox', 'title'=>A::t('users', 'Zip Code'), 'default'=>'', 'validation'=>array('required'=>($fieldZipCode == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>32, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'32', 'autocomplete'=>'off', 'class'=>'small'));
    if($fieldCountry !== 'no'){
        $onchange = ($fieldState !== 'no') ? "users_ChangeCountry('frmUserEdit',this.value,'')" : '';
        $fields['separatorAddress']['country_code'] = array('type'=>'select', 'title'=>A::t('users', 'Country'), 'tooltip'=>'', 'default'=>$defaultCountryCode, 'validation'=>array('required'=>($fieldCountry == 'allow-required' ? true : false), 'type'=>'set', 'source'=>array_keys($countries)), 'data'=>$countries, 'htmlOptions'=>array('onchange'=>$onchange));
    }
    if($fieldState !== 'no') $fields['separatorAddress']['state'] = array('type'=>'textbox', 'title'=>A::t('users', 'State/Province'), 'default'=>'', 'validation'=>array('required'=>($fieldState == 'allow-required' ? true : false), 'type'=>'text', 'maxLength'=>64, 'unique'=>false), 'htmlOptions'=>array('maxlength'=>'64', 'autocomplete'=>'off'));
    if(count($fields['separatorAddress']) == 1) unset($relatedTableFields['separatorAddress']);
    
    $fields['separatorAccount'] = array();
    $fields['separatorAccount']['separatorInfo'] = array('legend'=>A::t('users', 'Account Information'));
    if($fieldEmail !== 'no') $fields['separatorAccount']['email'] = array('type'=>'textbox', 'title'=>A::t('users', 'Email'), 'default'=>'', 'validation'=>array('required'=>($fieldEmail == 'allow-required' ? true : false), 'type'=>'email', 'maxLength'=>100, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'100', 'autocomplete'=>'off', 'class'=>'middle'));
    $fields['separatorAccount']['username']	= array('type'=>'label', 'title'=>A::t('users', 'Username'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false);
    $fields['separatorAccount']['password']	= array('type'=>'password', 'title'=>A::t('users', 'Password'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'password', 'minLength'=>6, 'maxlength'=>25), 'encryption'=>array('enabled'=>CConfig::get('password.encryption'), 'encryptAlgorithm'=>CConfig::get('password.encryptAlgorithm'), 'encryptSalt'=>$salt), 'htmlOptions'=>array('maxlength'=>'25', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;'));
    if(count($groups)) $fields['separatorAccount']['group_id'] = array('type'=>'select', 'title'=>A::t('app', 'Group'), 'tooltip'=>'', 'default'=>$defaultGroupId, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($groups)), 'data'=>$groups, 'htmlOptions'=>array());
    $fields['separatorAccount']['language_code'] = array('type'=>'select', 'title'=>A::t('users', 'Preferred Language'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($langList)), 'data'=>$langList);
    
    $fields['separatorOther'] = array();
    $fields['separatorOther']['separatorInfo'] = array('legend'=>A::t('users', 'Other'));
    $fields['separatorOther']['created_at'] = array('type'=>'label', 'title'=>A::t('users', 'Created at'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(null=>A::t('app', 'Never')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat, 'stripTags'=>false);
    //$fields['separatorOther']['created_ip'] = array('type'=>'label', 'title'=>A::t('users', 'Created from IP'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('000.000.000.000'=>A::t('app', 'Unknown')), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false);        
    $fields['separatorOther']['last_visited_at'] = array('type'=>'label', 'title'=>A::t('users', 'Last visit at'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(null=>A::t('app', 'Never')), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat, 'stripTags'=>false);
    //$fields['separatorOther']['last_visited_ip'] = array('type'=>'label', 'title'=>A::t('users', 'Last visit from IP'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('000.000.000.000'=>A::t('app', 'Unknown')), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false);        
    $fields['separatorOther']['notifications'] = array('type'=>'checkbox', 'title'=>A::t('users', 'Notifications'), 'default'=>'0', 'validation'=>array('type'=>'set', 'source'=>array(0,1)));
    
    echo CWidget::create('CDataForm', array(
        'model'         => 'Modules\Users\Models\Users',
        'primaryKey'    => $id,
        'operationType' => 'edit',
        'action'        => 'users/editAccount',
        //'successUrl'=>'users/manage/msg/updated',
        //'cancelUrl'=>'users/manage',
        'method'=>'post',
        'htmlOptions'=>array(
            'id'=>'frmUserEdit',                
            'name'=>'frmUserEdit',                
            'autoGenerateId'=>true
        ),
        'requiredFieldsAlert'=>true,
        'fields'=>$fields,
        'buttons'=>array(
            'submitUpdate'=>array('type'=>'submit', 'value'=>A::t('users', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate', 'class'=>'btn v-btn v-btn-default v-small-button')),
            'reset'=>array('type'=>'reset', 'value'=>A::t('users', 'Reset'), 'htmlOptions'=>array('class'=>'btn v-btn v-second-dark v-small-button')),
        ),
        'messagesSource'=>'core',
        'return'=>true,
    ));       

    if($fieldCountry !== 'no' && $fieldState !== 'no'){
        A::app()->getClientScript()->registerScript(
            'usersChangeCountry',
            '$(document).ready(function(){
                users_ChangeCountry(
                    "frmUserEdit","'.$countryCode.'","'.$stateCode.'"
                );
            });',
            1
        );
    }    
?>
</div>
</div>