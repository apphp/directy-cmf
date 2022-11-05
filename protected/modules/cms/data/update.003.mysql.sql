
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.3', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'cms';


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `is_required`) VALUES
(NULL, 'cms', '', 'page_link_format', 'pages/view/id/ID', 'Page Link Format', 'Defines a SEO format for page links that will be used on the site', 'enum', 'pages/view/id/ID,pages/view/id/ID/Name,pages/view/ID,pages/view/ID/Name,pages/ID,pages/ID/Name', 0);


ALTER TABLE `<DB_PREFIX>cms_pages` DROP `tag_title`;
ALTER TABLE `<DB_PREFIX>cms_pages` DROP `tag_keywords`;
ALTER TABLE `<DB_PREFIX>cms_pages` DROP `tag_description`;
    
ALTER TABLE `<DB_PREFIX>cms_page_translations` ADD `tag_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `id`;
ALTER TABLE `<DB_PREFIX>cms_page_translations` ADD `tag_keywords` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `tag_title`;
ALTER TABLE `<DB_PREFIX>cms_page_translations` ADD `tag_description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `tag_keywords`;        
