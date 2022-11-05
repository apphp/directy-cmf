<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'Add New')));
	
	$this->_activeMenu = 'userGroups/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('users', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('users', 'Users'), 'url'=>$backendPath.'modules/settings/code/users'),
        array('label'=>A::t('users', 'User Groups Management'), 'url'=>'userGroups/manage'),
    );    
?>

<h1><?= A::t('users', 'Users Management'); ?></h1>

<div class="bloc">  	
	
	<?= $tabs; ?>
	
    <div class="content">
	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('users', 'add')){
	    	echo '<a href="userGroups/add" class="add-new">'.A::t('users', 'Add New').'</a>';
    	}
        
        echo CWidget::create('CGridView', array(
            'model'         => 'Modules\Users\Models\UserGroups',
            'actionPath'    => 'userGroups/manage',
            'passParameters'=> true,
            'pagination'    => array('enable'=>true, 'pageSize'=>14),
            'sorting'=>true,            
            'fields'=>array(               
               'name'        => array('title'=>A::t('users', 'Group Name'), 'type'=>'label', 'align'=>'', 'width'=>'210px', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
               'description' => array('title'=>A::t('users', 'Description'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),                 
               'is_default'  => array('title'=>A::t('users', 'Default'), 'type'=>'enum', 'class'=>'center ', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray badge-square">'.A::t('users', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('users', 'Yes').'</span>'), 'emptyOption'=>true, 'emptyValue'=>'', 'width'=>'100px'),
            ),
            'actions'=>array(
                'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('users', 'edit'),								
					'link'=>'userGroups/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('users', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('users', 'delete'),
					'link'=>'userGroups/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('users', 'Delete this record'), 'onDeleteAlert'=>true
				)
                
            ),
            
            'return'=>true,
        ));    ?>        
    </div>
</div>
