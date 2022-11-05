<?php
	Website::setMetaTags(array('title'=>A::t('webforms', 'Messages')));

    $this->_activeMenu  = $backendPath.'modules/settings/code/webforms';
    $this->_breadCrumbs = array(
        array('label' => A::t('webforms', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label' => A::t('webforms', 'Web Forms'),    'url'=>$backendPath.'modules/settings/code/webforms'),
        array('label' => A::t('webforms', 'Messages')),
    );    
?>

<h1><?= A::t('webforms', 'Messages Management'); ?></h1>

<div class="bloc">
    <?= $tabs; ?>
    <div class="content">
    <?php 
        echo $actionMessage;  
        if(Admins::hasPrivilege('modules', 'edit') && Admins::hasPrivilege('webformMessages', 'add')){
            echo '<a href="webformsMessages/add" class="add-new">'.A::t('webforms', 'Add Message').'</a>';
        }

	    $fieldName = ModulesSettings::model()->param('webforms', 'field_name');
	    $fieldEmail = ModulesSettings::model()->param('webforms', 'field_email');
	    $fieldPhone = ModulesSettings::model()->param('webforms', 'field_phone');
	    $fieldCompany = ModulesSettings::model()->param('webforms', 'field_company');

	    $filters = array();
        $filters['name'] = array('title' => A::t('webforms', 'Name'), 'type' => 'textbox', 'operator' => '%like%', 'width' => '120px', 'maxLength' => '64');
        $filters['email'] = array('title' => A::t('webforms', 'Email'), 'type' => 'textbox', 'operator' => '%like%', 'width' => '120px', 'maxLength' => '128');
        $filters['created_at'] = array('title'=>A::t('webforms', 'Date'), 'type'=>'datetime', 'operator'=>'like%', 'width'=>'80px', 'maxLength'=>'10');
        
        $fields = array();
	    if ($fieldName == 'show-optional' || $fieldName == 'show-required') $fields['name'] = array('title' => A::t('webforms', 'Name'), 'type' => 'label', 'align' => 'left', 'width' => '150px', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'defaultValues' => array());
   	    if ($fieldEmail == 'show-optional' || $fieldEmail == 'show-required') $fields['email'] = array('title' => A::t('webforms', 'Email'), 'type' => 'label', 'align' => 'left', 'width' => '', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'defaultValues' => array());
        if ($fieldPhone == 'show-optional' || $fieldPhone == 'show-required') $fields['phone'] = array('title' => A::t('webforms', 'Phone'), 'type' => 'label', 'align' => 'left', 'width' => '140px', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'defaultValues' => array());
        if ($fieldCompany == 'show-optional' || $fieldCompany == 'show-required') $fields['company'] = array('title' => A::t('webforms', 'Company'), 'type' => 'label', 'align' => 'left', 'width' => '150px', 'class' => 'left', 'headerClass' => 'left', 'isSortable' => true, 'defaultValues' => array());

        $fields['created_at'] = array('title' => A::t('webforms', 'Date Created'), 'type' => 'label', 'align' => 'left', 'width' => '130px', 'class' => 'left', 'headerClass' => 'left', 'isSorting' => true, 'defaultValues' => array(), 'format' => $dateTimeFormat);
        $fields['id'] = array('title' => A::t('webforms', 'ID'), 'type' => 'label', 'align' => 'center', 'width' => '60px', 'class' => 'center', 'headerClass' => 'center', 'isSorting' => true, 'defaultValues' => array());

        CWidget::create('CGridView', array(
            'model'          => '\Modules\Webforms\Models\WebformsMessages',
            'actionPath'     => 'webformsMessages/manage',
            'condition'      => '',
            'defaultOrder'   => array('id'=>'DESC'),
            'passParameters' => true,
            'pagination'     => array('enable'=>true, 'pageSize' => 20),
            'sorting'        => true,
            'filters'        => $filters, 
            'fields'         => $fields,
            'actions'        => array(
                'edit' => array('disabled' => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('webformMessages', 'edit'), 'link' => 'webformsMessages/edit/id/{id}', 'imagePath' => 'templates/backend/images/edit.png', 'title' => A::t('webforms', 'Edit this record')),
                'delete' => array('disabled' => !Admins::hasPrivilege('modules', 'edit') || !Admins::hasPrivilege('webformMessages', 'delete'), 'link' => 'webformsMessages/delete/id/{id}', 'imagePath' => 'templates/backend/images/delete.png', 'title' => A::t('webforms', 'Delete this record'), 'onDeleteAlert' => true)),
            'return' => false,
        ));

    ?>        
    </div>
</div>
