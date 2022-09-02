<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Country')));
	
	$this->_activeMenu = 'locations/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Locations'), 'url'=>'locations/manage'),
		array('label'=>A::t('app', 'Edit Country')),
    );    
?>

<h1><?= A::t('app', 'Locations Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Edit Country'); ?></div>
    <div class="content">
	<?php
		echo CWidget::create('CDataForm', array(
			'model'				=> 'Countries',
			'primaryKey'		=> $country->id,
			'operationType'		=> 'edit',
			'action'			=> 'locations/edit/id/'.$country->id,
			'successUrl'		=> 'locations/manage/msg/updated',
			'cancelUrl'			=> 'locations/manage',
			'passParameters'	=> true,
			'requiredFieldsAlert' => true,
			'return'			=> true,
			'htmlOptions'		=> array(
				'name'				=> 'frmLocationAdd',
				//'enctype'			=> 'multipart/form-data',
				'autoGenerateId'	=> true
			),
			'fields'			=> array(
				'code'       		=> array('type'=>'label', 'title'=>A::t('app', 'Code'), 'tooltip'=>''),
				'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'htmlOptions'=>($country->is_default == 1 ? array('disabled'=>'disabled', 'uncheckValue'=>$country->is_active) : array()), 'viewType'=>'custom'),
				'is_default' 		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Default'), 'htmlOptions'=>($country->is_default == 1 ? array('disabled'=>'disabled', 'uncheckValue'=>$country->is_default) : array()), 'viewType'=>'custom'),  
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'4', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'4', 'class'=>'small')),
			),
			'translationInfo' 	=> array('relation'=>array('code', 'country_code'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'name' 				=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any'), 'htmlOptions'=>array('title'=>'')),
			),
			'buttons' 			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),                
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Country')),
            'return'            => true,
		));
	?>    
    </div>
</div>
