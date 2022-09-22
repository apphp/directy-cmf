<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Languages Management')));
	
	$this->_activeMenu = 'languages/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Language Settings'), 'url'=>'languages/'),
        array('label'=>A::t('app', 'Languages')),
    );    
?>

<h1><?= A::t('app', 'Languages Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Languages'); ?></div>
    <div class="content">
		<?php 
			echo $actionMessage; 
		    	
		    if(Admins::hasPrivilege('languages', 'edit')){
			    echo '<a href="languages/add" class="add-new">'.A::t('app', 'Add New').'</a>';
		    }
        
            echo CWidget::create('CGridView', array(
                'model'=>'Languages',
                'actionPath'=>'languages/manage',
                'defaultOrder'=>array('sort_order'=>'ASC'),
                'pagination'=>array('enable'=>true, 'pageSize'=>20),
                'sorting'=>true,
                'filters'=>array(),
                'fields'=>array(
                    'name_native' => array('title'=>A::t('app', 'Language Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
                    'icon'        => array('title'=>A::t('app', 'Icon'), 'type'=>'image', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/flags/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'16px', 'imageHeight'=>'', 'alt'=>'', 'showImageInfo'=>true),
                    'code'        => array('title'=>A::t('app', 'Code'), 'type'=>'label', 'class'=>'center upper-case', 'headerClass'=>'center', 'width'=>'100px'),
                	'direction'   => array('title'=>A::t('app', 'Direction'), 'type'=>'label', 'class'=>'center upper-case', 'headerClass'=>'center', 'width'=>'100px'),
                	'sort_order'  => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
                	'used_on'     => array('title'=>A::t('app', 'Used On'), 'type'=>'label', 'class'=>'center capitalize', 'headerClass'=>'center', 'width'=>'100px'),
                    'is_default'  => array('title'=>A::t('app', 'Default'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'100px'),
                    'is_active'   => array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'100px'),
                ),
				'actions'=>array(
					'edit'   => array(
						'disabled'=>!Admins::hasPrivilege('languages', 'edit'),
						'link'=>'languages/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
					),
					'delete' => array(
						'disabled'=>!Admins::hasPrivilege('languages', 'edit'),
						'link'=>'languages/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
					)
				),
            ));
        
        ?>
    
    </div>
</div>
