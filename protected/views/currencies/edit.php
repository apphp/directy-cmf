<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Edit Currency')));

    $this->_activeMenu = 'currencies/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Currencies'), 'url'=>'currencies/manage'),
		array('label'=>A::t('app', 'Edit Currency')),
    );    
?>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Edit Currency'); ?></div>
    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
            'model'				=> 'Currencies',
            'primaryKey'		=> $currency->id,
            'operationType'		=> 'edit',
            'action'			=> 'currencies/edit/id/'.$currency->id,
            'successUrl'		=> 'currencies/manage',
            'cancelUrl'			=> 'currencies/manage',
            'passParameters'	=> false,
            'method'			=> 'post',
            'htmlOptions'		=> array(
                'name'				=> 'frmCurrencyEdit',
                //'enctype'			=> 'multipart/form-data',
                'autoGenerateId'	=> true
            ),
            'requiredFieldsAlert' => true,
            'fieldSets'			=> array('type'=>'frameset'),
            'fields'			=> array(
                'name'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Currency Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50')),
                'symbol'     		=> array('type'=>'textbox', 'title'=>A::t('app', 'Symbol'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>5, 'forbiddenChars'=>array('"', "'")), 'htmlOptions'=>array('maxLength'=>'5', 'class'=>'small')),
                'code'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'alpha', 'maxLength'=>3), 'htmlOptions'=>array('maxLength'=>'3', 'class'=>'small')),
                'rate'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Rate'), 'tooltip'=>A::t('app', 'Relative to the default currency'), 'default'=>($numberFormat == 'european' ? '1,00' : '1.00'), 'validation'=>array('required'=>true, 'type'=>'float', 'minValue'=>'0', 'format'=>$numberFormat, 'maxLength'=>10), 'htmlOptions'=>array('maxLength'=>'10', 'class'=>'small')),
                'symbol_place' 		=> array('type'=>'select', 'title'=>A::t('app', 'Symbol Place'), 'tooltip'=>'', 'default'=>'after', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array('before', 'after')), 'data'=>array('before'=>A::t('app', 'before'), 'after'=>A::t('app', 'after')), 'htmlOptions'=>array()),
                'decimals'   		=> array('type'=>'select', 'title'=>A::t('app', 'Decimals'), 'tooltip'=>'', 'default'=>'2', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array('0','1','2','3','4')), 'data'=>array('0','1','2','3','4'), 'htmlOptions'=>array()),
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Order'), 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
				'is_default' 		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($currency->is_default ? array('disabled'=>'disabled', 'uncheckValue'=>$currency->is_default) : ''), 'viewType'=>'custom'),
				'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($currency->is_default ? array('disabled'=>'disabled', 'uncheckValue'=>$currency->is_active) : ''), 'viewType'=>'custom'),
            ),
            'buttons'			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Currency')),
            'return'            => true,
        ));
	?>    
    </div>
</div>
    