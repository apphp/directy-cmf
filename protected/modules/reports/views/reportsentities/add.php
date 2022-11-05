<?php
	Website::setMetaTags(array('title'=>A::t('reports', 'Add Report Comment')));
	
	$this->_activeMenu = 'reportsProjects/manage';
	$this->_breadCrumbs = array(
		array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
		array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
		array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
		array('label'=>A::t('reports', 'Report Comments Management'), 'url'=>'reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId),
		array('label'=>A::t('reports', 'Add Report Comment')),
	);
?>

<!-- register tinymce files -->
<?php  A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php  A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php  A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('reports', 'Report Comments Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>
	
    <div class="sub-title">
        <a class="sub-tab previous" href="<?= 'reportsEntities/manage/projectId/'.$projectId; ?>"><?= $projectName; ?></a>
        <?php if($showReportTabs){ ?>
            <span class="sub-tab-divider">&raquo;</span>
            <?php
				foreach($allReports as $key => $val){
					echo '<a class="sub-tab '.($reportId == $val['id'] ? 'active' : '').'" href="reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$val['id'].'"><img src="templates/backend/images/icons/reports.png" alt="icon" class="menu-icon"> '.$val['type_name'].'</a>';
				}
            ?>
        <?php } ?>
    </div>
    <div class="sub-title active"><?= A::t('reports', 'Add New Comment'); ?></div>
    <div class="content">
    <?php
        echo $actionMessage;
		
        echo CWidget::create('CDataForm', array(
            'model'              	=> 'Modules\Reports\Models\ReportsEntityComments',
            'operationType'      	=> 'add',
            'action'             	=> 'reportsEntities/addComment/projectId/'.$projectId.'/reportId/'.$reportId,
            'successUrl'         	=> 'reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId,
            'cancelUrl'          	=> 'reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId,
            'passParameters'     	=> false,
            'method'             	=> 'post',
            'htmlOptions' 			=> array(
                'name'           		=> 'frmReportsTypeItemsAdd',
                'enctype'        		=> 'multipart/form-data',
                'autoGenerateId' 		=> true
            ),
            'requiredFieldsAlert' 	=> true,
            'fields'             	=> array(
                'comment_text' 			=> array('type'=>'textarea', 'title'=>A::t('reports', 'Comments'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>2048)),
                'posted_date'       	=> array('type' => 'data', 'format' => $dateFormat, 'default' => LocalTime::currentDateTime()),
                'display_date'      	=> array('type' => 'datetime', 'default' => LocalTime::currentDate(), 'title' => A::t('reports', 'Display Date'), 'validation'=>array('required'=>true, 'type'=>'date', 'maxLength'=>10), 'htmlOptions'=>array('maxLength'=>10)),
                'entity_id'         	=> array('type' => 'data', 'default' => $reportId),
                'author'            	=> array('type' => 'data', 'default' => CAuth::getLoggedId()),
                'image_1' 				=> array(
                    'type'					=> 'imageupload',
                    'title'					=> A::t('reports', 'Image {number}', array('{number}'=>1)),
                    'tooltip'				=> '',
                    'default'				=> '',
                    'validation'			=> array('required'=>false, 'type'=>'image', 'targetPath'=>'assets/modules/reports/images/', 'maxSize'=>'1200k', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>CHash::getRandomString(10), 'htmlOptions'=>array()),
                    'imageOptions'  		=> array('showImage'=>true, 'imageClass'=>'avatar'),
                    'fileOptions' 			=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/reports/images/')
                ),
                'image_2' 				=> array(
                    'type'					=> 'imageupload',
                    'title'					=> A::t('reports', 'Image {number}', array('{number}'=>2)),
                    'tooltip'				=> '',
                    'default'				=> '',
                    'validation'			=> array('required'=>false, 'type'=>'image', 'targetPath'=>'assets/modules/reports/images/', 'maxSize'=>'1200k', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>CHash::getRandomString(10), 'htmlOptions'=>array()),
                    'imageOptions'  		=> array('showImage'=>true, 'imageClass'=>'avatar'),
                    'fileOptions' 			=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/reports/images/')
                ),
                'image_3' 				=> array(
                    'type'					=> 'imageupload',
                    'title'					=> A::t('reports', 'Image {number}', array('{number}'=>3)),
                    'tooltip'				=>'',
                    'default'				=> '',
                    'validation'			=> array('required'=>false, 'type'=>'image', 'targetPath'=>'assets/modules/reports/images/', 'maxSize'=>'1200k', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>CHash::getRandomString(10), 'htmlOptions'=>array()),
                    'imageOptions'  		=> array('showImage'=>true, 'imageClass'=>'avatar'),
                    'fileOptions' 			=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/reports/images/')
                ),
                'image_4' 				=> array(
                    'type'					=> 'imageupload',
                    'title'					=> A::t('reports', 'Image {number}', array('{number}'=>4)),
                    'tooltip'				=> '',
                    'default'				=> '',
                    'validation'			=> array('required'=>false, 'type'=>'image', 'targetPath'=>'assets/modules/reports/images/', 'maxSize'=>'1200k', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>CHash::getRandomString(10), 'htmlOptions'=>array()),
                    'imageOptions'  		=> array('showImage'=>true, 'imageClass'=>'avatar'),
                    'fileOptions' 			=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/reports/images/')
                ),
                'is_active'     	=> array('type'=>'checkbox', 'title'=>A::t('reports', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
            ),
            'buttons' 				=> array(
                'submit'    			=> array('type'=>'submit', 'value'=>A::t('reports', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel'    			=> array('type'=>'button', 'value'=>A::t('reports', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
			'messagesSource' 	=> 'core',
			'showAllErrors'     => false,
			'alerts'            => array('type'=>'flash', 'itemName'=>A::t('reports', 'Report Comment')),
			'return'            => true,
        ));
    ?>
    </div>
</div>
