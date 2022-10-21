<?php
	// Set meta tags according to active language
	Website::setMetaTags(array('title'=>A::t('app', 'Restore Admin Password')));
?>
<div id="content" class="restore-password">
    
    <img src="templates/backend/images/lock-closed.png" alt="icon" />
    <h1><?= A::t('app', 'Restore Password'); ?></h1>    
    <br>
    
	<?php	
		//#004 Add poupup form for validation		
		if(A::app()->getCookie()->get('restoreAttemptsAuth') != ''):
			echo CWidget::create('CMessage', array('error', A::t('app', 'Please confirm you are a human by clicking the button below!')));					
			echo CWidget::create('CFormView', array(
				'action' => $backendPath.'admin/restorePassword',
				'method' => 'post',
				'htmlOptions' => array(
					'name' 	=> 'frmLogin',
					'id'	=> 'frmLogin'
				),
				'fields' => array(
					'restoreAttemptsAuth' =>array('type'=>'hidden', 'value'=>A::app()->getCookie()->get('restoreAttemptsAuth')),
				),
				'links'   => array('login'=>array('type'=>'link', 'linkUrl'=>$backendPath.'admin/login', 'linkText'=>A::t('app', 'Login'), 'htmlOptions'=>array())),
				'buttons' => array(
					'submit' => array('type'=>'submit', 'value'=>A::t('app', 'Confirm')),
				),
				'return' => true,
			));    			
		else:
			echo $actionMessage;
			
			// Draw restore form
			echo CWidget::create('CFormView', array(
				'action' 	=> $backendPath.'admin/restorePassword',
				'method' 	=> 'post',
				'htmlOptions' => array(
					'name'	=> 'frmRestorePassword',
					'id'	=> 'frmRestorePassword'
				),
				'fields'=>array(
					'act'      	=> array('type'=>'hidden', 'value'=>'send'),
					'email' 	=> array('type'=>'textbox', 'value'=>$email, 'title'=>'', 'mandatoryStar'=>false, 'htmlOptions'=>array('class'=>'', 'placeholder'=>A::t('app', 'Email'), 'maxlength'=>'100', 'autocomplete'=>'off', 'autofocus'=>true)),
				),
				'links' 	=> array('login'=>array('type'=>'link', 'linkUrl'=>$backendPath.'admin/login', 'linkText'=>A::t('app', 'Login'), 'htmlOptions'=>array())),
				'buttons'	=> array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Restore Password'))),
				'events'	=> array('focus'=>array('field'=>$errorField)),
				'return'	=> true,
			));
		endif;
	?>
	
    <div class="clear"></div>    
</div>

<div class="apphp"><a href="https://www.apphp.com" target="_blank" rel="noopener noreferrer" title="Visit ApPHP">Powered by ApPHP</a></div>