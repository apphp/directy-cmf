<?php

return array(
    // Application data
    'name' => 'ApPHP Directy CMF',
    'version' => '2.6.2',
    
    // Installation settings
    'installationKey' => '<INSTALLATION_KEY>',

    // Password keys settings (for database passwords only - don't change it)
    // md5, sha1, sha256, whirlpool, etc
	'password' => array(
        'encryption' => true,
        'encryptAlgorithm' => 'sha256',
		'encryptSalt' => true,
		'hashKey' => 'apphp_directy_cmf',    
    ),
    
    // Default email settings
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
    
    // Validations
	// Define 'exclude' controllers array, ex.: array('PaymentProviders', 'Checkout')
    'validation' => array(
        'csrf' 		 => array('enable' => true, 'exclude' => array()),
        'bruteforce' => array('enable' => true, 'badLogins' => 5, 'redirectDelay' => 3),
    ),

    // Output compression
	'compression' => array(
		'enable' => true, 
		'method' => 'gzip'
	),

    // Session settings
    'session' => array(
        'customStorage' => false,	/* true value means use a custom storage (database), false - standard storage */
        'cacheLimiter' => '',		/* to prevent 'Web Page expired' message for POST request use "private,must-revalidate" */
        'lifetime' => 24,			/* session timeout in minutes, default: 24 min = 1440 sec */
    ),
    
    // Cookies settings
    'cookies' => array(
        'domain' => '', 
        'path' => '/' 
    ),

    // Cache settings 
    'cache' => array(
        'enable' => false, 
        'lifetime' => 20,  /* in minutes */
        'path' => 'protected/tmp/cache/'
    ),

    // RSS Feed settings 
    'rss' => array(
        'path' => 'feeds/'
    ),

    // Datetime settings
    'defaultTimeZone' => 'UTC',
    
    // Application settings
    'defaultTemplate' => 'default',
	'defaultController' => 'Index',
    'defaultAction' => 'index',
    
    // Application components
    'components' => array(
        'BackendMenu' => array('enable' => true, 'class' => 'BackendMenu'),
        'Bootstrap' => array('enable' => true, 'class' => 'Bootstrap'),
        'FrontendMenu' => array('enable' => true, 'class' => 'FrontendMenu'),               
        'LocalTime' => array('enable' => true, 'class' => 'LocalTime'),
		'SearchForm' => array('enable' => true, 'class' => 'SearchForm'),
        'Website' => array('enable' => true, 'class' => 'Website'),
    ),

	// Widget settings
	'widgets' => array(
		'paramKeysSensitive' => true
	),

    // Application helpers
    'helpers' => array(
        //'helper' => array('enable' => true, 'class' => 'Helper'),
    ),

    // Application modules
    'modules' => array(
        'setup' => array('enable' => true, 'removable' => false, 'backendDefaultUrl' => ''),
    ),

    // Url manager
    'urlManager' => array(
        'urlFormat' => 'shortPath',  /* get | path | shortPath */
        'rules' => array(
			//'paymentProviders/handlePayment/provider/([a-zA-Z0-9\_]+)' => 'paymentProviders/handlePayment/provider/{$0}',
			'paymentProviders/handlePayment/([a-zA-Z0-9\_]+)' => 'paymentProviders/handlePayment/provider/{$0}',
			//'paymentProviders/handlePayment/([a-zA-Z0-9\_]+)/handler/([a-zA-Z0-9\_]+)' => 'paymentProviders/handlePayment/provider/{$0}/handler/{1}',
			'paymentProviders/handlePayment/([a-zA-Z0-9\_]+)/([a-zA-Z0-9\_]+)' => 'paymentProviders/handlePayment/provider/{$0}/handler/{1}',
            //'controller/action/value1/value2' => 'controller/action/param1/value1/param2/value2',
        ),
    ),
    
);