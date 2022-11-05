<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Edit Report Type')));
	
	$this->_activeMenu = 'reportsTypes/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Report Types Management'), 'url'=>'reportsTypes/manage'),
        array('label'=>A::t('reports', 'Edit Report Type')),
    );
?>

<h1><?= A::t('reports', 'Report Types Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
		
	<div class="sub-title"><?= A::t('reports', 'Edit Report Type'); ?></div>
    <div class="content">
	<?php
        echo $actionMessage;
        echo CWidget::create('CDataForm', array(
            'model'                 => 'Modules\Reports\Models\ReportsTypes',
            'primaryKey'            => $reportsType->id,
            'operationType'         => 'edit',
            'action'                => 'reportsTypes/edit/id/'.$reportsType->id,
            'successUrl'            => 'reportsTypes/manage',
            'cancelUrl'             => 'reportsTypes/manage',
            'passParameters'        => false,
            'method'                => 'post',
            'htmlOptions'           => array(
                'name'           		=> 'frmReportsTypesEdit',
                'autoGenerateId' 		=> true
            ),
            'requiredFieldsAlert'   => true,
            'fields'                => array(
                'name'               	=> array('type'=>'textbox', 'title'=>A::t('reports', 'Report Type Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength' => 45), 'htmlOptions'=>array('maxLength' => 45)),
                'template_name'      	=> array('type'=>'textbox', 'title'=>A::t('reports', 'Template Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'fileName', 'maxLength' => 60), 'htmlOptions'=>array('maxLength' => 60)),
                'code'               	=> array('type'=>'label', 'title'=>A::t('reports', 'Report Type Code'), 'tooltip'=>'', 'htmlOptions'=>array('maxLength' => 45)),
                'js_event_handler'      => array('type'=>'textarea', 'title'=>A::t('reports', 'Report Event Handler'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength' => 4096), 'htmlOptions'=>array('maxLength' => 4096)),
                'is_active'          	=> array('type'=>'checkbox', 'title'=>A::t('reports', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
             ),
            'buttons'               => array(
                'submitUpdateClose'    	=> array('type'=>'submit', 'value'=>A::t('reports', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                'submitUpdate'         	=> array('type'=>'submit', 'value'=>A::t('reports', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                'cancel'               	=> array('type'=>'button', 'value'=>A::t('reports', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'        => 'core',
			'alerts'				=> array('type'=>'flash', 'itemName'=>A::t('reports', 'Report Type')),
            'showAllErrors'         => false,
            'return'                => true,
        ));
    ?>
    </div>
</div>
