
ALTER TABLE  `<DB_PREFIX>settings` CHANGE  `ssl_mode`  `ssl_mode` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  '0 - no, 1 - entire site, 2 - admin area, 3 - user area, 4 - payment modules'; 
ALTER TABLE  `<DB_PREFIX>settings` ADD  `smtp_auth` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `mailer`;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `general_email_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '' AFTER  `general_email`;
ALTER TABLE  `<DB_PREFIX>settings` CHANGE  `caching_allowed`  `cache_allowed` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `<DB_PREFIX>settings` DROP  `cache_allowed`, DROP  `cache_lifetime`;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `dashboard_hotkeys` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `offline_message`;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `dashboard_notifications` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `dashboard_hotkeys`;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `dashboard_statistics` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `dashboard_notifications`;


UPDATE  `<DB_PREFIX>privileges` SET  `description` =  'Edit, manage, install, update and uninstall modules' WHERE `category` = 'modules' AND `code` = 'edit';
ALTER TABLE  `<DB_PREFIX>privileges` ADD  `module_code` VARCHAR( 20 ) NOT NULL DEFAULT  '' AFTER  `id` ;


ALTER TABLE  `<DB_PREFIX>states` ADD  `code` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `country_code`;
ALTER TABLE  `<DB_PREFIX>states` ADD  `sort_order` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';


ALTER TABLE  `<DB_PREFIX>modules` ADD  `show_in_menu` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `show_on_dashboard`;
ALTER TABLE  `<DB_PREFIX>modules` DROP  `tables`;


ALTER TABLE  `<DB_PREFIX>module_settings` CHANGE  `property_value`  `property_value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '';
ALTER TABLE  `<DB_PREFIX>module_settings` ADD UNIQUE  `code_key_value` (  `module_code` ,  `property_key` ,  `property_value` );
ALTER TABLE  `<DB_PREFIX>module_settings` CHANGE  `property_group`  `property_group` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '';


DROP TABLE IF EXISTS `<DB_PREFIX>email_templates`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `module_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>email_template_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>email_template_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `template_name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `template_subject` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `template_content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>accounts`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT 'defined for each module separately',
  `username` varchar(25) CHARACTER SET latin1 NOT NULL,
  `password` varchar(64) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `created_at` datetime NULL DEFAULT NULL,
  `created_ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `last_visited_at` datetime NULL DEFAULT NULL,
  `last_visited_ip` varchar(15) CHARACTER SET latin1 NOT NULL DEFAULT '000.000.000.000',
  `notifications` tinyint(1) NOT NULL DEFAULT '0',
  `notifications_changed_at` datetime NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - registration pending, 1 - active account',
  `is_removed` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'for logical removal',
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `registration_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE  `admins` CHANGE  `lastvisited_at`  `last_visited_at` datetime NULL DEFAULT NULL;


DROP TABLE IF EXISTS `<DB_PREFIX>sessions`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>sessions` (
  `session_id` varchar(32) NOT NULL,
  `expires_at` int(11) NOT NULL,
  `session_data` text NOT NULL,
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


---------------------------------
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES
(NULL, 1, 'emailTemplates/', '', '', 1, 1, 7);

INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES
(NULL, 11, 'en', 'Email Templates');

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, '', 'email_templates', 'view', 'View Email Templates', 'View Email Templates of the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, '', 'email_templates', 'edit', 'Edit Email Templates', 'Edit Email Templates of the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


DROP TABLE IF EXISTS `<DB_PREFIX>rss_channels`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>rss_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_code` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `channel_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_items` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `items_count` tinyint(1) NOT NULL DEFAULT '10',
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>search_categories`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>search_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `category_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `callback_class` varchar(80) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `callback_method` varchar(125) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `items_count` tinyint(1) NOT NULL DEFAULT '20',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_code` (`category_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>search_words`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>search_words` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `word_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `word_count` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `word_text` (`word_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>ban_lists`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>ban_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_type` enum('ip','email','username') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ip',
  `item_value` varchar(100) CHARACTER SET latin1 NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `started_at` datetime NULL DEFAULT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ban_item_type` (`item_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


