DROP TABLE IF EXISTS `<DB_PREFIX>tickets`;
DROP TABLE IF EXISTS `<DB_PREFIX>tickets_replies`;

DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'tickets';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'tickets';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'tickets');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'tickets';

DELETE FROM `<DB_PREFIX>email_template_translations` WHERE `template_code` IN (SELECT code FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'tickets');
DELETE FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'tickets';

DELETE FROM `<DB_PREFIX>frontend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'tickets');
DELETE FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'tickets';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'tickets');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'tickets';
