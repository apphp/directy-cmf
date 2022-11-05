<?php
    Website::setMetaTags(array('title'=>A::t('reports', 'Add Report Type Item')));
	
	$this->_activeMenu = 'reportsTypeItems/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
        array('label'=>A::t('reports', 'Report Type Items Management'), 'url'=>'reportsTypeItems/manage'),
        array('label'=>A::t('reports', 'Add Report Type Item')),
    );
?>

<h1><?= A::t('reports', 'Report Type Items Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
    <div class="sub-title">
        <a class="sub-tab active" href="<?= 'reportsTypeItems/manage/reportType/'.$reportTypeId; ?>"><?= $reportTypeName; ?></a>
        <?= A::t('reports', 'Add Report Type Item'); ?>
    </div>

    <div class="content">
        <?php
			echo $actionMessage;
			echo CWidget::create('CDataForm', array(
				'model'                 => 'Modules\Reports\Models\ReportsTypeItems',
				'operationType'         => 'add',
				'action'                => 'reportsTypeItems/add/reportType/'.$reportTypeId,
				'successUrl'            => 'reportsTypeItems/manage/reportType/'.$reportTypeId,
				'cancelUrl'             => 'reportsTypeItems/manage/reportType/'.$reportTypeId,
				'passParameters'        => false,
				'method'                => 'post',
				'htmlOptions'           => array(
					'name'           		=> 'frmReportsTypeItemsAdd',
					'enctype'        		=> 'multipart/form-data',
					'autoGenerateId' 		=> true
				),
				'requiredFieldsAlert'   => true,
				'fields'                => array(
					'field_title'            => array('type'=>'textbox', 'title'=>A::t('reports', 'Field Title'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength' => 60), 'htmlOptions'=>array('maxLength' => 60)),
					'type_id'                => array('type'=>'data', 'default'=>$reportTypeId),
					'field_type'             => array('type'=>'select', 'title'=>A::t('reports', 'Field Type'), 'tooltip'=>'', 'data'=>$fieldTypes, 'default'=>'textbox', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($fieldTypes))),
					'field_validation_type'  => array('type'=>'select', 'title'=>A::t('reports', 'Validation Type'), 'tooltip'=>'', 'data'=>$validationTypes, 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($validationTypes))),
                    'readonly'               => array('type'=>'checkbox', 'title'=>A::t('reports', 'ReadOnly'), 'tooltip'=>'0 - writeable, 1 - readonly', 'default'=>'0', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
					'field_maxlength'        => array('type'=>'textbox', 'title'=>A::t('reports', 'Max. Length'), 'tooltip'=>A::t('reports', 'Maximum number of characters for this field'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'range', 'minValue'=>'0', 'maxValue'=>'20000', 'maxLength'=>5), 'htmlOptions'=>array('maxLength'=>5, 'class'=>'small')),
					'field_width'     		 => array('type'=>'textbox', 'title'=>A::t('reports', 'Width'), 'tooltip'=>A::t('reports', 'for add/edit modes only Ex.: 210px (em, pt or %)'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'htmlSize', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>20, 'class'=>'medium')),
					'field_height'     		 => array('type'=>'textbox', 'title'=>A::t('reports', 'Height'), 'tooltip'=>A::t('reports', 'for add/edit modes only Ex.: 210px (em, pt or %)'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'htmlSize', 'maxLength'=>20), 'htmlOptions'=>array('maxLength'=>20, 'class'=>'medium')),
					'field_placeholder'      => array('type'=>'textbox', 'title'=>A::t('reports', 'Placeholder'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
					'field_default_value'    => array('type'=>'textbox', 'title'=>A::t('reports', 'Default Value'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
                    'field_tooltip'          => array('type'=>'textbox', 'title'=>A::t('reports', 'Tooltip'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
                    'field_prepend_code'     => array('type'=>'textbox', 'title'=>A::t('reports', 'Prepend Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
                    'field_append_code'      => array('type'=>'textbox', 'title'=>A::t('reports', 'Append Code'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255)),
					'sort_order'             => array('title'=> A::t('reports','Sort Order'), 'type'=>'textbox', 'align'=>'', 'width'=>'50px', 'default'=>'0', 'class'=>'small', 'headerClass'=>'center', 'isSortable'=>false, 'validation'=>array('required'=>false, 'type'=>'numeric', 'maxLength'=>2), 'htmlOptions'=>array('maxLength' => 2, 'class' => 'small')),
					'field_required'         => array('type'=>'checkbox', 'title'=>A::t('reports', 'Required'), 'tooltip'=>'', 'default'=>'0', 'viewType'=>'custom'),
					'show_on_mainview'       => array('type'=>'checkbox', 'title'=>A::t('reports', 'Show on Main View'), 'tooltip'=>'', 'default'=>'1', 'viewType'=>'custom'),
					'internal_use'           => array('type'=>'checkbox', 'title'=>A::t('reports', 'Show in Preview/PDF'), 'tooltip'=>'Defines whether to show or not this field in Preview/PDF', 'default'=>'1', 'viewType'=>'custom'),
					'is_active'              => array('type'=>'checkbox', 'title'=>A::t('reports', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
				),
				'buttons'       		=> array(
					'submit'   				=> array('type'=>'submit', 'value'=>A::t('reports', 'Create'), 'htmlOptions'=>array('name'=>'')),
					'cancel'    			=> array('type'=>'button', 'value'=>A::t('reports', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
				),
				'messagesSource'        => 'core',
                'buttonsPosition'       => 'both',
				'alerts'				=> array('type'=>'flash', 'itemName'=>A::t('reports', 'Report Type Item')),
				'showAllErrors'         => false,
				'return'                => true,
			));
        ?>
    </div>
</div>
<?php

A::app()->getClientScript()->registerScript(
	'frmReportsTypeItemsAdd',
    "$(document).ready(function(){
        if($('#frmReportsTypeItemsAdd_field_type').val() != 'videoLink'){
            $('#frmReportsTypeItemsAdd_field_height').attr('readonly', 'readonly');
        }
    });
    $('#frmReportsTypeItemsAdd_field_type').on('change', function(){
        if($(this).val() == 'videoLink'){
			$('#frmReportsTypeItemsAdd_field_maxlength').val('255');
            $('#frmReportsTypeItemsAdd_field_validation_type').val('url');
            $('#frmReportsTypeItemsAdd_field_height').attr('readonly', false);
        }else{
            $('#frmReportsTypeItemsAdd_field_height').attr('readonly', 'readonly');
        }

		if($(this).val() == 'autoIncrement'){
			$('#frmReportsTypeItemsAdd_field_maxlength').val('0');
			$('#frmReportsTypeItemsAdd_field_validation_type').val('');
		}else if($(this).val() == 'datetime'){
			$('#frmReportsTypeItemsAdd_field_maxlength').val('10');
			$('#frmReportsTypeItemsAdd_field_validation_type').val('date');
		}else if ($(this).val() == 'textarea'){
		    $('#frmReportsTypeItemsAdd_field_maxlength').val('1024');
			$('#frmReportsTypeItemsAdd_field_validation_type').val('text');
		}
		// Replace textbox and textarea
		var elem = $('#frmReportsTypeItemsAdd_field_default_value');
		if($(this).val() == 'textarea'){
			var textarea = $(document.createElement('textarea')).attr({id : elem.attr('id'), name : elem.attr('name'), value : elem.val(), maxlength : '255', class : elem.attr('class')});
			elem.replaceWith(textarea);
		}else{
			var textbox = $(document.createElement('input')).attr({id : elem.attr('id'), type : 'text', name : elem.attr('name'), value : elem.val(), maxlength : '255', class : elem.attr('class')});
			elem.replaceWith(textbox);
		}
	});",
	2
);
?>
