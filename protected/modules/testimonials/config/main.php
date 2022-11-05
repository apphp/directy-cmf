<?php

return array(
    // Module classes
    'classes' => array(
		'Modules\Testimonials\Components\TestimonialsComponent',
		'Modules\Testimonials\Controllers\Testimonials',
    ),
    // Management links
    'managementLinks' => array(
        A::t('testimonials', 'Testimonials') => 'testimonials/manage'
    ),	
);
