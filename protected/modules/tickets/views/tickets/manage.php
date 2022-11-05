<?php
	Website::setMetaTags(array('title'=>A::t('tickets', 'Tickets Management')));
	
	$this->_pageTitle = A::t('tickets', 'Tickets Management').' | '.CConfig::get('name');
	$this->_activeMenu = 'tickets/manage';
	$this->_breadCrumbs = array(
		array('label'=>A::t('tickets', 'Modules'), 'url'=>$backendPath.'modules/'),
		array('label'=>A::t('tickets', 'Tickets'), 'url'=>$backendPath.'modules/settings/code/tickets'),
		array('label'=>A::t('tickets', 'Tickets Management')),
	);
	
	$statusParam = ($status !== '' ? '/status/'.$status : '');

?>

<h1><?= A::t('tickets', 'Tickets Management')?></h1>

<div class="bloc">
    <?= $tabs; ?>
    <div class="sub-title">
        <?= $subTabs; ?>
    </div>

    <div class="content">
		<?=$actionMessage;?>
        <?php
        echo CWidget::create('CGridView', array(
            'model'             => 'Modules\Tickets\Models\Tickets',
            'actionPath'		=> 'tickets/manage'.$statusParam,
            'pagination'		=> array('enable'=>true, 'pageSize'=>20),
			'condition'			=> ($status !== '' ? CConfig::get('db.prefix').'tickets.status = '.$statusCode : ''),
			'passParameters'	=> false,
            'sorting'			=> true,
			'filters'	        => array(
				'topic' 	    => array('title'=>A::t('tickets', 'Topic'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'110px', 'maxLength'=>'50', 'htmlOptions'=>array()),
				'message' 	    => array('title'=>A::t('tickets', 'Message'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'110px', 'maxLength'=>'100', 'htmlOptions'=>array()),
				'first_name,last_name' => array('title'=>A::t('tickets', 'Member'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'110px', 'maxLength'=>'100', 'htmlOptions'=>array()),
				'departments' 	=> array('title'=>A::t('tickets', 'Departments'), 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'120px', 'source'=>$editDepartmentsTicket, 'emptyOption'=>true, 'emptyValue'=>'', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'status' 	    => array('title'=>A::t('tickets', 'Status'), 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'120px', 'source'=>$editStatusTicket, 'emptyOption'=>true, 'emptyValue'=>'', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'date_created' 	=> array('title'=>A::t('tickets', 'Date Created'), 'type'=>'datetime', 'table'=>'', 'operator'=>'like%', 'default'=>'', 'width'=>'70px', 'maxLength'=>'', 'format'=>$dateFormat, 'htmlOptions'=>array()),
			),
            'defaultOrder'=>array('status'=>'ASC'),
            'passParameters'=>true,
            'fields'=>array(
				'index'             => array('title'=>'#', 'type'=>'index', 'align'=>'', 'width'=>'20px', 'class'=>'left', 'headerTooltip'=>'', 'headerClass'=>'left', 'isSortable'=>false),
                'topic'             => array('title'=>A::t('tickets', 'Topic'), 'type'=>'label', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'format'=>'', 'stripTags'=>true, 'maxLength'=>'42'),
                'fullname_user'     => array('title'=>A::t('tickets', 'Member'), 'type'=>'label', 'width'=>'140px', 'class'=>'left', 'headerClass'=>'left', 'format'=>'', 'stripTags'=>true, 'maxLength'=>'30'),
                'departments'       => array('title'=>A::t('tickets', 'Department'), 'type'=>'enum', 'align'=>'', 'width'=>'150px', 'class'=>'center', 'headerTooltip'=>'', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$editDepartmentsTicket),
                'date_created'      => array('title'=>A::t('tickets', 'Date Created'), 'type'=>'datetime', 'align'=>'left', 'width'=>'150px', 'class'=>'left', 'headerTooltip'=>'', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array('0000-00-00 00:00:00'=>'--', null=>'--'), 'format'=>$dateTimeFormat),
                'last_answer_date'  => array('title'=>A::t('tickets', 'Last Reply'), 'type'=>'datetime', 'align'=>'left', 'width'=>'150px', 'class'=>'left', 'headerTooltip'=>'', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array('0000-00-00 00:00:00'=>'--', null=>'--'), 'format'=>$dateTimeFormat),
                'status'            => array('title'=>A::t('tickets', 'Status'), 'type'=>'enum', 'align'=>'', 'width'=>'150px', 'class'=>'center', 'headerTooltip'=>'', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$manageStatusTicket),
				'replies_link'      => array('title'=>A::t('tickets', 'Items'), 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'definedValues'=>array(),  'linkUrl'=>' ticketReplies/replies/id/{id}'.$statusParam, 'linkText'=>A::t('tickets','Answers'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
				'id'    			=> array('title'=>A::t('app', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'30px'),
            ),
            'actions'=>array(
                'edit'    => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('tickets', 'edit'),
                    'link'=>'tickets/editTicket/id/{id}'.$statusParam, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('tickets','Edit this Ticket')
                ),
                'delete'  => array(
                    'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('tickets', 'delete'),
                    'link'=>'tickets/deleteTicket/id/{id}'.$statusParam, 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('tickets','Delete this Ticket'), 'onDeleteAlert'=>true
                ),
            ),
            'return'=>true,
        ));
        ?>
    </div>
</div>
