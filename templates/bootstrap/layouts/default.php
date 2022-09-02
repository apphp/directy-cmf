<div class="row">
	<!-- Blog Post Content Column -->
	<div class="col-lg-8" style="padding-right:20px;">
		<?= A::app()->view->getContent(); ?>
	</div>
	
	<!-- Blog Sidebar Widgets Column -->
	<div class="col-md-4">	
		<div class="well">
			<!--<h4>Blog Search</h4>-->
			<div class="search-group input-group">	
				<?= SearchForm::draw(
						'',
						array(
							'input-class'=>'form-control',
							'button-html'=>'<span class="input-group-btn"><button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button></span>'
						)
					);
				?>
			</div>
			<!-- /.input-group -->
		</div>
		
		<?= FrontendMenu::draw('right', $this->_activeMenu); ?>
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

