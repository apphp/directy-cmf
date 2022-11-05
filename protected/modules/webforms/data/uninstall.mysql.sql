
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'webforms';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'webforms';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'webformMessages');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'webformMessages';

DROP TABLE IF EXISTS `<DB_PREFIX>webforms_messages`;


