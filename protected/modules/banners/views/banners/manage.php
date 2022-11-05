<?php
    Website::setMetaTags(array('title'=>A::t('banners', 'Banners Management')));
	
	$this->_activeMenu = 'banners/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('banners', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('banners', 'Banners'), 'url'=>$backendPath.'modules/settings/code/banners'),
        array('label'=>A::t('banners', 'Banners Management'), 'url'=>'banners/manage'),
    );    
?>

<h1><?= A::t('banners', 'Banners Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
        echo $actionMessage;

    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('banners', 'add')){
	    	echo '<a href="banners/add" class="add-new">'.A::t('banners', 'Add Banner').'</a>';
    	}
		
		echo CWidget::create('CGridView', array(
			'model'=>'\Modules\Banners\Models\Banners',
			'actionPath'=>'banners/manage',
			'condition'=>'',
			'passParameters'=>false,
			'pagination'=>array('enable'=>true, 'pageSize'=>20),
			'sorting'=>true,
			'filters'=>array(),
			'fields'=>array(
				'image_file_thumb' 	=> array('title'=>A::t('banners', 'Image'), 'type'=>'image', 'align'=>'left', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'assets/modules/banners/images/items/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'60px', 'imageHeight'=>'24px', 'alt'=>'', 'showImageInfo'=>true),
				'banner_title' 		=> array('title'=>A::t('banners', 'Title'), 'type'=>'label', 'align'=>'left', 'width'=>'250px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>false),
				//'banner_description' => array('title'=>A::t('banners', 'Description'), 'type'=>'label', 'align'=>'left', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>false),
				'link_url'   		=> array('type'=>'textbox', 'title'=>A::t('banners', 'Link'), 'tooltip'=>'', 'align'=>'left', 'width'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'link'), 'htmlOptions'=>array('maxlength'=>'255')),
				'sort_order' 		=> array('title'=>A::t('banners', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'120px'),
				'is_active'			=> array('title'=>A::t('banners', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'banners/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('banners', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('banners', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
			),
			'actions'=>array(
				'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('banners', 'edit'),	
					'link'=>'banners/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('banners','Edit this banner')
				),
				'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('banners', 'delete'),	
					'link'=>'banners/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('banners','Delete this banner'), 'onDeleteAlert'=>true
				),
			),
			'return'=>true,
		));
    ?>        
    </div>
</div>
