<?php

return array(
    // Module classes
    'classes' => array(
    	'Modules\Users\Components\UsersComponent',
        'Modules\Users\Controllers\Users',
        'Modules\Users\Controllers\UserGroups',
        'Accounts'
    ),
    // Management links
    'managementLinks' => array(
        A::t('users', 'Users') => 'users/index',
        A::t('users', 'User Groups') => 'userGroups/index',
   	)
);
