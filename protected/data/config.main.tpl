<?php

return array(
    // application data
    'name' => 'ApPHP Directy CMF',
    'version' => '1.0.1',
    
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
        'from'   => 'info@email.me',
        'isHtml' => true,
        'smtp'   => array(
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
        'cacheLimiter' => '' /* private,must-revalidate */
    ),
    
    // cookies settings
    'cookies' => array(
        'domain' => '', 
        'path' => '/' 
    ),

    // datetime settings
    'defaultTimeZone' => 'UTC',
    
    // application settings
    'defaultTemplate' => 'default',
	'defaultController' => 'Index',
    'defaultAction' => 'index',
    
    // application components
    'components' => array(
        'AdminMenu' => array('enable' => true, 'class' => 'AdminMenu'),
        'SiteMenu' 	=> array('enable' => true, 'class' => 'SiteMenu'),               
        'Bootstrap' => array('enable' => true, 'class' => 'Bootstrap'),               
        'SiteSettings' => array('enable' => true, 'class' => 'SiteSettings'),        
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