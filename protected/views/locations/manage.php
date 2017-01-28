<?php
    $this->_activeMenu = 'locations/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Locations')),
    );    
?>

<h1><?php echo A::t('app', 'Locations Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Countries'); ?></div>
    <div class="content">
	<?php 
		echo $actionMessage; 
			
		if(Admins::hasPrivilege('locations', 'edit')){
			echo '<a href="locations/add" class="add-new">'.A::t('app', 'Add New').'</a>';		    
		}
   
		echo CWidget::create('CGridView', array(
			'model'=>'Countries',
			'actionPath'=>'locations/manage',
			'defaultOrder'=>array('sort_order'=>'DESC', 'name'=>'ASC'),
			'passParameters'=>true,
			'pagination'=>array('enable'=>true, 'pageSize'=>20),
			'sorting'=>true,
			'filters'=>array(
				'name'           => array('title'=>A::t('app', 'Name'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'140px', 'maxLength'=>''),
				'code'           => array('title'=>A::t('app', 'Code'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'60px', 'maxLength'=>'2'),
			),
			'fields'=>array(
				'country_name'   => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
				'code'           => array('title'=>A::t('app', 'Code'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px'),
				'sort_order'     => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px'),
				'is_default'     => array('title'=>A::t('app', 'Default'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'110px'),
				'is_active'      => array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'110px'),
				'sub_locations_link' => array('title'=>A::t('app', 'Sub-Locations'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'isSortable'=>false, 'linkUrl'=>'subLocations/manage/country/{id}', 'linkText'=>A::t('app', 'States'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
			),
			'actions'=>array(
				'edit'   => array(
					'disabled'=>!Admins::hasPrivilege('locations', 'edit'),
					'link'=>'locations/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
				),
				'delete' => array(
					'disabled'=>!Admins::hasPrivilege('locations', 'edit'),
					'link'=>'locations/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
				)
			),
		));
	?>        
    </div>
</div>
        
