<?php
    /** @var $backendPath */
    /** @var $tabs */

    Website::setMetaTags(array('title'=>A::t('events', 'Add Event Category')));
    
    $this->_activeMenu = 'eventsCategories/manage';
    $this->_breadCrumbs = array(
        array('label' => A::t('events', 'Modules'), 'url' => $backendPath.'modules/'),
        array('label' => A::t('events', 'Events Module'), 'url' => $backendPath.'modules/settings/code/events'),
        array('label' => A::t('events', 'Events Categories'), 'url' => 'eventsCategories/manage'),
        array('label' => A::t('events', 'Add Event Category')),
    );
?>
<h1><?= A::t('events', 'Events Cateogry Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

   	<div class="sub-title"><?= A::t('events', 'Add Events Category'); ?></div>
    <div class="content">
    <?php		
        echo CWidget::create('CDataForm', array(
            'model'             => '\Modules\Events\Models\EventsCategories',
            'operationType'		=> 'add',
            'action'			=> 'eventsCategories/add/',     
            'successUrl'		=> 'eventsCategories/manage',
            'cancelUrl'			=> 'eventsCategories/manage',
            'passParameters'	=> false,
            'method'			=> 'post',
            'htmlOptions'		=> array(
                'name'				=> 'frmCategoryAdd',                
                'autoGenerateId'	=> true
            ),
            'requiredFieldsAlert' => true,            
            'fields'			=> array(              
                'event_category_is_active'  => array('type'=>'checkbox', 'title'=>A::t('events', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array(), 'viewType'=>'custom'),
                'event_category_sort_order' => array('type'=>'textbox', 'title'=>A::t('events', 'Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'numeric'), 'htmlOptions'=>array('maxlength'=>'4', 'class'=>'small')),              
            ),           
            'translationInfo' 	=> array('relation'=>array('id', 'event_category_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
            'translationFields' => array(
                'event_category_name' 		 => array('type'=>'textbox', 'title'=>A::t('events', 'Events Category Name'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>50), 'htmlOptions'=>array('maxLength'=>'50', 'class'=>'middle')),               
                'event_category_description' => array('type'=>'textarea', 'title'=>A::t('events', 'Events Category Description'), 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>'2048')),
            ),   	
            'buttons'			=> array(
                'submit'			=> array('type'=>'submit', 'value'=>A::t('events', 'Create'), 'htmlOptions'=>array('name'=>'')),
                'cancel'			=> array('type'=>'button', 'value'=>A::t('events', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource' 	=> 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('events', 'Event Category')),
            'return'            => true,
        ));        
    ?>        
    </div>
</div>
