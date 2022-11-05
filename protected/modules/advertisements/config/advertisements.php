<?php

return array(
    // Module components
    'components' => array(
        'AdvertisementsComponent' => array('enable' => true, 'class' => 'AdvertisementsComponent'),
    ),

    // url manager (optional)
    //'urlManager' => array(
    //    'rules' => array(
    //        'page/([0-9]+)/(.*?)' => 'page/view/id/{$0}',
    //    ),
    //),    

    // Default settings (optional, if defined - will be used as application default settings)
    //'defaultController' => 'controller',
    //'defaultAction' => 'action',
    'backendDefaultUrl' => 'advertisements/manage'
);
