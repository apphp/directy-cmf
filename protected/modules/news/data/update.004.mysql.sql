
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.4', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'news';


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `is_required`) VALUES
(NULL, 'news', 'Subscription', 'moduleblock', 'drawSubscriptionBlock', 'Subscription Block', 'Draws subscription side block', 'label', '', 0),
(NULL, 'news', 'Subscription', 'news_subscriberss_full_name', 'no', 'Full Name Field', 'Defines whether to allow a Full Name field on Subscription', 'enum', 'allow-required,allow-optional,no', 0),
(NULL, 'news', 'Subscription', 'news_subscribers_first_name', 'allow-optional', 'First Name Field', 'Defines whether to show a First Name field on Subscription', 'enum', 'allow-required,allow-optional,no', 0),
(NULL, 'news', 'Subscription', 'news_subscribers_last_name', 'allow-optional', 'Last Name Field', 'Defines whether to allow a Last Name field on Subscription', 'enum', 'allow-required,allow-optional,no', 0);


INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'news_subscription', 'news', 1), (NULL, 'news_unsubscription', 'news', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'news_subscription', code, 'New subscriber', 'You have successfully subscribed to the newsletter', 'Dear <b>{FIRST_NAME} {LAST_NAME}!</b>\nThank you for your subscription\nTo unsubscribe please go to <a href="{UNSUBSCRIBE_URL}">link</a>\n<br />\nYours sincerely,\nYour <a href="{SITE_URL}">{WEB_SITE}</a> team.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'news_unsubscription', code, 'Delete subscriber', 'You have successfully unsubscribed from our newsletter.', 'Your email address has been removed from our mailing list.\nIf you want to your subscription restored click on <a href="{SUBSCRIBE_URL}">this link</a>\n<br />\nYours sincerely,\nYour <a href="{SITE_URL}">{WEB_SITE}</a> team.' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>news_subscribers`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>news_subscribers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `full_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
