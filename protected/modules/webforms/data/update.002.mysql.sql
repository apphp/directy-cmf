
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.2', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'webforms';

INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'webforms', 'Form Fields', 'field_captcha', 'show', 'Captcha Validation', 'Defines whether to display a Captcha on the form', 'enum', 'show,hide', '', '', '', 0);