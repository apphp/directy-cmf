<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'User Login')));
	
    A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
    if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');
    A::app()->getClientScript()->registerScriptFile('assets/modules/users/js/users.js'); 

	// Define active menu	
	$this->_activeMenu = 'users/login';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('users', 'User Login');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('users', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('users', 'User Login')),
	);
?>

<h1 class="title"><?= A::t('users', 'User Login'); ?></h1>
<div id="login-form">    
<?php 

    //#004 Add poupup form for validation		
    if(A::app()->getCookie()->get('userLoginAttemptsAuth') != ''){
        echo CWidget::create('CMessage', array('error', A::t('app', 'Please confirm you are a human by clicking the button below!')));					
        echo CWidget::create('CFormView', array(
            'action'=>'users/login',
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmLogin',
                'id'=>'frmLogin'
            ),
            'fields'=>array(
                'userLoginAttemptsAuth' =>array('type'=>'hidden', 'value'=>A::app()->getCookie()->get('userLoginAttemptsAuth')),
            ),
            'buttons'=>array(
                'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Confirm'), 'htmlOptions'=>array('class'=>'btn v-btn v-btn-default v-small-button')),
            ),
            'return'=>true,
        ));    			
    }else{

        echo $actionMessage;

        echo CHtml::openForm('users/login', 'post', array('onsubmit'=>'return users_LoginForm(this)')) ;
        echo CHtml::hiddenField('act', 'send');
        
        echo '<div class="row">';
        echo '<label>'.A::t('users', 'Username').':</label>';
        echo '<input id="login_username" type="text" name="login_username" value="" maxlength="32" data-required="true" autocomplete="off" />';
        echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameErrorEmpty'), A::t('users', 'Username field cannot be empty!'));
        echo '</div>';
        
        echo '<div class="row">';
        echo '<label>'.A::t('users', 'Password').':</label>';
        echo '<input id="login_password" type="password" name="login_password" value="" maxlength="25" data-required="true" autocomplete="off" />';
        echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordErrorEmpty'), A::t('users', 'Password field cannot be empty!'));
        echo '</div>';
        
        echo '<div class="row row-button">';
        echo '<input type="submit" class="btn v-btn v-btn-default v-small-button" value="'.A::t('users', 'Login').'" />';
        if($allowRememberMe){     
            echo '<input id="remember" type="checkbox" name="remember" /> <label for="remember" class="remember">'.A::t('users', 'Remember Me').'</label><br/>';
        }
        echo '</div>';
            
        echo '<div class="row">';
        if(isset($allowRegistration)){         
            echo '<a href=users/registration>'.A::t('users', 'Create account').'</a><br/>';    
        }
        if(isset($allowResetPassword)){
            echo '<a href="users/restorePassword">'.A::t('users', 'Forgot your password?').'</a>';
        }
        echo '</div>';
      
        if(!empty($buttons)):
            echo '<div class="row">';
            echo $buttons;
            echo '</div>';
        endif;

        echo CHtml::closeForm();
    }
 ?>     

</div>