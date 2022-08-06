
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
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `last_visited_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_visited_ip` varchar(15) CHARACTER SET latin1 NOT NULL DEFAULT '000.000.000.000',
  `notifications` tinyint(1) NOT NULL DEFAULT '0',
  `notifications_changed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - registration pending, 1 - active account',
  `is_removed` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'for logical removal',
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `registration_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE  `admins` CHANGE  `lastvisited_at`  `last_visited_at` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00'


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
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
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
  `started_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ban_item_type` (`item_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


ALTER TABLE  `<DB_PREFIX>accounts` ADD  `salt` VARCHAR( 50 ) NOT NULL DEFAULT  '' AFTER  `username`;
ALTER TABLE  `<DB_PREFIX>accounts` ADD  `token_expires_at` VARCHAR( 20 ) NOT NULL DEFAULT  '' AFTER  `salt`;

