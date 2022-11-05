<?php
	Website::setMetaTags(array('title'=>A::t('reports', 'Report Comments Management')));
	
	$this->_activeMenu = 'reportsProjects/manage';
	$this->_breadCrumbs = array(
		array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
		array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
		array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
		array('label'=>A::t('reports', 'Report Comments Management'), 'url'=>'reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId),
	);
?>

<!-- register fancybox files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.mousewheel.pack.js', 2); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.fancybox.pack'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.js', 2); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/fancybox/jquery.fancybox'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.css'); ?>

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

    <div class="content">
    <?php
        echo $actionMessage;

        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('reports', 'add')){
            echo '<a href="reportsEntities/addComment/projectId/'.$projectId.'/reportId/'.$reportId.'" class="add-new">'.A::t('reports', 'Add Comment').'</a>';
        }

        // Denied access to change status if user haven't edit page priviliges
        $is_active = array('title' => A::t('reports', 'Active'), 'type' => 'label', 'class' => 'center', 'headerClass' => 'center', 'width' => '60px', 'linkUrl' => '', 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Active')));
        if(Admins::hasPrivilege('reports', 'edit')){
            $is_active = array('title' => A::t('reports', 'Active'), 'type' => 'link', 'class' => 'center', 'headerClass' => 'center', 'width' => '60px', 'linkUrl' => 'reportsEntities/changeCommentStatus/id/{id}/page/{page}/projectId/'.$projectId.'/reportId/'.$reportId, 'linkText' => '', 'definedValues' => array('0' => '<span class="badge-red">'.A::t('reports', 'No').'</span>', '1' => '<span class="badge-green">'.A::t('reports', 'Yes').'</span>'), 'htmlOptions' => array('class' => 'tooltip-link', 'title' => A::t('reports', 'Click to change status')));
        }

        echo CWidget::create('CGridView', array(
            'model'				=> 'Modules\Reports\Models\ReportsEntityComments',
            'actionPath'		=> 'reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId,
            'condition'			=> 'entity_id = '.$reportId,
            'defaultOrder'		=> array('display_date'=>'ASC'),
            'passParameters'	=> false,
            'pagination'		=> array('enable'=>true, 'pageSize'=>20),
            'sorting'			=> true,
            'filters'			=> array(),
            'fields'			=> array(
                'posted_date' 		=> array('title'=> A::t('reports', 'Date Created'), 'type'=>'date', 'align'=>'left', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(null=>'not yet'), 'format'=>$dateFormat),
                'changed_date' 		=> array('title'=> A::t('reports', 'Last Changed'), 'type'=>'date', 'align'=>'left', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(null=>'not yet'), 'format'=>$dateFormat),
                'display_date' 		=> array('title'=> A::t('reports', 'Display Date'), 'type'=>'date', 'align'=>'left', 'width'=>'100px', 'class'=>'center', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(null=>'not yet'), 'format'=>$dateFormat),
                'author_name' 		=> array('title'=> A::t('reports', 'Created By'), 'type'=>'label', 'align'=>'left', 'width'=>'150px', 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true),
                'comment_text' 		=> array('title'=> A::t('reports','Comment Text'), 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true ),
                'image_1'       	=> array('title'=> A::t('reports', 'Image {number}', array('{number}'=>1)), 'type'=>'image', 'width' => '70px',  'align'=>'', 'imagePath'=>'assets/modules/reports/images/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'64px', 'imageHeight'=>'64px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'htmlOptions'=>array(), 'prependCode'=>'<a class="fancybox" rel="reference_picture" href="#">', 'appendCode'=>'</a>'),
                'image_2'       	=> array('title'=> A::t('reports', 'Image {number}', array('{number}'=>2)), 'type'=>'image', 'width' => '70px',  'align'=>'', 'imagePath'=>'assets/modules/reports/images/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'64px', 'imageHeight'=>'64px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'htmlOptions'=>array(), 'prependCode'=>'<a class="fancybox" rel="reference_picture" href="#">', 'appendCode'=>'</a>'),
                'image_3'       	=> array('title'=> A::t('reports', 'Image {number}', array('{number}'=>3)), 'type'=>'image', 'width' => '70px',  'align'=>'', 'imagePath'=>'assets/modules/reports/images/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'64px', 'imageHeight'=>'64px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'htmlOptions'=>array(), 'prependCode'=>'<a class="fancybox" rel="reference_picture" href="#">', 'appendCode'=>'</a>'),
                'image_4'       	=> array('title'=> A::t('reports', 'Image {number}', array('{number}'=>4)), 'type'=>'image', 'width' => '70px',  'align'=>'', 'imagePath'=>'assets/modules/reports/images/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'64px', 'imageHeight'=>'64px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'htmlOptions'=>array(), 'prependCode'=>'<a class="fancybox" rel="reference_picture" href="#">', 'appendCode'=>'</a>'),
                'is_active' 		=> $is_active,
            ),
            'actions'			=> array(
                'edit'    => array(
                    'disabled'		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'edit'),
                    'link'			=> 'reportsEntities/editComment/id/{id}/projectId/'.$projectId.'/reportId/'.$reportId, 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('reports', 'Edit this record')
                ),
                'delete'  => array(
                    'disabled' 		=> !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('reports', 'delete'),
                    'link'     		=> 'reportsEntities/deleteComment/id/{id}/projectId/'.$projectId.'/reportId/'.$reportId, 'imagePath' => 'templates/backend/images/delete.png', 'title' => A::t('reports', 'Delete this comment'), 'onDeleteAlert'=>true
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
        'cyclic' : true,
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'fade'
    });
    ",
    5
);
