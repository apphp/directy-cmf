<?php
Website::setMetaTags(array('title'=>A::t('tickets', 'Create Ticket')));

A::app()->getClientScript()->registerCssFile('assets/modules/tickets/css/tickets.css');

$this->_breadCrumbs = array(
    array('label'=>A::t('tickets', 'Home'), 'url'=>Website::getDefaultPage()),
    array('label'=>A::t('tickets', 'Support'), 'url'=>'tickets/userManageTickets'),
    array('label'=>A::t('tickets', 'Create Ticket')),
);
?>

<h1><?= A::t('tickets', 'Create Ticket'); ?></h1>

<div class="block-body">
    <div class="sub-title"><?= A::t('tickets', 'Create Ticket'); ?></div>
    <div id="ticket-form">
		<?php
		echo CWidget::create('CDataForm', array(
			'model'         => 'Modules\Tickets\Models\Tickets',
			'operationType' => 'add',
			'action'        => 'tickets/userAddTicket/',
			'successUrl'    => 'tickets/userManageTickets/',
			'cancelUrl'     => 'tickets/userManageTickets/',
			'passParameters'=>false,
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmAddTicket',
				'enctype'=>'multipart/form-data',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,

			'fields'=>array(
				'topic'=>array('type'=>'textbox',    'title'=>A::t('tickets', 'Topic'),      'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>50), 'htmlOptions'=>array('maxlength'=>'50', 'class'=>'large')),
				'email'   => array('type'=>'textbox', 'title'=>A::t('tickets', 'Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'large')),
				'message'   => array('type'=>'textarea', 'title'=>A::t('tickets', 'Message'), 'tooltip'=>'', 'default'=>'', 'validation' => array('required'=>true, 'type'=>'link', 'maxLength'=>5000), 'htmlOptions'=>array('maxLength'=>'5000', 'class'=>'large')),
				'departments'=>array('type'=>'select', 'title'=>A::t('tickets', 'Departments'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($editDepartmentsTicket)), 'data'=>$editDepartmentsTicket, 'emptyOption'=>true, 'emptyValue'=>'', 'viewType'=>'dropdownlist', 'multiple'=>false, 'storeType'=>'separatedValues', 'separator'=>';', 'htmlOptions'=>array('class'=>'chosen-select-filter')),
				'status'	=> array('type'=>'data', 'default'=> 0),
				'date_created'	=> array('type'=>'data', 'default'=> date('Y-m-d H:i:s')),
				'account_role'	=> array('type'=>'data', 'default'=> CAuth::getLoggedRole()),
				'account_id'	=> array('type'=>'data', 'default'=> CAuth::getLoggedId()),
                'file'=>array(
                    'type'			 	=> 'fileUpload',
                    'title'			 	=> A::t('tickets', 'Attachment'),
                    'tooltip'		 	=> '',
                    'default'		 	=> '',
                    'download'          => false,
                    'validation'	 	=> array('required'=>false, 'type'=>'file', 'targetPath'=>'assets/modules/tickets/uploaded/'.$createPath.'/', 'maxSize'=>'1M', 'mimeType'=>'application/zip, image/jpg, image/jpeg, image/png', 'fileName'=>CHash::getRandomString(10), 'filePrefix'=>'', 'filePostfix'=>'', 'htmlOptions'=>array()),
                    'fileOptions'	 	=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/tickets/uploaded/'.$createPath),
                    'appendCode' 		=> '<br>('.A::t('tickets', 'allowed types').': zip, pdf, jpeg, jpg, png, gif)',
                ),
			),
			'buttons'=>array(
				'submit'=>array('type'=>'submit', 'value'=>A::t('tickets', 'Create'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-primary')),
				'cancel'=>array('type'=>'button', 'value'=>A::t('tickets', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-default')),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('tickets', 'Ticket')),
			'return'            => true,
		));
		?>
    </div>
</div>

