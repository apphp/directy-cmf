
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'gallery';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'gallery';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'gallery_albums');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'gallery_albums';

DROP TABLE IF EXISTS `<DB_PREFIX>gallery_albums`;
DROP TABLE IF EXISTS `<DB_PREFIX>gallery_album_translations`;

DROP TABLE IF EXISTS `<DB_PREFIX>gallery_album_items`;
DROP TABLE IF EXISTS `<DB_PREFIX>gallery_album_item_translations`;

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'gallery');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'gallery';



