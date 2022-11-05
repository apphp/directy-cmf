<?php

return array(
    // Module classes
    'classes' => array(
        'Modules\Blog\Components\BlogComponent',
        'Modules\Blog\Controllers\Posts',
    ),
    // Management links
    'managementLinks' => array(
        A::t('blog', 'Blog') => 'Posts/manage'
    ),    
);
