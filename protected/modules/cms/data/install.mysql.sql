
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'cms', 'Cms', 'Content Management', 'CMS module allows management of site content', '0.0.5', 'icon.png', 1, 1, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'cms', '', 'page_link_format', 'pages/view/id/ID', 'Page Link Format', 'Defines a SEO format for page links that will be used on the site', 'enum', 'pages/view/id/ID,pages/view/id/ID/Name,pages/view/ID,pages/view/ID/Name,pages/ID,pages/ID/Name', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'cms', 'pages', 'add', 'Add Pages', 'Add Pages on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'cms', 'pages', 'edit', 'Edit Pages', 'Edit Pages on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'cms', 'pages', 'delete', 'Delete Pages', 'Delete Pages from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);


INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, 0, '', 'cms', 'cms.png', 0, 1, 6);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Content Management' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'cms' AND bm.parent_id = 0), '<SITE_BO_URL>modules/settings/code/cms', 'cms', '', 0, 1, 0);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Settings' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'cms' AND bm.parent_id = 0), 'pages/manage', 'cms', '', 0, 1, 1);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Pages' FROM `<DB_PREFIX>languages`;

INSERT INTO `<DB_PREFIX>search_categories` (`id`, `module_code`, `category_code`, `category_name`, `callback_class`, `callback_method`, `items_count`, `sort_order`, `is_active`) VALUES
(NULL, 'cms', 'pages', 'Pages', 'Modules\\Cms\\Models\\Pages', 'search', '20', (SELECT COUNT(sc.id) + 1 FROM `<DB_PREFIX>search_categories` sc), 1);

DROP TABLE IF EXISTS `<DB_PREFIX>cms_pages`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>cms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comments_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NULL DEFAULT NULL,
  `modified_at` datetime NULL DEFAULT NULL,
  `finish_publishing_at` date NULL DEFAULT NULL,
  `is_homepage` tinyint(1) NOT NULL DEFAULT '0',
  `publish_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1 - removed, 0 - draft, 1 - published',
  `show_in_search` tinyint(1) NOT NULL DEFAULT '1',
  `access_level` enum('public','registered') CHARACTER SET latin1 NOT NULL DEFAULT 'public',
  `sort_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `publish_status` (`publish_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `<DB_PREFIX>cms_pages` (`id`, `comments_allowed`, `created_at`, `modified_at`, `finish_publishing_at`, `is_homepage`, `publish_status`, `show_in_search`, `access_level`, `sort_order`) VALUES
(1, 0, '2013-01-01 00:00:01', '2013-01-01 00:00:01', NULL, 1, 1, 1, 'public', 0);


DROP TABLE IF EXISTS `<DB_PREFIX>cms_page_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>cms_page_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT '0',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `tag_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag_keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `tag_description` text COLLATE utf8_unicode_ci NOT NULL,
  `page_header` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `language_code` (`language_code`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `<DB_PREFIX>cms_page_translations` (`id`, `page_id`, `language_code`, `tag_title`, `tag_keywords`, `tag_description`, `page_header`, `page_text`) SELECT NULL, 1, code, 'Our Site', 'php site', 'Our Site', 'WELCOME TO OUR WEBSITE!', '<h3>Hi there, Guest!</h3>\r\n<p>If you can read this message, this script has been successfully installed on your web hosting.</p>\r\n<p>This is an example of a HomePage, you could edit this to put information about yourself or your site do readers know where you are coming from. It’s a great way to get attention.</p>\r\n<p><strong>Dummy Text</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac mattis elit. Nam convallis tristique lorem non ornare. Sed mi augue, luctus quis est sed, viverra aliquet metus. Pellentesque urna neque, elementum sit amet aliquam dapibus, tristique id metus. In pretium venenatis faucibus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec varius lectus sed neque tincidunt tempor. In aliquam leo quis dui egestas, quis feugiat leo facilisis.</p>\r\n<p>Aliquam at lacus non lacus rhoncus bibendum id eget dolor. Donec placerat velit sed dictum tincidunt. Praesent odio lectus, eleifend nec viverra eu, sollicitudin vitae metus. Fusce quis tortor convallis ipsum aliquam dignissim. Nullam dignissim facilisis consectetur. Vestibulum sagittis augue nibh, non aliquet diam interdum tempor. Phasellus rhoncus commodo lectus id suscipit. Nullam non enim eu metus tempus lacinia ut condimentum tellus. Vestibulum eu odio eu mauris feugiat vulputate ut sed leo. Vivamus mollis non neque quis scelerisque.</p>' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>cms_page_comments`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>cms_page_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_page_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(80) CHARACTER SET latin1 NOT NULL,
  `comment_text` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - awaiting, 1 - approved, 2 - denied',
  `created_at` datetime NULL DEFAULT NULL,
  `changed_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cms_page_id` (`cms_page_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

