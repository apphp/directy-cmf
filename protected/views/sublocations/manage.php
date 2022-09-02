<?php
    Website::setMetaTags(array('title'=>A::t('app', 'States')));
	
	$this->_activeMenu = 'locations/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Locations'), 'url'=>'locations/'),
		array('label'=>A::t('app', $selectedCountry->country_name)),
    );    
?>

<h1><?= A::t('app', 'Sub-Locations Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'States'); ?> <a class="back-link" href="locations/"><?= A::t('app', 'Back'); ?></a></div>
    <div class="content">
    <?php 
        echo $actionMessage; 
            
        if(Admins::hasPrivilege('locations', 'edit')){
            echo '<a href="subLocations/add/country/'.$selectedCountry->id.'" class="add-new">'.A::t('app', 'Add New').'</a>';
        }
                        
        echo CWidget::create('CGridView', array(
            'model'			=> 'States',
            'actionPath'	=> 'subLocations/manage/country/'.$selectedCountry->id,
            'defaultOrder'	=> array('sort_order'=>'ASC', 'name'=>'ASC'),
            'pagination'	=> array('enable'=>true, 'pageSize'=>20),
            'condition'		=> 'country_code="'.$selectedCountry->code.'"',
            'sorting'		=> true,
            'fields'		=> array(
                'state_name' 	=> array('title'=>A::t('app', 'Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
                'code'       	=> array('title'=>A::t('app', 'Code'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
				'sort_order' 	=> array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px'),
                'is_active'  	=> array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'110px'),
            ),
            'actions'		=> array(
                'edit' => array(
                    'disabled' 	=> !Admins::hasPrivilege('locations', 'edit'),
                    'link'		=> 'subLocations/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
                ),
                'delete' => array(
                    'disabled'	=> !Admins::hasPrivilege('locations', 'edit'),
                    'link'		=> 'subLocations/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
                )
            ),
        ));
    ?>        
    </div>
</div>
