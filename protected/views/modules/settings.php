<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Module Settings')));
	
	$this->_activeMenu = 'modules/settings/code/'.$module->code;
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Modules'), 'url'=>'modules/'),
		array('label'=>A::t($module->code, $module->name), 'url'=>'modules/settings/code/'.$module->code),
        array('label'=>A::t('app', 'Module Settings')),
    );
	
	$spinnersCount = 0;
?>

<h1><?= A::t('app', 'Module Settings').' / '.$module->name; ?></h1>	

<div class="bloc">
	<?= $tabs; ?>
	
    <div class="content">        
   	<?php 
    	echo $actionMessage;
        
		if(is_array($moduleSettings) && count($moduleSettings) > 0){
			// Open form
			$formName = 'frmModuleSettingsEdit';
			echo CHtml::openForm('modules/settings/code/'.$module->code, 'post', array('name'=>$formName, 'autoGenerateId'=>true));
			
			// Required fields alert
			if(Admins::hasPrivilege('modules', 'edit_management')) echo CHtml::tag('span', array('class'=>'required-fields-alert'), A::t('core','Items marked with an asterisk (*) are required'), true);
			
			// Hidden fields
			echo CHtml::hiddenField('act', 'send');
			echo CHtml::hiddenField('code', $module->code);
			?>
			<table id="tblModulesSettings">
			<thead>
			<tr>
				<th class="left" width="190px"><?= A::t('app', 'Name'); ?></th>
				<th class="left"><?= A::t('app', 'Description'); ?></th>
				<th class="right" width="300px"><?= A::t('app', 'Value'); ?>&nbsp;&nbsp;</th>
			</tr>
			</thead>
            </table>
            
            <table id="tblModulesSettings">
			<tbody>
			<?php
            $currentGroup = '';
			// Add settings fields for the module
			foreach($moduleSettings as $setting){				
				$hiddenField = CHtml::hiddenField('id_'.$setting['id'], $setting['id']);
				$settingName = A::t($setting['module_code'], $setting['name']).(($setting['is_required']) ? CHtml::$afterRequiredLabel : '');
				$fieldName = 'value_'.$setting['id'];
				$fieldValue = isset($valuesArray[$setting['id']]) ? $valuesArray[$setting['id']] : $setting['property_value'];
				$appendText = isset($setting['append_text']) ? $setting['append_text'] : '';
                
                if($currentGroup != $setting['property_group'] && $setting['property_group'] != ''){
                    echo '<tr><td class="property-group left" colspan="3"><div class="property-group-title">&bull; '.$setting['property_group'].' / '.A::t('app', 'Settings').':</div></td></tr>';
                }
                $currentGroup = $setting['property_group'];
                ?>
				<tr>
					<td class="left" width="180px"><?= CHtml::label($settingName, $fieldName); ?></td>
					<td class="left"><?= A::t($setting['module_code'], $setting['description']); ?></td>
					<td class="right" width="300px"> 
					<?php
                    echo $hiddenField;
                    
					if($errorField == $fieldName){
						A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$fieldName.'.focus();', 2);
					}

					if(!Admins::hasPrivilege('modules', 'edit_management')) $setting['property_type'] = 'readonly';
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
							echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'100', 'class'=>'email', 'placeholder'=>'email@example.com', 'autocomplete'=>'off'));
							break;
                        case 'phone':
                            echo CHtml::textField($fieldName, $fieldValue, array('maxlength'=>'32', 'class'=>'phone', 'placeholder'=>''));
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
					
					echo '&nbsp; '.A::t($module->code, $appendText);
					?>
					</td>
				</tr>
                <?php
                }
            ?>
			</tbody>
			</table>
			
			<?php if(Admins::hasPrivilege('modules', 'edit_management')){ ?>
			<div class="buttons-wrapper">
				<input value="<?= A::t('app', 'Update'); ?>" type="submit">
				<input class="button white" onclick="$(location).attr('href','modules/<?= ($module->is_system ? 'system' : 'application');?>');" value="<?= A::t('app', 'Cancel'); ?>" type="button">
			</div>
			<?php } ?>
		<?php 
			echo CHtml::closeForm();
		
			if($spinnersCount){
				A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
				A::app()->getClientScript()->registerScript($formName, 'var spinner = $(".spinner").spinner();', 2);
			}
		
		}else{
			echo CWidget::create('CMessage', array('info', A::t('app', 'No settings defined for this module'), array('button'=>false)));
		}
		?>
    </div>
</div>
