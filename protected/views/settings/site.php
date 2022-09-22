<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Site Info')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Site Info'))
    );
?>
    
<h1><?= A::t('app', 'Site Info'); ?></h1>

<div class="bloc">
	<?= $tabs; ?>

	<div class="content">
	<?= $actionMessage; ?>
	
	<?php
		echo CHtml::openForm('settings/site', 'post', array());
		echo CHtml::hiddenField('act', 'send', array());	
	?>	
	<fieldset>
		<legend><?= A::t('app', 'Domain Info'); ?>:</legend>
		<ul>
			<li>
				<strong><?= A::t('app', 'Site Domain'); ?>:</strong>
				<br>
				<?php
					if(CAuth::isLoggedInAs('owner')):
						echo CHtml::textField('website_domain', $settings->website_domain, array('class'=>'domain-name middle'.($settings->website_domain != '' ? ' hide' : ''), 'readonly'=>false, 'placeholder'=>A::t('app', 'Enter your domain here, ex.: domain.com'), 'maxlength'=>'255', 'autocomplete'=>'off'));
						if($settings->website_domain != ''):
							echo '<label class="lbl-domain-name">'.$settings->website_domain.'</label>';
							echo '[ <a href="javascript:void(0);" class="settings-link" data-edit="'.A::t('app', 'Edit').'" data-cancel="'.A::t('app', 'Cancel').'">'.A::t('app', 'Edit').'</a> ]';
						endif;
					else:
						echo $settings->website_domain;
					endif;
				?>
			</li>
		</ul>		
	</fieldset>
	<fieldset>
		<legend><?= A::t('app', 'Website Traffic Rank'); ?>:</legend>
		<ul>
			<!--<li><b><?php //echo A::t('app', 'Google Rank'); ?>:</b> <i><?php //echo ($settings->google_rank == '' ? A::t('app', 'Unknown') : $settings->google_rank); ?></i></li>-->
			<li><strong><?= A::t('app', 'Alexa Rank'); ?>:</strong> <?= ($settings->alexa_rank == '' || $settings->alexa_rank == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->alexa_rank.'</i> &nbsp;<a href="http://www.alexa.com/siteinfo/'.$settings->website_domain.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'); ?></li>
		</ul>		
	</fieldset>
	<fieldset>
		<legend><?= A::t('app', 'Website Indexed Pages'); ?>:</legend>
		<ul>
			<li><strong><?= A::t('app', 'Google'); ?>:</strong> <?= ($settings->indexed_pages_google == '' || $settings->indexed_pages_google == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->indexed_pages_google.'</i>').' &nbsp;<a href="https://www.google.com/search?filter=0&q=site:'.$settings->website_domain.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'; ?></li>
			<li><strong><?= A::t('app', 'Bing'); ?>:</strong> <?= ($settings->indexed_pages_bing == '' || $settings->indexed_pages_bing == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->indexed_pages_bing.'</i>').' &nbsp;<a href="http://www.bing.com/search?scope=web&setmkt=en-US&setlang=match&FORM=W5WA&q=site:'.$settings->website_domain.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'; ?></li>
			<li><strong><?= A::t('app', 'Yahoo'); ?>:</strong> <?= ($settings->indexed_pages_yahoo == '' || $settings->indexed_pages_yahoo == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->indexed_pages_yahoo.'</i>').' &nbsp;<a href="https://search.yahoo.com/search?fr=sfp&p=site:'.$settings->website_domain.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'; ?></li>
			<li><strong><?= A::t('app', 'Yandex'); ?> (<?= A::t('i18n', 'countries.ru'); ?>):</strong> <?= ($settings->indexed_pages_yandex == '' || $settings->indexed_pages_yandex == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->indexed_pages_yandex.'</i>').' &nbsp;<a href="http://yandex.ru/yandsearch?text=site:'.$settings->website_domain.'&lr=10418'.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'; ?></li>
			<li><strong><?= A::t('app', 'Baidu'); ?> (<?= A::t('i18n', 'countries.cn'); ?>):</strong> <?= ($settings->indexed_pages_baidu == '' || $settings->indexed_pages_baidu == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->indexed_pages_baidu.'</i>').' &nbsp;<a href="http://www.baidu.com/s?ie=utf-8&wd=site:'.$settings->website_domain.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'; ?></li>
			<li><strong><?= A::t('app', 'Goo'); ?> (<?= A::t('i18n', 'countries.jp'); ?>):</strong> <?= ($settings->indexed_pages_goo == '' || $settings->indexed_pages_goo == '0' ? '<i>'.A::t('app', 'Unknown').'</i>' : '<i>'.$settings->indexed_pages_goo.'</i>').' &nbsp;<a href="http://search.goo.ne.jp/web.jsp?OE=UTF-8&mode=0&IE=UTF-8&MT=site:'.$settings->website_domain.'" target="_blank"><img src="templates/backend/images/external_link.gif" title="'.A::te('app', 'Click to see').'"></a>'; ?></li>
		</ul>		
	</fieldset>
	<fieldset>
		<?= A::t('app', 'Last Time Updated').': '.(!CTime::isEmptyDateTime($settings->site_last_updated) ? CLocale::date($dateTimeFormat, $settings->site_last_updated) : A::t('app', 'never')); ?>
	</fieldset>
	<?php
		if(CAuth::isLoggedInAs('owner')):
			echo '<div class="buttons-wrapper bw-bottom">';
			echo CHtml::submitButton(A::t('app', 'Update'), array('buttonTag'=>'input', 'disabled'=>!Admins::hasPrivilege('site_settings', 'edit')));
			echo '</div>';
			echo CHtml::closeForm();
		endif;
	?>
	</div>
</div>   

<?php if(CAuth::isLoggedInAs('owner') && $settings->website_domain != ''): ?>
<style>
	.lbl-domain-name { width:auto; margin-right:10px }
	.settings-link { margin-top:5px !important; display:inline-block; }
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('a.settings-link').click(function () {
			var $textField = $('.domain-name'),
				domainVal = $textField.val() != '' ? $textField.val() : '- empty -';
			if(!$textField.hasClass('editable')){
				$(this).html($(this).data('cancel'));
				$textField.show().focus();
				$('.lbl-domain-name').remove();
			}else{
				$(this).html($(this).data('edit'));
				$textField.after('<label class="lbl-domain-name">'+domainVal+'</label>');
				$textField.hide();
			}
			$textField.toggleClass('editable')
		});
	});
</script>
<?php endif; ?>