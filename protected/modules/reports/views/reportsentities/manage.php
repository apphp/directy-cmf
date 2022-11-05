<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Reports Management')));
	
	$this->_activeMenu = 'reportsProjects/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
        array('label'=>A::t('reports', 'Reports Management'), 'url'=>'reportsEntities/manage'),
    );    
?>

<h1><?= A::t('reports', 'Reports Management'); ?></h1>

<div class="bloc">

   	<?= $tabs; ?>

    <div class="sub-title">
        <a class="sub-tab active" href="<?= 'reportsEntities/manage/projectId/'.$projectId; ?>"><?= $projectName; ?></a>
		<?php if($showReportTabs){ ?>
			<span class="sub-tab-divider">&raquo;</span>
			<?php
				foreach($allReports as $key => $val){
                    echo '<a class="sub-tab previous" href="reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$val['id'].'"><img src="templates/backend/images/icons/reports.png" alt="icon" class="menu-icon"> '.$val['type_name'].'</a>';
				}
			?>
		<?php } ?>
    </div>
	
    <div class="content">
	<?php 
		echo $actionMessage;

        // Denied access to change status if user haven't edit page priviliges
        $isActive = array('title' => A::t('reports', 'Active'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '70px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Active')));
        if(Admins::hasPrivilege('reports', 'edit')){
            $isActive = array('title' => A::t('reports', 'Active'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '70px', 'linkUrl' => 'reportsEntities/changeStatus/projectId/'.$projectId.'/id/{id}/page/{page}', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
        }

        echo CWidget::create('CGridView', array(
            'model'             => 'Modules\Reports\Models\ReportsEntities',
            'actionPath'        => 'reportsEntities/manage/projectId/'.$projectId,
            'condition'         => 'project_id = '.$projectId.' AND '.CConfig::get('db.prefix').'reports_types.is_active = 1 ',
            'defaultOrder'      => array('sort_order'=>'ASC'),
            'passParameters'    => false,
            'pagination'        => array('enable'=>true, 'pageSize'=>20),
            'sorting'           => true,
            'filters'           => array(),
            'fields'            => array(
                'type_name' 	    => array('title'=> A::t('reports','Report Name'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true ),
                'related_report' 	=> array('title'=> A::t('reports','Related Order'), 'type'=>'enum', 'align'=>'', 'width'=>'160px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'source'=>$reportNameList ),
                'links' 		    => array('title'=> '', 'type'=>'link', 'align'=>'', 'width'=>'130px', 'class'=>'left', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'reportsEntityItems/manage/projectId/'.$projectId.'/reportId/{id}', 'linkText'=>A::t('reports', 'Manage Rows'), 'htmlOptions'=>array(), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'linkComments' 	    => array('title'=> '', 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'left', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'reportsEntities/showComments/projectId/'.$projectId.'/reportId/{id}', 'linkText'=>A::t('reports', 'Comments'), 'htmlOptions'=>array(), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'linkPreview' 	    => array('title'=> '', 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'left', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'reportsEntities/preview/projectId/'.$projectId.'/reportId/{id}', 'linkText'=>A::t('reports', 'Preview'), 'htmlOptions'=>array('target'=>'_blank'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'linkExport' 	    => array('title'=> '', 'type'=>'link', 'align'=>'', 'width'=>'100px', 'class'=>'left', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'reportsEntities/getPdf/projectId/'.$projectId.'/reportId/{id}', 'linkText'=>A::t('reports', 'Export PDF'), 'htmlOptions'=>array('target'=>'_blank'), 'prependCode'=>'[ ', 'appendCode'=>' ]'),
                'sort_order' 	    => array('title'=> A::t('reports', 'Sort Order'), 'type'=>'label', 'align'=>'', 'width'=>'80px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true),
				'is_active' 	    => $isActive
            ),
            'actions'           => array(
                'setup' => array(
                    'disabled'	=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'edit'),
                    'link'		=> 'reportsEntities/editReport/id/{id}/projectId/'.$projectId, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('reports', 'Setup this Report')
                ),
            ),
            'return'=>true,
        ));
    ?>        
    </div>
</div>
