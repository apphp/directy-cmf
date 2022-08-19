<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Item')));
	
    $this->_activeMenu = 'banLists/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Ban Lists'), 'url'=>'banLists/manage'),
        array('label'=>A::t('app', 'Edit Item')),
    );    
?>

<h1><?php echo A::t('app', 'Ban Lists Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Edit Item'); ?></div>
    <div class="content">        
    <?php
	
		$fields = array();		
		$fields['item_type'] = array('type'=>'select', 'title'=>A::t('app', 'Type'), 'tooltip'=>'', 'default'=>'ip', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($itemTypes)), 'data'=>$itemTypes, 'emptyOption'=>true, 'htmlOptions'=>array());
		
		if($itemType == 'email'){
			$fields['item_value'] = array('type'=>'textbox', 'title'=>A::t('app', 'Item'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>'100'), 'htmlOptions'=>array('maxLength'=>'100', 'class'=>'normal'));
		}else if($itemType == 'username'){
			$fields['item_value'] = array('type'=>'textbox', 'title'=>A::t('app', 'Item'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'mixed', 'maxLength'=>'40'), 'htmlOptions'=>array('maxLength'=>'40', 'class'=>'normal'));
		}else{
			$fields['item_value'] = array('type'=>'textbox', 'title'=>A::t('app', 'Item'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'ip', 'maxLength'=>'15'), 'htmlOptions'=>array('maxLength'=>'15', 'class'=>'normal'));
		}
		
		$fields['reason'] = array('type'=>'textarea', 'title'=>'Ban Reason', 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255'));
		//$fields['started_at'] = array('type'=>'data', 'default'=>LocalTime::currentDate());
		$fields['expires_at'] = array('type'=>'datetime', 'title'=>A::t('app', 'Expires'), 'defaultEditMode'=>'0000-00-00', 'validation'=>array('required'=>false, 'type'=>'date', 'maxLength'=>10, 'minValue'=>date('Y-m-d'), 'maxValue'=>''), 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px'), 'definedValues'=>array('0000-00-00'=>''));
		$fields['is_active'] = array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)));
	
        echo CWidget::create('CDataForm', array(
            'model'				=> 'BanLists',
			'primaryKey'		=> $id,
            'operationType'		=> 'edit',
            'action'			=> 'banLists/edit/id/'.$id,
            'successUrl'		=> 'banLists/manage',
            'cancelUrl'			=> 'banLists/manage',
            'passParameters'	=> false,
            'method'			=> 'post',
            'htmlOptions'		=> array(
                'name'				=> 'frmBanListsEdit',
                ///'enctype'			=> 'multipart/form-data',
                'autoGenerateId'	=> true
            ),
            'requiredFieldsAlert'=>true,
            'fieldSets'			=> array('type'=>'frameset'),
            'fields'			=> $fields,
            'buttons'			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'	=> 'core',
            'showAllErrors'		=> false,
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Ban List Item')),
            'return'            => true,
        ));
    ?>        
    </div>
</div>
