
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.2', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'gallery';

INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'gallery', '', 'shortcode', '{module:gallery|ID|opened}', 'Opened Album Shortcode', 'This shortcode allows you to display single gallery album on the site in opened mode', 'label', '', '', '', '', 0),
(NULL, 'gallery', '', 'shortcode', '{module:gallery|ID}', 'Album Shortcode', 'This shortcode allows you to display single gallery album on the site', 'label', '', '', '', '', 0);

