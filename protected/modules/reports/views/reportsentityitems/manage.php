<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Report Rows Management')));
	
	$this->_activeMenu = 'reportsProjects/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
        array('label'=>A::t('reports', 'Report Rows Management'), 'url'=>'reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId),
    );    
?>
<!-- register fancybox files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.mousewheel.pack.js', 2); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.fancybox.pack'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.js', 2); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/fancybox/jquery.fancybox'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.css'); ?>
<style>
    #add_mass_rows > div{
        float: left;
        margin-top: 0;
    }
</style>
<h1><?= A::t('reports', 'Report Rows Management'); ?></h1>
<div class="bloc">
   	<?= $tabs; ?>
    <div class="sub-title">
		<a class="sub-tab previous" href="<?= 'reportsEntities/manage/projectId/'.$projectId; ?>"><?= $projectName; ?></a>
		<span class="sub-tab-divider">&raquo;</span>
		<?php if($showReportTabs){ ?>
			<?php
				foreach($allReports as $key => $val){
					echo '<a class="sub-tab '.($reportId == $val['id'] ? 'active' : '').'" href="reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$val['id'].'"><img src="templates/backend/images/icons/reports.png" alt="icon" class="menu-icon"> '.$val['type_name'].'</a>';
				}
			?>
		<?php }else{ ?>
			<a class="sub-tab active" href="reportsEntityItems/manage/projectId/<?= $projectId.'/reportId/'.$reportId; ?>"><?= $reportName; ?></a>
		<?php } ?>
	</div>

    <div class="content">
	<?php 
    	echo $actionMessage;
 
    	if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('reports', 'add') && Admins::hasPrivilege($reportCode, 'edit')){
	    	echo '<div style="float:left;"><a href="reportsEntityItems/add/projectId/'.$projectId.'/reportId/'.$reportId.'" class="add-new">'.A::t('reports', 'Add Report Row').'</a></div>';
            
			echo CWidget::create('CFormView', array(
				'action'=>'#',
				'cancelUrl'=>'#',
				'method'=>'post',
				'htmlOptions'=>array(
					'name'=>'add_mass_rows',
					'id'    => 'add_mass_rows',
					'style' => 'float:left;margin-top:10px;padding:0 5px;margin-left:10px;margin-right:50px;',
					'enctype'=>'multipart/form-data',
					'autoGenerateId'=>false
				),
				'requiredFieldsAlert'=>false,
				'fieldSetType'=>'frameset|tabs',
				'fields'=>array(
					'count'   	=> array('type'=>'textbox',  'title'=>'', 'tooltip'=>'', 'maxLength'=>2, 'mandatoryStar'=>true, 'value'=>'', 'htmlOptions'=>array('maxLength'=>'2', 'style'=>'margin:0 5px !important;', 'class'=>'small', 'data-type'=>'numeric', 'id'=>'emptyRowsCount')),
				),
				'buttons'=>array(
				   'custom' 	=> array('type'=>'button', 'value'=>A::te('reports', 'Insert Empty Rows'), 'htmlOptions'=>array('class'=>'', 'onclick'=>'checkEmptyInput();', 'style'=>'padding:2px 10px;')),
				),
				'events'=>array(
					'focus'		=> array('field'=>$errorField)
				),
				'return'=>true,
			));
			
			if(count($reportsRows)){
				echo '<a href="reportsEntities/getPdf/projectId/'.$projectId.'/reportId/'.$reportId.'"  target="_blank" rel="noopener noreferrer" class="align-right preview-data ml5"><b class="icon-export">&nbsp;</b> '.A::t('reports', 'Export PDF').'</a>';
				echo '<a href="reportsEntities/preview/projectId/'.$projectId.'/reportId/'.$reportId.'" target="_blank" rel="noopener noreferrer" class="align-right export-data ml5"><b class="icon-preview">&nbsp;</b> '.A::t('reports', 'Preview').'</a>';
			}else{
				echo '<a href="javascript:void(0);" class="align-right preview-data ml5 disabled"><b class="icon-export">&nbsp;</b> '.A::t('reports', 'Export PDF').'</a>';
				echo '<a href="javascript:void(0);" class="align-right export-data ml5 disabled"><b class="icon-preview">&nbsp;</b> '.A::t('reports', 'Preview').'</a>';
			}
    	}

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('reports', 'add') && Admins::hasPrivilege($reportCode, 'edit')){
            echo '<a style="margin-left:20px;" href="reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId.'" class="add-new">'.A::t('reports', 'Show Comments').'</a>';
        }

        // Denied access to change status if user haven't edit page priviliges
        $status = array('title' => A::t('reports', 'Status'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '120px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red" style="width: 50px;">'.A::t('reports', 'Pending').'</span>', '1' => '<span class="badge-green" style="width: 50px;">'.A::t('reports', 'Approved').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Active')));
        if(Admins::hasPrivilege('reports', 'edit') && Admins::hasPrivilege($reportCode, 'approve')){
            $status = array('title' => A::t('reports', 'Status'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '120px', 'linkUrl' => 'reportsEntityItems/changeStatus/id/{id}/page/{page}/projectId/'.$projectId.'/reportId/'.$reportId, 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red" style="width: 50px;">'.A::t('reports', 'Pending').'</span>', '1' => '<span class="badge-green" style="width: 50px;">'.A::t('reports', 'Approved').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
        }

        echo CWidget::create('CGridView', array(
            'model'         =>'Modules\Reports\Models\ReportsEntityItems',
            'actionPath'    =>'reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId,
            'condition'     =>'entity_id = '.$reportId,
            'defaultOrder'  =>array('id'=>'ASC'),
            'passParameters'=>true,
            'pagination'=> array('enable'=>true, 'pageSize'=>20),
            'sorting'	=> true,
            'filters'	=> array(),
            'fields' 	=> array_merge($fieldsList, array(
                'status' => $status,
            )),
            'actions'=>array(
                'edit'    => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'edit') || !Admins::hasPrivilege($reportCode, 'edit'),
					'link'=>'reportsEntityItems/edit/id/{id}/projectId/'.$projectId.'/reportId/'.$reportId, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('reports', 'Edit this record')
				),
                'delete'  => array(
					'disabled'=>!Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'delete') || !Admins::hasPrivilege($reportCode, 'edit'),
					'link'=>'reportsEntityItems/delete/id/{id}/projectId/'.$projectId.'/reportId/'.$reportId, 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('reports', 'Delete this record'), 'onDeleteAlert'=>true
				)
            ),
            'return'=>true,
        ));

    ?>        
    </div>
</div>
<?php
A::app()->getClientScript()->registerScript(
	'fancyboxHandler',
	"$('.fancybox').each(function() {
		var src = $(this).find('img').attr('src'),
			rel = $(this).attr('rel');
			row_id = $(this).closest('tr').attr('id');
		$(this).attr('href', src);
		$(this).attr('rel', rel + row_id);
	});
	$('.fancybox').fancybox({
		'opacity'		: true,
		'overlayShow'	: false,
		'overlayColor'	: '#000',
		'overlayOpacity': 0.5,
		'titlePosition'	: 'inside', 
		'cyclic' 		: true,
		'transitionIn'	: 'elastic', 
		'transitionOut'	: 'fade'
	});",
	5
);

A::app()->getClientScript()->registerScript(
    'fancyboxHandler',
    "function checkEmptyInput(){
        if(typeof(countRows) === 'undefined'){
            alert('Please specify the number of empty rows you want to add!');
            $( '#emptyRowsCount' ).focus();
        }else{
            $(location).attr('href','reportsEntityItems/addMass/projectId/".$projectId."/reportId/".$reportId."/count/'+countRows);
        }
    }",
    2
);

A::app()->getClientScript()->registerScript(
    'fancyboxHandler',
    "$('input[data-type=\"numeric\"]').forceNumericOnly();
    $('input[data-type=\"numeric\"]').attr('autocomplete', 'off');
    $('#emptyRowsCount').keyup(function(){
        if($(this).val() > 30){
            $(this).val(30);
        }
        countRows = $(this).val();
    });",
    4
);
