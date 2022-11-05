
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'faq', 'Faq', 'FAQ', 'FAQ module allows creating and displaying FAQ on the site', '0.0.3', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'faq', '', 'shortcode', '{module:faq}', 'Shortcode', 'This shortcode allows you to display faq on the site pages', 'label', '', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'faq', 'faq', 'add', 'Add FAQ', 'Add faq on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'faq', 'faq', 'edit', 'Edit FAQ', 'Edit faq on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'faq', 'faq', 'delete', 'Delete FAQ', 'Delete faq from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


DROP TABLE IF EXISTS `<DB_PREFIX>faq_categories`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>faq_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>faq_category_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>faq_category_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_category_id` int(11) DEFAULT '0',
  `language_code` varchar(3) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `category_name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>faq_category_items`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>faq_category_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_category_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` tinyint(6) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>faq_category_item_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>faq_category_item_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_category_item_id` int(11) DEFAULT '0',
  `language_code` varchar(3) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `faq_question` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `faq_answer` VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


