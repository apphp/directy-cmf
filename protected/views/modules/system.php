<?php
    Website::setMetaTags(array('title'=>A::t('app', 'System Modules')));
	
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

		<?php
		$totalcount = count($modulesList) +  count($notInstalledModulesList);
		if($totalcount > 0){
		?>
	    <table>
		<thead>
		<tr>
			<th class="left" colspan="2" style="width:110px;"><?php echo A::t('app', 'Module Name'); ?></th>
			<th class="left"><?php echo A::t('app', 'Description'); ?></th>
			<th class="center" style="width:110px;"><?php echo A::t('app', 'Installed'); ?></th>
			<th class="center" style="width:110px;"><?php echo A::t('app', 'Updated'); ?></th>
			<th class="center" style="width:80px;"><?php echo A::t('app', 'Version'); ?></th>
			<th class="center" style="width:85px;"><?php echo A::t('app', 'Status'); ?></th>
			<th class="center" style="width:70px;"><?php echo A::t('app', 'Order'); ?></th>
			<?php if(Admins::hasPrivilege('modules', 'edit_management')){ ?>
			<th class="actions"><?php echo A::t('app', 'Actions'); ?></th>
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
					echo '<td class="left" width="36px"><img src="images/modules/'.$module['code'].'/'.$module['icon'].'" alt="icon" style="height:24px;margin-top:1px;" /></td>';
					echo '<td class="left">'.((Admins::hasPrivilege('modules', 'edit_management')) ? '<a href="'.$moduleDefaultUrl.'">'.A::t($module['code'], $module['name']).'</a>' : A::t($module['code'], $module['name'])).'</td>';
                    echo '<td class="left">'.A::t($module['code'], $module['description']).'</td>';
					echo '<td class="center">'.(!CTime::isEmptyDateTime($module['installed_at']) ? '<span class="tooltip-link" title="'.CLocale::date($dateTimeFormat, $module['installed_at']).'">'.date($dateFormat, strtotime($module['installed_at'])).'</span>' : A::t('app', 'never')).'</td>';
					echo '<td class="center">'.(!CTime::isEmptyDateTime($module['updated_at']) ? '<span class="tooltip-link" title="'.CLocale::date($dateTimeFormat, $module['updated_at']).'">'.date($dateFormat, strtotime($module['updated_at'])).'</span>' : A::t('app', 'never')).'</td>';
					echo '<td class="center">'.$module['version'].'</td>';
					echo '<td class="center"><img src="templates/backend/images/enabled.png" title="'.A::t('app', 'Enabled').'" class="tooltip-link" alt="tooltip" height="16px" /></td>';
					echo '<td class="center">'.$module['sort_order'].'</td>';
					
					if(Admins::hasPrivilege('modules', 'edit_management')){
                        $moduleVersion = $allModulesList[$module['code']]['version'];
						echo '<td class="actions">';
                        if(!empty($moduleVersion) && $module['version'] < $moduleVersion) echo '<a href="modules/update/code/'.$module['code'].'" class="tooltip-link" title="'.A::t('app', 'Update to version {version}', array('{version}'=>$moduleVersion)).'"><img src="templates/backend/images/update.png" alt="update"></a>';
                        echo '<a class="tooltip-link" title="'.A::t('app', 'Edit this record').'" href="modules/edit/id/'.$module['id'].'"><img src="templates/backend/images/edit.png" alt="edit"></a>';
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
						<td class="center">'.$module['version'].'</td>
						<td class="center"><img src="templates/backend/images/disabled.png" title="'.A::t('app', 'Disabled').'" class="tooltip-link" alt="tooltip" height="16px" /></td>
						<td class="center"></td>';
					
					if(Admins::hasPrivilege('modules', 'edit_management')){
						echo '<td class="actions"><a href="modules/install/code/'.$code.'">'.A::t('app', 'Install').'</a></td>';
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
