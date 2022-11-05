<?php
	Website::setMetaTags(array('title'=>A::t('tickets', 'Edit Ticket')));

    $this->_activeMenu = 'tickets/manage';
	$this->_breadCrumbs = array(
		array('label'=>A::t('tickets', 'Modules'), 'url'=>$backendPath.'modules/'),
		array('label'=>A::t('tickets', 'Tickets'), 'url'=>$backendPath.'modules/settings/code/tickets'),
		array('label'=>A::t('tickets', 'Tickets Management'), 'url'=>'tickets/manage'),
		array('label'=>A::t('tickets', 'Edit Ticket')),
	);

	$statusParam = ($status !== '' ? '/status/'.$status : '');
?>

<h1><?= A::t('tickets', 'Tickets Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

	<div class="sub-title"><?= A::t('tickets', 'Edit Ticket'); ?></div>
	<div class="content">
	<?php
		if($tickets->status == 3):
			$status = array('type'=>'label',    'title'=>A::t('tickets', 'Close Ticket'),      'tooltip'=>'', 'default'=>A::t('tickets', 'The Ticket is Closed'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50', 'class'=>'large'));
			$buttons = array(
				'cancel'=>array('type'=>'button', 'value'=>A::t('tickets', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white'))
			);
		else:
			$status = array('type'=>'checkbox', 'title'=>A::t('tickets', 'Close Ticket'),   'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom');
			$buttons = array(
				'submitUpdateClose' =>array('type'=>'submit', 'value'=>A::t('tickets', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose', 'class'=>'btn btn-primary')),
				'submitUpdate'      =>array('type'=>'submit', 'value'=>A::t('tickets', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate', 'class'=>'btn btn-primary')),
				'cancel'            =>array('type'=>'button', 'value'=>A::t('tickets', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-default')),
			);
		endif;

		echo CWidget::create('CDataForm', array(
			'model'			=> 'Modules\Tickets\Models\Tickets',
			'primaryKey'	=> $tickets->id,
			'operationType'	=> 'edit',
			'action'		=> 'tickets/editTicket/id/'.$tickets->id.$statusParam,
			'successUrl'	=> 'tickets/manage'.$statusParam,
			'cancelUrl'		=> 'tickets/manage'.$statusParam,
			'passParameters'=> true,
			'method'		=> 'post',
			'htmlOptions'=>array(
				'name'=>'frmTicketsEdit',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'topic'     =>array('type'=>'label',    'title'=>A::t('tickets', 'Topic'),      'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50', 'class'=>'large')),
				'email'     => array('type'=>'label', 'title'=>A::t('tickets', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength'=>100), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'large')),
                'close_ticket'  =>$status,
			),
			'buttons'			=> $buttons,
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('tickets', 'Ticket')),
            'return'            => true,
		));
	?>
	</div>
</div>

