<!-- Set slider options -->
<div class="home-slider-wrap fullwidthbanner-container">
	
	<?php
		if(Modules::model()->isInstalled('banners') && Website::isDefaultPage()):
			echo BannersComponent::drawSlider(array(
				'type' 			=> 'revolution',
				'width' 		=> '1170',
				'height' 		=> '500',
				'navigation' 	=> 'true',
				//'delay'			=> 7000
			));		
		endif;
	?>
	
	<div class="shadow-right"></div>
</div>

