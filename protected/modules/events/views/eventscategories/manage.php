<?php
	Website::setMetaTags(array('title'=>A::t('events', 'Events Categories Management')));
	
	$this->_activeMenu = 'eventsCategories/manage';
	$this->_breadCrumbs = array(
		array('label' => A::t('events', 'Modules'), 'url' => $backendPath.'modules/'),
		array('label' => A::t('events', 'Events Module'), 'url' => $backendPath.'modules/settings/code/events'),
		array('label' => A::t('events', 'Events Categories Management'), 'url' => 'eventsCategories/manage'),
	);
?>
<h1><?= A::t('events', 'Events Management'); ?></h1>

<div class="bloc">  	
    <?= $tabs; ?>
    <div class="content">
        <?php
        echo $actionMessage;

        if (Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('events', 'add')) {
            echo '<a href="eventsCategories/add" class="add-new">' . A::t('events', 'Add New') . '</a>';
        }

        echo CWidget::create('CGridView', array(
			'model' => '\Modules\Events\Models\EventsCategories',
			'actionPath' => 'eventsCategories/manage',
			'condition' => '',
			'defaultOrder' => array('event_category_sort_order' => 'ASC'),
			'passParameters' => true,
			'pagination' => array('enable' => true, 'pageSize' => 20),
			'sorting' => true,
			'fields' => array(
				'event_category_name' => array('title' => A::t('events', 'Category'), 'type' => 'label', 'align' => '', 'width' => '', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'definedValues' => array(), 'format' => '', 'stripTags' => true),
				'event_category_sort_order' => array('title' => A::t('events', 'Order'), 'type' => 'label', 'align' => '', 'width' => '90px', 'class' => 'center', 'headerClass' => 'center', 'isSortable' => true, 'definedValues' => array(), 'format' => ''),
				'event_category_is_active' => array('title' => A::t('events', 'Active'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '60px', 'linkUrl' => 'eventsCategories/changeStatus/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('events', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('events', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('events', 'Click to change status'))),
				'events_link' => array('title' => A::t('events', 'Events'), 'type' => 'link', 'align' => '', 'width' => '120px', 'class' => 'center', 'headerClass' => 'center', 'isSortable' => false, 'definedValues' => array(), 'linkUrl' => 'events/manage/catId/{id}', 'linkText' => A::t('events', 'Events'), 'htmlOptions' => array('class' => 'subgrid-link'), 'prependCode' => '[ ', 'appendCode' => ' ]'),
				'events_count' => array('title' => A::t('events', 'Events Count'), 'type' => 'label', 'align' => '', 'width' => '90px', 'class' => 'center', 'headerClass' => 'center', 'isSortable' => true, 'definedValues' => array(), ),
			),
			'actions' => array(
				'edit' => array(
				'disabled' => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('events', 'edit'),
				'link' => 'eventsCategories/edit/id/{id}', 'imagePath' => 'templates/backend/images/edit.png', 'title' => A::t('events', 'Edit this record')
			),
			'delete' => array(
				'disabled' => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('events', 'delete'),
				'link' => 'eventsCategories/delete/id/{id}', 'imagePath' => 'templates/backend/images/delete.png', 'title' => A::t('events', 'Delete this record'), 'onDeleteAlert' => true
				)
			),
			'return' => true,
        ));
        ?>        
    </div>
</div>
