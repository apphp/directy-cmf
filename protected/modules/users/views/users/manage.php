<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'Users Management')));
	
	$this->_activeMenu = 'users/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('users', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('users', 'Users'), 'url'=>$backendPath.'modules/settings/code/users'),
        array('label'=>A::t('users', 'Users Management'), 'url'=>'users/manage'),
    );    
?>

<h1><?= A::t('users', 'Users Management'); ?></h1>

<div class="bloc">  	
	<?= $tabs; ?>
	
    <div class="content">
	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('users', 'add')){
	    	echo '<a href="users/add" class="add-new">'.A::t('users', 'Add New').'</a>';
    	}
        
        $fields = array();
        $filterFields = array();
        $condition = '';
        
        if(ModulesSettings::model()->param('users', 'field_last_name') !== 'no'){
            $fields['last_name'] = array('title'=>A::t('users', 'Last Name'), 'type'=>'label', 'align'=>'', 'width'=>'140px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');
            $filterFields['last_name'] = array('title'=>A::t('users', 'Last Name'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'100px', 'maxLength'=>'32');
        }
        if(ModulesSettings::model()->param('users', 'field_first_name') !== 'no'){
            $fields['first_name'] = array('title'=>A::t('users', 'First Name'), 'type'=>'label', 'align'=>'', 'width'=>'140px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');
        }

        $fields['username'] = array('title'=>A::t('users', 'Username'), 'type'=>'label', 'align'=>'', 'width'=>'170px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''); 
        $filterFields['username'] = array('title'=>A::t('users', 'Username'), 'type'=>'textbox', 'operator'=>'like%', 'default'=>'', 'width'=>'100px', 'maxLength'=>'25');

        if(ModulesSettings::model()->param('users', 'field_email') !== 'no'){
            $fields['email'] = array('title'=>A::t('users', 'Email'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>'');       
            $filterFields['email'] = array('title'=>A::t('users', 'Email'), 'type'=>'textbox', 'operator'=>'like%', 'width'=>'100px', 'maxLength'=>'100');
        }
        
        $fields['group_name'] = array('title'=>A::t('users', 'Group'), 'type'=>'label', 'align'=>'', 'width'=>'110px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(''=>'--'), 'format'=>'');       
        if(count($groups)) $filterFields['group_name'] = array('title'=>A::t('users', 'Group'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>$groups);

		$fields['is_active'] = array('title'=>A::t('users', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'users/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status')));
		
        $fields['id'] = array('title'=>A::t('users', 'ID'), 'type'=>'label', 'align'=>'center', 'width'=>'20px', 'class'=>'center', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''); 
        $filterFields['is_active'] = array('title'=>A::t('users', 'Active'), 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array(''=>'', '0'=>A::t('app', 'No'), '1'=>A::t('app', 'Yes')), 'emptyOption'=>true, 'emptyValue'=>'');

        if($removalType == 'logical' || $removalType == 'physical_or_logical'){
            $filterFields['is_removed'] = array('title'=>A::t('users', 'Removed'), 'type'=>'enum', 'operator'=>'=', 'default'=>'0', 'width'=>'', 'source'=>array('0'=>A::t('app', 'No'), '1'=>A::t('app', 'Yes')), 'emptyOption'=>true, 'emptyValue'=>'');
        }
        
        echo CWidget::create('CGridView', array(
            'model'         => 'Modules\Users\Models\Users',
            'actionPath'    => 'users/manage',
            'condition'     => $condition,
            'passParameters'=> true,
            'pagination'=>array('enable'=>true, 'pageSize'=>14),
            'sorting'=>true,            
            'filters'=>$filterFields,
            'fields'=>$fields,
            'actions'=>array(
                'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('users', 'edit'),								
					'link'=>'users/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('users', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>($removalType == 'logical' || !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('users', 'delete')),
					'link'=>'users/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('users', 'Delete this record'), 'onDeleteAlert'=>true
				)                
            ),
            
            'return'=>true,
        ));    ?>        
    </div>
</div>
