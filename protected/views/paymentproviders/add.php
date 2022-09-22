<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Add New Payment Provider')));
	
	$this->_activeMenu = 'paymentProviders/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Payment Providers'), 'url'=>'paymentProviders/manage'),
        array('label'=>A::t('app', 'Add New Payment Provider')),
    );    
?>

<h1><?= A::t('app', 'Payment Providers Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Add New Payment Provider'); ?></div>
    <div class="content">        
	<?php
		echo CWidget::create('CDataForm', array(
			'model'				=> 'PaymentProviders',
			///'primaryKey'		=> 0,
			'operationType'		=> 'add',
			'action'			=> 'paymentProviders/add',
			'successUrl'		=> 'paymentProviders/manage',
			'cancelUrl'			=> 'paymentProviders/manage',
			'passParameters'	=> false,
			'method'			=> 'post',
			'htmlOptions'		=> array(
				'name'				=> 'frmPaymentProvidersAdd',
				///'enctype'			=> 'multipart/form-data',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert'=>true,
			'fields'			=> array(
				'name'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'40')),
				'description'		=> array('type'=>'textarea', 'title'=>A::t('app', 'Description').' ('.A::t('app', 'for internal use').')', 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048', 'class'=>'normal')),
				'instructions'		=> array('type'=>'textarea', 'title'=>A::t('app', 'Instructions').' ('.A::t('app', 'for external use').')', 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048')),
				'code'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'unique'=>true, 'type'=>'seoLink', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>'20')),
				'used_on'			=> array('type'=>'select', 'title'=>A::t('app', 'Used On'), 'tooltip'=>'', 'default'=>'global', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($usedOn)), 'data'=>$usedOn, 'emptyOption'=>true, 'viewType'=>'dropdownlist|checkboxes', 'multiple'=>false, 'storeType'=>'serialized|separatedValues', 'separator'=>';', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'mode'  			=> array('type'=>'select', 'title'=>A::t('app', 'Mode'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($modes)), 'data'=>$modes, 'emptyOption'=>true, 'viewType'=>'dropdownlist', 'multiple'=>false, 'storeType'=>'serialized|separatedValues', 'separator'=>';', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'required_fields'   => array('type'=>'select', 'title'=>A::t('app', 'Required Fields'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($requiredFields)), 'data'=>$requiredFields, 'emptyOption'=>true, 'viewType'=>'checkboxes', 'multiple'=>true, 'storeType'=>'separatedValues', 'separator'=>';'),
				//'merchant_id'       => array('type'=>'textbox', 'title'=>A::t('app', 'Merchant ID'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'255')),
				//'merchant_code'     => array('type'=>'textbox', 'title'=>A::t('app', 'Merchant Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'60')),
				//'merchant_key'      => array('type'=>'textbox', 'title'=>A::t('app', 'Merchant Key'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'60')),
				'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
				'is_default' 		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'4', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'4', 'class'=>'small')),
			),
			'buttons'			=>array(
			   'submit' 			=> array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'		=>'core',
            'showAllErrors'         => false,
			'alerts'				=> array('type'=>'flash', 'itemName'=>A::t('app', 'Payment Provider')),
			'return'=>true,
		));		                
	?>    
    </div>
</div>
<?php
	// Draw check boxes vertically
	A::app()->getClientScript()->registerCss(
		'payment-provider',
		'
			#frmPaymentProvidersAdd_row_6 ul.checkboxes-list li{
				width:55%;
			}
			#frmPaymentProvidersAdd_row_6 ul.checkboxes-list li label{
				width:30%;
			}		
		'
	);

?>
