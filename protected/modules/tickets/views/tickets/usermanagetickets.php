<?php
Website::setMetaTags(array('title'=>A::t('tickets', 'Support')));

A::app()->getClientScript()->registerCssFile('assets/modules/tickets/css/tickets.css');

// Define active menu
$this->_activeMenu = 'tickets/userManageTickets';

// Define breadcrumbs title
$this->_breadcrumbsTitle = A::t('tickets', 'Support');

// Define breadcrumbs for this page
$this->_breadCrumbs = array(
	array('label'=>A::t('tickets', 'Home'), 'url'=>Website::getDefaultPage()),
	array('label'=>A::t('tickets', 'Support')),
);
?>


<div class="bloc">
	<div class="content">
		<?php
		if($accessClosed):
			echo $accessClosed;
		else:
			echo $actionMessage;
			echo '<p><a href="tickets/userAddTicket" class="btn btn-success ">'.A::t('tickets', 'Create Ticket').'</a></p>';

			echo CWidget::create('CGridView', array(
				'model'             => 'Modules\Tickets\Models\Tickets',
				'actionPath'        => 'tickets/userManageTickets',
				'condition'		    => CConfig::get('db.prefix').'tickets.account_id = '.CAuth::getLoggedId().' AND '.CConfig::get('db.prefix').'tickets.account_role = \''.CAuth::getLoggedRole().'\'',
				'pagination'        =>array('enable'=>true, 'pageSize'=>20),
				'sorting'           =>true,
                'options'	        => array(
				    'gridWrapper'	=> array('tag'=>'div', 'class'=>'gridTable'),
                ),
				'filters'	        => array(
					'topic' 	    => array('title'=>A::t('tickets', 'Topic'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'110px', 'maxLength'=>'50', 'htmlOptions'=>array('class'=>'ticket-form')),
					'message' 	    => array('title'=>A::t('tickets', 'Message'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'110px', 'maxLength'=>'100', 'htmlOptions'=>array('class'=>'ticket-form')),
				),
				'defaultOrder'=>array('date_created'=>'DESC'),
				'passParameters'=>true,
				'fields'=>array(
					'id'    			=> array('title'=>A::t('app', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'30px'),
					'topic'             => array('title'=>A::t('tickets', 'Topic'), 'type'=>'label', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'format'=>'', 'stripTags'=>true, 'maxLength'=>'42'),
					'date_created'      => array('title'=>A::t('tickets', 'Date Created'), 'type'=>'datetime', 'align'=>'left', 'width'=>'150px', 'class'=>'left', 'headerTooltip'=>'', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array('0000-00-00 00:00:00'=>'--', null=>'--'), 'format'=>$dateTimeFormat),
					'last_answer_date'  => array('title'=>A::t('tickets', 'Last Reply'), 'type'=>'datetime', 'align'=>'left', 'width'=>'150px', 'class'=>'left', 'headerTooltip'=>'', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array('0000-00-00 00:00:00'=>'--', null=>'--'), 'format'=>$dateTimeFormat),
					'status'            => array('title'=>A::t('tickets', 'Status'), 'type'=>'enum', 'align'=>'', 'width'=>'200px', 'class'=>'center', 'headerTooltip'=>'', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>$manageStatusTicket),
					'replies_link'      => array('title'=>A::t('tickets', 'Items'), 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'definedValues'=>array(),  'linkUrl'=>' ticketReplies/userReplies/id/{id}', 'linkText'=>A::t('tickets','Answers'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
				),
				'actions'=>array(
					'edit'    => array(
						'link'=>'tickets/userEditTicket/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('tickets','Edit this Ticket')
					),

				),
				'return'=>true,
			));
		endif;
		?>
	</div>
</div>