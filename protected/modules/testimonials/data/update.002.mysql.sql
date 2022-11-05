
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.2', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'testimonials';

INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `is_required`) VALUES (NULL, 'testimonials', 'shortcode', '{module:testimonials}', 'Shortcode', 'This shortcode allows you to display a list of testimonials on the site pages', 'label', '', '0');
INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `is_required`) VALUES (NULL, 'testimonials', 'testimonials_per_page', '10', 'Testimonials Per Page', 'Defines how many testimonials will be shown per page', 'range', '1-100', 1);

ALTER TABLE  `<DB_PREFIX>testimonials` CHANGE  `author_name`  `author_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

