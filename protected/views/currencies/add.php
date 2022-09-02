<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Add New Currency')));

    $this->_activeMenu = 'currencies/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Currencies'), 'url'=>'currencies/manage'),
        array('label'=>A::t('app', 'Add New Currency')),
    );    
?>

<h1><?= A::t('app', 'Currencies Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Add New Currency'); ?></div>
    <div class="content">        
    <?php
        echo CWidget::create('CDataForm', array(
            'model'				=> 'Currencies',
            ///'primaryKey'		=> 0,
            'operationType'		=> 'add',
            'action'			=> 'currencies/add',     
            'successUrl'		=> 'currencies/manage',
            'cancelUrl'			=> 'currencies/manage',
            'passParameters'	=> false,
            'method'			=> 'post',
            'requiredFieldsAlert' => true,
            'htmlOptions'		=> array(
                'name'				=> 'frmCurrencyAdd',
                //'enctype'			=> 'multipart/form-data',
                'autoGenerateId'	=> true
            ),
            'fieldSets'			=> array('type'=>'frameset'),
            'fields'=>array(
                'name'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Currency Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50')),
                'symbol'     		=> array('type'=>'textbox', 'title'=>A::t('app', 'Symbol'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>5, 'forbiddenChars'=>array('"', "'")), 'htmlOptions'=>array('maxLength'=>'5', 'class'=>'small')),
                'code'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'alpha', 'maxLength'=>3), 'htmlOptions'=>array('maxLength'=>'3', 'class'=>'small')),
                'rate'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Rate'), 'tooltip'=>A::t('app', 'Relative to the default currency'), 'default'=>($numberFormat == 'european' ? '1,00' : '1.00'), 'validation'=>array('required'=>true, 'type'=>'float', 'minValue'=>'0', 'format'=>$numberFormat, 'maxLength'=>10), 'htmlOptions'=>array('maxLength'=>'10', 'class'=>'small')),
                'symbol_place' 		=> array('type'=>'select', 'title'=>A::t('app', 'Symbol Place'), 'tooltip'=>'', 'default'=>'after', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array('before', 'after')), 'data'=>array('before'=>A::t('app', 'before'), 'after'=>A::t('app', 'after')), 'htmlOptions'=>array()),
                'decimals'   		=> array('type'=>'select', 'title'=>A::t('app', 'Decimals'), 'tooltip'=>'', 'default'=>'2', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array('0','1','2','3','4')), 'data'=>array('0','1','2','3','4'), 'htmlOptions'=>array()),
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Order'), 'default'=>$sortOrder, 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
				'is_default' 		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
				'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom'),
            ),
            'buttons'			=> array(
			   'submit' 			=> array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Currency')),
            'return'            => true,
        ));
    ?>        
    </div>
</div>
