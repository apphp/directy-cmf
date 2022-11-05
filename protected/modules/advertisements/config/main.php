<?php

return array(
    // Module classes
    'classes' => array(
        'Modules\Advertisements\Components\AdvertisementsComponent',
        'Modules\Advertisements\Controllers\Advertisements',
    ),
    // Management links
    'managementLinks' => array(
        A::t('advertisements', 'Advertisements') => 'advertisements/manage'
    ),
);
