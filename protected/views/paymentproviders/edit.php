<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Payment Provider')));
	
	$this->_activeMenu = 'paymentProviders/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Payment Providers')),
        array('label'=>A::t('app', 'Edit Payment Provider')),
    );    
?>

<h1><?php echo A::t('app', 'Payment Providers Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Edit Payment Provider'); ?></div>
    <div class="content">        
	<?php
	
		// Prepare required fields to show in Edit mode
		$requiredFields = explode(';', $paymentProvider->required_fields);
		$required['merchant_id'] = false;
		$required['merchant_code'] = false;
		$required['merchant_key'] = false;
		foreach($requiredFields as $key => $requiredField){
			if($requiredField == 'merchant_id'){
				$required['merchant_id'] = true;
			}else if($requiredField == 'merchant_code'){
				$required['merchant_code'] = true;
			}else if($requiredField == 'merchant_key'){
				$required['merchant_key'] = true;
			}
		}
	
		echo CWidget::create('CDataForm', array(
			'model'				=> 'PaymentProviders',
			'primaryKey'		=> $paymentProvider->id,
			'operationType'		=> 'edit',
			'action'			=> 'paymentProviders/edit/id/'.$paymentProvider->id,
			'successUrl'		=> 'paymentProviders/manage',
			'cancelUrl'			=> 'paymentProviders/manage',
			'passParameters'	=> false,
			'method'			=> 'post',
			'htmlOptions'		=> array(
				'name'				=> 'frmPaymentProvidersEdit',
				///'enctype'			=> 'multipart/form-data',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert'=>true,
			'fields'			=> array(
				'name'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'40')),
				'description'		=> array('type'=>'textarea', 'title'=>A::t('app', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255', 'class'=>'small')),
				'instructions'		=> array('type'=>'textarea', 'title'=>A::t('app', 'Instructions'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048')),
				'used_on'			=> array('type'=>'select', 'title'=>A::t('app', 'Used On'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($usedOn)), 'data'=>$usedOn, 'emptyOption'=>true, 'viewType'=>'dropdownlist|checkboxes', 'multiple'=>false, 'storeType'=>'serialized|separatedValues', 'separator'=>';', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'mode'  			=> array('type'=>'select', 'title'=>A::t('app', 'Mode'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($modes)), 'data'=>$modes, 'emptyOption'=>true, 'viewType'=>'dropdownlist', 'multiple'=>false, 'storeType'=>'serialized|separatedValues', 'separator'=>';', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'merchant_id'       => array('type'=>'textbox', 'title'=>A::t('app', 'Merchant ID'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'255'), 'disabled'=>!$required['merchant_id']),
				'merchant_code'     => array('type'=>'textbox', 'title'=>A::t('app', 'Merchant Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'60'), 'disabled'=>!$required['merchant_code']),
				'merchant_key'      => array('type'=>'textbox', 'title'=>A::t('app', 'Merchant Key'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>40), 'htmlOptions'=>array('maxLength'=>'60'), 'disabled'=>!$required['merchant_key']),
				'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'htmlOptions'=>($paymentProvider->is_default == 1 ? array('disabled'=>'disabled', 'uncheckValue'=>$paymentProvider->is_active) : array())),
				'is_default' 		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'htmlOptions'=>($paymentProvider->is_default == 1 ? array('disabled'=>'disabled', 'uncheckValue'=>$paymentProvider->is_default) : array())),                    
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'4', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'4', 'class'=>'small')),
			),
			'buttons'			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
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
