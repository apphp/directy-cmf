<?php
	Website::setMetaTags(array('title'=>A::t('news', 'Unsubscribe from Newsletter')));
	///A::app()->getClientScript()->registerCssFile('assets/modules/news/css/news.css');

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('news', 'Unsubscribe from Newsletter');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('news', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('news', 'Unsubscribe from Newsletter')),
	);
	
	$layout = 'advanced';
?>

<div class="v-heading-v2">
	<h1 class="title"><?= A::t('news', 'Unsubscribe from Newsletter') ?></h1>
</div>

<?php if($layout == 'advanced'): ?>
	<div class="v-comment-form">
<?php else: ?>	
	<div class="subscribe-form-content">
<?php endif; ?>

	<?= !empty($actionMessage) ? $actionMessage : '<p id="messageInfo" class="alert alert-info">'.A::t('news', 'Are you sure you want to unsubscribe').'</p>'; ?>	
	
    <?php
        // Open form
        $formName = 'frmNewsUnsubscribe';
        echo CHtml::openForm(
			'newsSubscribers/unsubscribe',
			'post',
			array('id' => 'subscribe-form', 'name'=>$formName, 'autoGenerateId'=>true, 'class' => 'unsubscribe')
		);
    ?>
    <input type="hidden" name="act" value="send" />
	
	<?php if($layout == 'advanced'): ?>
		<div class="row form-group">
			<div class="col-sm-12">
				<label><?= A::t('news', 'Email'); ?> <span class="required">*</span></label>
				<input id="news_unsubscribe_email"<?= ($errorField == 'email') ? ' autofocus="autofocus"' : ''; ?> type="text" maxLength="32" value="<?= CHtml::encode($email); ?>" name="email" autocomplete="off"  class="form-control" />
			</div>
		</div>

		<br>
		<div class="row">
			<div class="col-sm-12">
				<button name="submit" type="submit" id="Button1" class="btn v-btn v-btn-default v-small-button"><i class="fa fa-times"></i> <?= A::te('news', 'Unsubscribe'); ?></button>
			</div>
		</div>
	<?php else: ?>
		<div class="row">
			<label for="news_unsubscribe_email"><?= A::t('news', 'Email'); ?>: </label>
			<input id="news_unsubscribe_email"<?= ($errorField == 'email') ? ' autofocus="autofocus"' : ''; ?> type="text" maxLength="128" value="<?= CHtml::encode($email); ?>" name="email" autocomplete="off"  class="large" />
		</div>
		<div class="clear"></div>
		<div class="buttons-wrapper">
			<input value="<?= A::te('news', 'Unsubscribe'); ?>" class="button" type="submit" />
		</div>
	<?php endif; ?>
    
	<?= CHtml::closeForm(); ?>

</div>
