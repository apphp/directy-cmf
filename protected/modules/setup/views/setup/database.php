<?php
	A::app()->view->setMetaTags('title', A::t('setup', 'Database Settings'));
	
    $this->_activeMenu = $this->_controller.'/'.$this->_action;
?>

<h1><?php echo A::t('setup', 'Database Settings'); ?></h1>
<p><?php echo A::t('setup', 'Database Settings Notice'); ?></p>

<?php echo $actionMessage; ?>
<br>

<?php
    echo CWidget::create('CFormView', array(
        'action'=>'setup/database',
        'method'=>'post',
        'htmlOptions'=>array(
            'name'=>'frmSetup',
            'id'=>'frmSetup'
        ),
        'fields'=>$formFields,
        'buttons'=>array(
            'back'=>array('type'=>'button', 'value'=>A::t('setup', 'Previous'), 'htmlOptions'=>array('name'=>'', 'onclick'=>"$(location).attr('href','setup/requirements');")),
            'submit'=>array('type'=>'submit', 'value'=>A::t('setup', 'Next'), 'htmlOptions'=>array('name'=>''))
        ),
        'events'=>array(
            'focus'=>array('field'=>$errorField)
        ),
        'return'=>true,
    )); 
?>
<br> 