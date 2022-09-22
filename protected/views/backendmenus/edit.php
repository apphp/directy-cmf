<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Menu')));
	
    $this->_activeMenu = 'backendMenus/';
    $breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/dashboard'),
    );    
	if($parentId && $parentName != ''){
		$breadCrumbs[] = array('label'=>A::t('app', 'Backend Menu'), 'url'=>'backendMenus/manage');
		$breadCrumbs[] = array('label'=>A::t('app', $parentName), 'url'=>'backendMenus/manage/pid/'.$parentId);
	}else{
		$breadCrumbs[] = array('label'=>A::t('app', 'Backend Menu'), 'url'=>'backendMenus/manage');		
	}
	$breadCrumbs[] = array('label'=>A::t('app', 'Edit Menu'));
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?= A::t('app', 'Backend Menu Management'); ?></h1>

<div class="bloc">
	<div class="title"><?= A::t('app', 'Edit Menu'); ?></div>
    <div class="content">
        
    <?= $actionMessage; ?>
    
    <?php
        $allowImageOptions = (!$parentId) ? false : true;
    	echo CWidget::create('CDataForm', array(
			'model'					=> 'BackendMenus',
			'primaryKey'			=> $id,
			'operationType'			=> 'edit',
			'action'				=> 'backendMenus/edit/id/'.$id.($parentId ? '/pid/'.$parentId : ''), 
			'successUrl'			=> 'backendMenus/manage'.($parentId ? '/pid/'.$parentId : '/pid/0').'/msg/updated',
			'cancelUrl'				=> 'backendMenus/manage'.($parentId ? '/pid/'.$parentId : ''),
			'requiredFieldsAlert'	=> true,
			//'passParameters'=>true,
			'return'				=> true,
			'htmlOptions'			=> array(
				'name'			 => 'frmMenuEdit',
                'enctype'		 => 'multipart/form-data',
				'autoGenerateId' => true
			),
			'fields'				=> array(
				'url'		  	=> array('type'=>'label', 'title'=>A::t('app', 'URL')),
				'parent_name' 	=> array('type'=>'label', 'title'=>A::t('app', 'Parent'), 'definedValues'=>array(''=>$parentName)),
				'is_system'   	=> array('type'=>'label', 'title'=>A::t('app', 'System'), 'definedValues'=>array('0'=>A::t('shoppingcart', 'No'), '1'=>A::t('shoppingcart', 'Yes')), 'htmlOptions'=>array()),
				'is_visible'  	=> array('type'=>'checkbox', 'title'=>A::t('app', 'Visible'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
				'sort_order'  	=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),

                'icon'				=> array(
                    'type'          => 'imageupload',
                    'title'         => A::t('app', 'Icon'),
                    'validation'    => array('required'=>false, 'type'=>'image', 'maxSize'=>'50k', 'maxWidth'=>'32px', 'maxHeight'=>'32px', 'targetPath'=>'templates/backend/images/icons/', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>'menu_'.$id),
                    'imageOptions'  => array('showImage'=>true, 'showImageName'=>$allowImageOptions, 'showImageSize'=>$allowImageOptions, 'showImageDimensions'=>$allowImageOptions, 'imageClass'=>'menu-preview-icon'),
                    'deleteOptions' => array('showLink'=>$allowImageOptions, 'linkUrl'=>'backendMenus/edit/id/'.$id.($parentId ? '/pid/'.$parentId : '').'/icon/delete', 'linkText'=>A::t('app', 'Delete')),
                    'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'templates/backend/images/icons/')
                ),
			),
			'translationInfo' 		=> array('relation'=>array('id', 'menu_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'name' 	=> array('type'=>'textbox', 'title'=>A::t('app', 'Name'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>125), 'htmlOptions'=>array('title'=>'')),
			),
			'buttons' => array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),                
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
            'messagesSource'        => 'core',
            'showAllErrors'         => false,
			'alerts'				=> array('type'=>'flash', 'itemName'=>A::t('app', 'Menu').' '.$menuName),
            'return'                => true,
   		));            
	?>
    </div>
</div>
