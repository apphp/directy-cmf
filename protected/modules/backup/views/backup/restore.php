<?php
    Website::setMetaTags(array('title'=>A::t('backup', 'Restore Backup')));
	
	$this->_activeMenu = 'backup/restore';
    $this->_pageTitle = A::t('backup', 'Restore Backup').' | '.CConfig::get('name');
    $this->_breadCrumbs = array(
        array('label'=>A::t('backup', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('backup', 'Backup'), 'url'=>'modules/settings/code/backup'),
        array('label'=>A::t('backup', 'Restore')),
    );
	
	$disabled = !Admins::hasPrivilege('modules', 'edit');
?>

<h1><?= A::t('backup', 'Restore Backup')?></h1>	

<div class="bloc">
<?= $tabs; ?>
    
    <div class="content">
    <?= (!$disabled ? $actionMessage : ''); ?>

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
	                            '.(!$disabled ? '<td class="actions">[ <a class="tooltip-link subgrid-link" title="" href="backup/restore/file/'.$backup['fileName'].'" data-file="'.htmlspecialchars($backup['fileName']).'" onclick="return onRestoreClick(this);">'.A::t('backup', 'Restore').'</a> ] </td>' : '').'
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
	'backup_restore',
	'function onRestoreClick(el){
		return confirm("'.A::t('backup', 'Backup Restore Alert').'\n" + $(el).data("file"));
	};',
	0
);
?>

