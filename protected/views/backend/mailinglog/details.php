<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Mailing Log')));

    $this->_activeMenu = $backendPath.'mailingLog/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard/'),
        array('label'=>A::t('app', 'Mailing Log'), 'url'=>$backendPath.'mailingLog/manage'),
		array('label'=>A::t('app', 'Mailing Details')),
    );    
?>

<h1><?= A::t('app', 'Mailing Log'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Mailing Details'); ?></div>
    <div class="content">
    <?php
		echo CWidget::create('CDataForm', array(
			'model'				=> 'MailingLog',
			'primaryKey'		=> $mail->id,
			'operationType'		=> 'edit',
			'action'			=> $backendPath.'mailingLog/details/id/'.$mail->id,
			'successUrl'		=> $backendPath.'mailingLog/manage',
			'cancelUrl'			=> $backendPath.'mailingLog/manage',
			'passParameters'	=> false,
			'method'			=> 'get',
			'htmlOptions'		=> array(
				'name'				=> 'frmMailingLogDetails',
				///'enctype'			=> 'multipart/form-data',
				'autoGenerateId'	=> true
			),
			'requiredFieldsAlert' => false,
			'fieldSets'			=> array('type'=>'frameset'),
			'fields'			=> array(
                'email_from'      		=> array('type'=>'label', 'title'=>A::t('app', 'From Email'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
                'email_to'      		=> array('type'=>'label', 'title'=>A::t('app', 'To Email'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
                'email_subject'     	=> array('type'=>'html', 'title'=>A::t('app', 'Subject'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
                'email_content'     	=> array('type'=>'html', 'title'=>'', 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false, 'prependCode'=>'<label for="frmMailingLogDetails_email_content">'.A::t('app', 'Content').':</label><div class="div-content div-word-break">', 'appendCode'=>'</div>', 'callback'=>array('function'=>'nl2br', 'params'=>false)),
				'email_attachments'     => array('type'=>'html', 'title'=>'', 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false, 'prependCode'=>'<label for="frmMailingLogDetails_email_content">'.A::t('app', 'Attachments').':</label><div class="div-content div-word-break">', 'appendCode'=>'</div>', 'callback'=>array('function'=>'nl2br', 'params'=>false)),
				'sent_at'				=> array('type'=>'label', 'title'=>A::t('app', 'Date Sent'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>$dateTimeFormat, 'stripTags'=>false),
				'status' 				=> array('type'=>'label', 'title'=>A::t('app', 'Status'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array('0'=>'<span class="label-red badge-square">'.A::t('app', 'Not Sent').'</span>', '1'=>'<span class="label-green badge-square">'.A::t('app', 'Sent').'</span>')),
				'status_description'	=> array('type'=>'html', 'title'=>'', 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(''=>'--'), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false, 'prependCode'=>'<label for="frmMailingLogDetails_status_description">'.A::t('app', 'Additional Info').':</label><div class="div-content">', 'appendCode'=>'</div>', 'callback'=>array('function'=>'nl2br', 'params'=>false)),
			),
			'buttons'			=> array(
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Back'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
			),
			'messagesSource'	=> 'core',
			'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Language')),
            'return'            => true,
		));		                
	?>    
    </div>
</div>
<?php
	A::app()->getClientScript()->registerCss(
		'mailing-details',
		'.div-content{ display:table; width:50%; }
		.div-content label{ display:table; width:100%; }'
	);
	
	A::app()->getClientScript()->registerScript(
		'mailing-details',
		'// Replace active tags
		jQuery(".div-content img").css({"max-width":"80px", "max-heigth":"60px"});
		jQuery(".div-content").find("a").each(function(){
			var href = jQuery(this).attr("href");
			jQuery(this).attr("href", "javascript:void(\'" +href + "\');");
			jQuery(this).addClass("tooltip-link");
			jQuery(this).attr("title", href);
		});
		',
		2
	);
?>
