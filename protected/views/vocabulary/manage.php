<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Vocabulary Management')));
	
    $this->_activeMenu = 'vocabulary/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'Language Settings'), 'url'=>'languages/'),
        array('label'=>A::t('app', 'Vocabulary')),
    );    

	// Register CodeMirror files
	A::app()->getClientScript()->registerCssFile('assets/vendors/codemirror/codemirror.css');
	A::app()->getClientScript()->registerScriptFile('assets/vendors/codemirror/codemirror.js');
	A::app()->getClientScript()->registerScriptFile('assets/vendors/codemirror/clike.js');
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
					'fileContent'=>array('type'=>'textarea', 'value'=>$fileContent, 'title'=>A::t('app', 'Content'), 'htmlOptions'=>array('maxlength'=>'50000', 'class'=>'full')),					
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
		{ lineNumbers: true, '.(A::app()->getLanguage('direction') == 'rtl' ? 'rtlMoveVisually: true,' : '').' mode: "text/x-csrc", indentUnit: 4, indentWithTabs: true, enterMode: "keep", tabMode: "shift" }
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