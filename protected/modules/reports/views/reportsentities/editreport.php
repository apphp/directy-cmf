<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Edit Report')));
	
	$this->_activeMenu = 'reportsProjects/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
        array('label'=>A::t('reports', 'Reports Management'), 'url'=>'reportsEntities/manage/projectId/'.$projectId),
        array('label'=>A::t('reports', 'Edit Report')),
    );
?>

<!-- register tinymce files -->
<?php  A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php  A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php  A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('reports', 'Reports Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

    <div class="sub-title">
        <a class="sub-tab previous" href="<?= 'reportsEntities/manage/projectId/'.$projectId; ?>"><?= $projectName; ?></a>
        <span class="sub-tab-divider">&raquo;</span>
        <?= '<a class="sub-tab active"><img src="templates/backend/images/icons/reports.png" alt="icon" class="menu-icon"> '.$reportName.'</a>';?>
    </div>
	
    <div class="sub-title active"><?= A::t('reports', 'Edit Report'); ?></div>
    <div class="content">
    <?php
        echo $actionMessage;
		
        echo CWidget::create('CDataForm', array(
            'model'                 => 'Modules\Reports\Models\ReportsEntities',
            'primaryKey'            => $entityId,
            'operationType'         => 'edit',
            'action'                => 'reportsEntities/editReport/id/'.$entityId.'/projectId/'.$projectId,
            'successUrl'            => 'reportsEntities/manage/projectId/'.$projectId,
            'cancelUrl'             => 'reportsEntities/manage/projectId/'.$projectId,
            'passParameters'        => false,
            'method'                => 'post',
            'htmlOptions' 			=> array(
                'name'           		=> 'frmReportsChangeEntity',
                'enctype'        		=> 'multipart/form-data',
                'autoGenerateId' 		=> true
            ),
            'requiredFieldsAlert'   => true,
            'fields'            	=> array(
                'related_report' 	    => array('type'=>'select', 'title'=>A::t('reports', 'Related Report'), 'data'=>$entitiesList, 'tooltip'=>'', 'default'=>0, 'source' => array_keys($entitiesList), 'validation'=>array('required'=>false, 'type'=>'set', 'maxLength'=>1024, 'source' => array_keys($entitiesList)), 'htmlOptions'=>array('maxLength'=>1024)),
                'sort_order' 	        => array('title'=> A::t('reports', 'Sort Order'), 'type'=>'textbox', 'align'=>'', 'class'=>'center', 'width' => '10px', 'maxLength'=>3, 'headerClass'=>'center', 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>3), 'isSortable'=>true, 'htmlOptions'=>array('maxLength'=>3, 'width' => '10px', 'class'=>'small')),
                'is_active'          	=> array('type'=>'checkbox', 'title'=>A::t('reports', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
            ),
            'buttons'               => array(
                'submitUpdateClose' 	=> array('type'=>'submit', 'value'=>A::t('reports', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate'      	=> array('type'=>'submit', 'value'=>A::t('reports', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel'            	=> array('type'=>'button', 'value'=>A::t('reports', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource' 	=> 'core',
			'showAllErrors'     => false,
			'alerts'            => array('type'=>'flash', 'itemName'=>A::t('reports', 'Report')),
			'return'            => true,
        ));
    ?>
    </div>
</div>
