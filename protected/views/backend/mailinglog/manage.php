<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Mailing Log')));

    $this->_activeMenu = $backendPath.'mailingLog/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Mail Settings'), 'url'=>$backendPath.'emailTemplates/manage'),
        array('label'=>A::t('app', 'Mailing Log')),
    );    
?>

<h1><?= A::t('app', 'Mailing Log'); ?></h1>

<div class="bloc">
    <div class="title">
		<?= A::t('app', 'Mailing Log'); ?>	
		<a class="feature-settings-link tooltip-link" title="<?= A::te('app', 'Email Settings');?>" href="<?=$backendPath;?>settings/email"></a>
	</div>
    <div class="content">
		
	<?php if($logCount && Admins::hasPrivilege('mailing_log', 'delete')): ?>
		<a href="<?=$backendPath;?>mailingLog/deleteAll" class="delete-all"><?= A::t('app', 'Delete All'); ?></a>
	<?php endif; ?>
		
	<?php 
		echo $actionMessage; 
			
		echo CWidget::create('CGridView', array(
			'model'				=> 'MailingLog',
			'actionPath'		=> $backendPath.'mailingLog/manage',
			'defaultOrder'		=> array('sent_at'=>'DESC'),
			'passParameters'	=> true,
			'pagination'		=> array('enable'=>true, 'pageSize'=>20),
			'sorting'			=> true,
			'filters'			=> array(
                'email_subject'  	=> array('title'=>A::t('app', 'Subject'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'140px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'email_content'  	=> array('title'=>A::t('app', 'Content'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'140px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'email_from'  		=> array('title'=>A::t('app', 'From'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'130px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'email_to'  		=> array('title'=>A::t('app', 'To'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'130px', 'maxLength'=>'125', 'htmlOptions'=>array()),
				'sent_at' 	        => array('title'=>A::t('app', 'Date Sent'), 'type'=>'datetime', 'table'=>'', 'operator'=>'like%', 'default'=>'', 'width'=>'80px', 'maxLength'=>'10', 'format'=>'', 'htmlOptions'=>array(), 'viewType'=>'date'),
                'status'     		=> array('title'=>A::t('app', 'Status'), 'type'=>'enum', 'operator'=>'=', 'width'=>'90px', 'source'=>array('0'=>A::t('app', 'Not Sent'), '1'=>A::t('app', 'Sent')), 'emptyOption'=>true),
            ),
			'fields'			=> array(
				'email_subject' 	=> array('title'=>A::t('app', 'Subject'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'310px'),
				'email_from' 		=> array('title'=>A::t('app', 'From Email'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'200px'),
				'email_to' 			=> array('title'=>A::t('app', 'To Email'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left','width'=>'200px'),
                'email_attachments' => array('title'=>A::t('app', 'Attachments'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left','width'=>'', 'maxLength'=>'40px'),
				'sent_at' 			=> array('title'=>A::t('app', 'Date Sent'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'definedValues'=>array(null=>A::t('app', 'Never')), 'width'=>'140px', 'format'=>$dateTimeFormat),
				'status' 			=> array('title'=>A::t('app', 'Status'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'definedValues'=>array('0'=>'<span class="label-red badge-square">'.A::t('app', 'Not Sent').'</span>', '1'=>'<span class="label-green badge-square">'.A::t('app', 'Sent').'</span>')),
			),
			'actions'			=> array(
				'details'   		=> array(
					'disabled'		=> !Admins::hasPrivilege('email_templates', 'view'),
					'link'			=> $backendPath.'mailingLog/details/id/{id}', 'imagePath'=>'templates/backend/images/details.png', 'title'=>A::t('app', 'View Details')
				),
				'delete' 		=> array(
					'disabled'	=> !Admins::hasPrivilege('mailing_log', 'delete'),
					'link'		=> $backendPath.'mailingLog/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
				)
			),
		));
	?>        
    </div>
</div>

<?php
	A::app()->getClientScript()->registerScript(
		'delete-all-records',
		'jQuery(".delete-all").click(function(){
			return confirm("'.A::te('core', 'Are you sure you want to delete all records?').'");
		});'
		,
		2
	);				
?>