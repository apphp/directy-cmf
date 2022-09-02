<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Templates Settings')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Templates Settings'))
    );    
?>
    
<h1><?= A::t('app', 'Templates Settings'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
		<?= $actionMessage; ?>

		<div class="left-side">
		<?php 
			$templatesList = array();
			if(is_array($allTemplates)){
				foreach($allTemplates as $temp){
					if(!in_array($temp, array('backend', 'setup'))){
						$templatesList[$temp] = ucfirst($temp);  			
					}
				}
			}
			echo CWidget::create('CFormView', array(
				'action'=>'settings/templates',
				'method'=>'post',				
				'htmlOptions'=>array(
					'name'=>'frmTemplates',
					'autoGenerateId'=>true
				),
				'requiredFieldsAlert'=>false,
				'fields'=>array(
					'act'      =>array('type'=>'hidden', 'value'=>'send'),
					'template' =>array('type'=>'select', 'value'=>$selectedTemplate, 'title'=>A::t('app', 'Template'), 'tooltip'=>A::t('app', 'Template Tooltip'), 'data'=>$templatesList, 'mandatoryStar'=>true, 'htmlOptions'=>array('submit'=>'$(this).closest("form").find("input[name=act]").val("changeTemp");$(this).closest("form").submit();')),
				),
				'buttons'=>Admins::hasPrivilege('site_settings', 'edit') ? 
					array(
						'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')))
					: array(),
				'events'=>array(
					'focus'=>array('field'=>$errorField)
				),
				'return'=>true,
			));
		?>
	    </div>
	
	    <div class="central-part">
			<img class="template" src="<?= $icon; ?>" alt="<?= A::t('app', 'Template Preview'); ?>">
		</div>
		
	    <div class="right-side">
			<ul>
				<li><b><?= A::t('app', 'Name'); ?>:</b> <i><?= $name; ?></i></li>
				<li><b><?= A::t('app', 'Description'); ?>:</b> <i><?= $description; ?></i></li>
				<li><b><?= A::t('app', 'Author'); ?>:</b> <i><?= $author; ?></i></li>
				<li><b><?= A::t('app', 'License'); ?>:</b> <i><?= $license; ?></i></li>
				<li><b><?= A::t('app', 'Version'); ?>:</b> <i><?= $version; ?></i></li>
				<li><b><?= A::t('app', 'Layout'); ?>:</b> <i><?= $layout; ?></i></li>
				<li><b><?= A::t('app', 'Text Direction'); ?>:</b> <i><?= $textDirection; ?></i></li>
				<li><b><?= A::t('app', 'Menus'); ?>:</b> <i><?= $menus; ?></i></li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
</div>   

