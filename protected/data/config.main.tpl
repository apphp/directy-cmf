<?php

return array(
    // application data
    'name' => 'ApPHP Directy CMF',
    'version' => '1.1.2',
    
    // installation settings
    'installationKey' => '<INSTALLATION_KEY>',

    // password keys settings (for database passwords only - don't change it)
    // md5, sha1, sha256, whirlpool, etc
	'password' => array(
        'encryption' => true,
        'encryptAlgorithm' => 'sha256', 
        'hashKey' => 'apphp_directy_cmf',    
    ),
    
    // default email settings
	'email' => array(
        'mailer' => 'smtpMailer', /* phpMail | phpMailer | smtpMailer */
        'from' => 'info@email.me',
        'fromName' => '', /* John Smith */
        'isHtml' => true,
        'smtp' => array(
            'auth' => true, /* true or false */
            'secure' => 'ssl', /* 'ssl', 'tls' or '' */
            'host' => 'smtp.gmail.com',
            'port' => '465',
            'username' => '',
            'password' => '',
        ),
    ),
    
    // validation
   	'validation' => array(
        'csrf' => true,
        'bruteforce' => array('enable' => true, 'badLogins' => 5, 'redirectDelay' => 3),
    ),

    // session settings
    'session' => array(
        'customStorage' => false, /* true value means to use a custom storage */
        'cacheLimiter' => '' /* private,must-revalidate */
    ),
    
    // cookies settings
    'cookies' => array(
        'domain' => '', 
        'path' => '/' 
    ),

    // cache settings 
    'cache' => array(
        'enable' => false, 
        'lifetime' => 20,  /* in minutes */
        'path' => 'protected/tmp/cache/'
    ),

    // datetime settings
    'defaultTimeZone' => 'UTC',
    
    // application settings
    'defaultTemplate' => 'default',
	'defaultController' => 'Index',
    'defaultAction' => 'index',
    
    // application components
    'components' => array(
        'BackendMenu' => array('enable' => true, 'class' => 'BackendMenu'),
        'Bootstrap' => array('enable' => true, 'class' => 'Bootstrap'),
        'FrontendMenu' => array('enable' => true, 'class' => 'FrontendMenu'),               
        'LocalTime' => array('enable' => true, 'class' => 'LocalTime'),               
        'Website' => array('enable' => true, 'class' => 'Website'),        
    ),

    // application modules
    'modules' => array(
        'setup' => array('enable' => true),
    ),

    // url manager
    'urlManager' => array(
        'urlFormat' => 'shortPath',  /* get | path | shortPath */
        'rules' => array(
            //'controller/action/value1/value2' => 'controller/action/param1/value1/param2/value2',
        ),
    ),
    
);