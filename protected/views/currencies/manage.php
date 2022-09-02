<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Currencies Management')));
	
	$this->_activeMenu = 'currencies/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Currencies')),
    );    
?>

<h1><?= A::t('app', 'Currencies Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Currencies'); ?></div>
    <div class="content">
		<?php 
			echo $actionMessage; 
		    	
		    if(Admins::hasPrivilege('currencies', 'edit')){
			    echo '<a href="currencies/add" class="add-new">'.A::t('app', 'Add New').'</a>';
		    }
            
            echo CWidget::create('CGridView', array(
                'model'			=> 'Currencies',
                'actionPath'	=> 'currencies/manage',
                'defaultOrder'	=> array('sort_order'=>'ASC'),
                'pagination'	=> array('enable'=>true, 'pageSize'=>20),
                'sorting'		=> true,
                'filters'		=> array(),
                'fields'		=> array(
                    'name'         => array('title'=>A::t('app', 'Currency Name'), 'type'=>'label', 'class'=>'left', 'headerClass'=>'left'),
	                'symbol'       => array('title'=>A::t('app', 'Symbol'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'100px'),
                    'example_symbol_place' => array('title'=>A::t('app', 'Symbol Place'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'110px'),
                    'code'         => array('title'=>A::t('app', 'Code'), 'type'=>'label', 'class'=>'center upper-case', 'headerClass'=>'center', 'width'=>'85px'),
                	'rate'         => array('title'=>A::t('app', 'Rate'), 'type'=>'decimal', 'class'=>'center', 'headerClass'=>'center', 'width'=>'85px', 'format'=>$numberFormat),
                	///'decimals'     => array('title'=>A::t('app', 'Decimals'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'0', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4'), 'width'=>'80px'),
                	'sort_order'   => array('title'=>A::t('app', 'Sort Order'), 'type'=>'label', 'class'=>'center', 'headerClass'=>'center', 'width'=>'85px'),
                    'is_default'   => array('title'=>A::t('app', 'Default'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-gray">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'90px'),
                    'is_active'    => array('title'=>A::t('app', 'Active'), 'type'=>'enum', 'class'=>'center', 'headerClass'=>'center', 'source'=>array('0'=>'<span class="badge-red">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green">'.A::t('app', 'Yes').'</span>'), 'width'=>'90px'),
                ),
				'actions'		=> array(
					'edit' => array(
						'disabled'	=> !Admins::hasPrivilege('currencies', 'edit'),
						'link'		=> 'currencies/edit/id/{id}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>A::t('app', 'Edit this record')
					),
					'delete' => array(
						'disabled'	=> !Admins::hasPrivilege('currencies', 'edit'),
						'link'		=> 'currencies/delete/id/{id}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>A::t('app', 'Delete this record'), 'onDeleteAlert'=>true
					)
				),
            ));        
        ?>    
    </div>
</div>
