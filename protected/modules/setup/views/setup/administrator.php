<?php
    $this->_activeMenu = $this->_controller.'/'.$this->_action;
?>

<h1><?php echo A::t('setup', 'Administrator Account'); ?></h1>
<p><?php echo A::t('setup', 'Administrator Account Notice'); ?></p>
    
<?php echo $actionMessage; ?>
<br>

<?php
    echo CWidget::create('CFormView', array(
        'action'=>'setup/administrator',
        'method'=>'post',
        'htmlOptions'=>array(
            'name'=>'frmSetup'
        ),
        'fields'=>array(
            'act'=>array('type'=>'hidden', 'value'=>'send'),            
            'email'=>array('type'=>'textbox', 'value'=>$email, 'title'=>A::t('setup', 'Email'), 'mandatoryStar'=>false, 'htmlOptions'=>array('maxLength'=>'100', 'autocomplete'=>'off')),
            'username'=>array('type'=>'textbox', 'value'=>$username, 'title'=>A::t('setup', 'Username'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'32', 'autocomplete'=>'off')),
            'password'=>array('type'=>'password', 'value'=>$password, 'title'=>A::t('setup', 'Password'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxLength'=>'20', 'autocomplete'=>'off')),
        ),
        'buttons'=>array(
            'back'=>array('type'=>'button', 'value'=>A::t('setup', 'Previous'), 'htmlOptions'=>array('name'=>'', 'onclick'=>"$(location).attr('href','setup/database');")),
            'submit'=>array('type'=>'submit', 'value'=>A::t('setup', 'Next'), 'htmlOptions'=>array('name'=>''))
        ),
        'events'=>array(
            'focus'=>array('field'=>$errorField)
        ),
        'return'=>true,
    ));

?>
<br>