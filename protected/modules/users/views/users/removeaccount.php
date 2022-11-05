<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'Remove Account')));
	
    $this->_activeMenu = 'users/removeAccount';

    A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
    if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');
?>
<h1 class="title"><?= A::t('users', 'Remove Account'); ?></h1>
<div class="block-body">
<?php
    echo $actionMessage;
    if($accountRemoved){
        echo '<script type="text/javascript">setTimeout(function(){window.location.href = "users/logout";}, 5000);</script>';
    }else{
        $breadCrumbs = array(
            array('label'=>A::t('users', 'Dashboard'), 'url'=>'users/dashboard'),
            array('label'=>A::t('users', 'My Account'), 'url'=>'users/myAccount'),
            array('label'=>A::t('users', 'Remove')),
        );   
        echo CWidget::create('CBreadCrumbs', array('links'=>$breadCrumbs, 'separator'=>'&nbsp;/&nbsp;', 'return'=>true));
        echo '<br>';
        
        echo CHtml::openForm('users/removeAccount', 'post', array('name'=>'remove-account-form', 'id'=>'remove-account-form')) ; 
        echo CHtml::hiddenField('act', 'send');    
        echo CHtml::tag('p', array(), A::t('users', 'Account removal notice'));
        echo CHtml::openTag('div', array('class'=>'row row-button'));
        echo CHtml::tag('button', array('type'=>'submit', 'class'=>'btn v-btn v-btn-default v-small-button'), A::t('users', 'Remove'));
        echo CHtml::link(A::t('users', 'Cancel'), 'users/myAccount');
        echo CHtml::closeTag('div');
        echo CHtml::closeForm();
    }    
?>
</div>