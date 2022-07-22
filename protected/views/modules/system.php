<?php
    $this->_activeMenu = 'modules/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('app', 'Modules Management')),
    );    		
?>

<h1><?php echo A::t('app', 'System Modules')?></h1>	

<div class="bloc">
	<?php echo $tabs; ?>
	
    <div class="content">
		<?php echo $actionMessage; ?>	

	    <table>
		<thead>
		<tr>
			<th class="left" colspan="2" style="width:110px;"><?php echo A::t('app', 'Module Name'); ?></th>
			<th class="left"><?php echo A::t('app', 'Description'); ?></th>
			<th class="center" style="width:80px;"><?php echo A::t('app', 'Version'); ?></th>
			<th class="center" style="width:80px;"><?php echo A::t('app', 'Status'); ?></th>
			<?php if(Admins::hasPrivilege('modules', 'edit')){ ?>
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
					echo '<td class="left">'.((Admins::hasPrivilege('modules', 'edit')) ? '<a href="modules/settings/code/'.$module['code'].'">'.A::t($module['code'], $module['name']).'</a>' : A::t($module['code'], $module['name'])).'</td>';
                    echo '<td class="left">'.A::t($module['code'], $module['description']).'</td>';
					echo '<td class="center">'.$module['version'].'</td>';
					echo '<td class="center"><img src="templates/backend/images/enabled.png" title="'.A::t('app', 'Enabled').'" class="tooltip-link" alt="tooltip" height="16px" /></td>';
					
					if(Admins::hasPrivilege('modules', 'edit')){
                        $moduleVersion = $allModulesList[$module['code']]['version'];
						echo '<td class="actions">';
                        if(!empty($moduleVersion) && $module['version'] < $moduleVersion) echo '<a href="modules/update/code/'.$module['code'].'" class="tooltip-link" title="'.A::t('app', 'Update to version {version}', array('{version}'=>$moduleVersion)).'"><img src="templates/backend/images/update.png" alt="update"></a>';
                        echo '<a class="tooltip-link" title="'.A::t('app', 'Edit this record').'" href="modules/edit/id/'.$module['id'].'"><img src="templates/backend/images/edit.png" alt="edit"></a>';
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
					
					if(Admins::hasPrivilege('modules', 'edit')){
						echo '<td class="actions"><a href="modules/install/code/'.$code.'">'.A::t('app', 'Install').'</a></td>';
					}
					echo '</tr>';
				}
			}
			?>
		</tbody>
		</table>
	</div>
</div>
