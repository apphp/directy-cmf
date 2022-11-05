
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'blog';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'blog';

DROP TABLE IF EXISTS `<DB_PREFIX>blog_posts`;
DROP TABLE IF EXISTS `<DB_PREFIX>blog_post_comments`;

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'posts');
DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'posts';

DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'blog');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'blog';

DELETE FROM `<DB_PREFIX>search_categories` WHERE `module_code` = 'blog';

