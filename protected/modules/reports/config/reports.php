<?php

return array(
    // Module components
    'components' => array(
        'ReportsProjectsComponent' => array('enable' => true, 'class' => 'ReportsProjectsComponent'),
    ),

    // Url manager (optional)	
    //'urlManager' => array(
    //    'rules' => array(
    //        'page/([0-9]+)/(.*?)' => 'page/view/id/{$0}',
    //    ),
    //),    

    // Default settings (optional, if defined - will be used as application default settings)
    //'defaultController' => 'controller',
    //'defaultAction' => 'action',

	// Default Backend url (optional, if defined - will be used as application default settings)
	'backendDefaultUrl' => 'reportsProjects/manage'
);
