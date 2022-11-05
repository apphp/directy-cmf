<?php

return array(
    // Module classes
    'classes' => array(
		'Modules\Webforms\Components\WebformsComponent',
		'Modules\Webforms\Controllers\Webforms',
		'Modules\Webforms\Controllers\WebformsMessages',
    ),
	// Management links
	'managementLinks' => array(
		A::t('webforms', 'Messages') => 'webformsMessages/manage'
	),
);
