UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.3', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'banners';


ALTER TABLE `<DB_PREFIX>banners_translations` ADD COLUMN `banner_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL AFTER `language_code`;
ALTER TABLE `<DB_PREFIX>banners_translations` CHANGE  `banner_text`  `banner_text` VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `<DB_PREFIX>banners_translations` ADD COLUMN `banner_button` varchar(50) COLLATE utf8_unicode_ci NOT NULL AFTER `language_code`;
