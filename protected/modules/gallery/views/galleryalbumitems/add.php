<?php
  /**
   * @var string $backendPath
   * @var int $albumId
   * @var string $albumLink
   * @var string $albumTitle
   * @var string $tabs
   */

	Website::setMetaTags(array('title'=>A::t('gallery', 'Add Album Item')));

    $this->_activeMenu = 'galleryAlbumItems/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('gallery', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('gallery', 'Gallery'), 'url'=>$backendPath.'modules/settings/code/gallery'),
        array('label'=>A::t('gallery', 'Gallery Albums'), 'url'=>'galleryAlbums/manage'),
        array('label'=>A::t('gallery', 'Gallery Album Items Management'), 'url'=>'galleryAlbumItems/manage/albumId/'.$albumId),
        array('label'=>A::t('gallery', 'Add Album Item')),
    );
?>
<h1><?= A::t('gallery', 'Gallery Album Items Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

	<div class="sub-title">
        <a class="sub-tab active" href="<?= $albumLink; ?>"><?= strip_tags($albumTitle); ?></a>
        <?= A::t('gallery', 'Add Album Item'); ?>
    </div>

    <div class="content">
    <?php			
        echo CWidget::create('CDataForm', array(
            'model'         => 'Modules\Gallery\Models\GalleryAlbumItems',
            'operationType' => 'add',
            'action'        => 'galleryAlbumItems/add/albumId/'.$albumId,
            'successUrl'    => 'galleryAlbumItems/manage/albumId/'.$albumId,
            'cancelUrl'     => 'galleryAlbumItems/manage/albumId/'.$albumId,
            'passParameters'=> false,
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmGalleryAlbumItemAdd',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'gallery_album_id' => array('type'=>'data', 'default'=>$albumId),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('gallery', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
				'item_file' => array(
					'type'          => 'imageupload',
					'title'         => A::t('gallery', 'Image'),
					'validation'    => array('required'=>true, 'type'=>'image', 'maxSize'=>'900k', 'targetPath'=>'assets/modules/gallery/images/items/', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>'a'.$albumId.'_'.CHash::getRandomString(10)),
					'imageOptions'  => array('showImage'=>false),
                    'thumbnailOptions' => array('create'=>true, 'field'=>'item_file_thumb', 'width'=>'120', 'height'=>'90'),
					'deleteOptions' => array('showLink'=>false),
					'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
				),
                'is_active'  => array('type'=>'checkbox', 'title'=>A::t('gallery', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
            ),
			'translationInfo' => array('relation'=>array('id', 'gallery_album_item_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'name' 	      => array('type'=>'textbox', 'title'=>A::t('gallery', 'Item Name'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
				'description' => array('type'=>'textarea', 'title'=>A::t('gallery', 'Item Description'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>1024), 'htmlOptions'=>array('maxLength'=>'1024')),
			),
            'buttons'=>array(
               'submit'=>array('type'=>'submit', 'value'=>A::t('gallery', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel'=>array('type'=>'button', 'value'=>A::t('gallery', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource' 	=> 'core',
			'showAllErrors'     => false,
			'alerts'            => array('type'=>'flash', 'itemName'=>A::t('gallery', 'Album Item')),
			'return'            => true,			
        ));        
    ?>        
    </div>
</div>
    