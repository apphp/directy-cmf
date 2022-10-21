<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Backend Menu Management')));
	
    $this->_activeMenu = $backendPath.'backendMenus/';
    $breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard'),
    );
	if($parentId && $parentName != ''){
		$breadCrumbs[] = array('label'=>A::t('app', 'Backend Menu'), 'url'=>$backendPath.'backendMenus/manage');
		$breadCrumbs[] = array('label'=>A::t('app', $parentName));
	}else{
		$breadCrumbs[] = array('label'=>A::t('app', 'Backend Menu'));
	}
    $this->_breadCrumbs = $breadCrumbs;
?>

<h1><?= A::t('app', 'Backend Menu Management'); ?></h1>

<div class="bloc">
	<div class="title">
	<?php
		echo (($parentName != '') ? $parentName.' <a class="back-link" href="'.$backendPath.'backendMenus/manage">'.A::t('app', 'Back').'</a>' : A::t('app', 'Backend Menu'));
	?>
	</div>
    <div class="content">
	<?php 
		echo $actionMessage; 

		echo CWidget::create('CGridView', array(
			'model'=>'BackendMenus',
			'actionPath'=>$backendPath.'backendMenus/manage'.($parentId ? '/pid/'.(int)$parentId : ''),
			'pagination'=>array('enable'=>true, 'pageSize'=>30),
			'condition'=>'parent_id='.$parentId,
			'defaultOrder'=>array('sort_order'=>'ASC'),
			'sorting'=>true,
			'passParameters'=>true,
			'filters'=>array(),
			'fields'=>array(
				'icon'        	 => array('title'=>'', 'type'=>'image', 'align'=>'', 'width'=>'30px', 'class'=>'left', 'headerClass'=>'', 'isSortable'=>false, 'imagePath'=>'templates/backend/images/icons/', 'defaultImage'=>$parentIcon, 'imageWidth'=>'16px', 'imageHeight'=>'', 'alt'=>'', 'showImageInfo'=>true),
				'menu_name'    	 => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>''),
				'url'			 => array('title'=>A::t('app', 'URL'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'', 'disabled'=>(!$parentId ? true : false)),
				'is_system'      => array('title'=>A::t('app', 'System'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray badge-square">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('app', 'Yes').'</span>'), 'width'=>'110px'),
				'is_visible' 	 => array('title'=>A::t('app', 'Visible'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>$backendPath.'backendMenus/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change visibility'))),
				'sort_order'     => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'changeOrder'=>true),
				'sub_menus_link' => array('title'=>A::t('app', 'Sub-Menus'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'isSortable'=>false, 'linkUrl'=>($parentId == 0 ? $backendPath.'backendMenus/manage/pid/{id}' : ''), 'linkText'=>($parentId == 0 ? A::t('app', 'Sub-Menus') : A::t('app', 'N/A')), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>($parentId == 0 ? '[ ' : ''), 'appendCode'=>($parentId == 0 ? ' ]' : '')),
			),
			'actions'=>array(
				'edit'   => array(
					'disabled'=>!Admins::hasPrivilege('backend_menu', 'edit'),
					'link'=>$backendPath.'backendMenus/edit/id/{id}'.($parentId ? '/pid/'.(int)$parentId : ''), 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
				),
			),
		));
	?>
    </div>
</div>
