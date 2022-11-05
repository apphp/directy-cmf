
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.2', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'polls';

-- Add new settings --
INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_key`, `property_value`, `property_group`, `name`, `description`, `property_type`, `property_source`, `is_required`) VALUES
(NULL, 'polls', 'allow_polls', '1', '', 'Allow Polls', 'Specifies whether to allow polls to site', 'bool', '', 0),
(NULL, 'polls', 'widget', '&#x3C;iframe src=&#x22;http://{site}/polls/widget/{ID}&#x22;&#x3E;&#x3C;/iframe&#x3E;', '', 'Widget', 'This widget lets you display poll on any html pages', 'label', '', 0),
(NULL, 'polls', 'ajax_polls', '1', '', 'Ajax processing polls', 'This option allows you to work with the poll without reloading the page', 'bool', '', 0);

-- Remove one type of voting "visitors only" --
UPDATE `<DB_PREFIX>module_settings` SET `property_value` = 'registered_only', `property_source` = 'all,registered_only' WHERE `module_code` = 'polls' AND `property_key` = 'voter_type';

-- Add two bloks for left and right menu --
INSERT INTO `<DB_PREFIX>frontend_menus` VALUES (NULL, '0', 'moduleblock', 'polls', 'drawPollsBlock', '', 'right', '23', 'public', '1');
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES (NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), 'en', 'Poll Right');
INSERT INTO `<DB_PREFIX>frontend_menus` VALUES (NULL, '0', 'moduleblock', 'polls', 'drawPollsBlock', '', 'left', '24', 'public', '1');
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) VALUES (NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), 'en', 'Poll Left');

-- Add new fields for polls --
ALTER TABLE `<DB_PREFIX>polls` ADD `type_comparison` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - Equally, 1 - Substring' AFTER  `poll_answer_5_votes`,
                               ADD `instruction` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER  `type_comparison`,
                               ADD `color_text` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `instruction`,
                               ADD `background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `color_text`,
                               ADD `sort_order` smallint(6) NOT NULL DEFAULT '0' AFTER `expires_at`;

-- Add default values --
ALTER TABLE `<DB_PREFIX>polls_translations` CHANGE `poll_question` `poll_question` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                            CHANGE `poll_answer_1` `poll_answer_1` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                            CHANGE `poll_answer_2` `poll_answer_2` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                            CHANGE `poll_answer_3` `poll_answer_3` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                            CHANGE `poll_answer_4` `poll_answer_4` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                                            CHANGE `poll_answer_5` `poll_answer_5` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '';

-- New table for votes --
DROP TABLE IF EXISTS `<DB_PREFIX>votes`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>votes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `account_id` int(11) NOT NULL DEFAULT 0,
    `poll_id` int(6) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `account_id` (`account_id`, `poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
