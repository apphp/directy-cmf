<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Report Types Management')));
	
	$this->_activeMenu = 'reportsTypes/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Report Types Management'), 'url'=>'reportsTypes/manage'),
    );    
?>

<h1><?= A::t('reports', 'Report Types Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
    	echo $actionMessage;
		
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('reports', 'add')){
	    	echo '<a href="reportsTypes/add" class="add-new">'.A::t('reports', 'Add Report Type').'</a>';
    	}

        // Denied access to change status if user haven't edit page priviliges
        $is_active = array('title' => A::t('reports', 'Active'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '120px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Active')));
        if(Admins::hasPrivilege('reports', 'edit')){
            $is_active = array('title' => A::t('reports', 'Active'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '120px', 'linkUrl' => 'reportsTypes/changeStatus/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
        }

        echo CWidget::create('CGridView', array(
            'model'				=> 'Modules\Reports\Models\ReportsTypes',
            'actionPath'		=> 'reportsTypes/manage',
            'condition'			=> '',
            //'defaultOrder'	=> array('field_1'=>'DESC'),
            'passParameters'	=> true,
            'pagination'		=> array('enable'=>true, 'pageSize'=>20),
            'sorting'			=> true,
            'filters'			=> array(
                'name' 				=> array('title'=> A::t('reports', 'Report Type Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'120px', 'maxLength'=>'45'),
            ),
            'fields'			=> array(
				'index' 			=> array('title'=>'#', 'type'=>'index', 'align'=>'', 'width'=>'30px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false),
                'name' 				=> array('title'=> A::t('reports', 'Report Type Name'), 'type'=>'label', 'align'=>'', 'width'=>'210px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, ),
                'template_name' 	=> array('title'=> A::t('reports', 'Template'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, ),
                'links' 			=> array('title'=> '', 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'reportsTypeItems/manage/reportType/{id}', 'linkText'=>A::t('reports', 'Fields'), 'htmlOptions'=>array(), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'is_active' 		=> $is_active
            ),
            'actions'			=> array(
                'edit'    => array(
					'disabled'		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'edit'),
					'link'			=> 'reportsTypes/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('reports', 'Edit this record')
				),
                'delete'  => array(
					'disabled'		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'delete'),
					'link'			=> 'reportsTypes/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('reports', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'	=> true,
        ));
    ?>        
    </div>
</div>
