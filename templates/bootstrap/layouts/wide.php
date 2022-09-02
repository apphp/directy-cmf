<div class="row">
	<!-- Blog Post Content Column -->
	<div class="col-lg-12" style="padding-right:20px;">
		<?= A::app()->view->getContent(); ?>
	</div>	
</div>
<!-- /.row -->

<hr>

<!-- Footer -->
<footer>
	<div class="row">
		<div class="col-lg-12">
			<div class="left-side">
				<?= $this->siteFooter; ?>
			</div>
			<div class="central-part">
				<?= FrontendMenu::draw('bottom', $this->activeMenu); ?>
			</div>            
			<div class="right-side">
				<a href="backend/login"><?= (CAuth::isGuest() ? A::t('app', 'Admin Login') : ''); ?></a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</footer>

