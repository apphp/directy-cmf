<?php
	Website::setMetaTags(array('title'=>A::t('testimonials', 'Edit Testimonials')));

    $this->_pageTitle = A::t('testimonials', 'Testimonials Management').' - '.A::t('testimonials', 'Edit Testimonials').' | '.CConfig::get('name');
    $this->_activeMenu = 'testimonials/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('testimonials', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('testimonials', 'Testimonials'), 'url'=>$backendPath.'modules/settings/code/testimonials'),
        array('label'=>A::t('testimonials', 'Testimonials Management'), 'url'=>'testimonials/manage'),
        array('label'=>A::t('testimonials', 'Edit Testimonials')),
    );
?>

<h1><?= A::t('testimonials', 'Testimonials Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

	<div class="sub-title"><?= A::t('testimonials', 'Edit Testimonials'); ?></div>
    <div class="content">
    <?php
    
        echo CWidget::create('CDataForm', array(
            'model'         =>'Modules\Testimonials\Models\Testimonials',
            'primaryKey'    =>$testimonial->id,
            'operationType' =>'edit',
            'action'        =>'testimonials/edit/id/'.$testimonial->id,
            'successUrl'    =>'testimonials/manage',
            'cancelUrl'     =>'testimonials/manage',
            'passParameters'=>false,
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmTestimonialsEdit',
                'enctype'=>'multipart/form-data',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'author_name'     => array('type'=>'textbox', 'title'=>A::t('testimonials', 'Author'),   'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50')),
                'author_email'    => array('type'=>'textbox', 'title'=>A::t('testimonials', 'Author Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email'), 'htmlOptions'=>array('maxLength'=>'100')),					
                'author_country'  => array('type'=>'textbox', 'title'=>A::t('testimonials', 'Country'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50')),
                'author_city'     => array('type'=>'textbox', 'title'=>A::t('testimonials', 'City'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50')),
                'author_company'  => array('type'=>'textbox', 'title'=>A::t('testimonials', 'Company'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('maxLength'=>'125', 'class'=>'middle')),
                'author_position' => array('type'=>'textbox', 'title'=>A::t('testimonials', 'Position'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('maxLength'=>'125', 'class'=>'middle')),						
				'author_image' => array(
					'type'          	=> 'imageupload',
					'title'         	=> A::t('testimonials', 'Image'),
					'validation'    	=> array('required'=>false, 'type'=>'image', 'maxSize'=>'200k', 'targetPath'=>'assets/modules/testimonials/images/authors/', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>'author_'.CHash::getRandomString(10)),
					'imageOptions'  	=> array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'showImageDimensions'=>true, 'imageClass'=>'icon-big'),
                    'thumbnailOptions' 	=> array('create'=>false, 'field'=>'image_file_thumb', 'width'=>'100', 'height'=>'100'),
					'deleteOptions' 	=> array('showLink'=>true, 'linkUrl'=>'testimonials/edit/id/'.$testimonial->id.'/image/delete', 'linkText'=>A::t('testimonials', 'Delete')),
					'fileOptions'   	=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/testimonials/images/authors/')
				),
                'testimonial_text'=> array('type'=>'textarea', 'title'=>A::t('testimonials', 'Text'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048', 'class'=>'xlarge')),
                'is_active'       => array('type'=>'checkbox', 'title'=>A::t('testimonials', 'Active'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'sort_order'      => array('type'=>'textbox', 'title'=>A::t('testimonials', 'Sort Order'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
            ),
            'buttons'=>array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('testimonials', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'showAllErrors'		=> false,
            'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('testimonials', 'Testimonial')),
            'messagesSource'	=> 'core',
            'return'			=> true,
        ));
    
    ?>
        
  </div>
</div>


    