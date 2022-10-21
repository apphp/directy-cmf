<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Social Login Management')));

    $this->_activeMenu = $backendPath.'socialNetworks/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard/'),
        array('label'=>A::t('app', 'Social Settings'), 'url'=>$backendPath.'socialNetworks/manage'),
        array('label'=>A::t('app', 'Social Login Management'))
    );
?>

<h1><?= A::t('app', 'Social Login Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="content">
    <?php
        echo $actionMessage;

        $fields = array();

        echo CWidget::create('CGridView', array(
            'model'         => 'SocialNetworksLogin',
            'actionPath'    => $backendPath.'socialNetworks/login/',
            'condition'     => '',
            'passParameters'=> true,
            'defaultOrder'  => array('sort_order'=>'ASC'),
            'pagination'    => array('enable'=>true, 'pageSize'=>20),
            'sorting'       => true,
            'fields'=>array(
                'name' => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'align'=>'', 'width'=>'190px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'application_id' => array('title'=>A::t('app', 'Application/Client ID'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false),
                'sort_order' => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'80px'),
                'is_active' => array('title'=>A::t('app', 'Active'), 'type'=>'link', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>$backendPath.'socialNetworks/changeLoginStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
            ),
            'actions'=>array(
                'edit' => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('social_networks', 'edit'),
                    'link'=>$backendPath.'socialNetworks/loginEdit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
                )
            ),
            'return'=>true,
        ));
    ?>
    </div>
</div>
