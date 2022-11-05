
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'gallery', 'Gallery', 'Gallery', 'Gallery module allows creating gallery and albums on site', '0.0.2', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'gallery', '', 'shortcode', '{module:gallery}', 'Gallery Shortcode', 'This shortcode allows you to display gallery albums on the site', 'label', '', '', '', '', 0),
(NULL, 'gallery', '', 'shortcode', '{module:gallery|ID}', 'Album Shortcode', 'This shortcode allows you to display single gallery album on the site', 'label', '', '', '', '', 0),
(NULL, 'gallery', '', 'shortcode', '{module:gallery|ID|opened}', 'Opened Album Shortcode', 'This shortcode allows you to display single gallery album on the site in opened mode', 'label', '', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'gallery', 'gallery_albums', 'add', 'Add Gallery Albums', 'Add albums and album items to the gallery'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'gallery', 'gallery_albums', 'edit', 'Edit Gallery Albums', 'Edit albums and album items in the gallery'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'gallery', 'gallery_albums', 'delete', 'Delete Gallery Albums', 'Delete albums and album items from the gallery'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


DROP TABLE IF EXISTS `<DB_PREFIX>gallery_albums`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>gallery_albums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `album_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - images, 1 - video',
  `sort_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>gallery_album_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>gallery_album_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gallery_album_id` int(10) DEFAULT '0',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_code` (`language_code`),
  KEY `gallery_album_id` (`gallery_album_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>gallery_album_items`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>gallery_album_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gallery_album_id` int(10) DEFAULT '0',
  `item_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_file_thumb` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `gallery_album_id` (`gallery_album_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>gallery_album_item_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>gallery_album_item_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gallery_album_item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_album_item_id` (`gallery_album_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;



