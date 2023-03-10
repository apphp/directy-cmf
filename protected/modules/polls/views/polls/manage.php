<?php
    Website::setMetaTags(array('title'=>A::t('polls', 'Polls Management')));

    $this->_activeMenu = 'polls/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('polls', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('polls', 'Polls'), 'url'=>$backendPath.'modules/settings/code/polls'),
        array('label'=>A::t('polls', 'Polls Management'), 'url'=>'polls/manage'),
    );
?>

<h1><?= A::t('polls', 'Polls Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="content">
    <?php
            echo $actionMessage;

            if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('polls', 'add')){
                echo '<a href="polls/add" class="add-new">' . A::t('polls', 'Add Polls') . '</a>';
            }

            if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('polls', 'edit')){
                $isActive = array('title'=>A::t('polls', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'polls/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">' . A::t('polls', 'No') . '</span>', '1'=>'<span class="badge-green">' . A::t('polls', 'Yes') . '</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('polls', 'Click to change status')));
            }else{
                $isActive = array('title'=>A::t('polls', 'Active'), 'type'=>'enum', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'<span class="badge-red">'.A::t('polls', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('polls', 'Yes').'</span>'));
            }

            echo CWidget::create('CGridView', array(
                'model'             => 'Modules\Polls\Models\Polls',
                'actionPath'        => 'polls/manage',
                'passParameters'    => false,
                'defaultOrder'      => array('sort_order'=>'ASC', 'created_at'=>'DESC'),
                'pagination'        => array('enable'=>true, 'pageSize'=>20),
                'sorting'           => true,
                'filters'           => array(
                    'poll_question' => array('title'=>A::t('polls', 'Poll Question'), 'type'=>'textbox', 'operator'=>'%like%', 'maxLength'=>255),
                ),
                'fields'            => array(
                    'poll_question'     => array('title'=>A::t('polls', 'Poll Question'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                    'instruction'       => array('title'=>A::t('polls', 'Instruction'), 'type'=>'label', 'width'=>'120px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'maxLength'=>'60'),
                    'total_votes'       => array('title'=>A::t('polls', 'Total Votes'), 'type'=>'label', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                    'created_at'        => array('title'=>A::t('polls', 'Created At'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'width'=>'120px'),
                    'expires_at'        => array('title'=>A::t('polls', 'Expires At'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'width'=>'120px', 'definedValues'=>array(null=>A::t('polls', 'Not defined'))),
                    'id'                => array('title'=>A::t('polls', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'80px', 'isSortable'=>true),
                    'sort_order'        => array('title'=>A::t('polls', 'Sort Order'), 'type'=>'label', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                    'is_active'         => $isActive,
                ),
                'actions'           => array(
                    'edit'              => array('title'=>A::t('polls', 'Edit this poll'), 'link'=>'polls/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('polls', 'edit')),
                    'delete'            => array('title'=>A::t('polls','Delete this poll'), 'link'=>'polls/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('polls', 'delete'), 'onDeleteAlert'=>true),
                ),
                'return'            => true,
            ));
        ?>
    </div>
</div>
