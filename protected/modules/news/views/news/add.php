<?php
	Website::setMetaTags(array('title'=>A::t('news', 'Add News')));

    $this->_activeMenu = $backendPath.'modules/settings/code/news';
    $this->_breadCrumbs = array(
        array('label'=>A::t('news', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('news', 'News'), 'url'=>$backendPath.'modules/settings/code/news'),
        array('label'=>A::t('news', 'News Management'), 'url'=>'news/manage'),
        array('label'=>A::t('news', 'Add News')),
    );
?>

<!-- register tinymce files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('news', 'News Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
		
	<div class="sub-title"><?= A::t('news', 'Add News'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;          
			// Open form
			$formName = 'frmNewsAdd';
			echo CHtml::openForm('news/insert', 'post', array('name'=>$formName, 'autoGenerateId'=>true, 'enctype'=>'multipart/form-data'));
        ?>
        <input type="hidden" name="act" value="send">
		
		<div class="left-side" id="left-editpage">
			<span class="required-fields-alert"><?= A::t('core','Items marked with an asterisk (*) are required'); ?></span>
			<div class="row">
				<label for="news_header"><?= A::t('news', 'News Header'); ?>: <span class="required">*</span></label>
				<input id="news_header" type="text" value="<?= CHtml::encode($newsHeader); ?>" name="news_header" class="large" maxlength="255" />
			</div>
			<div class="row">
				<label for="news_text"><?= A::t('news', 'News Text'); ?>:</label>
				<div style="float:left">
					<textarea id="news_text" name="news_text" class="full" maxlength="10000"><?= $newsText; ?></textarea>
				</div>
				<div style="clear:both;"></div>
			</div>
	    </div>
	
	    <div class="central-part" id="right-editpage">
			<div class="row">
				<label for="is_published" class="small"><?= A::t('news', 'Published'); ?>:</label>
				<span id="is_published">
					<input id="is_published_0" value="0" <?= ($isPublished == 0 ? 'checked="checked"' : ''); ?> type="radio" name="is_published"> <label for="is_published_0"><?= A::t('news', 'No'); ?></label>
					<input id="is_published_1" value="1" <?= ($isPublished == 1 ? 'checked="checked"' : ''); ?> type="radio" name="is_published"> <label for="is_published_1"><?= A::t('news', 'Yes'); ?></label>
				</span>
			</div>
			<div class="row">
				<label for="language" class="small"><?= A::t('news', 'Language'); ?>:</label>
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
				<label for="intro_image" class="small"><?= A::t('news', 'Intro Image'); ?>:</label>
				<input type="file" id="intro_image" name="intro_image" value="" <?= APPHP_MODE == 'demo' ? 'disabled' : ''; ?> style="margin-top:5px; display: inline-block;" />
			</div>
            <div class="row">
                <label class="small"><?= A::t('news', 'Shortcodes'); ?>:</label>
				<?php
				echo '<label class="shortcodes">';
				foreach($shortCodes as $key => $val){
					echo '<span class="tooltip-icon tooltip-link" title="'.CHtml::encode($val['description']).'"></span> '.$val['value'].'<br>';
				}
				echo '</label>';
				?>
            </div>

			<div class="buttons-wrapper">
				<input type="button" class="button white" onclick="$(location).attr('href','news/manage');" value="<?= A::t('news', 'Cancel'); ?>" />
				<input type="submit" value="<?= A::te('news', 'Create'); ?>" />
			</div>
		</div>
		
		<div class="clear"></div>	
		
	    <div class="buttons-wrapper">
			<input value="<?= A::te('news', 'Create'); ?>" type="submit">
			<input class="button white" onclick="$(location).attr('href','news/manage');" value="<?= A::t('news', 'Cancel'); ?>" type="button">
		</div>
		<br>
		<?= CHtml::closeForm(); ?>    
    </div>
</div>
<?php

A::app()->getClientScript()->registerCss(
	'news-edit',
	'img.intro-image {width:82px;} a.intro-delete {margin-left:100px; margin-top:10px; display: inline-block;}'
);

A::app()->getClientScript()->registerScript(
	$formName,
	'$(".intro-delete").click(function(){
		$("input[name=act]").val("delete-intro");
		$("form[name='.$formName.']").submit();	
	});'.
	(($errorField != '') ? 'document.forms["'.$formName.'"].'.$errorField.'.focus();' : ''),
	5
);

A::app()->getSession()->set('privilege_category', 'news');
A::app()->getClientScript()->registerScript('setTinyMceEditor', 'setEditor("news_text",'.(($errorField == 'news_text') ? 'true' : 'false').');', 2);

?>
