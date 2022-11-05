
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES 
(NULL, 'banners', 'Banners', 'Banners', 'This module allows you to show banners on the Frontend of the site', '0.0.3', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));

INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'banners', '', 'shortcode', '{module:banners}', 'Shortcode', 'This shortcode allows you to display banners on the site pages (main page)', 'label', '', '', '', '', '0'),
(NULL, 'banners', '', 'rotation_delay', '5', 'Rotation Delay', 'Defines banners rotation delay in seconds', 'enum', '1,2,3,4,5,6,7,8,9,10,15,20,25,30,35,40,45,50,55,60', '', '', '', '0'),
(NULL, 'banners', '', 'viewer_type', 'all', 'Viewer Type', 'Defines what type of users can view this banners', 'enum', 'all,visitors only,registered only', '', '', '', '0');


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'banners', 'banners', 'add', 'Add Banners', 'Add banners to the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'banners', 'banners', 'edit', 'Edit Banners', 'Edit banners on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'banners', 'banners', 'delete', 'Delete Banners', 'Delete banners from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


DROP TABLE IF EXISTS `<DB_PREFIX>banners`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>banners` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `image_file` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image_file_thumb` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `<DB_PREFIX>banners` (`id`, `image_file`, `image_file_thumb`, `link_url`, `sort_order`, `is_active`) VALUES
(1, 'banner1.png', 'banner1_thumb.png', 'https://apphp.com/php-directy-cmf', 0, 1),
(2, 'banner2.png', 'banner2_thumb.png', '', 1, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>banners_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>banners_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `language_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `banner_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `banner_text` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  `banner_button` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `<DB_PREFIX>banners_translations` (`id`, `banner_id`, `language_code`, `banner_title`, `banner_text`, `banner_button`)
SELECT NULL, 1, code, 'Directy CMF', 'Cross-platform content management framework <br>designed to provide modern and rapid development <br>of websites and web applications.', 'Read more' FROM `<DB_PREFIX>languages`;

INSERT INTO `<DB_PREFIX>banners_translations` (`id`, `banner_id`, `language_code`, `banner_title`, `banner_text`, `banner_button`)
SELECT NULL, 2, code, 'Directy CMF', 'Open-source content management framework (CMF), <br>built on PHP MVC Framework that provides a basic <br>functionality required for creating websites.', '' FROM `<DB_PREFIX>languages`;

