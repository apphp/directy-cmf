
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.2', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'faq';

ALTER TABLE `<DB_PREFIX>faq_category_item_translations` CHANGE `faq_question` `faq_question` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `<DB_PREFIX>faq_category_item_translations` CHANGE `faq_answer` `faq_answer` VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
