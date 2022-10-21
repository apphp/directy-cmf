<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Payment Providers Management')));
	
	$this->_activeMenu = $backendPath.'paymentProviders/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Payment Settings'), 'url'=>$backendPath.'currencies/manage'),
        array('label'=>A::t('app', 'Payment Providers')),
    );    
?>

<h1><?= A::t('app', 'Payment Providers Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Payment Providers'); ?></div>
    <div class="content">
		<?php 
			echo $actionMessage; 
		    	
		    if(Admins::hasPrivilege('payment_providers', 'edit')){
			    echo '<a href="'.$backendPath.'paymentProviders/add" class="add-new">'.A::t('app', 'Add New').'</a>';
				if(APPHP_MODE == 'debug'){
					echo '&nbsp;&nbsp;&nbsp;[ <a href="paymentProviders/testCheckout" target="_blank" class="_add-new">'.A::t('app', 'Test Checkout').'</a> ]';
				}
		    }
            
            echo CWidget::create('CGridView', array(
                'model'			=> 'PaymentProviders',
                'actionPath'	=> $backendPath.'paymentProviders/manage',
                'defaultOrder'	=> array('sort_order'=>'ASC'),
                'pagination'	=> array('enable'=>true, 'pageSize'=>20),
                'sorting'		=> true,
                'filters'		=> array(),
                'fields'		=> array(
                    'name'         => array('title'=>A::t('app', 'Payment Provider'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left', 'width'=>'150px'),
                    'description'  => array('title'=>A::t('app', 'Description'), 'type'=>'label', 'maxLength'=>'', 'showTooltip'=>true, 'class'=>'left', 'headerClass'=>'left', 'stripTags'=>true),
                	'used_on'	   => array('title'=>A::t('app', 'Used On'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$usedOn, 'width'=>'110px'),
					'mode'   	   => array('title'=>A::t('app', 'Mode'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>$modes, 'width'=>'110px'),
					'sort_order'   => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'85px'),
                    'is_default'   => array('title'=>A::t('app', 'Default'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray badge-square">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('app', 'Yes').'</span>'), 'width'=>'100px'),
					'is_active'    => array('title'=>A::t('app', 'Active'), 'type'=>'link', 'align'=>'', 'width'=>'90px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>$backendPath.'paymentProviders/changeStatus/id/{id}', 'linkText'=>'', 'definedValues'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'htmlOptions'=>array('class'=>'tooltip-link', 'title'=>A::t('app', 'Click to change status'))),
                ),
				'actions'		=> array(
					'edit' => array(
						'disabled'	=> !Admins::hasPrivilege('payment_providers', 'edit'),
						'link'		=> $backendPath.'paymentProviders/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
					),
					'delete' => array(
						'disabled'	=> !Admins::hasPrivilege('payment_providers', 'edit'),
						'link'		=> $backendPath.'paymentProviders/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
					)
				),
            ));        
        ?>    
    </div>
</div>
