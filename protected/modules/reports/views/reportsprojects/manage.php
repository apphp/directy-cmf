<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Projects Management')));
	
	$this->_activeMenu = 'reportsProjects/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Projects Management'), 'url'=>'reportsProjects/manage'),
    );    
?>

<h1><?= A::t('reports', 'Projects Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>

    <div class="content">
	<?php 
    	echo $actionMessage;
		
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('reports', 'add')){
	    	echo '<a href="reportsProjects/add" class="add-new">'.A::t('reports', 'Add Projects').'</a>';
    	}

        // Denied access to change status if user haven't edit page priviliges
        $is_active = array('title' => A::t('reports', 'Active'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '60px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Active')));
        if(Admins::hasPrivilege('reports', 'edit')){
            $is_active = array('title' => A::t('reports', 'Active'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '60px', 'linkUrl' => 'reportsProjects/changeStatus/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
        }

        echo CWidget::create('CGridView', array(
            'model'				=> 'Modules\Reports\Models\ReportsProjects',
            'actionPath'		=> 'reportsProjects/manage',
            'condition'			=> '',
            'defaultOrder'		=> array('sort_order'=>'ASC'),
            'passParameters'	=> true,
            'pagination'		=> array('enable'=>true, 'pageSize'=>20),
            'sorting'			=> true,
            'filters'			=> array(
                'project_name' 		=> array('title'=> A::t('reports', 'Project Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'150px', 'maxLength'=>'255'),
                'client_name' 		=> array('title'=> A::t('reports', 'Client Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>'45'),
                'client_email' 		=> array('title'=> A::t('reports', 'Client Email'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'140px', 'maxLength'=>'100'),
                'created_at' 		=> array('title'=> A::t('reports', 'Created At'), 'type'=>'datetime', 'operator'=>'=', 'width'=>'80px', 'maxLength'=>'', 'format'=>''),
            ),
            'fields'			=> array(
                'project_name' 		=> array('title'=> A::t('reports', 'Project Name'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'client_name' 		=> array('title'=> A::t('reports', 'Client Name'), 'type'=>'label', 'align'=>'left', 'width'=>'', 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true),
                'client_email' 		=> array('title'=> A::t('reports', 'Client Email'), 'type'=>'link', 'align'=>'left', 'width'=>'140px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'linkUrl'=>'mailto:{client_email}', 'linkText'=>'{client_email}', 'htmlOptions'=>array(), 'prependCode'=>'', 'appendCode'=>''),
                'started_at' 		=> array('title'=> A::t('reports', 'Start Date'), 'type'=>'datetime', 'align'=>'', 'width'=>'120px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(null=>'not yet'), 'format' => $dateFormat),
                'finished_at' 		=> array('title'=> A::t('reports', 'Finish Date'), 'type'=>'datetime', 'align'=>'', 'width'=>'110px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'definedValues'=>array(null=>'--'), 'format' => $dateFormat),
                'project_price' 	=> array('title'=> A::t('reports', 'Project Price'), 'type'=>'decimal', 'class'=>'right', 'headerClass'=>'right', ($currencyPlace == 'before' ? 'prependCode' : 'appendCode')=>$currencySymbol, 'width'=>'100px', 'format'=>$numberFormat, 'decimalPoints'=>'2', 'aggregate'=>array('function'=>'sum')),
                'contract_price' 	=> array('title'=> A::t('reports', 'Contractor Price'), 'type'=>'decimal', 'class'=>'right', 'headerClass'=>'right', ($currencyPlace == 'before' ? 'prependCode' : 'appendCode')=>$currencySymbol, 'width'=>'120px', 'format'=>$numberFormat, 'decimalPoints'=>'2', 'aggregate'=>array('function'=>'sum')),
                'sort_order' 		=> array('title'=> A::t('reports', 'Sort Order'), 'type'=>'label', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                'is_active' 		=> $is_active,
                'links' 			=> array('title'=> '', 'type'=>'link', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'reportsEntities/manage/projectId/{id}', 'linkText'=>A::t('reports', 'Reports'), 'htmlOptions'=>array(), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
            ),
            'actions'			=> array(
                'edit'    => array(
					'disabled'	=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'edit'),
					'link'		=> 'reportsProjects/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('reports', 'Edit this record')
				),
                'delete'  => array(
					'disabled'	=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'delete'),
					'link'		=> 'reportsProjects/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('reports', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'		=> true,
        ));
    ?>        
    </div>
</div>
