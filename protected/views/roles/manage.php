<?php
    $this->_activeMenu = 'roles/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Accounts'), 'url'=>'admins/'),
        array('label'=>A::t('app', 'Roles & Privileges'), 'url'=>'roles/'),
    );    
?>

<h1><?php echo A::t('app', 'Roles Management'); ?></h1>

<div class="bloc">
	<div class="title"><?php echo A::t('app', 'Roles'); ?></div>
    <div class="content">
		<?php 
			echo $actionMessage; 
			echo CWidget::create('CGridView', array(
                'model'=>'Roles',
                'actionPath'=>'roles/manage',
                'pagination'=>array('enable'=>false),
            	'condition'=>$rolesCondition,
				'sorting'=>true,
                'filters'=>array(),
                'fields'=>array(
                    'name'    		 => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'100px',),
                	'description'    => array('title'=>A::t('app', 'Description'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
                    'privileges_link'=> array('title'=>'', 'type'=>'link', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px', 'isSortable'=>false, 'linkUrl'=>'privileges/manage/role/{id}', 'linkText'=>A::t('app', 'Privileges')),
                ),
                'actions'=>array(
                    'edit'   => array('link'=>'roles/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')),
                ),
            ));
        
        ?>
    
    </div>
</div>
