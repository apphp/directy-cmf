<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Mailing Log')));

    $this->_activeMenu = 'mailingLog/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Mailing Log')),
    );    
?>

<h1><?= A::t('app', 'Mailing Log'); ?></h1>

<div class="bloc">
    <div class="title">
		<?= A::t('app', 'Mailing Log'); ?>	
		<a class="feature-settings-link tooltip-link" title="<?= A::te('app', 'Email Settings');?>" href="settings/email"></a>
	</div>
    <div class="content">
		
	<?php if(Admins::hasPrivilege('mailing_log', 'delete')): ?>
		<a href="mailingLog/deleteAll" class="delete-all"><?= A::t('app', 'Delete All'); ?></a>
	<?php endif; ?>
		
	<?php 
		echo $actionMessage; 
			
		echo CWidget::create('CGridView', array(
			'model'				=> 'MailingLog',
			'actionPath'		=> 'mailingLog/manage',
			'defaultOrder'		=> array('sent_at'=>'DESC'),
			'passParameters'	=> true,
			'pagination'		=> array('enable'=>true, 'pageSize'=>20),
			'sorting'			=> true,
			'filters'			=> array(
                'email_subject'  	=> array('title'=>A::t('app', 'Subject'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'140px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'email_content'  	=> array('title'=>A::t('app', 'Content'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'140px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'email_from'  		=> array('title'=>A::t('app', 'From'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'130px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'email_to'  		=> array('title'=>A::t('app', 'To'), 'type'=>'textbox', 'table'=>'', 'operator'=>'%like%', 'default'=>'', 'width'=>'130px', 'maxLength'=>'125', 'htmlOptions'=>array()),
				'sent_at' 			=> array('title'=>A::t('app', 'Date Sent'), 'type'=>'datetime', 'table'=>'', 'operator'=>'=', 'default'=>'', 'width'=>'80px', 'maxLength'=>'', 'format'=>'', 'htmlOptions'=>array()),
                'status'     		=> array('title'=>A::t('app', 'Status'), 'type'=>'enum', 'operator'=>'=', 'width'=>'90px', 'source'=>array('0'=>A::t('app', 'No Sent'), '1'=>A::t('app', 'Sent')), 'emptyOption'=>true),
            ),
			'fields'			=> array(
				'email_subject' 	=> array('title'=>A::t('app', 'Subject'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
				'email_from' 		=> array('title'=>A::t('app', 'From Email'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'210px'),
				'email_to' 			=> array('title'=>A::t('app', 'To Email'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left','width'=>'210px'),
				'sent_at' 			=> array('title'=>A::t('app', 'Date Sent'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'definedValues'=>array(null=>A::t('app', 'Never')), 'width'=>'140px', 'format'=>$dateTimeFormat),
				'status' 			=> array('title'=>A::t('app', 'Status'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'definedValues'=>array('0'=>'<span class="label-red">'.A::t('app', 'No Sent').'</span>', '1'=>'<span class="label-green">'.A::t('app', 'Sent').'</span>')),
			),
			'actions'			=> array(
				'details'   		=> array(
					'disabled'		=> !Admins::hasPrivilege('email_templates', 'view'),
					'link'			=> 'mailingLog/details/id/{id}', 'imagePath'=>'templates/backend/images/details.png', 'title'=>A::t('app', 'View Details')
				),
				'delete' 		=> array(
					'disabled'	=> !Admins::hasPrivilege('mailing_log', 'delete'),
					'link'		=> 'mailingLog/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
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