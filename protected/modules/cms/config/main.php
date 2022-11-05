<?php

return array(
    // Module classes
    'classes' => array(
		'Modules\Cms\Components\CmsComponent',
		'Modules\Cms\Controllers\Pages',
    ),
    // Management links
    'managementLinks' => array(
        A::t('cms', 'Pages') => 'pages/index'
    ),
    
);
