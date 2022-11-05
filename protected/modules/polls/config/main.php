<?php

return array(
    // Module classes
    'classes' => array(
		'Modules\Polls\Components\PollsComponent',
		'Modules\Polls\Controllers\Polls',
    ),
    // Management links
    'managementLinks' => array(
        A::t('polls', 'Polls') => 'polls/manage'
    ),
    // Option "loginType" of "social" (or "all") is used only once
    'members' => array(
        //'typeName'=>array('name'=>'NameTypeUser', 'loginPage'=>'users/login', 'loginType'=>'all|normal|social'),
		'user'=>array('name'=>A::t('polls', 'User'), 'loginPage'=>'users/login', 'loginType'=>'normal'),
        //'author'=>array('name'=>A::t('polls', 'Author'), 'loginPage'=>'authors/login', 'loginType'=>'normal'),
        'visitor'=>array('name'=>A::t('polls', 'Visitor'), 'loginPage'=>'visitors/login', 'loginType'=>'social'),
    ),
	'memberRole' => 'user',
	// Define login link, ex: authors/login or customers/login
    'loginLink' => 'users/login',
);
