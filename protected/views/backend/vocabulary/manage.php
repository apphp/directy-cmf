<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Vocabulary Management')));
	
    $this->_activeMenu = $backendPath.'vocabulary/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Language Settings'), 'url'=>$backendPath.'languages/'),
        array('label'=>A::t('app', 'Vocabulary')),
    );    
?>

<h1><?= A::t('app', 'Vocabulary Management'); ?></h1>

<div class="bloc">
    <div class="title"><?= A::t('app', 'Vocabulary'); ?></div>
    <div class="content">
    <?php  
		echo $actionMessage;
		
		$buttons = array();
		$importLink = '';
		if(Admins::hasPrivilege('vocabulary', 'edit')){
			$importLink = ($isActiveLanguage ? '' : ' &nbsp;<a href="'.$backendPath.'vocabulary/import/lang/'.$language.'" onclick="return onImportVocabulary();">[ '.A::t('app', 'Import From Default').' ]</a>');
			$buttons['submit'] = array('type'=>'submit', 'value'=>A::t('app', 'Update'));
		}
		        
        echo CWidget::create('CFormView', array(
				'action'    => $backendPath.'vocabulary/update',
				'method'    => 'post',
				'htmlOptions'=>array(
					'name'              => 'frmVocabulary',
					'autoGenerateId'    => true,
				),
        		'fields'=>array(
					'act'          => array('type'=>'hidden', 'value'=>'send'),
					'toggle_links' => array('type'=>'html', 'title'=>'', 'tooltip'=>'', 'mandatoryStar'=>false, 'value'=>'<a class="lnk-open-all" href="javascript:void(0)">'.A::te('app', 'Open All').'</a> | <a class="lnk-close-all" href="javascript:void(0)">'.A::te('app', 'Close All').'</a>', 'definedValues'=>array()),
					'language'	   => array('type'=>'select', 'value'=>$language, 'title'=>A::t('app', 'Language'), 'appendCode'=>$importLink, 'data'=>$langList, 'htmlOptions'=>array('submit'=>'$(this).closest("form").find("input[name=act]").val("changeLang");$(this).closest("form").attr("action","'.$backendPath.'vocabulary/manage");')),
					'fileName'	   => array('type'=>'select', 'value'=>$fileName, 'title'=>A::t('app', 'Language File'), 'data'=>$filesList, 'htmlOptions'=>array('submit'=>'$(this).closest("form").find("input[name=act]").val("changeFile");$(this).closest("form").attr("action","'.$backendPath.'vocabulary/manage");')),
					'fileContent'  => array('type'=>'vocabulary', 'value'=>$fileContent, 'title'=>A::t('app', 'Content'), 'htmlOptions'=>array('class'=>'full')),
				),
				'buttons'=>$buttons,
				'buttonsPosition'=>'both',
				'events'=>array(),
				'return'=>true,
        ));
    ?>
    </div>
</div>

<?php

A::app()->getClientScript()->registerScript(
	'vocabularyEdit',
	'jQuery(".row-key").on("click", function(){
        var $parent = $(this).closest(".key-group");	        
        var row_id = $parent.data("row");
        $parent.toggleClass("color-blue bold");
        $parent.find("#row-"+row_id).toggle();
    });
    jQuery(".lnk-open-all").on("click", function(){
        jQuery(".key-group").addClass("color-blue bold");
        jQuery(".row-value").show();
    });
    jQuery(".lnk-close-all").on("click", function(){
        jQuery(".key-group").removeClass("color-blue").removeClass("bold");
        jQuery(".row-value").hide();
    });
    jQuery(".row-text-value").on("dblclick", function(){
        var value = jQuery(this).val();	        
        jQuery(this).prop("readonly", false).focus().val("").val(value);
    });
    jQuery(".row-text-value").on("blur", function(){
        jQuery(this).prop("readonly", true);
    });',
	2
);

A::app()->getClientScript()->registerScript(
	'vocabularyImport',
	'function onImportVocabulary(val){
		return confirm("'.A::t('app', 'Vocabulary Import Alert').'");
	};',
	0
);

?>