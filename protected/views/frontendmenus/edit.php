<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Edit Menu')));
	
	$this->_activeMenu = 'frontendMenus/';
    $breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/dashboard'),
    );
	if($parentId && $parentName != ''){
		$breadCrumbs[] = array('label'=>A::t('app', 'Frontend Menu'), 'url'=>'frontendMenus/manage');
		$breadCrumbs[] = array('label'=>A::t('app', $parentName), 'url'=>'frontendMenus/manage/pid/'.$parentId);
		unset($menuTypesList['moduleblock']);
	}else{
		$breadCrumbs[] = array('label'=>A::t('app', 'Frontend Menu'), 'url'=>'frontendMenus/manage');
	}
	$breadCrumbs[] = array('label'=>A::t('app', 'Edit Menu'));
    $this->_breadCrumbs = $breadCrumbs;
	
	A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
	A::app()->getClientScript()->registerScriptFile('templates/backend/js/menu.js');
?>

<h1><?= A::t('app', 'Frontend Menu Management')?></h1>	

<div class="bloc">
	<div class="title"><?= A::t('app', 'Edit Menu'); ?></div>
    <div class="content">        
	<?php
		$fields = array();
		$fields['parent_name']  = array('type'=>'label', 'title'=>A::t('app', 'Parent'), 'definedValues'=>array(''=>$parentName));
		$fields['placement']    = array('type'=>'select', 'title'=>A::t('app', 'Display On'), 'tooltip'=>A::t('app', 'Take in account that some menu placements may be disabled in current Frontend template.'), 'data'=>$placementsList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($placementsList)));
		$fields['access_level'] = array('type'=>'select', 'title'=>A::t('app', 'Access'), 'data'=>$accessLevelsList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($accessLevelsList)));
		$fields['menu_type']    = array('type'=>'select', 'title'=>A::t('app', 'Menu Type'), 'data'=>$menuTypesList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($menuTypesList)), 'htmlOptions'=>array('onchange'=>'changeMenuType(this)'));

		$appendCode = '<a href="javascript:void(0)" onclick="$(\'#dialog\').dialog({maxHeight:400,width:320,maxWidth:600});" title="'.A::t('app', 'Set Link').'"><img style="margin-bottom:-7px;" src="assets/vendors/jquery/images/set_link.png" alt="Set Link" /></a>';
		if($menuType == 'modulelink'){
			$fields['link_target'] = array('type'=>'data', 'default'=>'_self');
			$fields['link_url']    = array('type'=>'textbox', 'title'=>A::t('app', 'Link URL'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('readonly'=>true, 'maxlength'=>'255', 'class'=>'large'), 'appendCode'=>$appendCode);
			$fields['module_code'] = array('type'=>'textbox', 'title'=>A::t('app', 'Module Code'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'mixed'), 'htmlOptions'=>array('readonly'=>true));
		}else if($menuType == 'moduleblock'){	
			$fields['link_target'] = array('type'=>'data', 'default'=>'');
			$fields['link_url']    = array('type'=>'textbox', 'title'=>A::t('app', 'Link URL'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('readonly'=>true, 'maxlength'=>'255', 'class'=>'large'), 'appendCode'=>$appendCode);
			$fields['module_code'] = array('type'=>'textbox', 'title'=>A::t('app', 'Module Code'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'mixed'), 'htmlOptions'=>array('readonly'=>true));
		}else{
			$fields['link_target'] = array('type'=>'select', 'title'=>A::t('app', 'Link Target'), 'default'=>'', 'data'=>$linkTargetsList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($linkTargetsList)));
			$fields['link_url']    = array('type'=>'textbox', 'title'=>A::t('app', 'Link URL'), 'tooltip'=>A::t('app', 'Link URL Tooltip'), 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('maxlength'=>'255', 'class'=>'large'), 'appendCode'=>$appendCode);
			$fields['module_code'] = array('type'=>'data', 'default'=>$moduleCode);
		}

		$fields['parent_id']  = array('type'=>'data', 'default'=>$parentId);
		$fields['sort_order'] = array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'3', 'class'=>'small'));
		$fields['is_active']  = array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'validation'=>array('type'=>'set', 'source'=>array(0,1)), 'viewType'=>'custom');
		
		if($parentId){
			$fields['placement'] = array('type'=>'data', 'default'=>'');
		}

		echo CWidget::create('CDataForm', array(
			'model'				=> 'FrontendMenus',
			'primaryKey'		=> $id,
			'operationType'		=> 'edit',
			'action'			=> 'frontendMenus/edit/id/'.$id.($parentId ? '/pid/'.$parentId : ''), 
			'successUrl'		=> 'frontendMenus/manage/pid/'.($parentId ? $parentId : '0'),
			'cancelUrl'			=> 'frontendMenus/manage'.($parentId ? '/pid/'.$parentId : ''),
			//'passParameters'  => true,
			'requiredFieldsAlert' => true,
			'method'			=> 'post',
			'messagesSource'	=> 'core',
			'return'			=> true,
			'htmlOptions'		=> array(
				'name'				=> 'frmMenuEdit',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert' => true,
			'fields'			=> $fields,
			'translationInfo' 	=> array('relation'=>array('id', 'menu_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'name' 				=> array('type'=>'textbox', 'title'=>A::t('app', 'Menu Title'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any'), 'htmlOptions'=>array('title'=>'', 'class'=>'middle')),
			),
			'buttons'			=> array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Menu').' '.$menuName),
            'return'            => true,
		));
	?>
	
	<div id="dialog" class="dialog-window" title="<?= $dialogTitle; ?>">
	<?= $dialogContent; ?>
	</div>

    </div>
</div>
