<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Ban Lists')));
	
    $this->_activeMenu = 'banLists/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Ban Lists')),
    );    
?>

<h1><?php echo A::t('app', 'Ban Lists Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Ban Lists'); ?></div>
    <div class="content">
	<?php 
		echo $actionMessage; 
			
		if(Admins::hasPrivilege('ban_lists', 'edit')){
			echo '<a href="banLists/add" class="add-new">'.A::t('app', 'Add New').'</a>';		    
		}
   
		echo CWidget::create('CGridView', array(
			'model'				=> 'BanLists',
			'actionPath'		=> 'banLists/manage',
			'defaultOrder'		=> array('id'=>'ASC'),
			'passParameters'	=> true,
			'pagination'		=> array('enable'=>true, 'pageSize'=>20),
			'sorting'			=> true,
			'filters'			=> array(
                'item_type'  		=> array('title'=>A::t('app', 'Type'), 'type'=>'enum', 'operator'=>'=', 'width'=>'100px', 'source'=>$itemTypes),
            ),
			'fields'			=> array(
				'item_value' 	=> array('title'=>A::t('app', 'Item'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'200px'),
				'reason' 	 	=> array('title'=>A::t('app', 'Ban Reason'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'maxLength'=>'70'),
				'item_type'  	=> array('title'=>A::t('app', 'Type'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'120px', 'case'=>'', 'definedValues'=>array('ip'=>A::t('app', 'IP Address'), 'email'=>A::t('app', 'Email'), 'email'=>A::t('app', 'Username'))),
				'started_at' 	=> array('title'=>A::t('app', 'Started'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'definedValues'=>array('0000-00-00'=>'--'), 'width'=>'150px', 'format'=>$dateFormat),
				'expires_at' 	=> array('title'=>A::t('app', 'Expires'), 'type'=>'datetime', 'class'=>'center', 'headerClass'=>'center', 'definedValues'=>array('0000-00-00'=>A::t('app', 'Never')), 'width'=>'150px', 'format'=>$dateFormat),
				'is_active'  	=> array('title'=>A::t('app', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'70px', 'class'=>'center', 'headerClass'=>'center', 'linkUrl'=>'banLists/changeStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
			),
			'actions'			=> array(
				'edit'   => array(
					'disabled'		=> !Admins::hasPrivilege('ban_lists', 'edit'),
					'link'			=> 'banLists/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
				),
				'delete' => array(
					'disabled'		=> !Admins::hasPrivilege('ban_lists', 'edit'),
					'link'			=> 'banLists/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
				)
			),
		));
	?>        
    </div>
</div>
