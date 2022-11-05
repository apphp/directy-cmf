<?php
    Website::setMetaTags(array('title'=>A::t('faq', 'Edit Category Item')));

    $this->_activeMenu = 'faqCategories/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('faq', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('faq', 'FAQ'), 'url'=>$backendPath.'modules/settings/code/faq'),
        array('label'=>A::t('faq', 'FAQ Management'), 'url'=>'faqCategories/manage'),
        array('label'=>A::t('faq', 'FAQ Category Items Management'), 'url'=>'faqCategoryItems/manage/catId/'.$catId),
        array('label'=>A::t('faq', 'Edit Category Item')),
    );

    A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js');
    A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js');
    A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css');

    $languages = Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'));
?>
<h1><?= A::t('faq', 'FAQ Category Items Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title">
        <a class="sub-tab active" href="<?= $categoryLink; ?>"><?= strip_tags($categoryName); ?></a>
        <?= '» '.A::t('faq', 'Edit Category Item'); ?>
    </div>

    <div class="content">
    <?php
        $formName = 'frmFaqCategoryItemEdit';
        echo CWidget::create('CDataForm', array(
            'model'         => '\Modules\Faq\Models\FaqCategoryItems',
            'primaryKey'    => $faqCategoryItem->id,
            'operationType' => 'edit',
            'action'        => 'faqCategoryItems/edit/catId/'.$catId.'/id/'.$faqCategoryItem->id,
            'successUrl'    => 'faqCategoryItems/manage/catId/'.$catId,
            'cancelUrl'     => 'faqCategoryItems/manage/catId/'.$catId,
            'passParameters'=>false,
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>$formName,
                'autoGenerateId'=>true
            ),
            'requiredFieldsAlert'=>true,
            'fields'=>array(
                'faq_category_id' => array('type'=>'data', 'default'=>$catId),
                'is_active'  => array('type'=>'checkbox', 'title'=>A::t('faq', 'Active'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'sort_order' => array('type'=>'textbox', 'title'=>A::t('faq', 'Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),
            ),
            'translationInfo' => array('relation'=>array('id', 'faq_category_item_id'), 'languages'=>$languages),
            'translationFields' => array(
                'faq_question' => array('type'=>'textarea', 'title'=>A::t('faq', 'Question'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>512), 'htmlOptions'=>array('maxLength'=>'512')),
                'faq_answer' => array('type'=>'textarea', 'title'=>A::t('faq', 'Answer'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048')),
            ),
            'buttons'=>array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('faq', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate' => array('type'=>'submit', 'value'=>A::t('faq', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel' => array('type'=>'button', 'value'=>A::t('faq', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource' 	=> 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('faq', 'FAQ Category Item')),
            'return'            => true,
        ));
    ?>
    </div>
</div>
<?php
foreach($languages as $lang){
    A::app()->getClientScript()->registerScript('setTinyMceEditor_'.$lang['code'], 'setEditor("'.$formName.'_faq_answer_'.$lang['code'].'",'.($errorField == 'faq_answer_'.$lang['code'] ? 'true' : 'false').', "simplest");', 2);
}

