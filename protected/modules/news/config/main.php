<?php
return array(
    // Module classes
    'classes' => array(
		'Modules\News\Components\NewsComponent',
		'Modules\News\Controllers\News',
		'Modules\News\Controllers\NewsSubscribers',
    ),
    // Management links
    'managementLinks' => array(
        A::t('news', 'News')       => 'news/manage',
        A::t('news', 'Subscribers') => 'newsSubscribers/manage'
    ),    
);
