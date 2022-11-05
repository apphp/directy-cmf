<?php
	Website::setMetaTags(array('title'=>A::t('cms', 'Add New Page')));

    $this->_pageTitle = A::t('cms', 'Pages Management').' - '.A::t('cms', 'Add New Page').' | '.CConfig::get('name');
    $this->_activeMenu = 'pages/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('cms', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('cms', 'CMS'), 'url'=>$backendPath.'modules/settings/code/cms'),
        array('label'=>A::t('cms', 'Pages'), 'url'=>'pages/manage'),
		array('label'=>A::t('cms', 'Add New Page')),
    );    
?>

<!-- register tinymce files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('cms', 'Pages Management')?></h1>	

<div class="bloc">
	<?= $tabs; ?>
		
	<div class="sub-title"><?= A::t('cms', 'Add New Page'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;          
			// Open form
			$formName = 'frmPageAdd';
			echo CHtml::openForm('pages/insert', 'post', array('name'=>$formName, 'autoGenerateId'=>true));
        ?>
        <input type="hidden" name="act" value="send">
		
		<div class="left-side" id="left-editpage">
			<span class="required-fields-alert"><?= A::t('core','Items marked with an asterisk (*) are required'); ?></span>

			<div class="row">
				<label for="page_header"><?= A::t('cms', 'Page Header'); ?>: <span class="required">*</span></label>
				<input id="page_header" type="text" value="<?= CHtml::encode($pageHeader); ?>" name="page_header" class="large" maxlength="255">
			</div>
			<div class="row">
				<label for="page_text"><?= A::t('cms', 'Page Text'); ?>:</label>
				<div style="float:left">
					<textarea id="page_text" name="page_text" class="full" maxlength="20000"><?= $pageText; ?></textarea>
				</div>
				<div style="clear: both;"></div>
			</div>
			<fieldset>
				<legend style="cursor:pointer;" onclick="javascript:$('#meta-content').toggle('fast');"><?= A::t('cms', 'META Tags'); ?></legend>
				<div id="meta-content" style="display:none;">
				<div class="row">
					<label class="meta-tag" for="tag_title"><?= CHtml::encode(A::t('cms', 'Tag TITLE')); ?>:</label>
					<textarea maxlength="255" class="small-wide" id="tag_title" name="tag_title"><?= $tagTitle; ?></textarea>
				</div>
				<div class="row">
					<label class="meta-tag" for="tag_keywords"><?= CHtml::encode(A::t('cms', 'Meta Tag KEYWORDS')); ?>:</label>
					<textarea maxlength="255" class="middle-wide" id="tag_keywords" name="tag_keywords"><?= $tagKeywords; ?></textarea>
				</div>
				<div class="row">
					<label class="meta-tag" for="tag_description"><?= CHtml::encode(A::t('cms', 'Meta Tag DESCRIPTION')); ?>:</label>
					<textarea maxlength="255" class="middle-wide" id="tag_description" name="tag_description"><?= $tagDescription; ?></textarea>
				</div>
				</div>
			</fieldset>
	    </div>
	
	    <div class="central-part" id="right-editpage">
			<div class="row">
				<label for="publish_status" class="small"><?= A::t('cms', 'Published'); ?>:</label>
				<span id="publish_status">
					<input id="publish_status_0" value="0" <?= ($publishStatus == 0 ? 'checked="checked"' : ''); ?> type="radio" name="publish_status"> <label for="publish_status_0"><?= A::t('cms', 'No'); ?></label>
					<input id="publish_status_1" value="1" <?= ($publishStatus == 1 ? 'checked="checked"' : ''); ?> type="radio" name="publish_status"> <label for="publish_status_1"><?= A::t('cms', 'Yes'); ?></label>
				</span>
			</div>
			<div class="row">
				<label for="language" class="small"><?= A::t('cms', 'Language'); ?>:</label>
				<select id="language" name="language" disabled="disabled">
					<?php 
					if(is_array($langList)){
						foreach($langList as $lang){
							echo '<option value="'.$lang['code'].'" '.($language == $lang['code'] ? 'selected="selected"' : '').'>'.$lang['name_native'].'</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="row">
				<label for="is_homepage" class="small"><?= A::t('cms', 'Home Page'); ?>:</label>
				<?php 
					$checkBoxField  = CHtml::openTag('div', array('class'=>'slideBox'));
					$checkBoxField .= CHtml::checkBox('is_homepage', ($isHomepage ? true : false), array('uncheckValue'=>0));
					$checkBoxField .= CHtml::label('', 'is_homepage');
					$checkBoxField .= CHtml::closeTag('div');
					echo $checkBoxField;					
				?>
			</div>
			<div class="row">
				<label for="sort_order" class="small"><?= A::t('cms', 'Sort Order'); ?>:</label>
				<input id="sort_order" type="text" value="<?= $sortOrder; ?>" name="sort_order" class="small" maxlength="4">
			</div>			
			<div class="row">
				<label class="small"><?= A::t('cms', 'Shortcodes'); ?>:</label>
				<?php
					echo '<label class="shortcodes">';
					foreach($shortCodes as $key => $val){
						echo '<span class="tooltip-icon tooltip-link" title="'.CHtml::encode($val['description']).'"></span> '.$val['value'].'<br>';
					}
					echo '</label>';
				?>
			</div>
			
			<div class="buttons-wrapper">
				<input class="button white" onclick="$(location).attr('href','pages/manage');" value="<?= A::t('cms', 'Cancel'); ?>" type="button">
				<input value="<?= A::t('cms', 'Create'); ?>" type="submit">
			</div>
		</div>
		
		<div class="clear"></div>	
		
	    <div class="buttons-wrapper">
			<input value="<?= A::t('cms', 'Create'); ?>" type="submit">
			<input class="button white" onclick="$(location).attr('href','pages/manage');" value="<?= A::t('cms', 'Cancel'); ?>" type="button">
		</div>

		<?= CHtml::closeForm(); ?>    
    </div>
</div>
<?php

if($errorField != ''){
	A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$errorField.'.focus();', 2);
}
A::app()->getSession()->set('privilege_category', 'pages');
A::app()->getClientScript()->registerScript('setTinyMceEditor', 'setEditor("page_text",'.(($errorField == 'page_text') ? 'true' : 'false').');', 2);
?>