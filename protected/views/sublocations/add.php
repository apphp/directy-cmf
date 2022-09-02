<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Add New State')));
	
	$this->_activeMenu = 'locations/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
		array('label'=>A::t('app', 'Locations'), 'url'=>'locations/manage'),
		array('label'=>A::t('app', $selectedCountry->country_name), 'url'=>'subLocations/manage/country/'.$selectedCountry->id),
    );    
?>

<h1><?= A::t('app', 'Sub-Locations Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Add New State'); ?> <a class="back-link" href="subLocations/manage/country/<?= $selectedCountry->id; ?>"><?= A::t('app', 'Back'); ?></a></div>
    <div class="content">        
	<?php
		echo CWidget::create('CDataForm', array(
			'model'				=> 'States',
			'primaryKey'		=> 0,
			'action'			=> 'subLocations/add/country/'.$selectedCountry->id,
			'successUrl'		=> 'subLocations/manage/country/'.$selectedCountry->id,
			'cancelUrl'			=> 'subLocations/manage/country/'.$selectedCountry->id,
			'method'			=> 'post',
			'htmlOptions'		=> array(
				'name'				=> 'frmStateAdd',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert' => true,
			'fields'			=> array(
				'country_code'		=> array('type'=>'data', 'default'=>$selectedCountry->code),
				'code'        		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'alpha', 'maxLength'=>'2', 'unique'=>false), 'htmlOptions'=>array('maxLength'=>'2', 'class'=>'small')),
				'is_active'   		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'default'=>true, 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'2', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'2', 'class'=>'small')),
			),
			'translationInfo' 	=> array('relation'=>array('id', 'state_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'name' 				=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>70), 'htmlOptions'=>array('title'=>'')),
			),
			'buttons'			=> array(
			   'submit' 			=> array('type'=>'submit', 'value'=>A::t('app', 'Create'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'State')),
            'return'            => true,
		));		                
	?>    
    </div>
</div>
