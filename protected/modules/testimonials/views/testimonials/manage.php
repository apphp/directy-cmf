<?php
    Website::setMetaTags(array('title'=>A::t('testimonials', 'Testimonials Management')));
	
	$this->_activeMenu = 'testimonials/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('testimonials', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('testimonials', 'Testimonials'), 'url'=>$backendPath.'modules/settings/code/testimonials'),
        array('label'=>A::t('testimonials', 'Testimonials Management')),
    );    
?>

<h1><?= A::t('testimonials', 'Testimonials Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
    	echo $actionMessage;

    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('testimonials', 'add')){
            echo '<a href="testimonials/add" class="add-new">'.A::t('testimonials', 'Add New').'</a>';
    	}
        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('testimonials', 'edit')){
			$isActive = array('title'=>A::t('testimonials', 'Active'), 'type'=>'link', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'testimonials/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('testimonials', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('testimonials', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('testimonials', 'Click to change status')));
        }else{
            $isActive = array('title'=>A::t('testimonials', 'Active'), 'type'=>'label', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('testimonials', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('testimonials', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::te('testimonials', 'Active')));
        }
		
		echo CWidget::create('CGridView', array(
			'model'         =>'Modules\Testimonials\Models\Testimonials',
			'actionPath'    =>'testimonials/manage',
			'condition'     =>'',
			'defaultOrder'  =>array('sort_order'=>'ASC'),
			'passParameters'=>true,
			'pagination'    =>array('enable'=>true, 'pageSize'=>20),
			'sorting'=>true,
			'filters'=>array(
				'author_name' => array('title'=>A::t('testimonials', 'Author'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'140px', 'maxLength'=>'255'),
				'author_email' => array('title'=>A::t('testimonials', 'Author Email'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'140px', 'maxLength'=>'255'),
			),
			'fields'=>array(
				'author_image'	 => array('title'=>A::t('testimonials', 'Image'), 'type'=>'image', 'align'=>'left', 'width'=>'35px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'assets/modules/testimonials/images/authors/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'28px', 'imageHeight'=>'28px', 'alt'=>'', 'showImageInfo'=>true),
				'author_name'    => array('title'=>A::t('testimonials', 'Author'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>true),
				'author_email'   => array('title'=>A::t('testimonials', 'Author Email'), 'type'=>'label', 'align'=>'', 'width'=>'230px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array()),
				'author_country' => array('title'=>A::t('testimonials', 'Country'), 'type'=>'label', 'align'=>'left', 'width'=>'160px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>true),
				'created_at'	 => array('title'=>A::t('testimonials', 'Date Added'), 'type'=>'datetime', 'align'=>'center', 'width'=>'140px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>$dateTimeFormat),
				'sort_order'     => array('title'=>A::t('testimonials', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px'),
				'is_active'      => $isActive,
			),
			'actions'=>array(
				'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('testimonials', 'edit'),								
					'link'=>'testimonials/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('testimonials', 'Edit this record')
				),
				'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('testimonials', 'delete'),
					'link'=>'testimonials/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('testimonials', 'Delete this record'), 'onDeleteAlert'=>true
				),
			),
			'return'=>true,
		));
        
    ?>        
    </div>
</div>
