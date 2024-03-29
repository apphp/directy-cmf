<?php
/**
 * Public web application cron file
 *
 * @project ApPHP Directy CMF
 * @author ApPHP <info@apphp.com>
 * @link https://www.apphp.com/php-directy-cmf/
 * @copyright Copyright (c) 2013 - 2019 ApPHP Directy CMF
 * @license https://www.apphp.com/php-directy-cmf/
 */

// Change the following paths if necessary
defined('APPHP_PATH') || define('APPHP_PATH', dirname(__FILE__));
// Directory separator
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
// Modes: production | debug | demo | test | hidden
defined('APPHP_MODE') or define('APPHP_MODE', 'hidden');

$apphp = dirname(__FILE__) . '/framework/Apphp.php';
$config = APPHP_PATH . '/protected/config/';

if (is_file($apphp)) {
	require_once($apphp);
	A::init($config)->run();
} else {
	echo 'Fatal error: ApPHP MVC Framework not found under "framework/" directory!';
}

// Check cronjob type
$settings = Bootstrap::init()->getSettings();
if ($settings->cron_type == 'batch') {
	// Un-comment if 'non-batch' cron job type is used
	$cron = new Cron();
	$cron->run();
} else {
	echo 'Error: running cron is not allowed!';
}