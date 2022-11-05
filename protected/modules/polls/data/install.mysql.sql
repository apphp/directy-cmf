
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'polls', 'Polls', 'Polls', 'The Poll core module allows administrator to create simple polls.', '0.0.2', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_key`, `property_value`, `property_group`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'polls', 'allow_polls', '1', '', 'Allow Polls', 'Specifies whether to allow polls to site', 'bool', '', '', '', '', 0),
(NULL, 'polls', 'shortcode', '{module:polls}', '', 'Shortcode', 'This shortcode allows you to display polls on the site pages', 'label', '', '', '', '', 0),
(NULL, 'polls', 'widget', '&#x3C;iframe src=&#x22;http://{site}/polls/widget/{ID}&#x22;&#x3E;&#x3C;/iframe&#x3E;', '', 'Widget', 'This widget lets you display poll on any html pages', 'label', '', '', '', '', 0),
(NULL, 'polls', 'moduleblock', 'drawPollsBlock', '', 'Polls Block', 'Draws polls side block', 'label', '', '', '', '', 0),
(NULL, 'polls', 'modulelink', 'polls/showAll', '', 'All Polls Link', 'This link leads to the page with all polls', 'label', '', '', '', '', 0),
(NULL, 'polls', 'ajax_polls', '1', '', 'Ajax processing polls', 'This option allows you to work with the poll without reloading the page', 'bool', '', '', '', '', 0),
(NULL, 'polls', 'voter_type', 'registered_only', '', 'Voter Type', 'Defines what type of users can vote', 'enum', 'all,registered_only', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'polls', 'polls', 'add', 'Add Polls', 'Add polls on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'polls', 'polls', 'edit', 'Edit Polls', 'Edit polls on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'polls', 'polls', 'delete', 'Delete Polls', 'Delete polls from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


INSERT INTO `<DB_PREFIX>frontend_menus` VALUES (NULL, '0', 'moduleblock', 'polls', 'drawPollsBlock', '', 'right', '23', 'public', '1');
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES (NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), 'en', 'Poll Right');
INSERT INTO `<DB_PREFIX>frontend_menus` VALUES (NULL, '0', 'moduleblock', 'polls', 'drawPollsBlock', '', 'left', '24', 'public', '1');
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES (NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), 'en', 'Poll Left');


DROP TABLE IF EXISTS `<DB_PREFIX>polls`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>polls` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `poll_answer_1_votes` int(10) unsigned NOT NULL DEFAULT '0',
    `poll_answer_2_votes` int(10) unsigned NOT NULL DEFAULT '0',
    `poll_answer_3_votes` int(10) unsigned NOT NULL DEFAULT '0',
    `poll_answer_4_votes` int(10) NOT NULL DEFAULT '0',
    `poll_answer_5_votes` int(10) unsigned NOT NULL DEFAULT '0',
    `type_comparison` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - Equally, 1 - Substring',
    `instruction` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `color_text` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `created_at` date NULL DEFAULT NULL,
    `expires_at` date NULL DEFAULT NULL,
    `sort_order` smallint(6) NOT NULL DEFAULT '0',
    `is_active` tinyint(4) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `<DB_PREFIX>polls` (`id`, `poll_answer_1_votes`, `poll_answer_2_votes`, `poll_answer_3_votes`, `poll_answer_4_votes`, `poll_answer_5_votes`, `type_comparison`, `instruction`, `color_text`, `background_color`, `created_at`, `expires_at`, `sort_order`, `is_active`) VALUES
(1, 1, 0, 3, 4, 5, 0, '', '#000000', '#ffffff', '2015-12-14', NULL, 0, 1),
(2, 12, 4, 2, 5, 2, 0, '', '#000000', '#ffffff', '2016-12-02', NULL, 0, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>polls_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>polls_translations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `polls_id` int(11) DEFAULT '0',
    `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
    `poll_question` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `poll_answer_1` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `poll_answer_2` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `poll_answer_3` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `poll_answer_4` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `poll_answer_5` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `<DB_PREFIX>polls_translations` (`id`, `polls_id`, `language_code`, `poll_question`, `poll_answer_1`, `poll_answer_2`, `poll_answer_3`, `poll_answer_4`, `poll_answer_5`) SELECT NULL, 1, code, 'Test Question', 'Answer 1', 'Answer 2', 'Answer 3', 'Answer 4', '' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>polls_translations` (`id`, `polls_id`, `language_code`, `poll_question`, `poll_answer_1`, `poll_answer_2`, `poll_answer_3`, `poll_answer_4`, `poll_answer_5`) SELECT NULL, 2, code, 'What browser do you prefer to use?', 'Chrome', 'Firefox', 'Opera', 'Internet Explorer', 'Safary' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>votes`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>votes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `account_id` int(11) NOT NULL DEFAULT 0,
    `poll_id` int(6) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `account_id` (`account_id`, `poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
