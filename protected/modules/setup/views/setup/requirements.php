<?php
	A::app()->view->setMetaTags('title', A::t('setup', 'Check Application Requirements - System Info and Important Settings'));
	
    $this->_activeMenu = $this->_controller.'/'.$this->_action;
?>

<h1><?php echo A::t('setup', 'Check Application Requirements - System Info and Important Settings'); ?></h1>
<p><?php echo A::t('setup', 'Requirements Notice'); ?></p>

<?php echo $notifyMessage; ?>

<fieldset>
<legend><?php echo A::t('setup', 'PHP Information'); ?></legend>
<ul>
    <li><?php echo A::t('setup', 'PHP Version'); ?>: <i><?php echo $phpVersion; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'PDO Extension'); ?>: <i><?php echo $pdoExtension; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
</ul>
</fieldset>

<fieldset>
<legend><?php echo A::t('setup', 'Server Information'); ?></legend>
<ul>
    <li><?php echo A::t('setup', 'System'); ?>: <i><?php echo $system; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'System Architecture'); ?>: <i><?php echo $systemArchitecture; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Build Date'); ?>: <i><?php echo $buildDate; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Server API'); ?>: <i><?php echo $serverApi; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
</ul>
</fieldset>

<fieldset>
<legend><?php echo A::t('setup', 'Important Settings'); ?></legend>
<ul>
    <li><?php echo A::t('setup', 'Virtual Directory Support'); ?>: <i><?php echo $vdSupport; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Mode_Rewrite'); ?>: <i><?php echo $modeRewrite; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'ASP Tags'); ?>: <i><?php echo $aspTags; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Safe Mode'); ?>: <i><?php echo $safeMode; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Short Open Tag'); ?>: <i><?php echo $shortOpenTag; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Session Support'); ?>: <i><?php echo $sessionSupport; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    
    <li><?php echo A::t('setup', 'Magic Quotes GPC'); ?>: <i><?php echo $magicQuotesGpc; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Magic Quotes Runtime'); ?>: <i><?php echo $magicQuotesRuntime; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'Magic Quotes Sybase'); ?>: <i><?php echo $magicQuotesSybase; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
</ul>
</fieldset>

<fieldset>
<legend><?php echo A::t('setup', 'Mail Server Settings'); ?></legend>
<ul>
    <li>SMTP: <i><?php echo $smtp; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
    <li><?php echo A::t('setup', 'SMTP Port'); ?>: <i><?php echo $smtpPort; ?></i> <span class="checked">&#10004; <?php echo A::t('setup', 'checked!'); ?></span></li>
</ul>
</fieldset>

<?php
    if(!$isCriticalError){
        echo CWidget::create('CFormView', array(
            'action'=>'setup/requirements',
            'method'=>'post',
            'htmlOptions'=>array(
                'name'=>'frmSetup',
            ),
            'fields'=>array(
                'act'=>array('type'=>'hidden', 'value'=>'send'),
            ),
            'buttons'=>array(
                'back'=>array('type'=>'button', 'value'=>A::t('setup', 'Previous'), 'htmlOptions'=>array('name'=>'', 'onclick'=>"$(location).attr('href','setup/index');")),
                'submit'=>array('type'=>'submit', 'value'=>A::t('setup', 'Next'), 'htmlOptions'=>array('name'=>''))
            ),
            'return'=>true,
        ));     
    }
?>
<br>
