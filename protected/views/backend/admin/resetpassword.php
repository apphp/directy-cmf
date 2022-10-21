<?php
// Set meta tags according to active language
Website::setMetaTags(array('title'=>A::t('app', 'Restore Admin Password')));
?>
<div id="content" class="restore-password">

	<img src="templates/backend/images/lock-closed.png" alt="icon" />
	<h1><?= A::t('app', 'Reset Password'); ?></h1>
	<br>

    <?php
        if($showForm):

            //#004 Add poupup form for validation
            if(A::app()->getCookie()->get('resetAttemptsAuth') != ''):
                echo CWidget::create('CMessage', array('error', A::t('app', 'Please confirm you are a human by clicking the button below!')));
                echo CWidget::create('CFormView', array(
                    'action' => $backendPath . 'admin/resetPassword/' . $urlParams,
                    'method' => 'post',
                    'htmlOptions' => array(
						'name' => 'frmResetPassword',
						'id' => 'frmResetPassword'
                    ),
                    'fields' => array(
                        'resetAttemptsAuth' =>array('type'=>'hidden', 'value'=>A::app()->getCookie()->get('resetAttemptsAuth')),
                    ),
                    'buttons' => array(
                        'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Confirm')),
                    ),
                    'return' => true,
                ));
            else:
	            echo $actionMessage;

                echo CWidget::create('CFormView', array(
					'action' => $backendPath . 'admin/resetPassword/' . $urlParams,
					'method' => 'post',
					'htmlOptions' => array(
						'name' => 'frmResetPassword',
						'id' => 'frmResetPassword'
					),
					'fields' => array(
						'act' => array('type' => 'hidden', 'value' => 'send'),
						'password' => array('type' => 'password', 'value' => $password, 'title' => '', 'mandatoryStar' => false, 'htmlOptions' => array('class' => '', 'placeholder' => A::t('app', 'New Password'), 'maxLength' => '25')),
						'password_confirm' => array('type' => 'password', 'value' => $confirmPassword, 'title' => '', 'mandatoryStar' => false, 'htmlOptions' => array('class' => '', 'placeholder' => A::t('app', 'Confirm New Password'), 'maxLength' => '25')),
					),
					'checkboxes' => array(),
					'links' => array(),
					'buttons' => array('submit' => array('type' => 'submit', 'value' => A::t('app', 'Change Password'))),
					'events' => array(
						'focus' => array('field' => $errorField)
					),
					'return' => true,
				));
			endif;
        else:
			echo $actionMessage;
		endif;
	?>
	<div class="clear"></div>
</div>

<div class="apphp"><a href="https://www.apphp.com" target="_blank" rel="noopener noreferrer" title="Visit ApPHP">Powered by ApPHP</a></div>