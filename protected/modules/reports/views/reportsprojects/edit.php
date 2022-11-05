<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Edit Project')));
	
	$this->_activeMenu = 'reportsProjects/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Projects Management'), 'url'=>'reportsProjects/manage'),
        array('label'=>A::t('reports', 'Edit Project')),
    );
?>

<h1><?= A::t('reports', 'Projects Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
		
	<div class="sub-title"><?= A::t('reports', 'Edit Project'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;
			echo CWidget::create('CDataForm', array(
				'model'                 => 'Modules\Reports\Models\ReportsProjects',
				'primaryKey'            => $project->id,
				'operationType'         => 'edit',
				'action'                => 'reportsProjects/edit/id/'.$project->id,
				'successUrl'            => 'reportsProjects/manage',
				'cancelUrl'             => 'reportsProjects/manage',
				'passParameters'        => false,
				'method'                => 'post',
				'htmlOptions'           => array(
					'name'           => 'frmReportsTypesEdit',
					'enctype'        => 'multipart/form-data',
					'autoGenerateId' => true
				),
				'requiredFieldsAlert'   => true,
				'fields'                => array(
					'separatorProject' =>array(
					   'separatorInfo' => array('legend'=>A::t('reports', 'Project Information')),
						'project_name'         => array('type'=>'textbox', 'title'=>A::t('reports', 'Project Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength' => 255), 'htmlOptions'=>array('maxLength' => 255)),
						'project_manage_price' => array('type'=>'textbox', 'title'=>A::t('reports', 'Project Management Price'), 'default'=>($numberFormat == 'european' ? '0,00' : '0.00'), 'validation'=>array('required'=>true, 'type'=>'float', 'format'=>$numberFormat, 'minValue'=>'0', 'maxValue'=>'999999'), 'htmlOptions'=>array('maxlength'=>'8', 'style' => 'width: 90px;'), ($currencyPlace == 'before' ? 'prependCode' : 'appendCode')=>' '.$currencySymbol.' '),
						'project_design_price' => array('type'=>'textbox', 'title'=>A::t('reports', 'Project Design Price'), 'default'=>($numberFormat == 'european' ? '0,00' : '0.00'), 'validation'=>array('required'=>true, 'type'=>'float', 'format'=>$numberFormat, 'minValue'=>'0', 'maxValue'=>'999999'), 'htmlOptions'=>array('maxlength'=>'8', 'style' => 'width: 90px;'), ($currencyPlace == 'before' ? 'prependCode' : 'appendCode')=>' '.$currencySymbol.' '),
						'project_price' 	   => array('type'=>'textbox', 'title'=>A::t('reports', 'Total Project Price'), 'default'=>($numberFormat == 'european' ? '0,00' : '0.00'), 'validation'=>array('required'=>true, 'type'=>'float', 'format'=>$numberFormat, 'minValue'=>'0', 'maxValue'=>'999999'), 'htmlOptions'=>array('maxlength'=>'8', 'style' => 'width: 90px;', 'readonly' => true), ($currencyPlace == 'before' ? 'prependCode' : 'appendCode')=>' '.$currencySymbol.' '),
						'contract_price' 	   => array('type'=>'textbox', 'title'=>A::t('reports', 'Contractor Price'), 'default'=>($numberFormat == 'european' ? '0,00' : '0.00'), 'validation'=>array('required'=>false, 'type'=>'float', 'format'=>$numberFormat, 'minValue'=>'0', 'maxValue'=>'999999'), 'htmlOptions'=>array('maxlength'=>'8', 'style' => 'width: 90px;'), ($currencyPlace == 'before' ? 'prependCode' : 'appendCode')=>' '.$currencySymbol.' '),
						'created_at'           => array('type'=>'label',  'title'=>A::t('reports', 'Created At'), 'default'=>'', 'tooltip'=>'', 'definedValues'=>array(), 'htmlOptions'=>array(), 'format'=>'', 'stripTags'=>false),
						'started_at'           => array('type'=>'datetime', 'title'=>A::t('reports', 'Start Date'), 'defaultEditMode'=>null, 'validation'=>array('required'=>false, 'type'=>'date', 'maxLength'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>''), 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px'), 'definedValues'=>array()),
						'finished_at'          => array('type'=>'datetime', 'title'=>A::t('reports', 'Finish Date'), 'defaultEditMode'=>null, 'validation'=>array('required'=>false, 'type'=>'date', 'minValue'=>10, 'minValue'=>'1900-00-00', 'maxValue'=>''), 'htmlOptions'=>array('maxlength'=>'10', 'style'=>'width:100px'), 'definedValues'=>array()),
						'sort_order'           => array('type'=>'textbox', 'title'=>A::t('reports', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength' => 3), 'htmlOptions'=>array('maxLength' => 3, 'class'=>'small')),
						'is_active'            => array('type'=>'checkbox', 'title'=>A::t('reports', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
					),
					'separatorClient' => array(
					   'separatorInfo'=> array('legend'=>A::t('reports', 'Client Information')),
						'client_name'          => array('type'=>'textbox', 'title'=>A::t('reports', 'Client Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength' => 45), 'htmlOptions'=>array('maxLength' => 45)),
						'client_address'       => array('type'=>'textbox', 'title'=>A::t('reports', 'Client Address'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength' => 125), 'htmlOptions'=>array('maxLength' => 125)),
						'client_email'         => array('type'=>'textbox', 'title'=>A::t('reports', 'Client Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength' => 100), 'htmlOptions'=>array('maxLength' => 100, 'class'=>'email', 'autocomplete'=>'off')),
						'client_phone'         => array('type'=>'textbox', 'title'=>A::t('reports', 'Client Phone'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'phone', 'maxLength' => 45), 'htmlOptions'=>array('maxLength' => 45)),
					)
				),
				'buttons'               => array(
					'submitUpdateClose'    => array('type'=>'submit', 'value'=>A::t('reports', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
					'submitUpdate'         => array('type'=>'submit', 'value'=>A::t('reports', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
					'cancel'               => array('type'=>'button', 'value'=>A::t('reports', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
				),
				'messagesSource'        => 'core',
				'alerts'				=> array('type'=>'flash', 'itemName'=>A::t('reports', 'Project')),
				'showAllErrors'         => false,
				'return'                => true,
			));
        ?>
    </div>
</div>
<?php
A::app()->getClientScript()->registerScript(
    'fancyboxHandler',
    '
    function CalculateTotal(){
        var projectManagementPrice = $(\'#frmReportsTypesEdit_project_manage_price\').val() == "" ? 0 : parseFloat($(\'#frmReportsTypesEdit_project_manage_price\').val());
        var projectDesignPrice = $(\'#frmReportsTypesEdit_project_design_price\').val() == "" ? 0 : parseFloat($(\'#frmReportsTypesEdit_project_design_price\').val());
        var total = projectManagementPrice + projectDesignPrice;
        $(\'#frmReportsTypesEdit_project_price\').val(isNaN(total) ? 0 : parseFloat(total).toFixed(2));
    }
    $(\'#frmReportsTypesEdit_project_manage_price\').keyup(function() {
        CalculateTotal();
    });
    $(\'#frmReportsTypesEdit_project_design_price\').keyup(function() {
        CalculateTotal();
    });
    '
    ,
    5
);
?>