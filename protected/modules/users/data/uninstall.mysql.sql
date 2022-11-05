
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'users';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'users';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'users');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'users';

DELETE FROM `<DB_PREFIX>email_template_translations` WHERE `template_code` IN (SELECT code FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'users');
DELETE FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'users';

DELETE FROM `<DB_PREFIX>frontend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'users');
DELETE FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'users';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'users');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'users';

DELETE FROM `<DB_PREFIX>accounts` WHERE `id` IN (SELECT account_id FROM `<DB_PREFIX>users_accounts`);
DROP TABLE IF EXISTS `<DB_PREFIX>users_accounts`;
DROP TABLE IF EXISTS `<DB_PREFIX>users_groups`;

