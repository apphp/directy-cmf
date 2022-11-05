
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'faq';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'faq';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'faq');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'faq';

DROP TABLE IF EXISTS `<DB_PREFIX>faq_categories`;
DROP TABLE IF EXISTS `<DB_PREFIX>faq_category_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>faq_category_items`;
DROP TABLE IF EXISTS `<DB_PREFIX>faq_category_item_translations`;

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'faq');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'faq';

