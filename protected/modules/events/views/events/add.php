<?php
 /** @var $backendPath */
 /** @var $catId */

	Website::setMetaTags(array('title'=>A::t('events', 'Add Event')));
	
	$this->_activeMenu = 'events/manage';
	$this->_breadCrumbs = array(
        array('label'=>A::t('events', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('events', 'Events Module'), 'url'=>$backendPath.'modules/settings/code/events'),
        array('label'=>A::t('events', 'Events Category'), 'url'=>'eventsCategories/manage'),
        array('label'=>A::t('events', 'Events'), 'url'=>'events/manage/catId/'.$catId),
		array('label'=>A::t('events', 'Add Events')),
	);
?>
<h1><?= A::t('events', 'Events Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title">
        <a class="sub-tab active" href="<?= $categoryLink; ?>"><?= strip_tags($categoryName); ?></a>
        <?= A::t('events', 'Add Events'); ?>
    </div>	    

    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
            'model'             => '\Modules\Events\Models\Events',
            'operationType' 	=> 'add',
            'action' 			=> 'events/add/catId/'.$catId,
            'successUrl' 		=> 'events/manage/catId/'.$catId,
            'cancelUrl' 		=> 'events/manage/catId/'.$catId,
            'passParameters' 	=> false,
            'method' 			=> 'post',
            'htmlOptions' 		=> array(
                'name' 				=> 'frmEventsAdd',
                'autoGenerateId' 	=> true
            ),
            'requiredFieldsAlert' => true,
            'fields' 			=> array(
                'event_category_id' => array('type'=>'data', 'default'=>$catId),
                'event_is_active' 	=> array('type'=>'checkbox', 'title'=>A::t('events', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0, 1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'event_link_url' 	=> array('type'=>'textbox', 'title'=>A::t('events', 'Event Link'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>'255')),
                'event_starts_at' 	=> array('type'=>'textbox', 'tooltip'=>'yyyy-mm-dd hh:mm', 'title'=>A::t('events', 'Event Starts At'), 'validation'=>array('required'=>true, 'type'=>'datetime'), 'format'=>'yy-mm-dd', 'htmlOptions'=>array('class'=>'eventsDatetimepicker', 'autocomplete'=>'off')),
                'event_ends_at' 	=> array('type'=>'textbox','tooltip'=>'yyyy-mm-dd hh:mm', 'title'=>A::t('events', 'Event Ends At'), 'validation'=>array('required'=>true, 'type'=>'datetime'), 'format'=>'yy-mm-dd', 'htmlOptions'=>array('class'=>'eventsDatetimepicker', 'autocomplete'=>'off')),
            ),
            'translationInfo' 	=> array('relation'=>array('id', 'event_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
            'translationFields'	=> array(
                'event_name' 		=> array('type'=>'textbox', 'title'=>A::t('events', 'Name'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50')),
                'event_description'=>array('type'=>'textarea', 'title'=>A::t('events', 'Description'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048')),
            ),
            'buttons' 			=> array(
                'submit' 			=> array('type'=>'submit', 'value'=>A::t('events', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel' 			=> array('type'=>'button', 'value'=>A::t('events', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource' 	=> 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('events', 'Event')),
            'return'            => true,
        ));
    ?>        
    </div>
</div>  


<?php
	A::app()->getClientScript()->registerScriptFile('assets/modules/events/js/datetimepicker.js', CClientScript::POS_BODY_BEGIN);
	A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
	A::app()->getClientScript()->registerCssFile('assets/modules/events/css/datetimepicker.css');
	A::app()->getClientScript()->registerScript('events-datetime-picker', '
		$(document).ready(function () {
			$(".eventsDatetimepicker").datetimepicker({
				controlType: "select",
				oneLine: true,
				dateFormat: "yy-mm-dd",
				timeFormat: "HH:mm"
			});
		});
	');
	
	A::app()->getClientScript()->registerCss('events-add', '
		#ui-timepicker-div dl{ text-align: left; } 
		#ui-timepicker-div dl dt{ height: 25px; } 
		#ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
	');
?>