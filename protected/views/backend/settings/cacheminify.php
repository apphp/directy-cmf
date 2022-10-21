<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Cache & Minification Settings')));
	
	$this->_activeMenu = $backendPath.'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>$backendPath.'dashboard'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>$backendPath.'settings/general'),
		array('label'=>A::t('app', 'Cache Settings'))
    );    
?>
    
<h1><?= A::t('app', 'Cache & Minification Settings'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
	<?php		
 		echo $actionMessage;		
		
		echo CWidget::create('CFormView', array(
			'action'=>$backendPath.'settings/cacheMinify',
			'method'=>'post',			
			'htmlOptions'=>array(
				'name'=>'frmCacheSettings',
                'id'=>'frmCacheSettings',
				'autoGenerateId'=>true
			),
			'requiredFieldsAlert'=>true,
			'fields'=> array(
				'act' => array('type'=>'hidden', 'value'=>'send'),
	        	'separatorCache' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'Cache Settings')),
                    'cacheAllowed' 			=> array('type'=>'label', 'title'=>A::t('app', 'Enabled'), 'tooltip'=>A::t('app', 'Cache Tooltip'), 'value'=>$cacheEnable, 'definedValues'=>array(''=>'<span class="badge-red badge-square">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('app', 'Yes').'</span>'), 'format'=>''),
					'cacheType' 			=> array('type'=>'label', 'title'=>A::t('app', 'Cache Type'), 'tooltip'=>A::t('app', 'Cache Type Tooltip'), 'value'=>$cacheType, 'definedValues'=>array('auto'=>A::t('app', 'Auto'), 'manual'=>A::t('app', 'Manual')), 'format'=>''),
                    'cacheLifetime' 		=> array('type'=>'label', 'title'=>A::t('app', 'Cache Lifetime'), 'tooltip'=>A::t('app', 'Cache Lifetime Tooltip'), 'value'=>$cacheLifetime.' '.($cacheLifetime == 1 ? A::t('app', 'minute') : A::t('app', 'minutes')), 'definedValues'=>array(), 'format'=>''),
                    'cachePath' 			=> array('type'=>'label', 'title'=>A::t('app', 'Cache Path'), 'tooltip'=>A::t('app', 'Cache Path Tooltip'), 'value'=>$cachePath, 'definedValues'=>array(), 'format'=>''),
                    'cacheFiles' 			=> array('type'=>'label', 'title'=>A::t('app', 'Cache Size'), 'tooltip'=>'', 'value'=>$cacheFiles, 'definedValues'=>array(), 'format'=>''),
                    'cacheDeleteLink' =>
                        !CFile::isDirectoryEmpty(CConfig::get('cache.db.path'), array('index.html')) ?
                        array('type'=>'link', 'title'=>A::t('app', 'Clear Cache'), 'tooltip'=>A::t('app', 'Clear Cache Tooltip'), 'linkUrl'=>$backendPath.'settings/cacheMinify/task/clearCache', 'linkText'=>A::t('app', 'Clear Cache'), 'htmlOptions'=>array('class'=>'settings-link', 'onclick'=>'javascript:return clearCacheAlert()'), 'prependCode'=>'[ ', 'appendCode'=>' ]') :
                        array('type'=>'label', 'title'=>A::t('app', 'Clear Cache'), 'tooltip'=>A::t('app', 'Clear Cache Tooltip'), 'value'=>A::t('app', 'No cache found'), 'definedValues'=>array(), 'format'=>''),
	            ),
	        	'separatorMinifyCss' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'CSS Minification Settings')),
                    'minifyAllowed' 		=> array('type'=>'label', 'title'=>A::t('app', 'Enabled'), 'tooltip'=>A::t('app', 'Minify Tooltip CSS'), 'value'=>$cssMinifyEnable, 'definedValues'=>array(''=>'<span class="badge-red badge-square">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('app', 'Yes').'</span>'), 'format'=>''),
                    'minifyPath' 			=> array('type'=>'label', 'title'=>A::t('app', 'Minify Path'), 'tooltip'=>A::t('app', 'Minify Path Tooltip CSS'), 'value'=>$cssMinifyPath, 'definedValues'=>array(), 'format'=>''),
                    'minifyFiles' 			=> array('type'=>'label', 'title'=>A::t('app', 'CSS Minify Size'), 'tooltip'=>'', 'value'=>$cssMinifyFiles, 'definedValues'=>array(), 'format'=>''),
                    'minifyDeleteLink' =>
                        !CFile::isDirectoryEmpty(CConfig::get('compression.css.path'), array('index.html')) ? 
                        array('type'=>'link', 'title'=>A::t('app', 'Clear CSS Minification'), 'tooltip'=>A::t('app', 'Delete Minify CSS Files Tooltip'), 'linkUrl'=>$backendPath.'settings/cacheMinify/task/clearCssMinify', 'linkText'=>A::t('app', 'Clear CSS Minification'), 'htmlOptions'=>array('class'=>'settings-link', 'onclick'=>'javascript:return clearCssMinifyAlert()'), 'prependCode'=>'[ ', 'appendCode'=>' ]') :
                        array('type'=>'label', 'title'=>A::t('app', 'Clear CSS Minification'), 'tooltip'=>A::t('app', 'Delete Minify CSS Files Tooltip'), 'value'=>A::t('app', 'No minified files found'), 'definedValues'=>array(), 'format'=>''),
	            ),
	        	'separatorMinifyJs' => array(
	                'separatorInfo' 		=> array('legend'=>A::t('app', 'JS Minification Settings')),
                    'minifyAllowed' 		=> array('type'=>'label', 'title'=>A::t('app', 'Enabled'), 'tooltip'=>A::t('app', 'Minify Tooltip JS'), 'value'=>$jsMinifyEnable, 'definedValues'=>array(''=>'<span class="badge-red badge-square">'.A::t('app', 'No').'</span>', '1'=>'<span class="badge-green badge-square">'.A::t('app', 'Yes').'</span>'), 'format'=>''),
                    'minifyPath' 			=> array('type'=>'label', 'title'=>A::t('app', 'Minify Path'), 'tooltip'=>A::t('app', 'Minify Path Tooltip JS'), 'value'=>$jsMinifyPath, 'definedValues'=>array(), 'format'=>''),
                    'minifyFiles' 			=> array('type'=>'label', 'title'=>A::t('app', 'JS Minify Size'), 'tooltip'=>'', 'value'=>$jsMinifyFiles, 'definedValues'=>array(), 'format'=>''),
                    'minifyDeleteLink' =>
                        !CFile::isDirectoryEmpty(CConfig::get('compression.js.path'), array('index.html')) ?
                        array('type'=>'link', 'title'=>A::t('app', 'Clear JS Minification'), 'tooltip'=>A::t('app', 'Delete Minify JS Files Tooltip'), 'linkUrl'=>$backendPath.'settings/cacheMinify/task/clearJsMinify', 'linkText'=>A::t('app', 'Clear JS Minification'), 'htmlOptions'=>array('class'=>'settings-link', 'onclick'=>'javascript:return clearJsMinifyAlert()'), 'prependCode'=>'[ ', 'appendCode'=>' ]') :
                        array('type'=>'label', 'title'=>A::t('app', 'Clear JS Minification'), 'tooltip'=>A::t('app', 'Delete Minify JS Files Tooltip'), 'value'=>A::t('app', 'No minified files found'), 'definedValues'=>array(), 'format'=>''),
	            ),
			),
//			'buttons' =>
//                Admins::hasPrivilege('site_settings', 'edit') ? 
//				array('submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')), 'cancel'=>array('type'=>'button', 'value'=>A::t('app', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white', 'onclick'=>"$(location).attr('href','settings/general');"))) :
//                array(),
			'events'=>array(
				'focus'=>array('field'=>$errorField)
			),
			'return'=>true,
		));
	?>
	</div>
</div>  
 
<?php
	A::app()->getClientScript()->registerScript(
		'cache-alert',
		'function clearCacheAlert(){
			if(!confirm("'.CHtml::encode(A::t('app', 'Are you sure you want to clear cache?')).'")) return false;
            return true;
		};
		function clearCssMinifyAlert(){
			if(!confirm("'.CHtml::encode(A::t('app', 'Are you sure you want to delete CSS minification files?')).'")) return false;
            return true;
		};
		function clearJsMinifyAlert(){
			if(!confirm("'.CHtml::encode(A::t('app', 'Are you sure you want to delete JS minification files?')).'")) return false;
            return true;
		};',
		0
	);
?>
	
