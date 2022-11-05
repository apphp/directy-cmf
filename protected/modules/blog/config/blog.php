<?php

return array(
    // Module components
    'components' => array(
        'BlogComponent' => array('enable' => true, 'class' => 'BlogComponent'),               
    ),

    // URL manager
    'urlManager' => array(
        'rules' => array(
            'posts/view/id/([0-9]+)' => 'posts/view/id/{$0}',
            'posts/view/id/([0-9]+)/(.*?)' => 'posts/view/id/{$0}',
            'posts/view/([0-9]+)' => 'posts/view/id/{$0}',
            'posts/view/([0-9]+)/(.*?)' => 'posts/view/id/{$0}',
            'posts/([0-9]+)' => 'posts/view/id/{$0}',
            'posts/([0-9]+)/(.*?)' => 'posts/view/id/{$0}',
        ),
    ),    

	// Default Backend url (optional, if defined - will be used as application default settings)
	'backendDefaultUrl' => 'posts/manage'
);
