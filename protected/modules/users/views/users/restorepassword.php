<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'Restore Password')));
	
    A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
    if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');
    A::app()->getClientScript()->registerScriptFile('assets/modules/users/js/users.js');    

	// Define active menu	
	$this->_activeMenu = 'users/restorePassword';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('users', 'Restore Password');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('users', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('users', 'Restore Password')),
	);
?>
<h1 class="title"><?= A::t('users', 'Restore Password'); ?></h1>
<?php
    echo CHtml::openForm('users/restorePassword', 'post', array('name'=>'restore-form', 'id'=>'restore-form')) ; 
    echo CHtml::hiddenField('act', 'send');

    echo CHtml::tag('p', array(), A::t('users', 'Password recovery instructions'));
    echo $actionMessage;

    echo '<div class="row">';
    echo CHtml::tag('label', array(), A::t('users', 'E-mail address').': ');
    echo CHtml::textField('email', '', array('maxlength'=>'100', 'autocomplete'=>'off'));
    echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorEmpty'), A::t('users', 'The field email cannot be empty!'));
    echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorValid'), A::t('users', 'You must provide a valid email address!'));
    echo '</div>';
    
    echo '<div class="row row-button">';
	echo CHtml::tag('button', array('type'=>'button', 'class'=>'btn v-btn v-btn-default v-small-button', 'data-sending'=>A::t('users', 'Sending...'), 'onclick'=>'javascript:users_RestorePasswordForm(this)'), A::t('users', 'Get New Password'));
    echo '</div>';    

    echo CHtml::closeForm();
?>