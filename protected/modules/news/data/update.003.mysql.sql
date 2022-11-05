
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.3', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'news';

ALTER TABLE `<DB_PREFIX>news` ADD `intro_image` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER  `id`;
