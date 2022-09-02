<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Email Templates Management')));

    $this->_activeMenu = 'emailTemplates/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Mail Settings'), 'url'=>'emailTemplates/manage'),
        array('label'=>A::t('app', 'Email Templates')),
    );    
?>

<h1><?= A::t('app', 'Email Templates Management'); ?></h1>

<div class="bloc">
    <div class="title">
		<?= A::t('app', 'Email Templates'); ?>	
		<a class="feature-settings-link tooltip-link" title="<?= A::te('app', 'Email Settings');?>" href="settings/email"></a>
	</div>
    <div class="content">
	<?php 
		echo $actionMessage; 
			
		if(Admins::hasPrivilege('email_templates', 'edit')){
			echo '<a href="emailTemplates/add" class="add-new">'.A::t('app', 'Add New').'</a>';		    
		}
   
		echo CWidget::create('CGridView', array(
			'model'				=> 'EmailTemplates',
			'actionPath'		=> 'emailTemplates/manage',
			'defaultOrder'		=> array('is_system'=>'DESC', 'module_code'=>'ASC', 'template_name'=>'ASC'),
			'passParameters'	=> true,
			'pagination'		=> array('enable'=>true, 'pageSize'=>20),
			'sorting'			=> true,
			'filters'			=> array(
                'module_code'       => array('title'=>A::t('app', 'Module'), 'type'=>'enum', 'table'=>'', 'operator'=>'=', 'width'=>'100px', 'source'=>$modules),
                'template_name'     => array('title'=>A::t('app', 'Name'), 'type'=>'textbox', 'table'=>CConfig::get('db.prefix').'email_template_translations', 'operator'=>'%like%', 'default'=>'', 'width'=>'100px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'template_subject'  => array('title'=>A::t('app', 'Subject'), 'type'=>'textbox', 'table'=>CConfig::get('db.prefix').'email_template_translations', 'operator'=>'%like%', 'default'=>'', 'width'=>'150px', 'maxLength'=>'125', 'htmlOptions'=>array()),
                'template_content'  => array('title'=>A::t('app', 'Content'), 'type'=>'textbox', 'table'=>CConfig::get('db.prefix').'email_template_translations', 'operator'=>'%like%', 'default'=>'', 'width'=>'', 'maxLength'=>'', 'htmlOptions'=>array()),
            ),
			'fields'			=> array(
				'template_name'    => array('title'=>A::t('app', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'270px'),
				'template_subject' => array('title'=>A::t('app', 'Subject'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
				'module_code'      => array('title'=>A::t('app', 'Module'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px', 'definedValues'=>array(''=>A::t('app', 'system'))),
				'is_system'        => array('title'=>A::t('app', 'System Template'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'120px'),
			),
			'actions'			=> array(
				'edit'   		=> array(
					'disabled'		=> !Admins::hasPrivilege('email_templates', 'edit'),
					'link'			=> 'emailTemplates/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
				),
				'delete' 		=> array(
					'disabled'	=> !Admins::hasPrivilege('email_templates', 'edit'),
					'link'		=> 'emailTemplates/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
				)
			),
		));
	?>        
    </div>
</div>
        
