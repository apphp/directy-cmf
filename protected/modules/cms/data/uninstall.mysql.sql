
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'cms';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'cms';

DROP TABLE IF EXISTS `<DB_PREFIX>cms_pages`;
DROP TABLE IF EXISTS `<DB_PREFIX>cms_page_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>cms_page_comments`;

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'menus');
DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'pages');

DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'menus';
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'pages';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'cms');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'cms';

DELETE FROM `<DB_PREFIX>search_categories` WHERE `module_code` = 'cms';
