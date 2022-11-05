<?php
	Website::setMetaTags(array('title'=>A::t('news', 'Subscribe to news')));
	///A::app()->getClientScript()->registerCssFile('assets/modules/news/css/news.css');

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('news', 'Subscribe to news');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('news', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('news', 'Subscribe to news')),
	);
	
	$layout = 'advanced';
?>

<div class="v-heading-v2">
	<h1 class="title"><?= A::t('news', 'Subscribe to news') ?></h1>
</div>

<?php if($layout == 'advanced'): ?>
	<div class="v-comment-form">
<?php else: ?>	
	<div class="subscribe-form-content">
<?php endif; ?>

<?= !empty($actionMessage) ? $actionMessage : '<p class="alert alert-info">'.A::t('news', 'View Message Subscribe').'</p>'; ?>	

<?php
	// Open form
	$formName = 'frmNewsSubscribe';
	echo CHtml::openForm('newsSubscribers/subscribe', 'post', array('id' => 'subscribe-form', 'name'=>$formName, 'autoGenerateId'=>true, 'class'=>'subscribe form-horizontal'));
?>
	<input type="hidden" name="act" value="send" />

	<?php if('no' == $typeFullName && 'no' != $typeFirstName): ?>
		<?php if($layout == 'advanced'): ?>
			<div class="row form-group">
				<div class="col-sm-12">
					<label><?= A::t('news', 'First Name'); ?> <?php if($typeFirstName == 'allow-required'): ?><span class="required">*</span><?php endif; ?></label>
					<input id="news_subscribers_first_name" type="text" maxLength="32" value="<?= CHtml::encode($firstName); ?>" name="first_name" autocomplete="off"  class="form-control" />
				</div>
			</div>
		<?php else: ?>
			<div class="form-group col-sm-12">
				<label for="news_subscribers_first_name" class="col-sm-2 control-label"><?= A::t('news', 'First Name'); ?>
				<?php if($typeFirstName == 'allow-required'): ?><span class="required">*</span><?php endif; ?>: </label>
				<div class="col-sm-10">
					<input id="news_subscribers_first_name" type="text" maxLength="32" value="<?= CHtml::encode($firstName); ?>" name="first_name" autocomplete="off"  class="form-control col-sm-10" />
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if('no' == $typeFullName && 'no' != $typeLastName): ?>
		<?php if($layout == 'advanced'): ?>
			<div class="row form-group">
				<div class="col-sm-12">
					<label><?= A::t('news', 'Last Name'); ?> <?php if($typeLastName == 'allow-required'): ?><span class="required">*</span><?php endif; ?></label>
					<input id="news_subscribers_last_name" type="text" maxLength="32" value="<?= CHtml::encode($lastName); ?>" name="last_name" autocomplete="off"  class="form-control" />
				</div>
			</div>
		<?php else: ?>
			<div class="form-group col-sm-12">
				<label for="news_subscribers_last_name" class="col-sm-2 control-label"><?= A::t('news', 'Last Name'); ?>
				<?php if($typeLastName == 'allow-required'){ ?><span class="required">*</span><?php } ?>: </label>
				<div class="col-sm-10">
					<input id="news_subscribers_last_name" type="text" maxLength="32" value="<?= CHtml::encode($lastName); ?>" name="last_name" autocomplete="off"  class="form-control col-sm-10" />
				</div>
			</div>
		<?php endif; ?>	
	<?php endif; ?>

	<?php if('no' != $typeFullName): ?>
		<?php if($layout == 'advanced'): ?>
			<div class="row form-group">
				<div class="col-sm-12">
					<label><?= A::t('news', 'Full Name'); ?> <?php if($typeLastName == 'allow-required'): ?><span class="required">*</span><?php endif; ?></label>
					<input id="news_subscribers_full_name" type="text" maxLength="32" value="<?= CHtml::encode($fullName); ?>" name="full_name" autocomplete="off"  class="form-control" />
				</div>
			</div>
		<?php else: ?>
			<div class="form-group col-sm-12">
				<label for="news_subscribers_full_name" class="col-sm-2 control-label"><?= A::t('news', 'Full Name'); ?>
				<?php if($typeLastName == 'allow-required'){ ?><span class="required">*</span><?php } ?>: </label>
				<div class="col-sm-10">
					<input id="news_subscribers_full_name" type="text" maxLength="64" value="<?= CHtml::encode($fullName); ?>" name="full_name" autocomplete="off"  class="form-control col-sm-10" />
				</div>
			</div>
		<?php endif; ?>	
	<?php endif; ?>

	<?php if($layout == 'advanced'): ?>
		<div class="row form-group">
			<div class="col-sm-12">
				<label><?= A::t('news', 'Email'); ?> <span class="required">*</span></label>
				<input id="news_subscribe_email" type="text" maxLength="32" value="<?= CHtml::encode($email); ?>" name="email" autocomplete="off"  class="form-control" />
			</div>
		</div>
	<?php else: ?>
		<div class="form-group col-sm-12">
			<label for="news_subscribe_email" class="col-sm-2 control-label"><?= A::t('news', 'Email'); ?> <span class="required">*</span> : </label>
			<div class="col-sm-10">
				<input id="news_subscribe_email" type="text" maxLength="128" value="<?= CHtml::encode($email); ?>" name="email" autocomplete="off"  class="form-control col-sm-10" />
			</div>
		</div>
	<?php endif; ?>	

	<?php if($layout == 'advanced'): ?>
		<br>
		<div class="row">
			<div class="col-sm-12">
				<button name="submit" type="submit" id="Button1" class="btn v-btn v-btn-default v-small-button"><i class="fa fa-envelope"></i> <?= A::te('news', 'Subscribe'); ?></button>
				<input onclick="window.location='newsSubscribers/unsubscribe'" class="btn v-btn v-third-dark v-small-button" value="<?= A::te('news', 'Unsubscribe'); ?>" type="button" name="ap0">
			</div>
		</div>
	<?php else: ?>
		<div class="buttons-wrapper bw-bottom col-sm-offset-2">
			<input value="<?= A::te('news', 'Subscribe'); ?>" class="button" type="submit" style="margin-left:10px;" />
			<input onclick="window.location='newsSubscribers/unsubscribe'" class="button" value="<?= A::te('news', 'Unsubscribe'); ?>" type="button" name="ap0">
		</div>
	<?php endif; ?>
	
<?= CHtml::closeForm(); ?>
</div>

<?php
	if(!empty($errorField)){
		A::app()->getClientScript()->registerScript($formName, 'document.forms["'.$formName.'"].'.$errorField.'.focus();', 2);
	}
?>
