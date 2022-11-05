<?php
    Website::setMetaTags(array('title'=>A::t('advertisements', 'Add Advertisement')));

    $this->_activeMenu = $activeMenu;
    $this->_breadCrumbs = array(
        array('label'=>A::t('advertisements', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('advertisements', 'Advertisement Module'), 'url'=>$backendPath.'modules/settings/code/advertisements'),
        array('label'=>A::t('advertisements', 'Advertisements Management'), 'url'=>'advertisements/manage'),
        array('label'=>A::t('advertisements', 'Add Advertisement')),
    );
?>

<h1><?= A::t('advertisements', 'Advertisements Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title"><?= A::t('advertisements', 'Add Advertisement'); ?></div>

    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
            'model'             => '\Modules\Advertisements\Models\Advertisements',
            'operationType'     => 'add',
            'action'            => 'advertisements/add/',
            'successUrl'        => 'advertisements/manage/',
            'cancelUrl'         => 'advertisements/manage/',
            'passParameters'    => false,
            'method'            => 'post',
            'htmlOptions'       => array(
                'id'             => 'frmAdvertisementAdd',
                'name'           => 'frmAdvertisementAdd',
                'enctype'        => 'multipart/form-data',
                'autoGenerateId' => true
            ),
            'requiredFieldsAlert' => true,
            'fields'            => array(
                'separatorBasicData' => array(
                    'separatorInfo' => array('legend'=>A::t('advertisements', 'Information on Basic Data')),
                    'url'           => array('type'=>'textbox', 'title'=>A::t('advertisements', 'Url'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'url', 'maxLength'=>'125'), 'htmlOptions'=>array('maxLength'=>'125', 'class'=>'large', 'placeholder'=>'http://')),
                    'image'         => array(
                        'type'              => 'imageUpload',
                        'title'             => A::t('advertisements', 'Image'),
                        'validation'        => array('required'=>false, 'type'=>'image', 'targetPath'=>'assets/modules/advertisements/images/items/', 'maxSize'=>'2M', 'maxWidth'=>'1200px', 'maxHeight'=>'900px', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>CHash::getRandomString(15), 'filePrefix'=>'', 'filePostfix'=>'', 'htmlOptions'=>array()),
                        'deleteOptions'     => array('showLink'=>false),
                        'imageOptions'      => array('showImage'=>true, 'showImageName'=>true, 'showImageSize'=>true, 'showImageDimensions'=>true, 'imageClass'=>'icon-xbig'),
                        'fileOptions'       => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/advertisements/images/items/')
                    ),
                    'width'            => array('type'=>'text', 'title'=>A::t('advertisements', 'Width'), 'default'=>'', 'tooltip'=>A::te('advertisements', 'Size can be written in the px, em, pt or %'), 'validation'=>array('required'=>false, 'type'=>'htmlSize'), 'htmlOptions'=>array('maxLength'=>'5', 'class'=>'small')),
                    'height'           => array('type'=>'text', 'title'=>A::t('advertisements', 'Height'), 'default'=>'', 'tooltip'=>A::te('advertisements', 'Size can be written in the px, em, pt or %'), 'validation'=>array('required'=>false, 'type'=>'htmlSize'), 'htmlOptions'=>array('maxLength'=>'5', 'class'=>'small')),
                    'color_text'       => array('type'=>'color', 'title'=>A::t('advertisements', 'Color Text'), 'default'=>'#000000', 'validation'=>array('required'=>false, 'type'=>'hexColor'), 'htmlOptions'=>array()),
                    'background_color' => array('type'=>'color', 'title'=>A::t('advertisements', 'Background Color'), 'default'=>'#FFFFFF', 'validation'=>array('required'=>false, 'type'=>'hexColor'), 'htmlOptions'=>array()),
                    'title'            => array('type'=>'textbox', 'title'=>A::t('advertisements', 'Title'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>'70'), 'htmlOptions'=>array('maxLength'=>'70', 'class'=>'large')),
                    'text'             => array('type'=>'textarea', 'title'=>A::t('advertisements', 'Text'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>'512'), 'htmlOptions'=>array('maxLength'=>'512')),
                ),
                'separatorComparison' => array(
                    'separatorInfo'     => array('legend'=>A::t('advertisements', 'Where to Show It')),
                    'type_comparison'   => array('type'=>'select', 'title'=>A::t('advertisements', 'Type Comparison'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($arrTypeComparison)), 'data'=>$arrTypeComparison, 'viewType'=>'dropdownlist'),
                    'instruction'       => array('type'=>'textbox', 'title'=>A::t('advertisements', 'URL / Part URL'), 'tooltip'=>A::te('advertisements', 'Leave this field empty if you want to, this advertisement show on all pages'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>'255'), 'htmlOptions'=>array('maxLength'=>'255', 'class'=>'large')),
                ),
                'separatorOther' => array(
                    'separatorInfo' => array('legend'=>A::t('advertisements', 'Other')),
                    'sort_order'    => array('type'=>'textbox', 'title'=>A::t('advertisements', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>false, 'type'=>'int', 'int'=>'6'), 'htmlOptions'=>array('maxLength'=>'6', 'class'=>'small')),
                    'is_active'     => array('type'=>'checkbox', 'title'=>A::t('advertisements', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0, 1)), 'viewType'=>'custom'),
                )
            ),
            'buttons'           => array('submit'=>array('type'=>'submit', 'value'=>A::t('advertisements', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel'             => array('type'=>'button', 'value'=>A::t('advertisements', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'    => 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('advertisements', 'Advertisement')),
            'return'            => true,
        ));
    ?>
    </div>
</div>
