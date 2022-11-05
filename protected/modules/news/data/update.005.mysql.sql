
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.5', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'news';

ALTER TABLE `<DB_PREFIX>news` ADD `hits` int(11) NOT NULL DEFAULT '0';
