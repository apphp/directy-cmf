
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'reports';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'reports';


DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `module_code` = 'reports');
DELETE FROM `<DB_PREFIX>privileges` WHERE `module_code` = 'reports';


DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'reports');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'reports';


DROP TABLE IF EXISTS `<DB_PREFIX>reports_entities`;
DROP TABLE IF EXISTS `<DB_PREFIX>reports_entity_items`;
DROP TABLE IF EXISTS `<DB_PREFIX>reports_projects`;
DROP TABLE IF EXISTS `<DB_PREFIX>reports_types`;
DROP TABLE IF EXISTS `<DB_PREFIX>reports_type_items`;
DROP TABLE IF EXISTS `<DB_PREFIX>reports_entities_comments`;



