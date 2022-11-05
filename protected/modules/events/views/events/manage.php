<?php
	Website::setMetaTags(array('title'=>A::t('events', 'Events Management')));

	$this->_activeMenu = 'eventsCategories/manage';
	$this->_breadCrumbs = array(
		array('label'=>A::t('events', 'Modules'), 'url'=>$backendPath.'modules/'),
		array('label'=>A::t('events', 'Events Module'), 'url'=>$backendPath.'modules/settings/code/events'),
		array('label'=>A::t('events', 'Events Category'), 'url'=>'eventsCategories/manage'),
		array('label'=>A::t('events', 'Events Management')),
	);
?>
<h1><?= A::t('events', 'Events Management'); ?></h1>

<div class="bloc">     
    <?php
    echo $tabs;
    ?>

    <div class="sub-title">
        <a class="sub-tab active" href="<?= $categoryLink; ?>"><?= strip_tags($categoryName); ?></a>
    </div>

    <div class="content">
        <?php
        echo $actionMessage;

        if (Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('events', 'add')) {
            echo '<a href="events/add/catId/'.$catId.'"  class="add-new">'.A::t('events', 'Add New').'</a>';
        }

        echo CWidget::create('CGridView', array(
            'model'             => '\Modules\Events\Models\Events',
            'actionPath' 		=> 'events/manage/catId/'.$catId,
            'condition' 		=> CConfig::get('db.prefix').'events.event_category_id = '.(int)$catId,
            'defaultOrder' 		=> array('event_starts_at'=>'DESC'),
            'passParameters' 	=> true,
            'pagination' 		=> array('enable'=>true, 'pageSize'=>20),
            'sorting' 			=> true,
            'filters' 			=> array(
                'event_name' 		=> array('title'=>A::t('events', 'Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>''),
                'event_starts_at' 	=> array('title'=>A::t('events', 'Event Starts At'), 'type'=>'datetime', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'10'),
            ),
            'fields' 			=> array(
                'event_name' 		=> array('title'=>A::t('events', 'Name'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'', 'stripTags'=>true),
                'event_starts_at' 	=> array('title'=>A::t('events', 'Event Starts At'), 'type'=>'label', 'align'=>'', 'width'=>'150px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'', 'stripTags'=>true),
                'event_ends_at' 	=> array('title'=>A::t('events', 'Event Ends At'), 'type'=>'label', 'align'=>'', 'width'=>'150px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'', 'stripTags'=>true),
                'event_is_active' 	=> array('title'=>A::t('events', 'Active'), 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'60px', 'linkUrl'=>'events/changeStatus/id/{id}/catId/{event_category_id}/page/{page}?returnTo='.rawurlencode(A::app()->getRequest()->getRequestUri()), 'linkText'=>'','definedValues'=>array('0'=>'<span class="badge-red">'.A::t('events', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('events', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('events', 'Click to change status'))),
            ),
            'actions' 			=> array(
                'edit' 			=> array(
                    'disabled' 		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('events', 'edit'),
                    'link' 			=> 'events/edit/catId/'.$catId.'/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('events', 'Edit this record')
                ),
                'delete' 		=> array(
                    'disabled' 		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('events', 'delete'),
                    'link' 			=> 'events/delete/catId/'.$catId.'/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('events', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
            'return' 			=> true
        ));
        ?>        
    </div>
</div>
