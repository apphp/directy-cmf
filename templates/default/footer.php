<!--Footer-Wrap-->
<div class="footer-wrap">
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<section class="widget">
						<?= FrontendMenu::draw('bottom', $this->_activeMenu); ?>
						
						<img alt="bootsbox" src="templates/default/images/logo-dark.png" style="height: 40px; margin-bottom: 20px;">
						<p class="pull-bottom-small">
							Donec quam felis, ultricies nec, pellen tesqueeu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel aliquet nec, vulputate eget aliquet nec, arcu.
						</p>
						<p>
							<a href="<?= Website::getDefaultPage(); ?>"><?= A::t('app', 'Read More'); ?> →</a>
						</p>

						<?php
							if(Modules::model()->isInstalled('news')): 
								NewsComponent::drawSubscriptionBlock();
							endif;
						?>
					</section>
				</div>
				
				<?php if(Modules::model()->isInstalled('news')): ?>
				<div class="col-sm-3">					
					<section class="widget v-news-widget">
						<div class="widget-heading">
							<h4><?= A::t('news', 'Latest News'); ?></h4>
							<div class="horizontal-break"></div>
						</div>
						<?= NewsComponent::drawLatestNewsBlock(array('title'=>'', 'showThumb'=>false, 'newsCount'=>3)); ?>
					</section>
				</div>
				<?php endif; ?>
				
				<?php if(Modules::model()->isInstalled('blog')): ?>	
				<div class="col-sm-3">
					<section class="widget v-recent-entry-widget">
						<div class="widget-heading">
							<h4><?= A::t('blog', 'Recent Posts'); ?></h4>
							<div class="horizontal-break"></div>
						</div>
						<?= BlogComponent::drawLastPostsBlock(); ?>
					</section>
				</div>
				<?php endif; ?>
				
				<?php if(Modules::model()->isInstalled('gallery')): ?>	
				<div class="col-sm-3">
					<section class="widget">
						<div class="widget-heading">
							<h4>Recent Works</h4>
							<div class="horizontal-break"></div>
						</div>
						<?= GalleryComponent::drawRecentImages(); ?>
					</section>
				</div>
				<?php endif; ?>
				
			</div>
		</div>
	</footer>

	<div class="copyright">
		<div class="container">
			<p><?= $this->siteFooter; ?></p>			
			
			<?php if(APPHP_MODE == 'debug' || APPHP_MODE == 'demo'): ?>
				&nbsp;&#124;&nbsp; <a href="backend/login"><?= (CAuth::isGuest() ? A::t('app', 'Admin Login') : ''); ?></a>
			<?php endif; ?>
	
			<?php
				$socialNetworks = SocialNetworks::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'), array(), 'social-networks');
				if(!empty($socialNetworks)):
					echo '<ul class="social-icons std-menu pull-right">';
					foreach($socialNetworks as $key => $socialNetwork):
						echo '<li><a href="'.$socialNetwork['link'].'" target="_blank" data-placement="top" rel="tooltip noopener noreferrer" title="" data-original-title="'.$socialNetwork['name'].'"><i class="fa fa-'.$socialNetwork['code'].'"></i></a></li>';
					endforeach;
					echo '</ul>';
				endif;
			?>			

		</div>
	</div>
</div>
<!--End Footer-Wrap-->

