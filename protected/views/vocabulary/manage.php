<!-- register CodeMirror files -->
<?php A::app()->getClientScript()->registerCssFile('js/vendors/codemirror/codemirror.css'); ?>
<?php A::app()->getClientScript()->registerScriptFile('js/vendors/codemirror/codemirror.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('js/vendors/codemirror/clike.js'); ?>

<?php
    $this->_activeMenu = 'vocabulary/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Languages Settings'), 'url'=>'languages/'),
        array('label'=>A::t('app', 'Vocabulary')),
    );    
?>

<h1><?php echo A::t('app', 'Vocabulary Management'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Vocabulary'); ?></div>
    <div class="content">
    <?php  
		echo $actionMessage;
		
		$buttons = array();
		$importLink = '';
		if(Admins::hasPrivilege('vocabulary', 'edit')){
			$importLink = ($isActiveLanguage ? '' : ' &nbsp;<a href="vocabulary/import/lang/'.$language.'" onclick="return onImportVocabulary();">[ '.A::t('app', 'Import From Default').' ]</a>');			 
			$buttons['submit'] = array('type'=>'submit', 'value'=>A::t('app', 'Update'));
		}
		        
        echo CWidget::create('CFormView', array(
				'action'=>'vocabulary/update',
				'method'=>'post',				
				'htmlOptions'=>array(
					'name'=>'frmVocabulary',
					'autoGenerateId'=>true
				),
        		'fields'=>array(
					'act'        =>array('type'=>'hidden', 'value'=>'send'),
					'language'	 =>array('type'=>'select', 'value'=>$language, 'title'=>A::t('app', 'Language'), 'appendCode'=>$importLink, 'data'=>$langList, 'htmlOptions'=>array('submit'=>'$(this).closest("form").find("input[name=act]").val("changeLang");$(this).closest("form").attr("action","vocabulary/manage");')),
					'fileName'	 =>array('type'=>'select', 'value'=>$fileName, 'title'=>A::t('app', 'Language File'), 'data'=>$filesList, 'htmlOptions'=>array('submit'=>'$(this).closest("form").find("input[name=act]").val("changeFile");$(this).closest("form").attr("action","vocabulary/manage");')),
					'fileContent'=>array('type'=>'textarea', 'value'=>$fileContent, 'title'=>A::t('app', 'Content'), 'htmlOptions'=>array('maxlength'=>'30000', 'class'=>'full')),					
				),
				'buttons'=>$buttons,
				'events'=>array(),
				'return'=>true,
        ));
    ?>
    </div>
</div>

<?php

A::app()->getClientScript()->registerScript(
	'vocabularyEditor',
	'var editor = CodeMirror.fromTextArea(
		document.getElementById("frmVocabulary_fileContent"),
		{ lineNumbers: true, mode: "text/x-csrc", indentUnit: 4, indentWithTabs: true, enterMode: "keep", tabMode: "shift" }
	);'
);


A::app()->getClientScript()->registerScript(
	'vocabularyImport',
	'function onImportVocabulary(val){
		return confirm("'.A::t('app', 'Vocabulary Import Alert').'");
	};',
	0
);

?>