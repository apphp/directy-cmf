<?php
	// Set meta tags according to active language
	Website::setMetaTags(array('title'=>A::t('app', 'Dashboard')));

    $this->_activeMenu = 'backend/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/dashboard'),
        array('label'=>A::t('app', 'Dashboard')),
    );

	// Register FancyBox files
	A::app()->getClientScript()->registerScriptFile('assets/vendors/toastr/toastr.min.js', 2);
	A::app()->getClientScript()->registerCssFile('assets/vendors/toastr/toastr.min.css');
?>

<h1><?= A::t('app', 'Dashboard'); ?></h1>

<?php if(is_array($alerts) && count($alerts) > 0){ ?>
<div class="bloc" id="blocAlerts">
    <div class="title collapsible closable"><?= A::t('app', 'Alerts (action required)'); ?></div>
    <div class="content">
        <?php
            foreach($alerts as $alert => $alertInfo){
                echo CWidget::create('CMessage', array($alertInfo['type'], $alertInfo['message'], array('button'=>true, 'id'=>'alerts_msg_'.$alert)));    
            }
        ?>
        <div class="cb"></div>
    </div>
</div>
<?php } ?>

<?php if($dashboardHotkeys){ ?>
<div class="bloc" id="blocHotKeys">
    <div class="title collapsible"><?= A::t('app', 'Dashboard (hotkeys)'); ?></div>
    <div class="content sortable-content">		
        <?php if(Admins::hasPrivilege('site_settings', 'view')){ ?><a href="settings/" id="icon-backend" class="shortcut tooltip-link" title="<?= A::t('app', 'General Settings'); ?>"><?= CString::substr(A::t('app', 'Settings'), 12, false, true); ?><img src="templates/backend/images/shortcuts/settings.png" alt="settings" /></a><?php } ?>
        <?php if(Admins::hasPrivilege('backend_menu', 'view')){ ?><a href="backendMenus/" id="icon-backend-menus" class="shortcut tooltip-link" title="<?= A::t('app', 'Backend Menu'); ?>"><?= CString::substr(A::t('app', 'Backend Menu'), 12, false, true); ?><img src="templates/backend/images/shortcuts/backend-menus.png" alt="backend menu" /></a><?php } ?>
        <?php if(Admins::hasPrivilege('frontend_menu', 'view')){ ?><a href="frontendMenus/" id="icon-frontend-menus" class="shortcut tooltip-link" title="<?= A::t('app', 'Frontend Menu'); ?>"><?= CString::substr(A::t('app', 'Frontend Menu'), 12, false, true); ?><img src="templates/backend/images/shortcuts/frontend-menus.png" alt="frontend menu" /></a><?php } ?>
		<?php if(Admins::hasPrivilege('social_networks', 'view')){ ?><a href="socialNetworks/" id="icon-backend-menus" class="shortcut tooltip-link" title="<?= A::t('app', 'Social Networks'); ?>"><?= CString::substr(A::t('app', 'Social Networks'), 12, false, true); ?><img src="templates/backend/images/shortcuts/social-networks.png" alt="social networks/" /></a><?php } ?>
        <?php if(Admins::hasPrivilege('locations', 'view')){ ?><a href="locations/" id="icon-locations" class="shortcut tooltip-link" title="<?= A::t('app', 'Locations'); ?>"><?= CString::substr(A::t('app', 'Locations'), 12, false, true); ?><img src="templates/backend/images/shortcuts/locations.png" alt="locations" /></a><?php } ?>
		<?php if(Admins::hasPrivilege('currencies', 'view')){ ?><a href="currencies/" id="icon-currencies" class="shortcut tooltip-link" title="<?= A::t('app', 'Currencies'); ?>"><?= CString::substr(A::t('app', 'Currencies'), 12, false, true); ?><img src="templates/backend/images/shortcuts/currencies.png" alt="currencies" /></a><?php } ?>
		<?php if(Admins::hasPrivilege('payment_providers', 'view')){ ?><a href="paymentProviders/" id="icon-payment-providers" class="shortcut tooltip-link" title="<?= A::t('app', 'Payment Providers'); ?>"><?= CString::substr(A::t('app', 'Payment Providers'), 12, false, true); ?><img src="templates/backend/images/shortcuts/payment-providers.png" alt="payment providers" /></a><?php } ?>
		<?php if(Admins::hasPrivilege('email_templates', 'view')){ ?><a href="emailTemplates/" id="icon-email-templates" class="shortcut tooltip-link" title="<?= A::t('app', 'Email Templates'); ?>"><?= CString::substr(A::t('app', 'Email Templates'), 12, false, true); ?><img src="templates/backend/images/shortcuts/email-templates.png" alt="email templates" /></a><?php } ?>
		<?php if(Admins::hasPrivilege('mailing_log', 'view')){ ?><a href="mailingLog/" id="icon-mailing-log" class="shortcut tooltip-link" title="<?= A::t('app', 'Mailing Log'); ?>"><?= CString::substr(A::t('app', 'Mailing Log'), 12, false, true); ?><img src="templates/backend/images/shortcuts/mailing-log.png" alt="mailing log" /></a><?php } ?>
		<?php if(Admins::hasPrivilege('ban_lists', 'view')){ ?><a href="banLists/" id="icon-ban-lists" class="shortcut tooltip-link" title="<?= A::t('app', 'Ban Lists'); ?>"><?= CString::substr(A::t('app', 'Ban Lists'), 12, false, true); ?><img src="templates/backend/images/shortcuts/ban-lists.png" alt="ban lists" /></a><?php } ?>		
		<?php if(CAuth::isLoggedInAs('owner','mainadmin')){ ?><a href="admins/" id="icon-admins" class="shortcut tooltip-link" title="<?= A::t('app', 'Admins'); ?>"><?= CString::substr(A::t('app', 'Admins'), 12, false, true); ?><img src="templates/backend/images/shortcuts/admins.png" alt="admins" /></a><?php } ?>
		<?php if(CAuth::isLoggedInAs('owner','mainadmin')){ ?><a href="roles/" id="icon-roles" class="shortcut tooltip-link" title="<?= A::t('app', 'Roles & Privileges'); ?>"><?= CString::substr(A::t('app', 'Roles'), 12, false, true); ?><img src="templates/backend/images/shortcuts/roles.png" alt="roles" /></a><?php } ?>
		<a href="admins/myAccount/" id="icon-my-account" class="shortcut tooltip-link" title="<?= A::t('app', 'My Account'); ?>"><?= CString::substr(A::t('app', 'My Account'), 12, false, true); ?><img src="templates/backend/images/shortcuts/my_account.png" alt="my account" /></a>
        <?php if(Admins::hasPrivilege('languages', 'view')){ ?><a href="languages/" id="icon-languages" class="shortcut tooltip-link" title="<?= A::t('app', 'Languages'); ?>"><?= CString::substr(A::t('app', 'Languages'), 12, false, true); ?><img src="templates/backend/images/shortcuts/languages.png" alt="languages" /></a><?php } ?>
        <?php if(Admins::hasPrivilege('vocabulary', 'view')){ ?><a href="vocabulary/" id="icon-vocabulary" class="shortcut tooltip-link" title="<?= A::t('app', 'Vocabulary'); ?>"><?= CString::substr(A::t('app', 'Vocabulary'), 12, false, true); ?><img src="templates/backend/images/shortcuts/vocabulary.png" alt="vocabulary" /></a><?php } ?>
        <?php if(Admins::hasPrivilege('modules', 'view')){ ?><a href="modules/" id="icon-modules" class="shortcut tooltip-link" title="<?= A::t('app', 'Modules'); ?>"><?= CString::substr(A::t('app', 'Modules'), 12, false, true); ?><img src="templates/backend/images/shortcuts/modules.png" alt="modules" /></a><?php } ?>
		<?php
			if(is_array($modulesToShow)){
				foreach($modulesToShow as $module){
					if(!Admins::hasPrivilege('modules', 'view')) continue;				
					echo '<a href="modules/settings/code/'.$module['code'].'/" id="icon-'.$module['code'].'" class="shortcut tooltip-link" title="'.$module['name'].'">'.CString::substr($module['name'], 12, false, true).'<img src="assets/modules/'.$module['code'].'/images/'.$module['icon'].'" alt="icon" /></a>';
				}
			}
		?>
        <div class="cb"></div>
    </div>
</div>    
<?php } ?>

