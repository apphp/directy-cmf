<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Social Networks Management')));

    $this->_activeMenu = 'socialNetworks/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Social Settings'), 'url'=>'socialNetworks/manage'),
        array('label'=>A::t('app', 'Social Networks Management'))
    );

    $spinnersCount = 0;
?>

<h1><?= A::t('app', 'Social Networks Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;
        $fields = array(
            'icon'         => array('title'=>A::t('app', 'Icon'), 'type'=>'image', 'align'=>'', 'width'=>'40px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'imagePath'=>'images/social_networks/', 'defaultImage'=>'no_icon.png', 'imageHeight'=>'24px', 'alt'=>'', 'showImageInfo'=>true),
            'name'         => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'align'=>'', 'width'=>'150px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'htmlOptions'=>array()),
            'link'         => array('title'=>A::t('app', 'Link'), 'type'=>'link', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'linkUrl'=>'{link}', 'definedValues'=>array(), 'linkText'=>'{link}', 'htmlOptions'=>array('target'=>'_blank')),
            'is_active'    => array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array(0=>'<span class="label-gray">'.A::t('app', 'No').'</span>', 1=>'<span class="label-green">'.A::t('app', 'Yes').'</span>')),
            'sort_order'   => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'80px')
        );

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('social_networks', 'edit')){
            echo '<a href="socialNetworks/add" class="add-new">'.A::t('app', 'Add Social Network').'</a>';
            $fields['is_active'] = array('title'=>A::t('app', 'Active'), 'type'=>'link', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'socialNetworks/changeNetworkStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status')));
        }

        CWidget::create('CGridView', array(
            'model'=>'SocialNetworks',
            'actionPath'=>'socialNetworks/add',
            'condition'=>'',
            'passParameters'=>true,
            'defaultOrder'=>array('sort_order'=>'ASC'),
            'pagination'=>array('enable'=>true, 'pageSize'=>20),
            'sorting'=>true,
            'filters'=>array(
            ),
            'fields'=>$fields,
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('social_networks', 'edit'),
                    'link'=>'socialNetworks/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
                ),
                'delete'=>array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('social_networks', 'delete'),
                    'link'=>'socialNetworks/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return'=>false,
        ));
    ?>
    </div>
</div>
