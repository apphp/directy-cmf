<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Module')));
	
	$this->_activeMenu = 'modules/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Modules'), 'url'=>'modules/'),
		array('label'=>A::t('app', ($module->is_system) ? 'System Modules' : 'Application Modules'), 'url'=>'modules/'.(($module->is_system) ? 'system' : 'application')),
        array('label'=>A::t('app', 'Edit Module').' - '.$module->name),
		
    );    
?>

<h1><?php echo A::t('app', ($module->is_system) ? 'System Modules' : 'Application Modules').' / '.$module->name; ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Edit Module'); ?></div>
    <div class="content">        
    <?php        
        echo CWidget::create('CDataForm', array(
            'model'				=> 'Modules',
            'primaryKey'		=> $module->id,
            'operationType'		=> 'edit',
            'action'			=> 'modules/edit/id/'.$module->id,
            'successUrl'		=> 'modules/'.($module->is_system ? 'system' : 'application'),
            'cancelUrl'			=> 'modules/'.($module->is_system ? 'system' : 'application'),
            'requiredFieldsAlert' => true,
            'htmlOptions'		=> array(
                'name'				=> 'frmModuleEdit',
                'enctype'			=> 'multipart/form-data',
                'autoGenerateId'	=> true
            ),
            'fields'			=> array(
                'name'		 		=> array('type'=>'label', 'title'=>A::t('app', 'Name')),
                'code'		 		=> array('type'=>'label', 'title'=>A::t('app', 'Code')),
                'description'		=> array('type'=>'label', 'title'=>A::t('app', 'Description'), 'htmlOptions'=>array('class'=>'label-description')),
                'version'    		=> array('type'=>'label', 'title'=>A::t('app', 'Version')),
                'icon'		 		=> array('type'=>'image', 'title'=>A::t('app', 'Icon Image'), 'src'=>'images/modules/'.$module->code.'/'.$module->icon, 'alt'=>A::t('app', 'Icon Image'), 'htmlOptions'=>array('class'=>'icon')), 
                'is_system'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'System Module'), 'htmlOptions'=>array('disabled'=>'disabled', 'uncheckValue'=>$module->is_system)),
                'show_on_dashboard' => array('type'=>'checkbox', 'title'=>A::t('app', 'Show On Dashboard'), 'validation'=>array('type'=>'set', 'source'=>array(0,1))),
                'show_in_menu'      => array('type'=>'checkbox', 'title'=>A::t('app', 'Show In Menu'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($module->is_system == 1 ? array('disabled'=>'disabled') : array())),
                'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>($module->is_system == 1 ? array('disabled'=>'disabled', 'uncheckValue'=>$module->is_active) : array())),
                'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'2', 'class'=>'small')),
            ),
            'buttons' 			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),                
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Module')),
            'return'            => true,
        ));		                
    ?>    
    </div>
</div>
