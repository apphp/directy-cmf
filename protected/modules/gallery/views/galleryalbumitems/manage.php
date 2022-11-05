<?php
    Website::setMetaTags(array('title'=>A::t('gallery', 'Gallery Album Items Management')));
	
	$this->_activeMenu = 'galleryAlbums/manage';    
    $this->_breadCrumbs = array(
        array('label'=>A::t('gallery', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('gallery', 'Gallery'), 'url'=>$backendPath.'modules/settings/code/gallery'),
        array('label'=>A::t('gallery', 'Gallery Albums'), 'url'=>'galleryAlbums/manage'),
        array('label'=>A::t('gallery', 'Gallery Album Items Management')),
    );    
?>
<h1><?= A::t('gallery', 'Gallery Album Items Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>
 
	<div class="sub-title">
        <a class="sub-tab active" href="<?= $albumLink; ?>"><?= strip_tags($albumTitle); ?></a>
    </div>
    
    <div class="content">
	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('gallery_albums', 'add')){
	    	echo '<a href="galleryAlbumItems/add/albumId/'.$albumId.'" class="add-new">'.A::t('gallery', 'Add Album Item').'</a>';
    	}

        echo CWidget::create('CGridView', array(
            'model'         => 'Modules\Gallery\Models\GalleryAlbumItems',
			'actionPath'    => 'galleryAlbumItems/manage/albumId/'.$albumId,
			'condition'     => CConfig::get('db.prefix').'gallery_album_items.gallery_album_id = '.(int)$albumId,
            'defaultOrder'  => array('sort_order'=>'ASC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'fields'=>array(
                'item_file_thumb' => array('title'=>'', 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'', 'isSortable'=>false, 'imagePath'=>'assets/modules/gallery/images/items/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'28px', 'imageHeight'=>'22px', 'alt'=>'', 'showImageInfo'=>true),
                'album_item_name' => array('title'=>A::t('gallery', 'Album Item'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'stripTags'=>true),
				'sort_order'      => array('title'=>A::t('gallery', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'90px'),
				'is_active'	  	  => array('title'=>A::t('gallery', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'galleryAlbumItems/changeStatus/albumId/'.$albumId.'/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('gallery_albums', 'edit'),								
                    'link'=>'galleryAlbumItems/edit/albumId/'.$albumId.'/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('gallery', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('gallery_albums', 'delete'),
					'link'=>'galleryAlbumItems/delete/albumId/'.$albumId.'/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('gallery', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'=>true,
        ));

    ?>        
    </div>
</div>