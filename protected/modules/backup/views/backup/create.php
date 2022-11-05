<?php
    Website::setMetaTags(array('title'=>A::t('backup', 'Create Backup')));
	
	$this->_activeMenu = 'backup/create';
    $this->_pageTitle = A::t('backup', 'Create Backup').' | '.CConfig::get('name');
    $this->_breadCrumbs = array(
        array('label'=>A::t('backup', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('backup', 'Backup'), 'url'=>'modules/settings/code/backup'),
        array('label'=>A::t('backup', 'Create')),
    );
	
	$disabled = !Admins::hasPrivilege('modules', 'edit');
?>

<h1><?= A::t('backup', 'Create Backup')?></h1>	

<div class="bloc">
	<?= $tabs; ?>

    <div class="content">
    <?= $actionMessage; ?>
    
		<fieldset>
		    <legend><?= A::t('backup', 'Backup your current installation'); ?></legend>
			<?php		
			echo CWidget::create('CFormView', array(
				'action'=>'backup/create',
				'method'=>'post',				
				'htmlOptions'=>array(
					'name'=>'frmBackup',
					'autoGenerateId'=>true
				),
				'requiredFieldsAlert'=>true,
				'return'=>true,
				'fields'=>array(
					'act' => array('type'=>'hidden', 'value'=>'send'),
					'backupFileName' => array('type'=>'textbox', 'value'=>$backupFileName, 'title'=>A::t('backup', 'Backup File Name'), 'mandatoryStar'=>true, 'htmlOptions'=>array('maxlength'=>'32')),		            	
				),
				'buttons'=>array(
					'submit'=>array('disabled'=>$disabled, 'type'=>'submit', 'value'=>A::t('backup', 'Create Backup'))
				),
				'events'=>array(
					'focus'=>array('field'=>$errorField)
				),
			));
			?>
		</fieldset>
		
		<fieldset>
		    <legend><?= A::t('backup', 'Existing Backups:'); ?></legend>
			<table>
			<tbody>
			<?php 
				if(count($backupsList) == 0){
					echo '<tr><td>'.A::t('backup', 'No backups found').'</td></tr>';
				}else{
					echo '<thead>
						<th class="left">'.A::t('backup', 'File Name').'</th>
						<th class="right">'.A::t('backup', 'File Size').'</th>
						<th></th>
					</thead>';
					if(is_array($backupsList)){
						foreach($backupsList as $backup){
							echo '<tr>
								<td class="left">'.$backup['fileName'].'</td>
								<td class="right">'.$backup['fileSize'].'&nbsp;&nbsp;</td>
								'.(!$disabled ? '<td class="actions">[ <a class="tooltip-link subgrid-link" title="" href="backup/delete/file/'.$backup['fileName'].'" data-file="'.htmlspecialchars($backup['fileName']).'" onclick="return onDeleteClick(this);">'.A::t('backup', 'Delete').'</a> ] </td>' : '').'
							</tr>';
						}
					}
				}
			?>
			</tbody>
			</table>
		</fieldset>
		
    </div>
</div>
<?php
A::app()->getClientScript()->registerScript(
	'backup_delete',
	'function onDeleteClick(el){
		return confirm("'.A::t('backup', 'Backup Delete Alert').'\n" + $(el).data("file"));
	};',
	0
);
?>

