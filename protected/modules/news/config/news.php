<?php

return array(
    // Module components
    'components' => array(
        'NewsComponent'       => array('enable' => true, 'class' => 'NewsComponent'),
    ),
	
	// Default Backend url (optional, if defined - will be used as application default settings)
	'backendDefaultUrl' => 'news/manage',
	
    // URL manager
    'urlManager' => array(
        'rules' => array(
            'news/view/id/([0-9]+)' => 'news/view/id/{$0}',
            'news/view/id/([0-9]+)/(.*?)' => 'news/view/id/{$0}',
            'news/view/([0-9]+)' => 'news/view/id/{$0}',
            'news/view/([0-9]+)/(.*?)' => 'news/view/id/{$0}',
            'news/([0-9]+)' => 'news/view/id/{$0}',
            'news/([0-9]+)/(.*?)' => 'news/view/id/{$0}',
        ),
    ),    
);
