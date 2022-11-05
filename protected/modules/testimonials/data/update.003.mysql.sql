
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.3', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'testimonials';


ALTER TABLE `<DB_PREFIX>testimonials` ADD COLUMN `author_image` varchar(50) COLLATE utf8_unicode_ci NOT NULL AFTER `author_email`;