ALTER TABLE  `<DB_PREFIX>accounts` ADD  `salt` VARCHAR( 50 ) NOT NULL DEFAULT  '' AFTER  `username`;
ALTER TABLE  `<DB_PREFIX>accounts` ADD  `token_expires_at` VARCHAR( 20 ) NOT NULL DEFAULT  '' AFTER  `salt`;

-- 26x
ALTER TABLE  `<DB_PREFIX>modules` ADD  `class_code` VARCHAR( 40 ) NOT NULL DEFAULT  '' AFTER  `code`;
ALTER TABLE  `modules` ADD  `installed_at` datetime NULL DEFAULT NULL AFTER  `is_active`;
ALTER TABLE  `modules` ADD  `updated_at` datetime NULL DEFAULT NULL AFTER  `installed_at`;

ALTER TABLE  `<DB_PREFIX>admins` CHANGE  `role`  `role` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  'admin' COMMENT  '''owner'',''mainadmin'',''admin'' or other'
ALTER TABLE  `<DB_PREFIX>module_settings` CHANGE  `property_type`  `property_type` ENUM(  'string',  'email',  'phone',  'numeric',  'float', 'positive float',  'unsigned float',  'integer',  'positive integer',  'unsigned integer',  'enum',  'range',  'bool',  'html size',  'text',  'code',  'label' ) CHARACTER SET latin1 NOT NULL;

-- 27x

DROP TABLE IF EXISTS `<DB_PREFIX>payment_providers`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>payment_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'for internal use',
  `instructions` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'for external use',
  `required_fields` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `merchant_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `merchant_code` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `merchant_key` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `used_on` enum('front-end','back-end','global') CHARACTER SET latin1 NOT NULL DEFAULT 'global',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - test mode, 1- real mode',
  `sort_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

INSERT INTO `<DB_PREFIX>payment_providers` (`id`, `code`, `name`, `description`, `instructions`, `required_fields`, `merchant_id`, `merchant_code`, `merchant_key`, `used_on`, `mode`, `sort_order`, `is_default`, `is_active`) VALUES
(1, 'online_order', 'Online Order', '''Online Order'' is designed to allow the customer to make an order on the site without any advance payment. It may be used like POA - "Pay on Arrival" order for hotel bookings, car rental etc. The administrator receives a notification about placing the order and can complete the order by himself.', '', '', '', '', '', 'global', 1, 0, 1, 1),
(2, 'online_credit_card', 'Online Credit Card', '''Online Credit Card'' is designed to allow the customer to make an order on the site with payment by credit card. The administrator receives a credit card info and can complete the order by himself (in case he''s allowed to do Offline Credit Card Processing).', '', '', '', '', '', 'global', 1, 1, 0, 1),
(3, 'wire_transfer', 'Wire Transfer', '''Wire Transfer'' is designed to allow the customer to perform a purchase on the site without any advance payment. The administrator receives a notification about placing this reservation and can complete it after the customer will pay a required sum to the provided bank account. After the customer send a payment with wire transfer and it successfully received, the status of purchase may be changes to ''paid''.', 'Bank name: {BANK NAME HERE}<br>Swift code: {CODE HERE}<br>Routing in Transit# or ABA#: {ROUTING HERE}<br>Account number *: {ACCOUNT NUMBER HERE}<br><br>*The account number must be in the IBAN format which may be obtained from the branch handling the customer''''s account or may be seen at the top the customer''''s bank statement', '', '', '', '', 'global', 1, 2, 0, 1),
(4, 'paypal_standard', 'PayPal Standard', 'To make PayPal Standard payments processing system works on your site you have to perform the following steps:<br><br>Create an account on PayPal: https://www.paypal.com<br>After account is created, log into and select from the top menu: My Account -> Profile<br>On Profile Summary page select from the Selling Preferences column: Instant Payment Notification (IPN) Preferences.<br>Turn ''On'' IPN by selecting Receive IPN messages (Enabled) and write into Notification URL: {site}/paymentProviders/handlePayment/paypal/orders, where {site} is a full URL to your site.<br><br>For example: http://your_domain.com/paymentProviders/handlePayment/paypal/orders or<br>http://your_domain.com/site_directory/paymentProviders/handlePayment/paypal/orders<br>Then go to My Account -> Profile -> Website Payment Preferences, turn Auto Return ''On'' and write into Return URL: {site}/paymentProviders/successPayment, where {site} is a full URL to your site.<br><br>For example: http://your_domain.com/paymentProviders/successPayment', 'PayPal Payments Standard allows you to pay with credit cards, debit cards, PayPal, and PayPal Credit.', 'merchant_id', 'sales@example.com', '', '', 'global', 1, 3, 0, 1),
(5, 'paypal_recurring', 'PayPal Recurring', 'To make PayPal Recurring payments processing system works on your site you have to perform the following steps:<br><br>Create an account on PayPal: https://www.paypal.com<br>After account is created, log into and select from the top menu: My Account -> Profile<br>On Profile Summary page select from the Selling Preferences column: Instant Payment Notification (IPN) Preferences.<br>Turn ''On'' IPN by selecting Receive IPN messages (Enabled) and write into Notification URL: {site}/paymentProviders/handlePayment/paypal/orders, where {site} is a full URL to your site.<br><br>For example: http://your_domain.com/paymentProviders/handlePayment/paypal/orders or<br>http://your_domain.com/site_directory/paymentProviders/handlePayment/paypal/orders<br>Then go to My Account -> Profile -> Website Payment Preferences, turn Auto Return ''On'' and write into Return URL: {site}/paymentProviders/successPayment, where {site} is a full URL to your site.<br><br>For example: http://your_domain.com/paymentProviders/successPayment', 'PayPal Recurring Payments allows you to pay for subscription payments and installment plan payments.', 'merchant_id', 'sales@example.com', '', '', 'global', 1, 3, 0, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>social_networks`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>social_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` smallint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9;

INSERT INTO `<DB_PREFIX>social_networks` (`id`, `code`, `icon`, `name`, `link`, `sort_order`, `is_active`) VALUES
(1, 'facebook', 'facebook.png', 'Facebook', 'https://facebook.com/', 1, 1),
(2, 'twitter', 'twitter.png', 'Twitter', 'https://twitter.com/', 2, 1),
(3, 'youtube', 'youtube.png', 'YouTube', 'https://youtube.com/', 3, 1),
(4, 'skype', 'skype.png', 'Skype', 'https://web.skype.com/', 4, 0),
(5, 'pinterest', 'pinterest.png', 'Pinterest', 'https://pinterest.com/', 5, 0),
(6, 'linkedin', 'linkedin.png', 'LinkedIn', 'https://linkedin.com/', 6, 0),
(7, 'instagram', 'instagram.png', 'Instagram', 'https://instagram.com/', 7, 0);


DROP TABLE IF EXISTS `<DB_PREFIX>social_networks_login`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>social_networks_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `application_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `application_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` smallint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4;

INSERT INTO `<DB_PREFIX>social_networks_login` (`id`, `name`, `type`, `application_id`, `application_secret`, `sort_order`, `is_active`) VALUES
(1, 'Facebook', 'facebook', '', '', 1, 0),
(2, 'Google', 'google', '', '', 2, 0),
(3, 'Twitter', 'twitter', '', '', 3, 0);


ALTER TABLE  `<DB_PREFIX>settings` ADD  `indexed_pages_google` varchar(10) CHARACTER SET latin1 NOT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `indexed_pages_bing` varchar(10) CHARACTER SET latin1 NOT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `indexed_pages_yahoo` varchar(10) CHARACTER SET latin1 NOT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `indexed_pages_yandex` varchar(10) CHARACTER SET latin1 NOT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `indexed_pages_baidu` varchar(10) CHARACTER SET latin1 NOT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `indexed_pages_goo` varchar(10) CHARACTER SET latin1 NOT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `site_last_updated` datetime NULL DEFAULT NULL;
ALTER TABLE  `<DB_PREFIX>settings` ADD  `mailing_log` tinyint(1) unsigned NOT NULL DEFAULT '0';
ALTER TABLE  `<DB_PREFIX>settings` ADD  `website_domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL;

INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES
(NULL, 4, 'emailTemplates/', '', '', 1, 1, 1),
(NULL, 4, 'mailingLog/', '', '', 1, 1, 2);

INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES
(NULL, 22, 'en', 'Mailing Log');

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES
(NULL, '', 'mailing_log', 'view', 'View Mailing Log', 'View Mailing Log of the system');


DROP TABLE IF EXISTS `<DB_PREFIX>mailing_log`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>mailing_log` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `email_from` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email_to` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email_subject` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_content` text COLLATE utf8_unicode_ci NOT NULL,
  `sent_at` datetime NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - error, 1- successfully sent',
  `status_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>role_privileges`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>role_privileges` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` int(5) NOT NULL DEFAULT '0',
  `privilege_id` int(5) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=85 ;

INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1),
(11, 1, 11, 1),
(12, 1, 12, 1),
(13, 1, 13, 1),
(14, 1, 14, 1),
(15, 1, 15, 1),
(16, 1, 16, 1),
(17, 1, 17, 1),
(18, 1, 18, 1),
(19, 1, 19, 1),
(20, 1, 20, 1),
(21, 1, 21, 1),
(22, 1, 22, 1),
(23, 1, 23, 1),
(24, 1, 24, 1),
(25, 1, 25, 1),
(26, 1, 26, 1),
(27, 1, 27, 1),
(28, 1, 28, 1),
(29, 2, 1, 1),
(30, 2, 2, 1),
(31, 2, 3, 1),
(32, 2, 4, 1),
(33, 2, 5, 1),
(34, 2, 6, 1),
(35, 2, 7, 1),
(36, 2, 8, 1),
(37, 2, 9, 1),
(38, 2, 10, 1),
(39, 2, 11, 1),
(40, 2, 12, 1),
(41, 2, 13, 1),
(42, 2, 14, 1),
(43, 2, 15, 1),
(44, 2, 16, 1),
(45, 2, 17, 1),
(46, 2, 18, 1),
(47, 2, 19, 1),
(48, 2, 20, 1),
(49, 2, 21, 1),
(50, 2, 22, 1),
(51, 2, 23, 1),
(52, 2, 24, 1),
(53, 2, 25, 1),
(54, 2, 26, 1),
(55, 2, 27, 1),
(56, 2, 28, 1),
(57, 3, 1, 1),
(58, 3, 2, 1),
(59, 3, 3, 1),
(60, 3, 4, 1),
(61, 3, 5, 1),
(62, 3, 6, 1),
(63, 3, 7, 1),
(64, 3, 8, 0),
(65, 3, 9, 0),
(66, 3, 10, 0),
(67, 3, 11, 0),
(68, 3, 12, 1),
(69, 3, 13, 0),
(70, 3, 14, 1),
(71, 3, 15, 0),
(72, 3, 16, 0),
(73, 3, 17, 0),
(74, 3, 18, 0),
(75, 3, 19, 0),
(76, 3, 20, 0),
(77, 3, 21, 1),
(78, 3, 22, 0),
(79, 3, 23, 1),
(80, 3, 24, 0),
(81, 3, 25, 1),
(82, 3, 26, 0),
(83, 3, 27, 0),
(84, 3, 28, 0);

ALTER TABLE  `<DB_PREFIX>admins` ADD `password_changed_at` datetime NULL DEFAULT NULL;
ALTER TABLE  `<DB_PREFIX>accounts` ADD `password_changed_at` datetime NULL DEFAULT NULL;

-- 2.8
ALTER TABLE  `<DB_PREFIX>module_settings` ADD  `trigger_condition` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'includes serialized trigger condition and action fields' AFTER  `append_text`;

-- 3.0
ALTER TABLE  `<DB_PREFIX>search_categories` ADD  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '0';
INSERT INTO  `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'bo_admin_password_forgotten_renew', '', 1);
INSERT INTO	 `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'bo_admin_password_forgotten_renew', code, 'Restore forgotten password', 'Forgotten Password', 'Hello!\r\n\r\nYou or someone else asked to restore your login info on our site:\r\n<a href={SITE_BO_URL}admin/login>{WEB_SITE}</a>\r\n\r\nYour new login:\r\n---------------\r\nUsername: {USERNAME}\r\nPassword: {PASSWORD}\r\n\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
ALTER TABLE  `<DB_PREFIX>admins` ADD `password_restore_hash` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE  `<DB_PREFIX>settings` ADD `shorttime_format` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'H:i';
ALTER TABLE  `<DB_PREFIX>mailing_log` ADD `email_attachments` varchar(512) COLLATE utf8_unicode_ci NOT NULL DEFAULT '';