<?php
	// Set meta tags according to active language
	Website::setMetaTags(array('title'=>A::t('app', 'Login')));
?>
<div id="content" class="login">
    
    <img src="templates/backend/images/lock-closed.png" alt="icon" />
    <h1><?= A::t('app', 'Admin Panel'); ?></h1>    
    <br>
    
    <?php
		//#004 Add poupup form for validation		
		if(A::app()->getCookie()->get('loginAttemptsAuth') != ''):
			echo CWidget::create('CMessage', array('error', A::t('app', 'Please confirm you are a human by clicking the button below!')));					
			echo CWidget::create('CFormView', array(
				'action' => $backendPath.'admin/login',
				'method' => 'post',
				'htmlOptions' => array(
					'name' 	=> 'frmLogin',
					'id'	=> 'frmLogin'
				),
				'fields' => array(
					'loginAttemptsAuth' =>array('type'=>'hidden', 'value'=>A::app()->getCookie()->get('loginAttemptsAuth')),
				),
				'buttons' => array(
					'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Confirm')),
				),
				'return' => true,
			));    			
		else:
			echo $actionMessage;
			// Draw login form
			echo CWidget::create('CFormView', array(
				'action' 	=> $backendPath.'admin/login',
				'method' 	=> 'post',
				'htmlOptions' => array(
					'name'	=> 'frmLogin',
					'id'	=> 'frmLogin'
				),
				'fields'=>array(
					'act'      => array('type'=>'hidden', 'value'=>'send'),
					'username' => array('type'=>'textbox', 'value'=>$username, 'title'=>'', 'mandatoryStar'=>false, 'htmlOptions'=>array('class'=>'', 'placeholder'=>A::t('app', 'Username'), 'maxlength'=>'32', 'autocomplete'=>'off', 'autofocus'=>true)),
					'password' => array('type'=>'password', 'value'=>$password, 'title'=>'', 'mandatoryStar'=>false, 'htmlOptions'=>array('class'=>'', 'placeholder'=>A::t('app', 'Password'), 'maxLength'=>'25')),
				),
				'checkboxes' => array('remember'=>array('type'=>'checkbox', 'title'=>A::t('app', 'Remember me'), 'value'=>'1', 'checked'=>$remember)),
				'links' 	 => CConfig::get('restoreAdminPassword.enable')
								? array('restorePassword'=>array('type'=>'link', 'linkUrl'=>$backendPath.'admin/restorePassword', 'linkText'=>A::t('app', 'Forgot your password?'), 'htmlOptions'=>array()))
								: array(),
				'buttons'	=> array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Login'))),
				'events'	=> array('focus'=>array('field'=>$errorField)),
				'return'	=> true,
			));    
		endif;
    ?>

    <div class="clear"></div>    
</div>

<div class="apphp"><a href="https://www.apphp.com" target="_blank" rel="noopener noreferrer" title="Visit ApPHP">Powered by ApPHP</a></div>