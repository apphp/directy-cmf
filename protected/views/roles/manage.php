<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Roles Management')));
	
	$this->_activeMenu = 'roles/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Roles & Privileges'), 'url'=>'roles/'),
    );    
?>

<h1><?= A::t('app', 'Roles Management'); ?></h1>

<div class="bloc">
	<div class="title"><?= A::t('app', 'Roles'); ?></div>
    <div class="content">
		<?php 
			echo $actionMessage; 

		    if(CAuth::isLoggedInAs('owner')){
			    echo '<a href="roles/add" class="add-new">'.A::t('app', 'Add New').'</a>';
		    }

			echo CWidget::create('CGridView', array(
                'model'			=> 'Roles',
                'actionPath'	=> 'roles/manage',
                'pagination'	=> array('enable'=>false),
            	'condition'		=> $rolesCondition,
				'sorting'		=> true,
                'filters'		=> array(),
                'fields'		=> array(
                    'name'    		  => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'120px',),
                	'description'     => array('title'=>A::t('app', 'Description'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
                    'privileges_link' => array('title'=>'', 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'130px', 'isSortable'=>false, 'linkUrl'=>'rolePrivileges/manage/role/{id}', 'linkText'=>A::t('app', 'Privileges'), 'htmlOptions'=>array('class'=>'subgrid-link'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
					'is_system'       => array('title'=>A::t('app', 'System Role'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'120px'),
                ),
                'actions'		=> array(
                    'edit'   		 => array('link'=>'roles/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')),
					'delete' 		 => array('link'=>'roles/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true)
                ),
            ));        
        ?>    
    </div>
</div>
