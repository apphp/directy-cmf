<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Report Type Items Management')));
	
	$this->_activeMenu = 'reportsTypeItems/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Report Type Items Management'), 'url'=>'reportsTypeItems/manage'),
    );    
?>

<h1><?= A::t('reports', 'Report Type Items Management'); ?></h1>

<div class="bloc">
   	<?= $tabs; ?>
    <div class="sub-title">
        <a class="sub-tab active" href="<?= 'reportsTypeItems/manage/reportType/'.$reportTypeId; ?>"><?= $reportTypeName; ?></a>
    </div>

    <div class="content">
	<?php 
    	echo $actionMessage;
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('reports', 'add')){
	    	echo '<a href="reportsTypeItems/add/reportType/'.$reportTypeId.'" class="add-new">'.A::t('reports', 'Add Report Type Item').'</a>';
    	}

        // Denied access to change status if user haven't edit page priviliges
        $isRequired = array('title' => A::t('reports', 'Required'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Required')));
        $isActive = array('title' => A::t('reports', 'Active'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Active')));
        $isShow = array('title' => A::t('reports', 'Show on Main View'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Show on Main View')));
        $isInternal = array('title' => A::t('reports', 'Show in Preview/PDF'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Internal Use Only')));
        if(Admins::hasPrivilege('reports', 'edit')){
			$isRequired = array('title' => A::t('reports', 'Required'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => 'reportsTypeItems/changeStatus/type/required/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
            $isActive = array('title' => A::t('reports', 'Active'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => 'reportsTypeItems/changeStatus/type/active/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
            $isShow = array('title' => A::t('reports', 'Show on Main View'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => 'reportsTypeItems/changeStatus/type/show/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
            $isInternal = array('title' => A::t('reports', 'Show in Preview/PDF'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '80px', 'linkUrl' => 'reportsTypeItems/changeStatus/type/internal/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
        }

        echo CWidget::create('CGridView', array(
            'model'				=> 'Modules\Reports\Models\ReportsTypeItems',
            'actionPath'		=> 'reportsTypeItems/manage/reportType/'.$reportTypeId,
            'condition'			=> 'type_id ='.$reportTypeId,
            'defaultOrder'		=> array('sort_order'=>'ASC'),
            'passParameters'	=> true,
            'pagination'		=> array('enable'=>true, 'pageSize'=>20),
            'sorting'			=> true,
            'filters'			=> array(
                'field_title'		=> array('title'=> A::t('reports','Field Name'), 'type'=>'textbox', 'operator'=>'%like%', 'width'=>'150px', 'maxLength'=>'255'),
                'field_type' 		=> array('title'=> A::t('reports','Field Type'), 'type'=>'enum', 'operator'=>'=', 'width'=>'150', 'maxLength'=>'45', 'source'=>array(''=>'')+$fieldTypes, 'emptyOption'=>true, 'emptyValue'=>''),
            ),
            'fields'			=> array(
                'field_title' 	        => array('title'=> A::t('reports','Field Name'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true),
                'field_type' 	        => array('title'=> A::t('reports','Field Type'), 'type'=>'label', 'align'=>'left', 'width'=>'120px', 'class'=>'left', 'headerClass'=>'right', 'isSortable'=>true),
                'field_validation_type' => array('title'=> A::t('reports','Validation Type'), 'type'=>'label', 'align'=>'left', 'width'=>'150px', 'class'=>'left', 'headerClass'=>'right', 'isSortable'=>true),
                'field_width' 	        => array('title'=> A::t('reports','Width'), 'type'=>'label', 'align'=>'left', 'width'=>'90px', 'class'=>'left', 'headerClass'=>'right', 'isSortable'=>true),
                'field_default_value'   => array('title'=> A::t('reports','Default Value'), 'type'=>'label', 'align'=>'', 'width'=>'250px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
                'sort_order' 	        => array('title'=> A::t('reports','Sort Order'), 'type'=>'label', 'align'=>'', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
                'show_on_mainview'      => $isShow,
                'internal_use'          => $isInternal,
				'field_required'        => $isRequired,
                'is_active' 	        => $isActive
            ),
            'actions'			=> array(
                'edit'    => array(
					'disabled'		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'edit'),
					'link'			=> 'reportsTypeItems/edit/id/{id}/reportType/'.$reportTypeId, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('reports', 'Edit this record')
				),
                'delete'  => array(
					'disabled'		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'delete'),
					'link'			=> 'reportsTypeItems/delete/id/{id}/reportType/'.$reportTypeId, 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('reports', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'			=> true,
        ));
    ?>        
    </div>
</div>
