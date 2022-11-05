<?php
	Website::setMetaTags(array('title'=>A::t('faq', 'Add FAQ Category')));

    $this->_activeMenu = 'faqCategories/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('faq', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('faq', 'FAQ'), 'url'=>$backendPath.'modules/settings/code/faq'),
        array('label'=>A::t('faq', 'FAQ Management'), 'url'=>'faqCategories/manage'),
        array('label'=>A::t('faq', 'Add FAQ Category')),
    );
?>
<h1><?= '» '.A::t('faq', 'FAQ Cateogry Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title"><?= A::t('faq', 'Add FAQ Category'); ?></div>
    <div class="content">
        <?php
        echo CWidget::create('CDataForm', array(
            'model'         => '\Modules\Faq\Models\FaqCategories',
            'operationType' => 'add',
            'action'        => 'faqCategories/add/',
            'successUrl'    => 'faqCategories/manage',
            'cancelUrl'     => 'faqCategories/manage',
            'passParameters'=> false,
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmCategoryAdd',
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'is_active'  => array('type'=>'checkbox', 'title'=>A::t('faq', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('faq', 'Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
            ),
            'translationInfo' => array('relation'=>array('id', 'faq_category_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
            'translationFields' => array(
                'category_name' => array('type'=>'textbox', 'title'=>A::t('faq', 'FAQ Category'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255', 'class'=>'middle')),
            ),
            'buttons'=>array(
                'submit'=>array('type'=>'submit', 'value'=>A::t('faq', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel'=>array('type'=>'button', 'value'=>A::t('faq', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource' 	=> 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('faq', 'FAQ Category')),
            'return'            => true,
        ));
    ?>
    </div>
</div>
