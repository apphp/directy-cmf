<?php
	Website::setMetaTags(array('title'=>A::t('tickets', 'Edit Ticket')));

A::app()->getClientScript()->registerCssFile('assets/modules/tickets/css/tickets.css');

$this->_breadCrumbs = array(
    array('label'=>A::t('tickets', 'Home'), 'url'=>Website::getDefaultPage()),
    array('label'=>A::t('tickets', 'Support'), 'url'=>'tickets/userManageTickets'),
    array('label'=>A::t('tickets', 'Create Ticket')),
);
?>

<h1><?= A::t('tickets', 'Edit Ticket'); ?></h1>

<div class="block-body">

	<div class="sub-title"><?= A::t('tickets', 'Edit Ticket'); ?></div>
    <div id="ticket-form">
	<?php
    if($tickets->status == 3){
        $status = array('type'=>'label',    'title'=>A::t('tickets', 'Close Ticket'),      'tooltip'=>'', 'default'=>A::t('tickets', 'The Ticket is Closed'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50', 'class'=>'large'));
		$buttons = array(
			'cancel'            =>array('type'=>'button', 'value'=>A::t('tickets', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-default')),
		);
    }else{
        $status = array('type'=>'checkbox', 'title'=>A::t('tickets', 'Close Ticket'),   'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());
		$buttons = array(
			'submitUpdateClose' =>array('type'=>'submit', 'value'=>A::t('tickets', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose', 'class'=>'btn btn-primary')),
			'submitUpdate'      =>array('type'=>'submit', 'value'=>A::t('tickets', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate', 'class'=>'btn btn-primary')),
			'cancel'            =>array('type'=>'button', 'value'=>A::t('tickets', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-default')),
		);
    }
		echo CWidget::create('CDataForm', array(
			'model'         => 'Modules\Tickets\Models\Tickets',
			'primaryKey'    => $tickets->id,
			'operationType' => 'edit',
			'action'        => 'tickets/userEditTicket/id/'.$tickets->id,
			'successUrl'    => 'tickets/userManageTickets/',
			'cancelUrl'     => 'tickets/userManageTickets/',
			'passParameters'=>false,
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmTicketsEdit',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'topic'         =>array('type'=>'label',    'title'=>A::t('tickets', 'Topic'),      'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50', 'class'=>'large')),
				'email'         =>array('type'=>'label', 'title'=>A::t('tickets', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength'=>100), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'large')),
                'close_ticket'  =>$status,
			),
			'buttons'=>$buttons,
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('tickets', 'Ticket')),
            'return'            => true,
		));
		?>
	</div>
</div>

