<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit State')));
	
	$this->_activeMenu = 'locations/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Locations'), 'url'=>'locations/manage'),
		array('label'=>A::t('app', $countryName), 'url'=>'subLocations/manage/country/'.$countryId),
		array('label'=>A::t('app', 'Edit State')),
    );    
?>

<h1><?= A::t('app', 'Sub-Locations Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Edit State'); ?> <a class="back-link" href="subLocations/manage/country/<?= $countryId; ?>"><?= A::t('app', 'Back'); ?></a></div>
    <div class="content">        
    <?php
        echo CWidget::create('CDataForm', array(
            'model'				=> 'States',
            'primaryKey'		=> $id,
            'operationType'		=> 'edit',
            'action'			=> 'subLocations/edit/id/'.$id,
            'successUrl'		=> 'subLocations/manage/country/'.$countryId,
            'cancelUrl'			=> 'subLocations/manage/country/'.$countryId,
            'method'			=> 'post',
            'htmlOptions'		=> array(
                'name'				=> 'frmStateEdit',
                'autoGenerateId'	=> true
            ),
            'requiredFieldsAlert' => true,
            'fields'			=> array(
                'code'       		=> array('type'=>'textbox', 'title'=>A::t('app', 'Code'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'alpha', 'maxLength'=>'2', 'unique'=>false), 'htmlOptions'=>array('maxLength'=>'2', 'class'=>'small')),
                'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
				'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'2', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'2', 'class'=>'small')),
            ),
            'translationInfo' 	=> array('relation'=>array('id', 'state_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
            'translationFields' => array(
                'name' 				=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>70), 'htmlOptions'=>array('title'=>'')),
            ),
            'buttons'			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'State')),
            'return'            => true,
        ));		                
    ?>    
    </div>
</div>
