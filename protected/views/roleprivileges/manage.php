<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Privileges Management')));
	
	$this->_activeMenu = 'roles/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Roles & Privileges'), 'url'=>'roles/'),
		array('label'=>$role->name),
    );
    
    A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
?>

<h1><?php echo A::t('app', 'Privileges Management'); ?></h1>

<div class="bloc">
	<div class="title"><?php echo $role->name.' / '.A::t('app', 'Privileges'); ?> <a class="back-link" href="roles/manage"><?php echo A::t('app', 'Back'); ?></a></div>
    <div class="content">

    <?php
	
		echo $actionMessage;
		
		$nl = "\n";

        $currentCategory = '';
        $currentModule = '';
        $currentModulesCount = 0;
		$buttons = '';
		if($role->code != 'owner'){
			$buttons = '<div class="buttons-wrapper">
				<input name="" value="'.A::t('app', 'Update').'" type="submit" />
				<input name="" class="button white" onclick="$(location).attr(\'href\',\'roles/manage\');" value="'.A::t('app', 'Cancel').'" type="button" />
				</div>';			
		}

        echo CHtml::openTag('div', array('id'=>'privilegesSettings'));
		echo CHtml::openForm('rolePrivileges/manage/role/'.$id, 'post', array('name'=>'frmPrivileges'));
		echo '<input type="hidden" name="act" value="send" />'.$nl;

		echo $buttons;
		echo '<fieldset>'.$nl;
        foreach($privileges as $key => $val){
            if($val['privilege_category'] == '') continue;
            
            $privilegeName = A::t('app', $val['privilege_name']);
            $privilegeDescription = A::t('app', $val['privilege_description']);
            $categoryName = A::t('app', $val['privilege_category']).'#'.$val['privilege_code'];
            
            // Draw privilege category frame
            if($currentCategory != $val['privilege_category']){
                if($currentCategory != '') echo '</fieldset>'.$nl;
            }

            // Draw module frame
            if($currentModule != $val['module_code']){
                if($currentModulesCount) echo '</fieldset>'.$nl; 
                echo '<fieldset>'.$nl;
                echo '<legend>'.A::t('app', 'Module').': '.ucfirst($val['module_code']).'</legend>'.$nl;
                $currentModulesCount++;
            }
            
            // Draw privilege category frame
            if($currentCategory != $val['privilege_category']){
                if($currentCategory != '') echo '<fieldset>'.$nl;
                echo '<legend>'.ucwords(str_replace('_', ' ', $val['privilege_category'])).'</legend>'.$nl;
            }

			echo '<div class="privilege-property">'; 			
			if($role->code == 'owner'){
				echo CHtml::tag('span', array('class'=>'badge-green', 'style'=>'float:left;margin-top:3px;text-transform:uppercase;'), '&nbsp;'.A::t('app', 'On'));
			}else{
				echo '<div class="slideBox">';
				echo CHtml::checkBox($categoryName, ($val['is_active'] ? true : false), array('uncheckValue'=>0, 'id'=>$privilegeName));
				echo '<label for="'.$privilegeName.'"></label>';
				echo '</div>';
			}
			echo '<label class="description tooltip-link" title="'.$privilegeDescription.'" for="'.$privilegeName.'">'.$privilegeName.'</label>';			
			echo '</div>'.$nl;
            $currentCategory = $val['privilege_category'];
            $currentModule = $val['module_code'];
        }
		echo '</fieldset>'.$nl;
        if($currentModulesCount) echo '</fieldset>'.$nl; 
		echo $buttons;
		
		echo CHtml::closeForm();
		echo CHtml::closeTag('div');       
    ?>
    
    </div>
</div>
    