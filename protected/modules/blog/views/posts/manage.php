<?php
	Website::setMetaTags(array('title'=>A::t('blog', 'Posts Management')));

    $this->_activeMenu = 'posts/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('blog', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('blog', 'Blog'), 'url'=>$backendPath.'modules/settings/code/blog'),
        array('label'=>A::t('blog', 'Posts Management'), 'url'=>'posts/manage'),
    );    
?>

<h1><?= A::t('blog', 'Posts Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('posts', 'add')){
	    	echo '<a href="posts/add" class="add-new">'.A::t('blog', 'Add Post').'</a>';
    	}

        echo CWidget::create('CGridView', array(
            'model'=>'\Modules\Blog\Models\Posts',
            'actionPath'=>'posts/manage',
            'condition'=>'',
            'defaultOrder'=>array('id'=>'DESC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'post_header' => array('title'=>A::t('blog', 'Post Header'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'100px', 'maxLength'=>''),
                'created_at'  => array('title'=>A::t('blog', 'Date Created'), 'type'=>'datetime', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'', 'format'=>''),
            ),
            'fields'=>array(
				'post_header'   => array('title'=>A::t('blog', 'Post Header'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'stripTags'=>true),
                'created_by'    => array('title'=>A::t('blog', 'Created By'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'120px'),
                'created_at'    => array('title'=>A::t('blog', 'Date Created'), 'type'=>'datetime', 'align'=>'center', 'width'=>'170px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>$dateTimeFormat),
				'publish_status'=> array('title'=>A::t('blog', 'Published'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'posts/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('blog', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('blog', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('blog', 'Click to change status'))),
				'id'    		=> array('title'=>A::t('blog', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'50px'),
            ),
            'actions'=>array(
                'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('posts', 'edit'),								
					'link'=>'posts/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('blog', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('posts', 'delete'),
					'link'=>'posts/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('blog', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'=>true,
        ));

    ?>        
    </div>
</div>
