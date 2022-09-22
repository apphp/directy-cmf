<!--Header Top-->
<header class="header-top">
	<div class="container">
		<div class="header-top-info">
			<ul>
				<? if($this->sitePhone != ''): ?>
					<li><i class="fa fa-phone"></i><span><?= A::t('app', 'Call us'); ?>:</span> <a href="tel:<?= preg_replace('/[^0-9]/i', '', $this->sitePhone); ?>"><?= $this->sitePhone; ?></a> </li>
				<? endif; ?>
				<? if($this->siteEmail != ''): ?>
					<li><a href="mailto:<?= $this->siteEmail; ?>"><i class="fa fa-envelope-o"></i><?= $this->siteEmail; ?></a> </li>
				<? endif; ?>
			</ul>
		</div>

		<?php
			$socialNetworks = SocialNetworks::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'), array(), 'social-networks');
			if(!empty($socialNetworks)):
				echo '<ul class="social-icons standard">';
				foreach($socialNetworks as $key => $socialNetwork):
					echo '<li class="'.str_replace('-', '', $socialNetwork['code']).'"><a href="'.$socialNetwork['link'].'" target="_blank"><i class="fa fa-'.$socialNetwork['code'].'"></i></a></li>';
				endforeach;
				echo '</ul>';
			endif;
		?>

		<nav class="header-top-menu std-menu">

			<!--Header Search-->
			<div id="headerSearchsMobile">
				<a href="#" id="headerSearchOpenMobile"><i class="fa fa-search"></i></a>
				<div class="search-input">
					<span class="v-arrow-wrap"><span class="v-arrow-inner"></span></span>
					<?
						echo SearchForm::draw(array(
							'innerWrapper'	=> true,
							'inputClass'	=> 'form-control search',
							//'placeHolder'	=> ''
							'buttonHtml'	=> '<span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button></span>',
						));
					?>			
				</div>
			</div>
			<!--End Header Search-->

			<ul class="menu nav-pills nav-main">
				<?php
					// Show links if module is installed
					if(Modules::model()->isInstalled('users')):
						echo '<li class="m-item"><a href="users/login">'.A::t('app', 'Login').'</a></li>';
						echo '<li class="m-item"><a href="users/registration">'.A::t('app', 'Registration').'</a></li>';
					endif;				
				?>
				<li class="dropdown m-item">
					<a class="dropdown-toggle" href="javascript:void(0);">
						<?= CFile::fileExists('images/flags/'.A::app()->getLanguage('icon')) ? '<img src="images/flags/'.A::app()->getLanguage('icon').'" alt="'.CHtml::encode(A::app()->getLanguage()).'" /> &nbsp;' : '' ; ?>
						<?= A::app()->getLanguage('name_native'); ?>
						<?//= A::t('app', 'Language'); ?>
						<?php
							$countLanguages = Languages::model()->count(array('condition'=>"is_active = 1 AND used_on IN ('front-end', 'global')", 'orderBy'=>'sort_order ASC'));
							if($countLanguages > 1):
								echo '<i class="fa fa-caret-down"></i>';
							endif;
						?>						
					</a>
					<?= Languages::drawSelector(array('display'=>'list', 'class'=>'dropdown-menu language-selector')); ?>
				</li>
				
				<? if(FALSE): ?>
				<!--<li class="dropdown m-item">
					<a class="dropdown-toggle" href="javascript:void(0);">
						<?//= A::app()->getCurrency('symbol'); ?>
						<?//= A::app()->getCurrency('name'); ?>
						<?//= A::t('app', 'Currency'); ?>
						<i class="fa fa-caret-down"></i>
					</a>
					<?//= Currencies::drawSelector(array('display'=>'list', 'class'=>'dropdown-menu currency-selector')); ?>
				</li>-->
				<? endif; ?>
			</ul>
		</nav>
	</div>
</header>

