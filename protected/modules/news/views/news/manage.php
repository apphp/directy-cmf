<?php
	Website::setMetaTags(array('title'=>A::t('news', 'News Management')));

    $this->_activeMenu = 'news/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('news', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('news', 'News'), 'url'=>$backendPath.'modules/settings/code/news'),
        array('label'=>A::t('news', 'News Management')),
    );    
?>

<h1><?= A::t('news', 'News Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('news', 'add')){
	    	echo '<a href="news/add" class="add-new">'.A::t('news', 'Add News').'</a>';
    	}

        echo CWidget::create('CGridView', array(
            'model'         => '\Modules\News\Models\News',
            'actionPath'    => 'news/manage',
            'condition'     => '',
            'defaultOrder'  => array('created_at'=>'DESC'),
            'passParameters'=> true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
                'news_header' => array('title'=>A::t('news', 'Header'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>''),
                'created_at'  => array('title'=>A::t('news', 'Date'), 'type'=>'datetime', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'10'),
            ),
            'fields'=>array(
                'intro_image'  => array('title'=>A::t('news', 'Intro Image'), 'type'=>'image', 'align'=>'', 'width'=>'90px', 'class'=>'left', 'headerClass'=>'', 'isSortable'=>false, 'imagePath'=>'assets/modules/news/images/intro_images/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'60px', 'imageHeight'=>'22px', 'alt'=>'', 'showImageInfo'=>true),
                'created_at'   => array('title'=>A::t('news', 'Date Created'), 'type'=>'datetime', 'align'=>'', 'width'=>'150px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>$dateTimeFormat),
                'news_header'  => array('title'=>A::t('news', 'Header'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>true),
				'is_published' => array('title'=>A::t('news', 'Published'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'news/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('news', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('news', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('news', 'Click to change status'))),
            ),
            'actions'=>array(
                'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('news', 'edit'),								
					'link'=>'news/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('news', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('news', 'delete'),
					'link'=>'news/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('news', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'=>true,
        ));

    ?>        
    </div>
</div>
