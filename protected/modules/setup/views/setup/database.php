<?php
	A::app()->view->setMetaTags('title', A::t('setup', 'Database Settings'));
	
    $this->_activeMenu = $this->_controller.'/'.$this->_action;
?>

<h1><?= A::t('setup', 'Database Settings'); ?></h1>
<p><?= A::t('setup', 'Database Settings Notice'); ?></p>

<?= $actionMessage; ?>
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
	
<?php
	A::app()->getClientScript()->registerScript(
		'setup-database',
		'// Reload page
		jQuery(\'select[name="dbConnectType"]\').on("change", function(){
			jQuery(this).closest("form").submit();
		});
		',
		2
	);
?>
