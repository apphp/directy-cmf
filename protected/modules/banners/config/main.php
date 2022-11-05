<?php

return array(
    // Module classes
    'classes' => array(
		'Modules\Banners\Components\BannersComponent',
		'Modules\Banners\Controllers\Banners',
    ),

    // Management links
    'managementLinks' => array(
        A::t('banners', 'Banners Management') => 'banners/manage',
    ),    
);
