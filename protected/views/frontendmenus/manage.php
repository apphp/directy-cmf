<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Frontend Menu Management')));
	
	$this->_activeMenu = 'frontendMenus/';
    $breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/dashboard'),
    );
	if($parentId && $parentName != ''){
		$breadCrumbs[] = array('label'=>A::t('app', 'Frontend Menu'), 'url'=>'frontendMenus/manage');
		$breadCrumbs[] = array('label'=>A::t('app', $parentName));
	}else{
		$breadCrumbs[] = array('label'=>A::t('app', 'Frontend Menu'));
	}
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?= A::t('app', 'Frontend Menu Management'); ?></h1>	
	
<div class="bloc">
    <div class="title">
	<?php
		echo (($parentName != '') ? $parentName.' <a class="back-link" href="frontendMenus/manage">'.A::t('app', 'Back').'</a>' : A::t('app', 'Frontend Menu')); 
	?>
	</div>
    <div class="content">
    <?php 
    	echo $actionMessage;
		
		if($menuType == 'moduleblock'){
			echo CWidget::create('CMessage', array('warning', A::t('app', 'Adding submenus to menu type "module block" is forbidden!'), array('button'=>true)));			
		}else{
			if(Admins::hasPrivilege('frontend_menu', 'edit')){
				echo '<a href="frontendMenus/add'.($parentId ? '/pid/'.(int)$parentId : '').'" class="add-new">'.A::t('app', 'Add New').'</a>';
			}		
					
			echo CWidget::create('CGridView', array(
				'model'				=> 'FrontendMenus',
				'actionPath'		=> 'frontendMenus/manage'.($parentId ? '/pid/'.(int)$parentId : ''),
				'pagination'		=> array('enable'=>false),
				'condition'			=> 'parent_id='.(int)$parentId,
				'sorting'			=> true,
				'defaultOrder'		=> array('placement'=>'ASC', 'sort_order'=>'ASC'),
				'passParameters'	=> true,
			    'filters'			=> array(
			    	'placement' 		=> array('title'=>A::t('app', 'Display On'), 'type'=>'enum', 'operator'=>'=', 'width'=>'130px', 'source'=>$placementsFilterList, 'emptyOption'=>true),
			    	'module_code' 		=> array('title'=>A::t('app', 'Module'), 'type'=>'enum', 'operator'=>'=', 'width'=>'130px', 'source'=>$modulesFilterList, 'emptyOption'=>true),
			    ),
				'fields'			=> array(
					'menu_name'    	=> array('title'=>A::t('app', 'Menu Title'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
					'menu_type'    	=> array('title'=>A::t('app', 'Menu Type'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px'),
					'module_code'   => array('title'=>A::t('app', 'Module'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px'),
					'placement'		=> array('title'=>A::t('app', 'Display On'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$placementsList, 'width'=>'130px', 'disabled'=>($parentId == 0 ? false : true)),
					'access_level'	=> array('title'=>A::t('app', 'Access'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$accessLevelsList, 'width'=>'80px'),
					'sort_order'    => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px'),
					'is_active' 	=> array('title'=>A::t('app', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'frontendMenus/changeStatus/id/{id}'.($parentId != 0 ? '/pid/'.(int)$parentId : ''), 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
					'sub_menus_link'=> array('title'=>A::t('app', 'Sub-Menus'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px', 'isSortable'=>false, 'linkUrl'=>($parentId == 0 ? 'frontendMenus/manage/pid/{id}' : ''), 'linkText'=>($parentId == 0 ? A::t('app', 'Sub-Menus') : A::t('app', 'N/A')), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>($parentId == 0 ? '[ ' : ''), 'appendCode'=>($parentId == 0 ? ' ]' : ''), 'disabled'=>($parentId == 0 ? false : true)),
					'menu_id'    	=> array('title' => '', 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'45px', 'source'=>$subMenuCounts, 'definedValues'=>array(''=>'<span class="label-zerogray">0</span>'), 'isSortable'=>true, 'class' => 'left', 'prependCode'=>'<span class="label-lightgray">', 'appendCode'=>'</span>', 'disabled'=>($parentId == 0 ? false : true)),
					'id'    		=> array('title'=>A::t('app', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'40px'),
				),
				'actions'			=> array(
					'edit' => array(
						'disabled'	=> !Admins::hasPrivilege('frontend_menu', 'edit'),
						'link'		=> 'frontendMenus/edit/id/{id}'.($parentId ? '/pid/'.(int)$parentId : ''), 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
					),
					'delete' => array(
						'disabled'	=> !Admins::hasPrivilege('frontend_menu', 'edit'),
						'link'		=> 'frontendMenus/delete/id/{id}/pid/'.($parentId ? (int)$parentId : '0'), 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
					)
				),
			));			
		}		
    ?>
    </div>
</div>
