<?php
    $this->_activeMenu = 'frontendMenus/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/dashboard'),
    );
	if($parentId && $parentName != ''){
		$this->_breadCrumbs[] = array('label'=>A::t('app', 'Frontend Menu'), 'url'=>'frontendMenus/manage');
		$this->_breadCrumbs[] = array('label'=>A::t('app', $parentName), 'url'=>'frontendMenus/manage/pid/'.$parentId);
		unset($menuTypesList['moduleblock']);
	}else{
		$this->_breadCrumbs[] = array('label'=>A::t('app', 'Frontend Menu'), 'url'=>'frontendMenus/manage');
	}
	$this->_breadCrumbs[] = array('label'=>A::t('app', 'Edit Menu'));
	
	A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
	A::app()->getClientScript()->registerScriptFile('templates/backend/js/menu.js');
?>

<h1><?php echo A::t('app', 'Frontend Menu Management')?></h1>	

<div class="bloc">
	<div class="title"><?php echo A::t('app', 'Edit Menu'); ?></div>
    <div class="content">        
	<?php
		$fields = array();
		$fields['parent_name']  = array('type'=>'label', 'title'=>A::t('app', 'Parent'), 'definedValues'=>array(''=>$parentName));
		$fields['placement']    = array('type'=>'select', 'title'=>A::t('app', 'Display On'), 'data'=>$placementsList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($placementsList)));
		$fields['access_level'] = array('type'=>'select', 'title'=>A::t('app', 'Access'), 'data'=>$accessLevelsList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($accessLevelsList)));
		$fields['menu_type']    = array('type'=>'select', 'title'=>A::t('app', 'Menu Type'), 'data'=>$menuTypesList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($menuTypesList)), 'htmlOptions'=>array('onchange'=>'changeMenuType(this)'));

		$appendCode = '<a href="javascript:void(0)" onclick="$(\'#dialog\').dialog();" title="'.A::t('app', 'Set Link').'"><img style="margin-bottom:-7px;" src="js/vendors/jquery/images/set_link.png" alt="Set Link" /></a>';
		if($menuType == 'modulelink'){
			$fields['link_target'] = array('type'=>'hidden', 'default'=>'_self');
			$fields['link_url']    = array('type'=>'textbox', 'title'=>A::t('app', 'Link URL'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('readonly'=>true, 'maxlength'=>'255', 'class'=>'middle'), 'appendCode'=>$appendCode);
			$fields['module_code'] = array('type'=>'textbox', 'title'=>A::t('app', 'Module Code'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'mixed'), 'htmlOptions'=>array('readonly'=>true));
		}else if($menuType == 'moduleblock'){	
			$fields['link_target'] = array('type'=>'hidden', 'default'=>'');
			$fields['link_url']    = array('type'=>'textbox', 'title'=>A::t('app', 'Link URL'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('readonly'=>true, 'maxlength'=>'255', 'class'=>'middle'), 'appendCode'=>$appendCode);
			$fields['module_code'] = array('type'=>'textbox', 'title'=>A::t('app', 'Module Code'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'mixed'), 'htmlOptions'=>array('readonly'=>true));
		}else{
			$fields['link_target'] = array('type'=>'select', 'title'=>A::t('app', 'Link Target'), 'default'=>'', 'data'=>$linkTargetsList, 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($linkTargetsList)));
			$fields['link_url']    = array('type'=>'textbox', 'title'=>A::t('app', 'Link URL'), 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'any'), 'htmlOptions'=>array('maxlength'=>'255', 'class'=>'middle'), 'appendCode'=>$appendCode);
			$fields['module_code'] = array('type'=>'hidden', 'default'=>'');
		}

		$fields['parent_id']  = array('type'=>'hidden', 'default'=>$parentId, 'htmlOptions'=>array());
		$fields['sort_order'] = array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'3', 'class'=>'small'));
		
		if($parentId){
			$fields['placement'] = array('type'=>'hidden', 'default'=>'');
		}

		echo CWidget::create('CDataForm', array(
			'model'=>'FrontendMenus',
			'primaryKey'=>$id,
			'operationType'=>'edit',
			'action'=>'frontendMenus/edit/id/'.$id.($parentId ? '/pid/'.$parentId : ''), 
			'successUrl'=>'frontendMenus/manage/pid/'.($parentId ? $parentId : '0').'/msg/updated',
			'cancelUrl'=>'frontendMenus/manage'.($parentId ? '/pid/'.$parentId : ''),
			//'passParameters'=>true,
			'requiredFieldsAlert'=>true,
			'method'=>'post',
			'messagesSource'=>'core',
			'return'=>true,
			'htmlOptions'=>array(
				'name'=>'frmMenuEdit',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>$fields,
			'translationInfo' => array('relation'=>array('id', 'menu_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
			'translationFields' => array(
				'name' => array('type'=>'textbox', 'title'=>A::t('app', 'Menu Title'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any')),
			),
			'buttons'=>array(
			   'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'')),
			   'cancel' => array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
		));
	?>
	
	<div id="dialog" style="display:none;padding-bottom:10px;" title="<?php echo $dialogTitle; ?>">
	<?php echo $dialogContent; ?>
	</div>

    </div>
</div>
