<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Application Modules')));
	
	$this->_activeMenu = 'modules/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('app', 'Modules Management')),
    );    
?>

<h1><?= A::t('app', 'Application Modules')?></h1>

<div class="bloc">
	<?= $tabs; ?>

    <div class="content">		
		<?= $actionMessage; ?>	
		
		<?php
		$totalcount = count($modulesList) +  count($notInstalledModulesList);
		if($totalcount > 0){
		?>
	    <table>
		<thead>
		<tr>
			<th class="left" colspan="2" style="width:110px;"><?= A::t('app', 'Module Name'); ?></th>
			<th class="left"><?= A::t('app', 'Description'); ?></th>
			<th class="center" style="width:110px;"><?= A::t('app', 'Installed'); ?></th>
			<th class="center" style="width:110px;"><?= A::t('app', 'Updated'); ?></th>
			<th class="center" style="width:75px;"><?= A::t('app', 'Version'); ?></th>
			<th class="center" style="width:80px;"><?= A::t('app', 'Status'); ?></th>
			<th class="center" style="width:65px;"><?= A::t('app', 'Order'); ?></th>
			<?php if(Admins::hasPrivilege('modules', 'edit_management')){ ?>
			<th class="actions"><?= A::t('app', 'Actions'); ?></th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
		<?php
			if(is_array($modulesList)){                
				foreach($modulesList as $module){
					// Prepare default module link
					$backendDefaultUrl = CConfig::get('modules.'.$module['code'].'.backendDefaultUrl');
					$moduleDefaultUrl = !empty($backendDefaultUrl) ? $backendDefaultUrl : 'modules/settings/code/'.$module['code'];

					echo '<tr>';
					echo '<td class="left" width="36px"><img src="assets/modules/'.$module['code'].'/images/'.$module['icon'].'" alt="icon" style="height:24px;margin-top:1px;" /></td>';
					echo '<td class="left">'.((Admins::hasPrivilege('modules', 'edit_management')) ? '<a href="'.$moduleDefaultUrl.'">'.A::t($module['code'], $module['name']).'</a>' : A::t($module['code'], $module['name'])).'</td>';
					echo '<td class="left">'.A::t($module['code'], $module['description']).'</td>';
					echo '<td class="center">'.(!CTime::isEmptyDateTime($module['installed_at']) ? '<span class="tooltip-link" title="'.CLocale::date($dateTimeFormat, $module['installed_at']).'">'.date($dateFormat, strtotime($module['installed_at'])).'</span>' : A::t('app', 'never')).'</td>';
					echo '<td class="center">'.(!CTime::isEmptyDateTime($module['updated_at']) ? '<span class="tooltip-link" title="'.CLocale::date($dateTimeFormat, $module['updated_at']).'">'.date($dateFormat, strtotime($module['updated_at'])).'</span>' : A::t('app', 'never')).'</td>';
					echo '<td class="center">'.$module['version'].'</td>';
					echo '<td class="center"><img src="templates/backend/images/'.($module['is_active'] ? 'enabled.png' : 'disabled.png').'" title="'.($module['is_active'] ? A::t('app', 'Enabled') : A::t('app', 'Disabled')).'" class="tooltip-link" alt="tooltip" height="16px" /></td>';
					echo '<td class="center">'.(int)$module['sort_order'].'</td>';
					
					if(Admins::hasPrivilege('modules', 'edit_management')){
                        echo '<td class="actions">';
                            $removalbe = (!CConfig::exists('modules.'.$module['code'].'.removable') || CConfig::get('modules.'.$module['code'].'.removable')) ? true : false;
                            $moduleVersion = isset($allModulesList[$module['code']]['version']) ? $allModulesList[$module['code']]['version'] : '';
                            echo (!empty($moduleVersion) && $module['version'] < $moduleVersion ? '<a href="modules/update/code/'.$module['code'].'" class="tooltip-link" title="'.A::t('app', 'Update to version {version}', array('{version}'=>$moduleVersion)).'"><img src="templates/backend/images/update.png" alt="update"></a>' : '');
                            echo '<a href="modules/edit/id/'.$module['id'].'" class="tooltip-link" title="'.A::t('app', 'Edit this record').'"><img src="templates/backend/images/edit.png" alt="edit"></a>';
                            if($removalbe){    
                                echo '<a href="modules/uninstall/id/'.$module['id'].'" class="tooltip-link" data-module="'.$module['name'].'" title="'.A::t('app', 'Uninstall this module').'" onclick="return onUninstallClick(this);"><img src="templates/backend/images/uninstall.png" alt="uninstall"></a>';
                            }
						echo '</td>';
					}
					echo '</tr>';
				}
			}
			// Not installed modules 
			if(is_array($notInstalledModulesList)){
				foreach($notInstalledModulesList as $code => $module){
					echo '<tr>
						<td class="left" width="36px"><img src="templates/backend/images/shortcuts/modules.png" alt="icon" height="24px" /></td>
						<td class="left">'.$module['name'].'</td>
						<td class="left">'.$module['description'].'</td>
						<td class="center">'.A::t('app', 'not yet').'</td>
						<td class="center">'.A::t('app', 'never').'</td>
						<td class="center">'.$module['version'].'</td>';
					
					if(Admins::hasPrivilege('modules', 'edit_management')){
						if(version_compare($frameworkVersion, $module['framework']) >= 0){
							echo '<td class="center"><img src="templates/backend/images/disabled.png" title="'.A::t('app', 'Disabled').'" class="tooltip-link" alt="tooltip" height="16px" /></td>';
							echo '<td class="center">--</td>';
							echo '<td class="actions">';                        
							if(!empty($module['framework']) && $module['framework'] > A::app()->getVersion()){
								echo '<span class="tooltip-link" title="'.A::te('core', 'Framework v'.A::app()->getVersion().' or higher required').'">'.A::te('app', 'Disabled').' [?]</span>';
							}else{
								echo '<a href="modules/install/code/'.$code.'" title="'.A::te('app', 'Click to install this module').'">'.A::t('app', 'Install').'</a>';
							}
							echo '</td>';
						}else{
							echo '<td class="right" colspan="3">'.A::t('app', 'Framework min. v{version} required!', array('{version}'=>$module['framework'])).'</td>';
						}						
					}
					echo '</tr>';
				}
			}
			?>
		</tbody>
		</table>
		<?php 	
		}else{
			echo CWidget::create('CMessage', array('info', A::t('app', 'No modules available'), array('button'=>false)));
		} 
		?>	
	</div>
	
</div>
<?php
A::app()->getClientScript()->registerScript(
	'module_uninstall',
	'function onUninstallClick(el){
		return confirm("'.A::t('app', 'Module').': "+$(el).data("module")+"\n'.A::t('app', 'Module Uninstall Alert').'");
	};',
	0
);
?>