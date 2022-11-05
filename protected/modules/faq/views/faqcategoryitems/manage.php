<?php
    Website::setMetaTags(array('title'=>A::t('faq', 'FAQ Category Items Management')));

    $this->_activeMenu = 'faqCategories/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('faq', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('faq', 'FAQ'), 'url'=>$backendPath.'modules/settings/code/faq'),
        array('label'=>A::t('faq', 'FAQ Management'), 'url'=>'faqCategories/manage'),
        array('label'=>A::t('faq', 'FAQ Category Items Management')),
    );
?>
<h1><?= A::t('faq', 'FAQ Category Items Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title">
        <a class="sub-tab active" href="<?= $categoryLink; ?>"><?= strip_tags($categoryName); ?></a>
    </div>

    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('faq', 'add')){
            echo '<a href="faqCategoryItems/add/catId/'.$catId.'"  class="add-new">'.A::t('faq', 'Add New').'</a>';
        }

        echo CWidget::create('CGridView', array(
            'model'         => '\Modules\Faq\Models\FaqCategoryItems',
            'actionPath'    => 'faqCategoryItems/manage/catId/'.$catId,
            'condition'     => CConfig::get('db.prefix').'faq_category_items.faq_category_id = '.(int)$catId,
            'defaultOrder'  => array('sort_order'=>'ASC'),
            'passParameters'=> true,
            'pagination'    => array('enable'=>true, 'pageSize'=>10),
            'sorting'=>true,
            'fields'=>array(
                'faq_question'  => array('title'=>A::t('faq', 'Question'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'', 'stripTags'=>true),
                'is_active'     => array('title'=>A::t('faq', 'Active'), 'type'=>'enum', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="badge-red">'.A::t('faq', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('faq', 'Yes').'</span>')),
                'is_active'     => array('title'=>A::t('faq', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'faqCategoryItems/changeStatus/catId/'.$catId.'/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
                'sort_order'    => array('title'=>A::t('faq', 'Order'), 'type'=>'label', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('faq', 'edit'),
                    'link'=>'faqCategoryItems/edit/catId/'.$catId.'/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('faq', 'Edit this record')
                ),
                'delete'  => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('faq', 'delete'),
                    'link'=>'faqCategoryItems/delete/catId/'.$catId.'/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('faq', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>true
        ));
    ?>
    </div>
</div>

