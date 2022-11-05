DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'advertisements';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'advertisements';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'advertisement' AND `code` = 'add');
DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'advertisement' AND `code` = 'edit');
DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'advertisement' AND `code` = 'delete');

DELETE FROM `<DB_PREFIX>privileges` WHERE `module_code` = 'advertisements';

DELETE FROM `<DB_PREFIX>frontend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'advertisements');
DELETE FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'advertisements';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'advertisements');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'advertisements';

DROP TABLE IF EXISTS `<DB_PREFIX>ads`;
