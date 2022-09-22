<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Server Info')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Server Info'))
    );   
?>
    
<h1><?= A::t('app', 'Server Info'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;		
	?>
	
	<fieldset>
		<legend><?= A::t('app', 'General Settings'); ?></legend>
		<ul>
			<li><b><?= A::t('app', 'PHP Version'); ?>:</b> <i><?= $phpVersion; ?></i></li>
			<li><b><?= $dbDriver.' '.A::t('app', 'Version'); ?>:</b> <i><?= $mysqlVersion; ?></i></li>
			<li><b><?= A::t('app', 'System'); ?>:</b> <i><?= $system; ?></i></li>
			<li><b><?= A::t('app', 'Build Date'); ?>:</b> <i><?= $buildDate; ?></i></li>
			<li><b><?= A::t('app', 'Server API'); ?>:</b> <i><?= $serverApi; ?></i></li>
			<li><b><?= A::t('app', 'Virtual Directory Support'); ?>:</b> <i><?= $vdSupport; ?></i></li>
            <li><b><?= A::t('app', 'Mode_Rewrite'); ?>:</b> <i><?= $modeRewrite; ?></i></li>
			<li><b><?= A::t('app', 'Safe Mode'); ?>:</b> <i><?= $safeMode; ?></i></li>
			<li><b><?= A::t('app', 'Post Max Size'); ?>:</b> <i><?= $postMaxSize; ?></i></li>
			<li><b><?= A::t('app', 'Upload Max Filesize'); ?>:</b> <i><?= $uploadMaxSize; ?></i></li>
		</ul>		
	</fieldset>	
	<fieldset>
		<legend><?= A::t('app', 'Important PHP Settings'); ?></legend>
		<ul>				
			<li><b><?= A::t('app', 'Asp Tags'); ?>:</b> <i><?= $aspTags; ?></i></li>
			<li><b><?= A::t('app', 'Short Open Tag'); ?>:</b> <i><?= $shortOpenTag; ?></i></li>				
			<li><b><?= A::t('app', 'Session Support'); ?>:</b> <i><?= $sessionSupport; ?></i></li>
			<?php if(version_compare(phpversion(), '5.3.0', '<')): ?>
				<li><b><?= A::t('app', 'Magic Quotes GPC'); ?>:</b> <i><?= $magicQuotesGpc; ?></i></li>
				<li><b><?= A::t('app', 'Magic Quotes RunTime'); ?>:</b> <i><?= $magicQuotesRuntime; ?></i></li>
				<li><b><?= A::t('app', 'Magic Quotes SyBase'); ?>:</b> <i><?= $magicQuotesSybase; ?></i></li>
			<?php endif; ?>
		</ul>		
	</fieldset>
	<fieldset>
		<legend><?= A::t('app', 'SMTP Settings'); ?></legend>
		<ul>
			<li><b><?= A::t('app', 'SMTP'); ?>:</b> <i><?= $smtp; ?></i></li>
			<li><b><?= A::t('app', 'SMTP Port'); ?>:</b> <i><?= $smtpPort; ?></i></li>
			<li><b><?= A::t('app', 'Sendmail From'); ?>:</b> <i><?= $sendmailFrom; ?></i></li>
			<li><b><?= A::t('app', 'Sendmail Path'); ?>:</b> <i><?= $sendmailPath; ?></i></li>
		</ul>
	</fieldset>	
	</div>
</div>   

