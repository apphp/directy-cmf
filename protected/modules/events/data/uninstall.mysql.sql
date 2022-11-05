
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'events';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'events';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'events');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'events';

DROP TABLE IF EXISTS `<DB_PREFIX>events_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>events`;

DROP TABLE IF EXISTS `<DB_PREFIX>events_categories_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>events_categories`;

DELETE FROM `<DB_PREFIX>frontend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'events');
DELETE FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'events';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'events');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'events';
