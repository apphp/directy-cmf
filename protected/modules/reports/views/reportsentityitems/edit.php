<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Edit Report Row')));
	
	$this->_activeMenu = 'reportsProjects/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
        array('label'=>A::t('reports', 'Report Rows Management'), 'url'=>'reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId),
        array('label'=>A::t('reports', 'Edit Report Row')),
    );
?>

<!-- Register tinymce files -->
<?php  A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php  A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php  A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('reports', 'Report Rows Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
    <div class="sub-title">
        <a class="sub-tab previous" href="<?= 'reportsEntities/manage/projectId/'.$projectId; ?>"><?= $projectName; ?></a>
        <span class="sub-tab-divider">&raquo;</span>
        <?php
			if($showReportTabs){
				foreach($allReports as $key => $val){
					echo '<a class="sub-tab '.($reportId == $val['id'] ? 'active' : '').'" href="reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$val['id'].'"><img src="templates/backend/images/icons/reports.png" alt="icon" class="menu-icon"> '.$val['type_name'].'</a>';
				}
			}else{
		?>
            <a class="sub-tab active" href="reportsEntityItems/manage/projectId/<?= $projectId.'/reportId/'.$reportId; ?>"><?= $reportName; ?></a>
        <?php } ?>
    </div>
    <div class="sub-title" style="background-color: #fefefe;"><?= A::t('reports', 'Edit Report Row'); ?></div>
    <div class="content">
    <?php
        echo $actionMessage;
		
        echo CWidget::create('CDataForm', array(
            'model'                 => 'Modules\Reports\Models\ReportsEntityItems',
            'primaryKey'            => $reportsEntityItem->id,
            'operationType'         => 'edit',
            'action'                => 'reportsEntityItems/edit/id/'.$reportsEntityItem->id.'/projectId/'.$projectId.'/reportId/'.$reportId,
            'successUrl'            => 'reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId.'/msg/updated',
            'cancelUrl'             => 'reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId,
            'passParameters'        => false,
            'method'                => 'post',
            'htmlOptions' => array(
                'name'           	=> 'frmReportsTypeItemsAdd',
                'enctype'        	=> 'multipart/form-data',
                'autoGenerateId' 	=> true
            ),
            'requiredFieldsAlert'   => true,
            'fields'            	=> array_merge($fieldsList, array(
                'status' 			=> array('type'=>'checkbox', 'title'=>A::t('reports', 'Approved'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'entity_id'     	=> array('type' => 'data', 'default' => $reportId)
            )),
            'buttons'               => array(
                'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('reports', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate'      => array('type'=>'submit', 'value'=>A::t('reports', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel'            => array('type'=>'button', 'value'=>A::t('reports', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'        => 'core',
            'buttonsPosition'       => 'both',
            'showAllErrors'         => false,
			      'alerts'				=> array('type'=>'flash', 'itemName'=>A::t('reports', 'Report Row')),
            'return'                => true,
        ));
    ?>
    </div>
</div>
<?php
A::app()->getClientScript()->registerScript(
    'fancyboxHandler',
    'var projectPrice ='.$projectPrice.';
    var projectManagePrice ='.$projectManagePrice.';
    var projectDesignPrice ='.$projectDesignPrice.';
    var contractorPrice = '.$contractorPrice.';
		$(\'input[data-type="numeric"]\').forceNumericOnly();
		$(\'input[data-type="numeric"]\').attr(\'autocomplete\', \'off\');
    '.
    $scriptList.'
    $(\'.percent_validator\').keyup(function() {
        if($(this).val() > 100){
            $(this).val(100);
        }
    });',
    5
);
