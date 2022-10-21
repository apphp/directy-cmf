<!--Header-->
<div class="header-container">
	<?php
	    if(CAuth::isLoggedInAsAdmin()){
	        echo CHtml::link(A::t('app', 'Back to Admin Panel'), Website::getBackendPath().'dashboard', array('class'=>'back-to'));
	    }
    ?>
	
	<?php include('header-top.php'); ?>

	<header class="header fixed clearfix">

		<div class="container">

			<!--Site Logo-->
			<div class="logo">
				<!--<img src="templates/default/images/logo.png" alt="logo" />-->
				<!--<span id="slogan"><?//= $this->siteSlogan; ?></span>-->				
				<a id="logo" href="<?= $this->defaultPage; ?>" title="<?= $this->siteTitle; ?>">
					<h2><?= $this->siteTitle; ?></h2>
					<small><?= $this->siteSlogan; ?></small>
				</a>
			</div>
			<!--End Site Logo-->
			
			<div class="navbar-collapse nav-main-collapse collapse">

				<!--Header Search-->
				<div class="search" id="headerSearch">
					<a href="javascript:void(0)" id="headerSearchOpen"><i class="fa fa-search"></i></a>
					<div class="search-input">
						<?php
							echo SearchForm::draw(array(
								'innerWrapper'	=> true,
								'inputClass'	=> 'form-control search',
								'inputId'	    => 'search-keywords',
								'categoryId'    => 'search-category-id',
								'buttonHtml'	=> '<span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button></span>',
							));
						?>
			
						<span class="v-arrow-wrap"><span class="v-arrow-inner"></span></span>
					</div>
				</div>
				<!--End Header Search-->
				
				<!--Main Menu-->
				<nav class="nav-main mega-menu">
					<?php
						echo FrontendMenu::draw('top', $this->_activeMenu, array(
							'menuId'			=> 'mainMenu',
							'menuClass'			=> 'nav nav-pills nav-main',
							'dropdownItemClass'	=> 'dropdown',
							'dropdownItemLinkClass'	=> 'dropdown-toggle',
							'subMenuClass'		=> 'dropdown-menu',
						));
					?>
				</nav>
				<!--End Main Menu-->
			</div>
			<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
				<i class="fa fa-bars"></i>
			</button>
		</div>

		<span class="v-header-shadow"></span>
	</header>
</div>
<!--End Header-->
