<?php
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
			<li><b><?php echo A::t('app', 'PHP Version'); ?>:</b> <i><?php echo $phpversion; ?></i></li>
			<li><b><?php echo $dbType.' '.A::t('app', 'Version'); ?>:</b> <i><?php echo $mysql_version; ?></i></li>
			<li><b><?php echo A::t('app', 'System'); ?>:</b> <i><?php echo $system; ?></i></li>
			<li><b><?php echo A::t('app', 'Build Date'); ?>:</b> <i><?php echo $build_date; ?></i></li>
			<li><b><?php echo A::t('app', 'Server API'); ?>:</b> <i><?php echo $server_api; ?></i></li>
			<li><b><?php echo A::t('app', 'Virtual Directory Support'); ?>:</b> <i><?php echo $vd_support; ?></i></li>
			<li><b><?php echo A::t('app', 'Safe Mode'); ?>:</b> <i><?php echo $safe_mode; ?></i></li>
		</ul>		
	</fieldset>	
	<fieldset>
		<legend><?php echo A::t('app', 'Important PHP Settings'); ?></legend>
		<ul>				
			<li><b><?php echo A::t('app', 'Asp Tags'); ?>:</b> <i><?php echo $asp_tags; ?></i></li>
			<li><b><?php echo A::t('app', 'Short Open Tag'); ?>:</b> <i><?php echo $short_open_tag; ?></i></li>				
			<li><b><?php echo A::t('app', 'Session Support'); ?>:</b> <i><?php echo $session_support; ?></i></li>
			<li><b><?php echo A::t('app', 'Magic Quotes GPC'); ?>:</b> <i><?php echo $magic_quotes_gpc; ?></i></li>
			<li><b><?php echo A::t('app', 'Magic Quotes RunTime'); ?>:</b> <i><?php echo $magic_quotes_runtime; ?></i></li>
			<li><b><?php echo A::t('app', 'Magic Quotes SyBase'); ?>:</b> <i><?php echo $magic_quotes_sybase; ?></i></li>
		</ul>		
	</fieldset>
	<fieldset>
		<legend><?php echo A::t('app', 'SMTP Settings'); ?></legend>
		<ul>
			<li><b><?php echo A::t('app', 'SMTP'); ?>:</b> <i><?php echo $smtp; ?></i></li>
			<li><b><?php echo A::t('app', 'SMTP Port'); ?>:</b> <i><?php echo $smtp_port; ?></i></li>
			<li><b><?php echo A::t('app', 'Sendmail From'); ?>:</b> <i><?php echo $sendmail_from; ?></i></li>
			<li><b><?php echo A::t('app', 'Sendmail Path'); ?>:</b> <i><?php echo $sendmail_path; ?></i></li>
		</ul>
	</fieldset>	
	</div>
</div>   

