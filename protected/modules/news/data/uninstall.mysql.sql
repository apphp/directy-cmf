
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'news';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'news';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'news');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'news';
DELETE FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'news';
DELETE FROM `<DB_PREFIX>email_template_translations` WHERE `template_code` = 'news_subscription';
DELETE FROM `<DB_PREFIX>email_template_translations` WHERE `template_code` = 'news_unsubscription';

DROP TABLE IF EXISTS `<DB_PREFIX>news`;
DROP TABLE IF EXISTS `<DB_PREFIX>news_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>news_subscribers`;

DELETE FROM `<DB_PREFIX>frontend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'news');
DELETE FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'news';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'news');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'news';

DELETE FROM `<DB_PREFIX>search_categories` WHERE `module_code` = 'news';
