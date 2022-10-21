<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Search Settings')));
	
	$this->_activeMenu = $backendPath.'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>$backendPath.'settings/general'),
		array('label'=>A::t('app', 'Search Settings'))
    );
    
    //A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
?>
    
<h1><?= A::t('app', 'Search Settings'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
	<?php		
		echo $actionMessage;
        
		echo CWidget::create('CFormView', array(
			'action'=>$backendPath.'settings/search',
			'method'=>'post',			
			'htmlOptions'=>array(
				'name'=>'frmGeneralSettings',
                'id'=>'frmGeneralSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=> array(
				'act' => array('type'=>'hidden', 'value'=>'send'),
	        	'separatorSearch' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'Search Settings')),
                    'searchItemsPerPage' 	=> array('type'=>'select', 'value'=>$settings->search_items_per_page, 'title'=>A::t('app', 'Search Results Page Size'), 'tooltip'=>A::t('app', 'Items Count On Search Result Page Tooltip'), 'data'=>$searchItemsPerPage, 'htmlOptions'=>array()),
                    'searchIsHighlighted' 	=> array('type'=>'checkbox', 'viewType'=>'custom', 'title'=>A::t('app', 'Highlight Results'), 'tooltip'=>A::t('app', 'Highlight Search Results Tooltip'), 'mandatoryStar'=>false, 'value'=>$settings->search_is_highlighted, 'checked'=>($settings->search_is_highlighted ? true : false), 'htmlOptions'=>array()),
                ),
			),
			'buttons' =>
                Admins::hasPrivilege('site_settings', 'edit') ? 
				array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')), 'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','".$backendPath."settings/search');"))) :
                array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	?>

		<br><br>
		<fieldset>
			<legend><?= A::t('app', 'Search Categories'); ?></legend>	
			<?php
				if($act == 'edit'):
					echo CWidget::create('CDataForm', array(
						'model'				=> 'Search',
						'primaryKey'		=> $searchCategory->id,
						'operationType'		=> 'edit',
						'action'			=> $backendPath.'settings/search/act/edit/id/'.$searchCategory->id,
						'successUrl'		=> $backendPath.'settings/search',
						'cancelUrl'			=> $backendPath.'settings/search',
						'passParameters'	=> true,
						'requiredFieldsAlert' => true,
						'return'			=> true,
						'htmlOptions'		=> array(
							'name'				=> 'frmSearchEdit',
							//'enctype'			=> 'multipart/form-data',
							'autoGenerateId'	=> true
						),
						'fields'			=> array(
							'module_code'       => array('type'=>'label', 'title'=>A::t('app', 'Module'), 'tooltip'=>''),
							'category_code'     => array('type'=>'label', 'title'=>A::t('app', 'Category Code'), 'tooltip'=>''),
							'category_name'     => array('type'=>'label', 'title'=>A::t('app', 'Category Name'), 'tooltip'=>''),
							'callback_class'    => array('type'=>'label', 'title'=>A::t('app', 'Callback Class'), 'tooltip'=>''),
							'callback_method'   => array('type'=>'label', 'title'=>A::t('app', 'Callback Method'), 'tooltip'=>''),
							'items_count' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Items Count'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'range', 'maxLength'=>'3', 'minValue'=>'1', 'maxValue'=>'100'), 'htmlOptions'=>array('maxLength'=>'3', 'class'=>'small')),
							'sort_order' 		=> array('type'=>'textbox', 'title'=>A::t('app', 'Sort Order'), 'tooltip'=>'', 'validation'=>array('required'=>true, 'maxLength'=>'2', 'type'=>'numeric'), 'htmlOptions'=>array('maxLength'=>'2', 'class'=>'small')),
							'is_active'  		=> array('type'=>'checkbox', 'title'=>A::t('app', 'Active'), 'htmlOptions'=>array(), 'viewType'=>'custom'),
						),
						'buttons' 			=> array(
							'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('app', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
							//'submitUpdate' 		=> array('type'=>'submit', 'value'=>A::t('app', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
							'cancel' 			=> array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
						),                
						'messagesSource'	=> 'core',
						'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('app', 'Search Category')),
						'return'            => true,
					));
				else:
					echo CWidget::create('CGridView', array(
						'model'				=> 'Search',
						'actionPath'		=> $backendPath.'settings/search',
						'defaultOrder'		=> array('sort_order'=>'ASC'),
						'passParameters'	=> true,
						'pagination'		=> array('enable'=>true, 'pageSize'=>100),
						'sorting'			=> true,
						'filters'			=> array(),
						'fields'			=> array(
							'module_code'    	=> array('title'=>A::t('app', 'Module'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'270px'),
							'category_code'    	=> array('title'=>A::t('app', 'Category Code'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'270px'),
							'category_name'    	=> array('title'=>A::t('app', 'Category Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'270px'),
							'callback_class'   	=> array('title'=>A::t('app', 'Callback Class'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'270px'),
							'callback_method'  	=> array('title'=>A::t('app', 'Callback Method'), 'type'=>'html', 'class'=>'left', 'headerClass'=>'left', 'width'=>'270px'),
							'items_count'    	=> array('title'=>A::t('app', 'Items Count'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'270px'),
							'sort_order'    	=> array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'270px', 'changeOrder'=>true),
							'is_active'    		=> array('title'=>A::t('app', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>$backendPath.'settings/changeSearchStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
						),
						'actions'			=> array(
							'edit'   		=> array(
								'disabled'		=> !Admins::hasPrivilege('site_settings', 'edit'),
								'link'			=> $backendPath.'settings/search/act/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
							),
						),
					));
				endif;
			?>
		</fieldset>

	</div>
</div>   
	
