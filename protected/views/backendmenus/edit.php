<?php
    $this->_activeMenu = 'backendMenus/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/dashboard'),
    );    
	if($parentId && $parentName != ''){
		$this->_breadCrumbs[] = array('label'=>A::t('app', 'Backend Menu'), 'url'=>'backendMenus/manage');
		$this->_breadCrumbs[] = array('label'=>A::t('app', $parentName), 'url'=>'backendMenus/manage/pid/'.$parentId);
	}else{
		$this->_breadCrumbs[] = array('label'=>A::t('app', 'Backend Menu'), 'url'=>'backendMenus/manage');		
	}
	$this->_breadCrumbs[] = array('label'=>A::t('app', 'Edit Menu'));
?>

<h1><?php echo A::t('app', 'Backend Menu Management'); ?></h1>

<div class="bloc">
	<div class="title"><?php echo A::t('app', 'Edit Menu'); ?></div>
    <div class="content">
    <?php
    	echo CWidget::create('CDataForm', array(
			'model'=>'BackendMenus',
			'operationType'=>'edit',
			'primaryKey'=>$id,
			'action'=>'backendMenus/edit/id/'.$id.($parentId ? '/pid/'.$parentId : ''), 
			'successUrl'=>'backendMenus/manage'.($parentId ? '/pid/'.$parentId : '/pid/0').'/msg/updated',
			'cancelUrl'=>'backendMenus/manage'.($parentId ? '/pid/'.$parentId : ''),
			'requiredFieldsAlert'=>true,
			//'passParameters'=>true,
			'return'=>true,
			'htmlOptions'=>array(
				'name'=>'frmMenuEdit',
				'autoGenerateId'=>true
			),
			'fields'=>array(
				'id'          => array('type'=>'hidden'),
				'url'		  => array('type'=>'label', 'title'=>A::t('app', 'URL')),
				'parent_name' => array('type'=>'label', 'title'=>A::t('app', 'Parent'), 'definedValues'=>array(''=>$parentName)),
				'is_system'   => array('type'=>'checkbox', 'title'=>A::t('app', 'System'), 'htmlOptions'=>array('disabled'=>'disabled')),
				'is_visible'  => array('type'=>'checkbox', 'title'=>A::t('app', 'Visible'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array()),                    
				'sort_order'  => array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
			),
			'translationInfo' => array('relation'=>array('id', 'menu_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
			'translationFields' => array(
				'name' => array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>125), 'htmlOptions'=>array('title'=>'')),
			),
			'buttons' => array(
			   'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),                
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
   		));
            
	?>        
        	
    </div>
</div>
