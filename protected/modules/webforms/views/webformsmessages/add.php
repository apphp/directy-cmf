<?php
    Website::setMetaTags(array('title'=>A::t('webforms', 'Add Messages')));

    $this->_activeMenu  = $backendPath.'modules/settings/code/webforms';
    $this->_breadCrumbs = array(
        array('label' => A::t('webforms', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label' => A::t('webforms', 'Web Forms'), 'url'=>$backendPath.'modules/settings/code/webforms'),
        array('label' => A::t('webforms', 'Messages'), 'url'=>'webformsMessages/manage'),
		array('label' => A::t('webforms', 'Add Messages')),
    );
?>

<h1><?= A::t('webforms', 'Messages Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title"><?= A::t('webforms', 'Add Message'); ?></div>

    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
			'model'             => '\Modules\Webforms\Models\WebformsMessages',
            'operationType'     => 'add',
            'action'            => 'webformsMessages/add/',
            'successUrl'        => 'webformsMessages/manage/msg/added',
            'cancelUrl'         => 'webformsMessages/manage/',
            'passParameters'    => false,
            'method'            => 'post',
            'htmlOptions'       => array(
                'name'              => 'frmPollAdd',
                'autoGenerateId'    => true
            ),
            'requiredFieldsAlert' => true,
            'fields'            => array(
				'name'          => array('type'=>'textbox', 'title'=>A::t('webforms', 'Name'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>32), 'htmlOptions'=>array('maxlength'=>'32')),
				'email'			=> array('type'=>'textbox', 'title'=>A::t('webforms', 'Email'), 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100, 'unique'=>true), 'htmlOptions'=>array('maxlength'=>'100', 'class'=>'email', 'autocomplete'=>'off')),
//                    'separatorInfo'    => array('legend'=>A::t('polls', 'Other')),
//                    'color_text'       => array('type'=>'color', 'title'=>A::t('polls', 'Color Text'), 'default'=>'#000000', 'validation'=>array('required'=>false, 'type'=>'hexColor'), 'htmlOptions'=>array()),
//                    'background_color' => array('type'=>'color', 'title'=>A::t('polls', 'Background Color'), 'default'=>'#FFFFFF', 'validation'=>array('required'=>false, 'type'=>'hexColor'), 'htmlOptions'=>array()),
//                    'expires_at'       => array('type'=>'datetime', 'title'=>A::t('polls', 'Expires At'), 'default'=>null, 'validation'=>array('type'=>'date', 'maxLength'=>10), 'definedValues'=>array(), 'htmlOptions'=>array('maxLength'=>10, 'class'=>'medium')),
//                    'is_active'        => array('type'=>'checkbox', 'title'=>A::t('polls', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0, 1)), 'viewType'=>'custom'),
            ),
            'buttons'           => array('submit'=>array('type'=>'submit', 'value'=>A::t('webforms', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel'             => array('type'=>'button', 'value'=>A::t('webforms', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'    => 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('webforms', 'Message')),
            'return'            => true,
        ));
    ?>
    </div>
</div>
