<?php
    Website::setMetaTags(array('title'=>A::t('polls', 'Add Polls')));

    $this->_activeMenu = $backendPath.'modules/settings/code/polls';
    $this->_breadCrumbs = array(
        array('label'=>A::t('polls', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('polls', 'Polls'), 'url'=>$backendPath.'modules/settings/code/polls'),
        array('label'=>A::t('polls', 'Polls Management'), 'url'=>'polls/manage'),
        array('label'=>A::t('polls', 'Add Polls')),
    );
?>

<h1><?= A::t('polls', 'Polls Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>

    <div class="sub-title"><?= A::t('polls', 'Add Polls'); ?></div>

    <div class="content">
    <?php
        echo CWidget::create('CDataForm', array(
            'model'             => 'Modules\Polls\Models\Polls',
            'operationType'     => 'add',
            'action'            => 'polls/add/',
            'successUrl'        => 'polls/manage/msg/added',
            'cancelUrl'         => 'polls/manage/',
            'passParameters'    => false,
            'method'            => 'post',
            'htmlOptions'       => array(
                'name'              => 'frmPollAdd',
                'autoGenerateId'    => true
            ),
            'requiredFieldsAlert' => true,
            'fields'            => array(
                'separatorComparison' => array(
                    'separatorInfo'     => array('legend'=>A::t('polls', 'Where to Show It')),
                    'type_comparison'   => array('type'=>'select', 'title'=>A::t('polls', 'Type Comparison'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0, 1, 2)), 'data'=>$arrViewType, 'viewType'=>'dropdownlist'),
                    'instruction'       => array('type'=>'textbox', 'title'=>A::t('polls', 'URL / Part URL'), 'tooltip'=>A::te('polls', 'Leave this field empty if you want to, this poll show on all pages'), 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>'255'), 'htmlOptions'=>array('maxLength'=>'255', 'class'=>'large')),
                ),
                'separatorOther' => array(
                    'separatorInfo'    => array('legend'=>A::t('polls', 'Other')),
                    'color_text'       => array('type'=>'color', 'title'=>A::t('polls', 'Color Text'), 'default'=>'#000000', 'validation'=>array('required'=>false, 'type'=>'hexColor'), 'htmlOptions'=>array()),
                    'background_color' => array('type'=>'color', 'title'=>A::t('polls', 'Background Color'), 'default'=>'#FFFFFF', 'validation'=>array('required'=>false, 'type'=>'hexColor'), 'htmlOptions'=>array()),
                    'expires_at'       => array('type'=>'datetime', 'title'=>A::t('polls', 'Expires At'), 'default'=>null, 'validation'=>array('type'=>'date', 'maxLength'=>10), 'definedValues'=>array(), 'htmlOptions'=>array('maxLength'=>10, 'class'=>'medium')),
                    'sort_order'       => array('type'=>'textbox', 'title'=>A::t('polls', 'Sort Order'), 'tooltip'=>'', 'default'=>'0', 'validation'=>array('required'=>true, 'type'=>'int', 'maxLength'=>'6'), 'htmlOptions'=>array('maxLength'=>'6', 'class'=>'small')),
                    'is_active'        => array('type'=>'checkbox', 'title'=>A::t('polls', 'Active'), 'tooltip'=>'', 'default'=>'1', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0, 1)), 'viewType'=>'custom'),
                ),
            ),
            'translationInfo'   => array('relation'=>array('id', 'polls_id'), 'languages'=>Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'))),
            'translationFields' => array(
                'poll_question'     => array('type'=>'textarea', 'title'=>A::t('polls', 'Poll Question'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255, 'class'=>'middle')),
                'poll_answer_1'     => array('type'=>'textbox', 'title'=>A::t('polls', 'Poll Answer').' 1', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255, 'class'=>'middle')),
                'poll_answer_2'     => array('type'=>'textbox', 'title'=>A::t('polls', 'Poll Answer').' 2', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255, 'class'=>'middle')),
                'poll_answer_3'     => array('type'=>'textbox', 'title'=>A::t('polls', 'Poll Answer').' 3', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255, 'class'=>'middle')),
                'poll_answer_4'     => array('type'=>'textbox', 'title'=>A::t('polls', 'Poll Answer').' 4', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255, 'class'=>'middle')),
                'poll_answer_5'     => array('type'=>'textbox', 'title'=>A::t('polls', 'Poll Answer').' 5', 'validation'=>array('required'=>false, 'type'=>'text', 'maxLength'=>255), 'htmlOptions'=>array('maxLength'=>255, 'class'=>'middle')),
            ),
            'buttons'           => array('submit'=>array('type'=>'submit', 'value'=>A::t('polls', 'Create'), 'htmlOptions'=>array('name'=>'')),
               'cancel'             => array('type'=>'button', 'value'=>A::t('polls', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
            ),
            'messagesSource'    => 'core',
            'showAllErrors'     => false,
            'alerts'            => array('type'=>'flash', 'itemName'=>A::t('polls', 'Poll')),
            'return'            => true,
        ));
    ?>
    </div>
</div>
