
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'events', 'Events', 'Events', 'Events module allows creating and displaying events on the site', '0.0.2', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'events', '', 'shortcode', '{module:events}', 'Shortcode', 'This shortcode allows you to display events on the site pages', 'label', '', '', '', '', 0),
(NULL, 'events', '', 'modulelink', 'events/showCalendar', 'Calendar Link', 'This link leads to the page with calendar', 'label', '', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'events', 'events', 'add', 'Add Events', 'Add events on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'events', 'events', 'edit', 'Edit Events', 'Edit events on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'events', 'events', 'delete', 'Delete Events', 'Delete events from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'modulelink', 'events', 'events/showCalendar', '', 'top', 1, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Events' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>events_categories`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events_categories` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`event_category_sort_order` smallint(6) unsigned NOT NULL DEFAULT '0',
	`event_category_is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `<DB_PREFIX>events_categories` (`id`,  `event_category_sort_order`,`event_category_is_active`) VALUES (1, 1, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>events_categories_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events_categories_translations` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`event_category_id` int(11) DEFAULT '0',
	`language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
	`event_category_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	`event_category_description` text COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`id`),
	KEY `language_code` (`language_code`),
	KEY `event_category_id` (`event_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4;

INSERT INTO `<DB_PREFIX>events_categories_translations` (`id`, `event_category_id`, `language_code`, `event_category_name`, `event_category_description`) SELECT NULL, 1, code, 'This is a test events category', 'This is a test events category content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi mauris mauris, tempor id augue et, vehicula sollicitudin libero. Fusce non lectus ut orci elementum molestie eu eu felis.' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>events`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`event_category_id` int(10) NOT NULL DEFAULT '0',
	`event_link_url` varchar(2000) CHARACTER SET utf8 NOT NULL,
	`event_starts_at` datetime NULL DEFAULT NULL,
	`event_ends_at` datetime NULL DEFAULT NULL,
	`event_is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `event_category_id` (`event_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `<DB_PREFIX>events` (`id`, `event_category_id`,  `event_link_url`, `event_starts_at`,`event_ends_at`, `event_is_active`) VALUES
(1, 1, '', '2016-01-10 00:00:00', '2016-01-14 00:00:00', 1);


DROP TABLE IF EXISTS `<DB_PREFIX>events_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events_translations` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`event_id` int(11) DEFAULT '0',
	`language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
	`event_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	`event_description` text COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`id`),
	KEY  `language_code` (`language_code`),
	KEY  `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4;

INSERT INTO `<DB_PREFIX>events_translations` (`id`, `event_id`, `language_code`, `event_name`, `event_description`) SELECT NULL, 1, code, 'This is a test events', 'This is a test events content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi mauris mauris, tempor id augue et, vehicula sollicitudin libero. Fusce non lectus ut orci elementum molestie eu eu felis.' FROM `<DB_PREFIX>languages`;
