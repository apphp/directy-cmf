<?php
    Website::setMetaTags(array('title'=>A::t('gallery', 'Edit Album')));

    $this->_activeMenu = 'galleryAlbums/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('gallery', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('gallery', 'Gallery'), 'url'=>$backendPath.'modules/settings/code/gallery'),
        array('label'=>A::t('gallery', 'Gallery Albums Management'), 'url'=>'galleryAlbums/manage'),
        array('label'=>A::t('gallery', 'Edit Album')),
    );
?>

<h1><?= A::t('gallery', 'Gallery Albums Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

	<div class="sub-title"><?= A::t('gallery', 'Edit Album'); ?></div>
    <div class="content">
    <?php    
        echo CWidget::create('CDataForm', array(
            'model'             => 'Modules\Gallery\Models\GalleryAlbums',
            'primaryKey'        => $galleryAlbum->id,
            'operationType'     => 'edit',
            'action'            => 'galleryAlbums/edit/id/'.$galleryAlbum->id,
            'successUrl'        => 'galleryAlbums/manage',
            'cancelUrl'         => 'galleryAlbums/manage',
            'passParameters'    => false,
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmAlbumEdit',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'album_type' => array('type'=>'select', 'title'=>A::t('gallery', 'Album Type'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'source'=>array_keys($albumTypes)), 'data'=>$albumTypes, 'htmlOptions'=>array()),
                'is_active'  => array('type'=>'checkbox', 'title'=>A::t('gallery', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('gallery', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
            ),
			'translationInfo' => array('relation'=>array('id', 'gallery_album_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'title' 	  => array('type'=>'textbox', 'title'=>A::t('gallery', 'Album Title'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
				'description' => array('type'=>'textarea', 'title'=>A::t('gallery', 'Album Description'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>'1024')),
			),
            'buttons'=>array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('gallery', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('gallery', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('gallery', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource' 	=> 'core',
			'showAllErrors'     => false,
			'alerts'            => array('type'=>'flash', 'itemName'=>A::t('gallery', 'Gallery Album')),
			'return'            => true,			
        ));
    
    ?>
        
  </div>
</div>


    