
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'news', 'News', 'News', 'News module allows creating and displaying news on the site', '0.0.5', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'news', 'Side Block', 'moduleblock', 'drawNewsBlock', 'News Block', 'Draws news side block', 'label', '', '', '', '', 0),
(NULL, 'news', 'Side Block', 'news_count', '5', 'News Count', 'Defines how many news headlines will be shown in the news block', 'range', '1-20', '', '', '', 1),
(NULL, 'news', 'Side Block', 'news_header_length', '80', 'News Header Length', 'Defines a length of news header in the news block', 'range', '1-255', '', '', '', 1),
(NULL, 'news', 'Side Block', 'show_block_date', '1', 'News Block Date', 'Specifies whether to show date in the news block', 'bool', '', '', '', '', 0),
(NULL, 'news', 'Side Block', 'view_all_link', 'always', 'View All Link', 'Specifies whether to show View All link in the news block', 'enum', 'never,show-after_2_items,show-after_3_items,show-after_4_items,show-after_5_items,show-after_10_items,always', '', '', '', 0),
(NULL, 'news', 'Footer Block', 'moduleblock', 'drawLatestNewsBlock', 'Latest News Block', 'Draws news footer block', 'label', '', '', '', '', 0),
(NULL, 'news', '', 'modulelink', 'news/viewAll', 'All News Link', 'This link leads to the page with all news', 'label', '', '', '', '', 0),
(NULL, 'news', '', 'shortcode', '{module:news}', 'Shortcode', 'This shortcode allows you to display news on the site pages', 'label', '', '', '', '', 0),
(NULL, 'news', '', 'news_per_page', '10', 'News Per Page', 'Defines how many news will be shown per page', 'range', '1-100', '', '', '', 1),
(NULL, 'news', '', 'news_link_format', 'news/view/id/ID', 'News Link Format', 'Defines a SEO format for news links that will be used on the site', 'enum', 'news/view/id/ID,news/view/id/ID/Name,news/view/ID,news/view/ID/Name,news/ID,news/ID/Name', '', '', '', 0),
(NULL, 'news', 'Subscription', 'moduleblock', 'drawSubscriptionBlock', 'Subscription Block', 'Draws subscription side block', 'label', '', '', '', '', 0),
(NULL, 'news', 'Subscription', 'news_subscribers_full_name', 'no', 'Full Name Field', 'Defines whether to allow a Full Name field on Subscription', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'news', 'Subscription', 'news_subscribers_first_name', 'allow-optional', 'First Name Field', 'Defines whether to allow a First Name field on Subscription', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'news', 'Subscription', 'news_subscribers_last_name', 'allow-optional', 'Last Name Field', 'Defines whether to allow a Last Name field on Subscription', 'enum', 'allow-required,allow-optional,no', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'news', 'news', 'add', 'Add News', 'Add news on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'news', 'news', 'edit', 'Edit News', 'Edit news on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'news', 'news', 'delete', 'Delete News', 'Delete news from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'news', 'drawNewsBlock', '', 'right', 0, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'News' FROM `<DB_PREFIX>languages`;

INSERT INTO `<DB_PREFIX>search_categories` (`id`, `module_code`, `category_code`, `category_name`, `callback_class`, `callback_method`, `items_count`, `sort_order`, `is_active`) VALUES
(NULL, 'news', 'news', 'News', 'Modules\\News\\Models\\News', 'search', '20', (SELECT COUNT(sc.id) + 1 FROM `<DB_PREFIX>search_categories` sc), 1);

INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'news_subscription', 'news', 1), (NULL, 'news_unsubscription', 'news', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'news_subscription', code, 'New subscriber', 'You have successfully subscribed to the newsletter', 'Dear <b>{FIRST_NAME} {LAST_NAME}!</b>\nThank you for your subscription\nTo unsubscribe please go to <a href="{UNSUBSCRIBE_URL}">link</a>\n<br />\nYours sincerely,\nYour <a href="{SITE_URL}">{WEB_SITE}</a> team.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'news_unsubscription', code, 'Delete subscriber', 'You have successfully unsubscribed from our newsletter.', 'Your email address has been removed from our mailing list.\nIf you want to your subscription restored click on <a href="{SUBSCRIBE_URL}">this link</a>\n<br />\nYours sincerely,\nYour <a href="{SITE_URL}">{WEB_SITE}</a> team.' FROM `<DB_PREFIX>languages`;

DROP TABLE IF EXISTS `<DB_PREFIX>news`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `intro_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `hits` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `<DB_PREFIX>news` (`id`, `intro_image`, `created_at`, `is_published`) VALUES (1, '1.png', '2017-09-01 12:45:00', 1);


DROP TABLE IF EXISTS `<DB_PREFIX>news_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>news_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `news_header` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `news_text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_code` (`language_code`),
  KEY `news_id` (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `<DB_PREFIX>news_translations` (`id`, `news_id`, `language_code`, `news_header`, `news_text`) SELECT NULL, 1, code, 'This is a test news', 'This is a test news content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi mauris mauris, tempor id augue et, vehicula sollicitudin libero. Fusce non lectus ut orci elementum molestie eu eu felis.' FROM `<DB_PREFIX>languages`;

DROP TABLE IF EXISTS `<DB_PREFIX>news_subscribers`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>news_subscribers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `full_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
