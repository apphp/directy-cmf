<?php
    $this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Email Settings'))
    );    
?>
    
<h1><?php echo A::t('app', 'Email Settings'); ?></h1>

<div class="bloc">

	<?php echo $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;		
		
		echo CWidget::create('CFormView', array(
			'action'=>'settings/email',
			'method'=>'post',
			'htmlOptions'=>array(
				'name'=>'frmEmailSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=>array(
				'act'   =>array('type'=>'hidden', 'value'=>'send'),
				'mailer'=>array('type'=>'select', 'value'=>($settings->mailer == '' ? CConfig::get('email.mailer') : $settings->mailer), 'title'=>A::t('app', 'Mailer'), 'tooltip'=>A::t('app', 'Mailer Tooltip'), 'data'=>$mailersList, 'htmlOptions'=>array('onchange'=>'showSMTPParams(this.value);')),
				'email'	=>array('type'=>'textbox', 'value'=>($settings->general_email == '' ? CConfig::get('email.from') : $settings->general_email), 'title'=>A::t('app', 'Email Address'), 'tooltip'=>A::t('app', 'Email Address Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'email', 'autocomplete'=>'off')),
				'smtpSecure'=>array('type'=>'select', 'value'=>$settings->smtp_secure, 'title'=>A::t('app', 'SMTP Secure'), 'tooltip'=>A::t('app', 'SMTP Secure Tooltip'), 'data'=>array('ssl'=>'SSL', 'tls'=>'TLS', ''=>A::t('app', 'No')), 'mandatoryStar'=>false),
				'smtpHost'	=>array('type'=>'textbox', 'value'=>($settings->smtp_host == '' ? CConfig::get('email.smtp.host') : $settings->smtp_host), 'title'=>A::t('app', 'SMTP Host'), 'tooltip'=>A::t('app', 'SMTP Host Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'70'), 'appendCode'=>A::t('app', 'e.g., smtp.gmail.com')),
				'smtpPort'	=>array('type'=>'textbox', 'value'=>($settings->smtp_port == '' ? CConfig::get('email.smtp.port') : $settings->smtp_port), 'title'=>A::t('app', 'SMTP Port'), 'tooltip'=>A::t('app', 'SMTP Port Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'5', 'class'=>'small'), 'appendCode'=>A::t('app', 'e.g., 465 or 587')),
				'smtpUsername'	=>array('type'=>'textbox', 'value'=>($settings->smtp_username == '' ? CConfig::get('email.smtp.username') : $settings->smtp_username), 'title'=>A::t('app', 'SMTP Username'), 'tooltip'=>A::t('app', 'SMTP Username Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'40'), 'appendCode'=>A::t('app', 'your full email address')),
				'smtpPassword'	=>array('type'=>'password', 'value'=>($smtpPassword == '' ? CConfig::get('email.smtp.password') : $smtpPassword), 'title'=>A::t('app', 'SMTP Password'), 'tooltip'=>A::t('app', 'SMTP Password Tooltip'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'20', 'placeholder'=>'&#9679;&#9679;&#9679;&#9679;&#9679;'), 'appendCode'=>A::t('app', 'your email password')),
			),
			'buttons'=> Admins::hasPrivilege('site_settings', 'edit') ? 
				array(
					'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')),
					'test'	=>array('type'=>'button', 'value'=>A::t('app', 'Test Email'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>'sendTestEmail(this)')),
					'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','settings/email');")))
				: array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	?>
	</div>
</div>  
 
<?php
	A::app()->getClientScript()->registerScript(
		'smtp-toggle',
		'function showSMTPParams(val){
			if(val == "smtpMailer"){
				$("#frmEmailSettings_row_2,#frmEmailSettings_row_3,#frmEmailSettings_row_4,#frmEmailSettings_row_5,#frmEmailSettings_row_6").show();
			}else{
				$("#frmEmailSettings_row_2,#frmEmailSettings_row_3,#frmEmailSettings_row_4,#frmEmailSettings_row_5,#frmEmailSettings_row_6").hide();
			}
		};',
		0
	);
	A::app()->getClientScript()->registerScript(
		'send-test-email',
		'function sendTestEmail(el){
			$(el).val("'.A::t('app', 'Sending').'...");
			$(el).addClass("hover");
			$(el).attr("disabled","disabled");
			$(el).closest("form").find("input[name=act]").val("test");
			$(el).closest("form").submit();
		};',
		0
	);	

	A::app()->getClientScript()->registerScript(
		'smtp-restore',
		'showSMTPParams("'.$settings->mailer.'");',
		2
	);
?>