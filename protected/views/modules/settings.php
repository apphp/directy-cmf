<?php
	$this->_activeMenu = 'modules/settings/code/'.$module[0]['code'];
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Modules'), 'url'=>'modules/'),
		array('label'=>A::t('app', $module[0]['name']), 'url'=>'modules/settings/code/'.$module[0]['code']),
        array('label'=>A::t('app', 'Module Settings')),
    );
	
	$spinnersCount = 0;
?>

<h1><?php echo A::t('app', 'Module Settings').' / '.$module[0]['name']; ?></h1>	

<div class="bloc">
	<?php echo $tabs; ?>
	
    <div class="content">        
   	<?php 
    	echo $actionMessage; 
		if(is_array($moduleSettings) && count($moduleSettings) > 0){
			// open form
			$formName = 'frmModuleSettingsEdit';
			echo CHtml::openForm('modules/settings', 'post', array('name'=>$formName, 'autoGenerateId'=>true));
			
			// required fields alert
			if(Admins::hasPrivilege('modules', 'edit')) echo CHtml::tag('span', array('class'=>'required-fields-alert'), A::t('core','Items marked with an asterisk (*) are required'), true);
			
			// hidden fields
			echo CHtml::hiddenField('act', 'send');
			echo CHtml::hiddenField('code', $module[0]['code']);
			?>
			<table id="tblModulesSettings">
			<thead>
			<tr>
				<th class="left" style="width:180px;"><?php echo A::t('app', 'Name'); ?></th>
				<th class="left"><?php echo A::t('app', 'Description'); ?></th>
				<th class="right" style="width:235px;"><?php echo A::t('app', 'Value'); ?>&nbsp;&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php 
			// add settings fields for the module
			foreach($moduleSettings as $setting){
				
				echo CHtml::hiddenField('id_'.$setting['id'], $setting['id']);
				$settingName = $setting['name'].(($setting['is_required']) ? CHtml::$afterRequiredLabel : '');
				$fieldName = 'value_'.$setting['id'];
				$fieldValue = isset($valuesArray[$setting['id']]) ? $valuesArray[$setting['id']] : $setting['property_value'];
				
			?>
				<tr>
					<td class="left"><?php echo CHtml::label($settingName, $fieldName); ?></td>
					<td class="left"><?php echo $setting['description']; ?></td>
					<td class="right"> 
					<?php 
					if($errorField == $fieldName){
						A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$fieldName.'.focus();', 2);
					}

					if(!Admins::hasPrivilege('modules', 'edit')) $setting['property_type'] = 'readonly';
					switch($setting['property_type']){
						case 'text': 
							echo CHtml::textArea($fieldName, $fieldValue, array('maxlength'=>'1000'));
							break;
						case 'positive integer':
							echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'5', 'min'=>'0', 'max'=>'99999', 'class'=>'small spinner'));
							$spinnersCount++;
							break;
						case 'enum': 
							$source = array();
							foreach(explode(',', $setting['property_source']) as $propertySource){
								$source[$propertySource] = ucwords(str_replace(array('_', '-'), array(' ', ' / '), $propertySource));
							}
							echo CHtml::dropDownList($fieldName, $fieldValue, $source);
							break;
						case 'range':
							$sourceParts = explode('-', $setting['property_source']);
							$min = isset($sourceParts[0]) ? $sourceParts[0] : '';
							$max = isset($sourceParts[1]) ? $sourceParts[1] : '';
							echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'5', 'min'=>$min, 'max'=>$max, 'class'=>'small spinner'));
							$spinnersCount++;
							break;
						case 'bool':
							echo '<div class="slideBox">';
							echo CHtml::checkBox($fieldName, ($fieldValue ? true : false), array('uncheckValue'=>0, 'id'=>'chk_'.$fieldName));
							echo '<label for="chk_'.$fieldName.'"></label>';
							echo '</div>';
							break;
						case 'email': 
							echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'100', 'class'=>'email'));
							break;
						case 'string':	 
							echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'255')); 
							break;
						case 'label':	 
							echo CHtml::label($fieldValue, false, array('class'=>'module-value')); 
							break;
						case 'readonly':
							echo CHtml::label(ucwords(str_replace(array('_', '-'), array(' ', ' / '), $fieldValue)), false, array('class'=>'module-value')); 
							break;
						default:	 
							echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'10', 'class'=>'small'));
					}					
					?>
					&nbsp;
					</td>
				</tr>
			<?php } ?>
			</tbody>
			</table>
			
			<?php if(Admins::hasPrivilege('modules', 'edit')){ ?>
			<div class="buttons-wrapper">
				<input value="<?php echo A::t('app', 'Update'); ?>" type="submit">
				<input class="button white" onclick="$(location).attr('href','modules/<?php echo ($module[0]['is_system'] ? 'system' : 'application');?>');" value="<?php echo A::t('app', 'Cancel'); ?>" type="button">
			</div>
			<?php } ?>
		<?php 
			echo CHtml::closeForm();
		
			if($spinnersCount){
				A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
				A::app()->getClientScript()->registerScript($formName, 'var spinner = $(".spinner").spinner();', 2);
			}
		
		}else{
			echo CWidget::create('CMessage', array('info', A::t('app', 'No settings defined for this module'), array('button'=>false)));
		}
		?>
    </div>
</div>
