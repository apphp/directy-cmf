<?php ///A::app()->getClientScript()->registerCssFile('assets/modules/news/css/news.css'); ?>
<div class='side-panel-block'>
    <h4 class='title'><?= A::t('news', 'Subscription') ?></h4>
<?php
	$formName = 'frmNewsSubscribeBlock';
	$fieldCount = 0;
	echo CHtml::openForm('newsSubscribers/subscribe', 'post', array('name' => $formName, 'id' => 'subscription-side-form', 'autGenerateId' => true));
?>
	<input id="frmNewsSubscribeBlock_APPHP_FORM_ACT" type="hidden" value="send" name="APPHP_FORM_ACT" />
	<input id="frmNewsSubscribeBlock_email_send" type="hidden" value="send" name="act" />

<?php
	if($typeFullName == 'no'):
		if('allow-required' == $typeFirstName):
			$fieldCount++;
		endif;
		if($typeLastName == 'allow-required'):
			$fieldCount++;
		endif;
	elseif($typeFullName == 'allow-required'):
		$fieldCount++;
	endif;
?>

<?php if(!$fieldCount): ?>
	<div class="input-group">
		<input class="form-control" maxLength="128" placeholder="<?= A::t('news', 'Email Address');?>" id="news_subscribers_email" type="text" required value="" name="email" />
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit" title="<?= A::t('news', 'Go!');?>"><i class="fa fa-envelope"></i></button>
		</span>
	</div>
<?php else: 
	$fieldCount = 0;
	if($typeFullName == 'no'):
		if('allow-required' == $typeFirstName):
	?>
		<div class="input-group" id="frmNewsSubscribeBlock_row_<?= $fieldCount++?>">
			<input maxLength="32" placeholder="<?= A::t('news', 'First Name'); ?>" id="news_subscribers_first_name" type="text" value="" name="first_name" />
		</div>
	<?php
		endif;
		if($typeLastName == 'allow-required'):
	?>
		<div class="input-group" id="frmNewsSubscribeBlock_row_<?= $fieldCount++?>">
			<input maxLength="32" placeholder="<?= A::t('news', 'Last Name'); ?>" id="news_subscribers_last_name" type="text" value="" name="last_name" />
		</div>
	<?php
		endif;
	elseif($typeFullName == 'allow-required'):
	?>
		<div class="input-group" id="frmNewsSubscribeBlock_row_<?= $fieldCount++?>">
			<input maxLength="64" placeholder="<?= A::t('news', 'Full Name'); ?>" id="news_subscribers_full_name" type="text" value="" name="full_name" />
		</div>
	<?php endif; ?>

	<div class="buttons-wrapper bw-bottom">
		<input name="" value="<?= A::t('news', 'Subscribe'); ?>" type="submit" />
    </div>
<?php endif; ?>
<?= CHtml::closeForm(); ?>
</div>
