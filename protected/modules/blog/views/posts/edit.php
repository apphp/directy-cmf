<?php
	Website::setMetaTags(array('title'=>A::t('blog', 'Edit Post')));
	
    $this->_pageTitle = A::t('blog', 'Posts Management').' - '.A::t('blog', 'Edit Post').' | '.CConfig::get('name');
    $this->_activeMenu = 'posts/manage';
    $this->_breadCrumbs = array(
        array('label'=>A::t('blog', 'Modules'), 'url'=>$backendPath.'modules/'),
        array('label'=>A::t('blog', 'Blog'), 'url'=>$backendPath.'modules/settings/code/blog'),
        array('label'=>A::t('blog', 'Posts Management'), 'url'=>'posts/manage'),
        array('label'=>A::t('blog', 'Edit Post')),
    );

	$rPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
	$rSortBy = A::app()->getRequest()->getQuery('sort_by', 'string');
	$rSortDir = A::app()->getRequest()->getQuery('sort_dir', 'string');				
	$additionalParams  = ($rSortBy) ? '/sort_by/'.$rSortBy : '';
	$additionalParams .= ($rSortDir) ? '/sort_dir/'.$rSortDir : '';
	$additionalParams .= ($rPage) ? '/page/'.$rPage : '';
?>

<!-- Register tinymce files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/tiny_mce.js'); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/tinymce/config.js'); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/tinymce/general.css'); ?>

<h1><?= A::t('blog', 'Posts Management'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>
		
	<div class="sub-title"><?= A::t('blog', 'Edit Post'); ?></div>
    <div class="content">
        <?php
			echo $actionMessage;          
			// Open form
			$formName = 'frmPostEdit';
			echo CHtml::openForm('posts/update'.$additionalParams, 'post', array('name'=>$formName, 'class'=>'widget-cformview', 'autoGenerateId'=>true));
        ?>
        <input type="hidden" name="act" value="send">
        <input type="hidden" name="id" value="<?= $post->id; ?>">
  
		<div class="left-side" id="left-editpost">
			<span class="required-fields-alert"><?= A::t('core','Items marked with an asterisk (*) are required'); ?></span>

			<div class="row">
				<label for="post_header"><?= A::t('blog', 'Post Header'); ?>: <span class="required">*</span></label>
				<input id="post_header" type="text" value="<?= CHtml::encode($post->post_header); ?>" name="post_header" class="large" maxlength="255">
			</div>
			<div class="row">
				<label for="post_text"><?= A::t('blog', 'Post Text'); ?>: <span class="required">*</span></label>
				<div style="float:left">
					<textarea id="post_text" name="post_text" class="full" maxlength="10000"><?= $post->post_text; ?></textarea>
				</div>
				<div style="clear:both;"></div>
			</div>
	    </div>
  
	    <div class="central-part" id="right-editpost">
            <div class="row">
                <label for="publish_status" class="small"><?= A::t('blog', 'Published'); ?>:</label>
                <span id="publish_status">
					<input id="publish_status_0" value="0" <?= ($post->publish_status == 0 ? 'checked="checked"' : ''); ?> type="radio" name="publish_status"> <label for="publish_status_0"><?= A::t('blog', 'No'); ?></label>
					<input id="publish_status_1" value="1" <?= ($post->publish_status == 1 ? 'checked="checked"' : ''); ?> type="radio" name="publish_status"> <label for="publish_status_1"><?= A::t('blog', 'Yes'); ?></label>
				</span>
            </div>
            <div class="row">
                <label class="small"><?= A::t('blog', 'Created At'); ?>:</label>
                <label><?= CLocale::date($dateTimeFormat, $post->created_at); ?></label>
            </div>
            <div class="row">
                <label class="small"><?= A::t('blog', 'Last Update'); ?>:</label>
                <label><?= CLocale::date($dateTimeFormat, $post->modified_at); ?></label>
            </div>
            <? if(!empty($shortCodes)): ?>
			<div class="row">
				<label class="small"><?= A::t('blog', 'Shortcodes'); ?>:</label>
				<?php
					echo '<label class="shortcodes">';
					foreach($shortCodes as $key => $val):
						echo '<span class="tooltip-icon tooltip-link" title="'.CHtml::encode($val['description']).'"></span> '.$val['value'].'<br>';
					endforeach;
					echo '</label>';
				?>
			</div>
            <? endif; ?>
			
			<div class="buttons-wrapper">
                <input type="button" class="button white" onclick="$(location).attr('href','posts/manage<?= $additionalParams; ?>');" value="<?= A::t('blog', 'Cancel'); ?>">
				<input class="btn-prevent-double-submit" value="<?= A::t('blog', 'Update'); ?>" type="submit">
			</div>
		</div>
        
		<div class="clear"></div>	
		
	    <div class="buttons-wrapper">
            <input type="submit" class="btn-prevent-double-submit" name="btnUpdateClose" value="<?= A::t('blog', 'Update & Close'); ?>">
			<input type="submit" class="btn-prevent-double-submit" name="btnUpdate" value="<?= A::t('blog', 'Update'); ?>">
			<input type="button" class="button white" onclick="$(location).attr('href','posts/manage<?= $additionalParams; ?>');" value="<?= A::t('blog', 'Cancel'); ?>">
		</div>

		<?= CHtml::closeForm(); ?>    
    </div>
</div>
<?php
if($errorField != ''){
	A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$errorField.'.focus();', 2);
}
A::app()->getSession()->set('privilege_category', 'posts');
A::app()->getClientScript()->registerScript('setTinyMceEditor', 'setEditor("post_text",'.(($errorField == 'post_text') ? 'true' : 'false').');', 2);
?>