<?php
    Website::setMetaTags(array('title'=>A::t('gallery', 'Gallery Albums Management')));

    $this->_activeMenu = 'galleryAlbums/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('gallery', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('gallery', 'Gallery'), 'url'=>$backendPath.'modules/settings/code/gallery'),
        array('label'=>A::t('gallery', 'Gallery Albums Management')),
    );    
?>

<h1><?= A::t('gallery', 'Gallery Albums Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('gallery_albums', 'add')){
	    	echo '<a href="galleryAlbums/add" class="add-new">'.A::t('gallery', 'Add Album').'</a>';
    	}

        echo CWidget::create('CGridView', array(
            'model'             => 'Modules\Gallery\Models\GalleryAlbums',
            'actionPath'        => 'galleryAlbums/manage',
            'condition'         => '',
            'defaultOrder'      => array('sort_order'=>'ASC'),
            'passParameters'    => true,
            'pagination'        => array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(),
            'fields'=>array(
                'album_title' => array('title'=>A::t('gallery', 'Album Title'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>true),
				'album_type'  => array('title'=>A::t('gallery', 'Type'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$albumTypes, 'width'=>'120px'),
				'sort_order'  => array('title'=>A::t('gallery', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px'),
				'is_active'	  => array('title'=>A::t('gallery', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'galleryAlbums/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
				'id'   		  => array('title'=>'ID', 'type'=>'label', 'align'=>'center', 'width'=>'20px', 'class'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''), 
				'album_items_link' => array('title'=>A::t('gallery', 'Album Items'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'isSortable'=>false, 'linkUrl'=>'galleryAlbumItems/manage/albumId/{id}', 'linkText'=>A::t('gallery', 'Items'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('gallery_albums', 'edit'),								
                    'link'=>'galleryAlbums/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('gallery', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('gallery_albums', 'delete'),
					'link'=>'galleryAlbums/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('gallery', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'=>true,
        ));
    ?>        
    </div>
</div>
