
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'advertisements', 'Advertisements', 'Advertisements', 'Advertising module allows the administrator to create and place ad units on the site', '0.0.1', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_key`, `property_value`, `property_group`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'advertisements', 'allow_ads', '1', '', 'Allow Advertisements', 'Specifies whether to allow advertisements to site', 'bool', '', '', '', '', 0),
(NULL, 'advertisements', 'shortcode', '{module:ads}', '', 'Shortcode', 'This shortcode allows you to display advertisement on the site pages', 'label', '', '', '', '', 0),
(NULL, 'advertisements', 'moduleblock', 'drawAdsBlock', '', 'Advertisement Block', 'Draws advertisement side block', 'label', '', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'advertisements', 'advertisement', 'add', 'Add Advertisement', 'Add advertisement on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'advertisements', 'advertisement', 'edit', 'Edit Advertisement', 'Edit advertisement on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'advertisements', 'advertisement', 'delete', 'Delete Advertisement', 'Delete advertisement from the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


INSERT INTO `<DB_PREFIX>frontend_menus` VALUES (NULL, '0', 'moduleblock', 'advertisements', 'drawAdsBlock', '', 'right', '21', 'public', '1');
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES (NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), 'en', 'Advertisement Right');
INSERT INTO `<DB_PREFIX>frontend_menus` VALUES (NULL, '0', 'moduleblock', 'advertisements', 'drawAdsBlock', '', 'left', '22', 'public', '1');
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES (NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), 'en', 'Advertisement Left');


DROP TABLE IF EXISTS `<DB_PREFIX>ads`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>ads` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `url` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `width` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `height` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `image` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `color_text` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `title` varchar(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `text` varchar(512) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `type_comparison` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - Equally, 1 - Substring',
    `instruction` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `created_at` date NULL DEFAULT NULL,
    `sort_order` smallint(6) NOT NULL DEFAULT '0',
    `is_active` tinyint(4) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `<DB_PREFIX>ads` (`id`, `url`, `width`, `height`, `image`, `color_text`, `background_color`, `title`, `text`, `type_comparison`, `instruction`, `created_at`, `sort_order`, `is_active`) VALUES
(1, 'https://www.apphp.com', '', '', 'ad_1.png', '#000000', '#ffffff', '', '', 1, '', '2016-11-03', 1, 1),
(2, '', '', '', 'ad_2.png', '#000000', '#ffffff', 'Title 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', 1, 'posts', '2016-11-03', 2, 1),
(3, 'https://www.apphp.com/php-microblog/index.php', '', '', '', '#000000', '#ffffff', 'Title 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', 1, 'pages', '2016-11-03', 3, 1);