<?php if($dashboardNotifications){ ?>
<div class="bloc<?= ($dashboardStatistics ? ' bloc-left' : ''); ?>" id="blocNotifications">
    <div class="title collapsible"><?= A::t('app', 'Whats new'); ?></div>
    <div class="content">
	<?php
		$notificationsCount = 0;
		if(is_array($systemNotifications)){
			foreach($systemNotifications as $notification){
				echo '<h5>'.$notification['date'].'</h5>';
				echo '<ul>';
				echo '<li>';
				echo $notification['title'];
				echo (!empty($notification['title']) && !empty($notification['content'])) ? '<br>' : '';
				echo $notification['content'];
				echo '</li>';
				echo '</ul>';
				$notificationsCount++;
			}
		}
		if(!$notificationsCount) echo '<b>'.A::t('app', 'No notifications yet').'</b>';
	?>
    </div>
</div>
<?php } ?>

<?php if($dashboardStatistics){ ?>
<div class="bloc<?= ($dashboardNotifications ? ' bloc-right' : ''); ?>" id="blocStatistics">
    <?php
        $tabStat1 = '<table>
        <tbody>
            <tr><td width="30%">'.A::t('app', 'Today').': </td><td><b>'.$todayDate.'</b></td></tr>
            <tr><td>'.A::t('app', 'Application').': </td><td><b>'.$scriptName.'</b></td></tr>
            <tr><td>'.A::t('app', 'Version').': </td><td><b>'.$scriptVersion.'</b></td></tr>
			<tr><td>'.A::t('app', 'Framework').': </td><td><b>'.A::t('app', 'ApPHP MVC Framework v').A::app()->version().'</b></td></tr>';
			// Show active sessions
			if($customStorage){
				$tabStat1 .= '<tr><td>'.A::t('app', 'Active Sessions').': </td><td><b>'.$activeSessions.'</b></td></tr>';
			}
		$tabStat1 .= '</tbody>
        </table>';

        $tabStat2 = '<table>
        <tbody>
            <tr><td width="30%">'.A::t('app', 'Last Login').': </td><td><b>'.$lastLoginDate.'</b></td></tr>
        </tbody>
        </table>';
        
        $tabStat3 = '<table>
            <tr><td width="30%">'.A::t('app', 'Total Admins').': </td><td><b>'.$adminsCount.'</b></td></tr>';
			
			$tabStat3 .= '<tr><td>'.A::t('app', 'Last 5 Registered Admins').': </td><td>';
			$count = 0;
			foreach($lastAdminsList as $admin){
				$tabStat3 .= $count++ ? ',' : '';
				$tabStat3 .= '<a href="admins/manage?username='.CHtml::encode($admin).'&but_filter=Filter">'.$admin.'</a>';	
			}
			$tabStat3 .= '</td></tr>';
			
			$tabStat3 .= '<tr><td>'.A::t('app', 'Last 5 Changed Password').': </td><td>';			
			$count = 0;
			foreach($changedPasswordAdminsList as $admin){
				$tabStat3 .= $count++ ? ',' : '';
				$tabStat3 .= '<a href="admins/manage?username='.CHtml::encode($admin).'&but_filter=Filter">'.$admin.'</a>';	
			}
			$tabStat3 .= '</td></tr>
        </tbody>
        </table>';

		$tabs = array();
        $tabs[A::t('app', 'General Info')]	= array('href'=>'#tabStat1', 'id'=>'tabStat1', 'content'=>$tabStat1);
        $tabs[A::t('app', 'My Account')]   	= array('href'=>'#tabStat2', 'id'=>'tabStat2', 'content'=>$tabStat2);
		if(CAuth::isLoggedInAs('owner')){
			$tabs[A::t('app', 'Admins Statistics')] = array('href'=>'#tabStat3', 'id'=>'tabStat3', 'content'=>$tabStat3);
		}

		echo CWidget::create('CTabs', array(
            'tabsWrapper'=>array('tag'=>'div', 'class'=>'title collapsible'),
            'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs static', 'id'=>'blockStatistics'),
            'contentWrapper'=>array('tag'=>'div', 'class'=>'content'),
            'contentMessage'=>'',
            'tabs'=>$tabs,
            'events'=>array(
                //'click'=>array('field'=>$errorField)
            ),
            'return'=>true,
        ));        
    ?>
</div>
<?php } ?>

<div class="cb"></div>
<br><br>

<?php

A::app()->getClientScript()->registerScript(
	'toastrMessage',
	'// Menu message
	var alertMessage = "'.A::te('app', 'Dashboard (hotkeys) order has beed changed!').'";
	jQuery(document).ready(function(){
		// Init		
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-top-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut",
			"rtl": '.(A::app()->getLanguage('direction') == 'rtl' ? 'true' : 'false').'
		}
	});',
	1
);

?>