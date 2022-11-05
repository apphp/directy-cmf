<?php

return array(
    // Module classes
    'classes' => array(
    	'Modules\Events\Components\EventsComponent',
        'Modules\Events\Controllers\EventsCategories',
        'Modules\Events\Controllers\Events'
    ),

    // Management links
    'managementLinks' => array(
        A::t('events', 'Events Categories') => 'eventsCategories/manage',       
   	)

);
