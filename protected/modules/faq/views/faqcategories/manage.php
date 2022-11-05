<?php
    Website::setMetaTags(array('title'=>A::t('faq', 'FAQ Management')));

    $this->_activeMenu = 'faqCategories/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('faq', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('faq', 'FAQ'), 'url'=>$backendPath.'modules/settings/code/faq'),
        array('label'=>A::t('faq', 'FAQ Categories Management'), 'url'=>'faqCategories/manage'),
    );
?>
<h1><?= A::t('faq', 'FAQ Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
	
    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('faq', 'add')){
            echo '<a href="faqCategories/add" class="add-new">'.A::t('faq', 'Add New').'</a>';
        }

        echo CWidget::create('CGridView', array(
            'model'         => '\Modules\Faq\Models\FaqCategories',
            'actionPath'    => 'faqCategories/manage',
            'condition'     => '',
            'defaultOrder'  => array('sort_order'=>'ASC'),
            'passParameters'=>true,
            'pagination'=>array('enable'=>true, 'pageSize'=>14),
            'sorting'=>true,
            'fields'=>array(
                'category_name'  => array('title'=>A::t('faq', 'Category'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'', 'stripTags'=>true),
                'sort_order'     => array('title'=>A::t('faq', 'Order'), 'type'=>'label', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
				'is_active' 	 => array('title'=>A::t('faq', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'faqCategories/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
                'questions_link' => array('title'=>A::t('faq', 'Items'), 'type'=>'link', 'align'=>'', 'width'=>'120px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'definedValues'=>array(),  'linkUrl'=>'faqCategoryItems/manage/catId/{id}', 'linkText'=>A::t('faq', 'Questions'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'questions_count' => array('title'=>A::t('faq', 'Items Count'), 'type'=>'label', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(),),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('faq', 'edit'),
                    'link'=>'faqCategories/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('faq', 'Edit this record')
                ),
                'delete'  => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('faq', 'delete'),
                    'link'=>'faqCategories/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('faq', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>true,
        ));

    ?>
    </div>
</div>
