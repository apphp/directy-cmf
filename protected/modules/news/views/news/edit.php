<?php
	Website::setMetaTags(array('title'=>A::t('news', 'Edit News')));

    $this->_activeMenu = $backendPath.'modules/settings/code/news';
    $this->_breadCrumbs = array(
        array('label'=>A::t('news', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('news', 'News'), 'url'=>$backendPath.'modules/settings/code/news'),
        array('label'=>A::t('news', 'News Management'), 'url'=>'news/manage'),
        array('label'=>A::t('news', 'Edit News')),
    );

	$rPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
	$rSortBy = A::app()->getRequest()->getQuery('sort_by', 'string');
	$rSortDir = A::app()->getRequest()->getQuery('sort_dir', 'string');				
	$additionalParams  = ($rSortBy) ? '/sort_by/'.$rSortBy : '';
	$additionalParams .= ($rSortDir) ? '/sort_dir/'.$rSortDir : '';
	$additionalParams .= ($rPage) ? '/page/'.$rPage : '';
?>

<!-- register tinymce files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('news', 'News Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

	<div class="sub-title"><?= A::t('news', 'Edit News'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;  
        	// Open form
			$formName = 'frmNewsEdit';
			echo CHtml::openForm('news/update'.$additionalParams, 'post', array('name'=>$formName, 'autoGenerateId'=>true, 'enctype'=>'multipart/form-data'));
        ?>
        <input type="hidden" name="act" value="send">
        <input type="hidden" name="id" value="<?= $id; ?>">

		<div class="left-side" id="left-editpage">
			<span class="required-fields-alert"><?= A::t('core', 'Items marked with an asterisk (*) are required'); ?></span>
			<div class="row">
				<label for="news_header"><?= A::t('news', 'News Header'); ?>: <span class="required">*</span></label>
				<input id="news_header" type="text" value="<?= CHtml::encode($newsHeader); ?>" name="news_header" class="large" maxlength="255">
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
                <select id="language" name="language">
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
				<input type="file" id="intro_image" name="intro_image" value="" <?= APPHP_MODE == 'demo' ? 'disabled' : ''; ?> style="margin-top:5px; display: inline-block;"  />
			</div>
			<div class="row">				
				<label for="intro_image" class="small"></label>				
				<?php
					if(!empty($introImage)){
						echo '<img class="intro-image" src="assets/modules/news/images/intro_images/'.$introImage.'" alt="intro" /><br>';
						echo '<a class="intro-delete" href="javascript:void(\'delete-intro\');">'.A::t('app', 'Delete').'</a>';
					}
				?>
			</div>

            <div class="row">
                <label class="small"><?= A::t('news', 'Shortcodes'); ?>:</label>
				<?php
				echo '<label class="shortcodes">';
				if(!empty($shortCodes)):
                    foreach($shortCodes as $key => $val):
                        echo '<span class="tooltip-icon tooltip-link" title="'.CHtml::encode($val['description']).'"></span> '.$val['value'].'<br>';
                    endforeach;
                else:
                    echo '--';
                endif;
				echo '</label>';
				?>
            </div>

            <div class="row">
                <label class="small"><?= A::t('news', 'Created At'); ?>:</label>
                <label><?= CLocale::date($dateTimeFormat, $createdAt); ?></label>
            </div>
            <div class="row">
                <label class="small"><?= A::t('news', 'Last Update'); ?>:</label>
                <label><?= CLocale::date($dateTimeFormat, $modifiedAt); ?></label>
            </div>

            <div class="buttons-wrapper">
				<input type="button" class="button white" onclick="$(location).attr('href','news/manage');" value="<?= A::t('news', 'Cancel'); ?>" />
				<input type="submit" name="btnUpdate" value="<?= A::t('news', 'Update'); ?>" />
			</div>
		</div>

		<div class="clear"></div>	
		
	    <div class="buttons-wrapper">
            <input type="submit" name="btnUpdateClose" value="<?= A::t('app', 'Update & Close'); ?>">
			<input type="submit" name="btnUpdate" value="<?= A::t('app', 'Update'); ?>">
			<input type="button" class="button white" onclick="$(location).attr('href','news/manage');" value="<?= A::t('news', 'Cancel'); ?>">
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
	'$("select#language").change(function(){
		$("form[name='.$formName.']").attr("action","news/edit");
		$("form[name='.$formName.']").submit();	
	});
	$(".intro-delete").click(function(){
		$("input[name=act]").val("delete-intro");
		$("form[name='.$formName.']").submit();	
	});'.
	(($errorField != '') ? 'document.forms["'.$formName.'"].'.$errorField.'.focus();' : ''),
	5
);

A::app()->getSession()->set('privilege_category', 'news');
A::app()->getClientScript()->registerScript('setTinyMceEditor', 'setEditor("news_text",'.(($errorField == 'news_text') ? 'true' : 'false').');', 5);

?>