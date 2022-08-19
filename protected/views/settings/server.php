<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Server Info')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Server Info'))
    );   
?>
    
<h1><?php echo A::t('app', 'Server Info'); ?></h1>

<div class="bloc">

	<?php echo $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;		
	?>
	
	<fieldset>
		<legend><?php echo A::t('app', 'General Settings'); ?></legend>
		<ul>
			<li><b><?php echo A::t('app', 'PHP Version'); ?>:</b> <i><?php echo $phpVersion; ?></i></li>
			<li><b><?php echo $dbDriver.' '.A::t('app', 'Version'); ?>:</b> <i><?php echo $mysqlVersion; ?></i></li>
			<li><b><?php echo A::t('app', 'System'); ?>:</b> <i><?php echo $system; ?></i></li>
			<li><b><?php echo A::t('app', 'Build Date'); ?>:</b> <i><?php echo $buildDate; ?></i></li>
			<li><b><?php echo A::t('app', 'Server API'); ?>:</b> <i><?php echo $serverApi; ?></i></li>
			<li><b><?php echo A::t('app', 'Virtual Directory Support'); ?>:</b> <i><?php echo $vdSupport; ?></i></li>
            <li><b><?php echo A::t('app', 'Mode_Rewrite'); ?>:</b> <i><?php echo $modeRewrite; ?></i></li>
			<li><b><?php echo A::t('app', 'Safe Mode'); ?>:</b> <i><?php echo $safeMode; ?></i></li>
			<li><b><?php echo A::t('app', 'Post Max Size'); ?>:</b> <i><?php echo $postMaxSize; ?></i></li>
			<li><b><?php echo A::t('app', 'Upload Max Filesize'); ?>:</b> <i><?php echo $uploadMaxSize; ?></i></li>
		</ul>		
	</fieldset>	
	<fieldset>
		<legend><?php echo A::t('app', 'Important PHP Settings'); ?></legend>
		<ul>				
			<li><b><?php echo A::t('app', 'Asp Tags'); ?>:</b> <i><?php echo $aspTags; ?></i></li>
			<li><b><?php echo A::t('app', 'Short Open Tag'); ?>:</b> <i><?php echo $shortOpenTag; ?></i></li>				
			<li><b><?php echo A::t('app', 'Session Support'); ?>:</b> <i><?php echo $sessionSupport; ?></i></li>
			<li><b><?php echo A::t('app', 'Magic Quotes GPC'); ?>:</b> <i><?php echo $magicQuotesGpc; ?></i></li>
			<li><b><?php echo A::t('app', 'Magic Quotes RunTime'); ?>:</b> <i><?php echo $magicQuotesRuntime; ?></i></li>
			<li><b><?php echo A::t('app', 'Magic Quotes SyBase'); ?>:</b> <i><?php echo $magicQuotesSybase; ?></i></li>
		</ul>		
	</fieldset>
	<fieldset>
		<legend><?php echo A::t('app', 'SMTP Settings'); ?></legend>
		<ul>
			<li><b><?php echo A::t('app', 'SMTP'); ?>:</b> <i><?php echo $smtp; ?></i></li>
			<li><b><?php echo A::t('app', 'SMTP Port'); ?>:</b> <i><?php echo $smtpPort; ?></i></li>
			<li><b><?php echo A::t('app', 'Sendmail From'); ?>:</b> <i><?php echo $sendmailFrom; ?></i></li>
			<li><b><?php echo A::t('app', 'Sendmail Path'); ?>:</b> <i><?php echo $sendmailPath; ?></i></li>
		</ul>
	</fieldset>	
	</div>
</div>   

