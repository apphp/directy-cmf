<?php

return array(
    // Module components
    'components' => array(
        'PollsComponent' => array('enable' => true, 'class' => 'PollsComponent'),
    ),

    // url manager (optional)
    'urlManager' => array(
        'rules' => array(
            // Widget
            'polls/widget/([0-9]+)[\/]?$' => 'polls/widget/id/{$0}',
    //        'page/([0-9]+)/(.*?)' => 'page/view/id/{$0}',
        ),
    ),

    // Default settings (optional, if defined - will be used as application default settings)
    //'defaultController' => 'controller',
    //'defaultAction' => 'action',
	
	// Default Backend url (optional, if defined - will be used as application default settings)
	'backendDefaultUrl' => 'polls/manage'	
);
