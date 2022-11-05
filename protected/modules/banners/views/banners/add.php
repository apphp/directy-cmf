<?php
    Website::setMetaTags(array('title'=>A::t('banners', 'Add Banner')));

    $this->_activeMenu = 'banners/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('banners', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('banners', 'Banners'), 'url'=>$backendPath.'modules/settings/code/banners'),
        array('label'=>A::t('banners', 'Banners Management'), 'url'=>'banners/manage'),
        array('label'=>A::t('banners', 'Add Banners')),
    );
?>

<h1><?= A::t('banners', 'Banners Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
		
	<div class="sub-title"><?= A::t('banners', 'Add Banners'); ?></div>
    <div class="content">
    <?php
		echo CWidget::create('CDataForm', array(
            'model'         => '\Modules\Banners\Models\Banners',
            'operationType' => 'add',
            'action'        => 'banners/add/',
            'successUrl'    => 'banners/manage/',
            'cancelUrl'     => 'banners/manage/',
            'passParameters'=>false,
            'method'        => 'post',
            'htmlOptions'   => array(
                'name'=>'frmBannerAdd',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
				'image_file' => array(
					'type'          => 'imageupload',
					'title'         => A::t('banners', 'Image'),
					'validation'    => array('required'=>false, 'type'=>'image', 'maxSize'=>'990k', 'targetPath'=>'assets/modules/banners/images/items/', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>'a'.CHash::getRandomString(10)),
					'imageOptions'  => array('showImage'=>false),
                    'thumbnailOptions' => array('create'=>true, 'field'=>'image_file_thumb', 'width'=>'120', 'height'=>'90'),
					'deleteOptions' => array('showLink'=>false),
					'fileOptions'   => array('showAlways'=>false, 'class'=>'file', 'size'=>'25')
				),
				'link_url'   => array('type'=>'textbox', 'title'=>A::t('banners', 'Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'link'), 'htmlOptions'=>array('maxlength'=>'255', 'class'=>'large')),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('banners', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
                'is_active'  => array('type'=>'checkbox', 'title'=>A::t('banners', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
            ),
			'translationInfo' => array('relation'=>array('id', 'banner_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
			'translationFields' => array(
				'banner_title'  => array('type'=>'textbox', 'title'=>A::t('banners', 'Title'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255', 'class'=>'large')),
				'banner_text'   => array('type'=>'textarea', 'title'=>A::t('banners', 'Description'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048')),
				'banner_button' => array('type'=>'textbox', 'title'=>A::t('banners', 'Button Text'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50', 'class'=>'middle')),
			),
            'buttons'=>array(
               'submit'=>array('type'=>'submit', 'value'=>A::t('banners', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel'=>array('type'=>'button', 'value'=>A::t('banners', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource' 	=> 'core',
			'showAllErrors'     => false,
			'alerts'            => array('type'=>'flash', 'itemName'=>A::t('banners', 'Banner')),
			'return'            => true,
        ));        			
	?>  
    </div>
</div>