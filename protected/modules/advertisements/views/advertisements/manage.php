<?php
    Website::setMetaTags(array('title'=>A::t('advertisements', 'Advertisements Management')));

    $this->_activeMenu = $activeMenu;
    $this->_breadCrumbs = array(
        array('label'=>A::t('advertisements', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('advertisements', 'Advertisement Module'), 'url'=>$backendPath.'modules/settings/code/advertisements'),
        array('label'=>A::t('advertisements', 'Advertisements Management'), 'url'=>'advertisements/manage'),
    );
?>

<h1><?= A::t('advertisements', 'Advertisements Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="content">
		<?php
            echo $actionMessage;

            if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('advertisement', 'add')){
                echo '<a href="advertisements/add" class="add-new">' . A::t('advertisements', 'Add Advertisement') . '</a>';
            }

            if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('advertisement', 'edit')){
                $isActive = array('title'=>A::t('advertisements', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'advertisements/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">' . A::t('advertisements', 'No') . '</span>', '1'=>'<span class="badge-green">' . A::t('advertisements', 'Yes') . '</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('advertisements', 'Click to change status')));
            }else{
                $isActive = array('title'=>A::t('advertisements', 'Active'), 'type'=>'enum', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="badge-red">'.A::t('advertisements', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('advertisements', 'Yes').'</span>'));
            }

            echo CWidget::create('CGridView', array(
                'model'             => '\Modules\Advertisements\Models\Advertisements',
                'actionPath'        => 'advertisements/manage',
                'passParameters'    => false,
                'defaultOrder'      => array('sort_order'=>'ASC'),
                'pagination'        => array('enable'=>true, 'pageSize'=>20),
                'sorting'           => true,
                'filters'           => array(),
                'fields'            => array(
                    'image'             => array('title'=>A::t('advertisements', 'Image'), 'type'=>'image', 'align'=>'', 'width'=>'35px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'assets/modules/advertisements/images/items/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'24px', 'imageHeight'=>'24px', 'alt'=>'', 'showImageInfo'=>true),
                    'title'             => array('title'=>A::t('advertisements', 'Title'), 'type'=>'label', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'maxLength'=>'80'),
                    'type_comparison'   => array('title'=>A::t('advertisements', 'Comparison'), 'type'=>'enum', 'width'=>'100px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'source'=>array('0'=>A::t('advertisements', 'Equally'), '1'=>A::t('advertisements', 'Substring'))),
                    'instruction'       => array('title'=>A::t('advertisements', 'URL / Part URL'), 'type'=>'label', 'width'=>'180px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'definedValues'=>array(''=>'- '.A::t('advertisements', 'Show on all pages').' -'), 'maxLength'=>'50'),
                    'background_color'  => array('title'=>A::t('advertisements', 'Background'), 'type'=>'label', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'prependCode'=>'<div style="display:inline-block;border-radius:9px;border:1px solid #ccc;width:18px;height:18px;background-color:', 'appendCode'=>'"></div>'),
                    'color_text'        => array('title'=>A::t('advertisements', 'Color Text'), 'type'=>'label', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'prependCode'=>'<div style="display:inline-block;border-radius:9px;border:1px solid #ccc;width:18px;height:18px;background-color:', 'appendCode'=>'"></div>'),
                    'created_at'        => array('title'=>A::t('advertisements', 'Created At'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'width'=>'120px', 'definedValues'=>array(null=>A::t('advertisements', 'Not defined')), 'format'=>$dateFormat),
                    'expires_at'        => array('title'=>A::t('advertisements', 'Expires At'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'width'=>'120px', 'definedValues'=>array(''=>A::t('advertisements', 'Not defined'), null=>A::t('advertisements', 'Not defined')), 'format'=>$dateFormat),
                    'id'                => array('title'=>A::t('advertisements', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'30px', 'isSortable'=>true),
                    'sort_order'        => array('title'=>A::t('advertisements', 'Sort Order'), 'type'=>'label', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                    'is_active'         => $isActive,
                ),
                'actions'           => array(
                    'edit'              => array('title'=>A::t('advertisements', 'Edit this record'), 'link'=>'advertisements/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('advertisement', 'edit')),
                    'delete'            => array('title'=>A::t('advertisements', 'Delete this record'), 'link'=>'advertisements/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('advertisement', 'delete'), 'onDeleteAlert'=>true),
                ),
                'return'            => true,
            ));
        ?>
    </div>
</div>
