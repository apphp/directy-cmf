<?php
    $this->_activeMenu = 'modules/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('app', 'Modules Management')),
    );    
?>

<h1><?php echo A::t('app', 'Application Modules')?></h1>

<div class="bloc">
	<?php echo $tabs; ?>

    <div class="content">		
		<?php echo $actionMessage; ?>	
		
		<?php
		$totalcount = count($modulesList) +  count($notInstalledModulesList);
		if($totalcount > 0){
		?>
	    <table>
		<thead>
		<tr>
			<th class="left" colspan="2" style="width:110px;"><?php echo A::t('app', 'Module Name'); ?></th>
			<th class="left"><?php echo A::t('app', 'Description'); ?></th>
			<th class="center" style="width:80px;"><?php echo A::t('app', 'Version'); ?></th>
			<th class="center" style="width:80px;"><?php echo A::t('app', 'Status'); ?></th>
			<?php if(Admins::hasPrivilege('modules', 'edit_management')){ ?>
			<th class="actions"><?php echo A::t('app', 'Actions'); ?></th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
		<?php
			if(is_array($modulesList)){                
				foreach($modulesList as $module){
					echo '<tr>';
					echo '<td class="left" width="36px"><img src="images/modules/'.$module['code'].'/'.$module['icon'].'" alt="icon" style="height:24px;margin-top:1px;" /></td>';
					echo '<td class="left">'.((Admins::hasPrivilege('modules', 'edit_management')) ? '<a href="modules/settings/code/'.$module['code'].'">'.A::t($module['code'], $module['name']).'</a>' : A::t($module['code'], $module['name'])).'</td>';
					echo '<td class="left">'.A::t($module['code'], $module['description']).'</td>';
					echo '<td class="center">'.$module['version'].'</td>';
					echo '<td class="center"><img src="templates/backend/images/'.($module['is_active'] ? 'enabled.png' : 'disabled.png').'" title="'.($module['is_active'] ? A::t('app', 'Enabled') : A::t('app', 'Disabled')).'" class="tooltip-link" alt="tooltip" height="16px" /></td>';
					
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
			// not installed modules 
			if(is_array($notInstalledModulesList)){
				foreach($notInstalledModulesList as $code => $module){
					echo '<tr>
						<td class="left" width="36px"><img src="templates/backend/images/shortcuts/modules.png" alt="icon" height="24px" /></td>
						<td class="left">'.$module['name'].'</td>
						<td class="left">'.$module['description'].'</td>
						<td class="center">'.$module['version'].'</td>
						<td class="center"><img src="templates/backend/images/disabled.png" title="'.A::t('app', 'Disabled').'" class="tooltip-link" alt="tooltip" height="16px" /></td>';
					
					if(Admins::hasPrivilege('modules', 'edit_management')){
                        echo '<td class="actions">';                        
						echo '<a href="modules/install/code/'.$code.'" title="'.A::t('app', 'Click to install this module').'">'.A::t('app', 'Install').'</a>';
                        echo '</td>';
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