<?php
	Website::setMetaTags(array('title'=>A::t('cms', 'Pages Management')));

    $this->_pageTitle = A::t('cms', 'Pages Management').' | '.CConfig::get('name');
    $this->_activeMenu = 'pages/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('cms', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('cms', 'CMS'), 'url'=>$backendPath.'modules/settings/code/cms'),
        array('label'=>A::t('cms', 'Pages Management')),
    );    
?>

<h1><?= A::t('cms', 'Pages Management')?></h1>	

<div class="bloc">
	<?= $tabs; ?>

    <div class="content">
   	<?php 
    	echo $actionMessage;  
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('pages', 'add')){
	    	echo '<a href="pages/add" class="add-new">'.A::t('cms', 'Add New').'</a>';
    	}
 
    	echo CWidget::create('CGridView', array(
			'model'			=> '\Modules\Cms\Models\Pages',
			'actionPath'	=> 'pages/manage',
			'pagination'	=> array('enable'=>true, 'pageSize'=>20),
			'sorting'		=> true,
			'defaultOrder'	=> array('is_homepage'=>'DESC', 'sort_order'=>'ASC'),
			'passParameters'=>true,
			'fields'=>array(
				'page_header'   	=> array('title'=>A::t('cms', 'Page Header'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'format'=>'', 'stripTags'=>true),
				'publish_status' 	=> array('title'=>A::t('cms', 'Published'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'linkUrl'=>'pages/changeStatus/id/{id}/page/{page}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
				'is_homepage'		=> array('title'=>A::t('cms', 'Home Page'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray badge-square">'.A::t('cms', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('cms', 'Yes').'</span>'), 'width'=>'100px'),
				'sort_order'    	=> array('title'=>A::t('cms', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
				'id'    			=> array('title'=>A::t('cms', 'ID'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'50px'),
			),
			'actions'=>array(
				'edit'   => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('pages', 'edit'),
					'link'=>'pages/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('cms', 'Edit this page')
				),
				'delete' => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('pages', 'delete'),
					'link'=>'pages/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('cms', 'Delete this page'), 'onDeleteAlert'=>true
				)
			),
		));
    	?>
    </div>
</div>
