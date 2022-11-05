
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'users', 'Users', 'Users', 'Users module allows users management on the site: creating accounts, registration, login etc.', '0.0.3', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`)VALUES
(NULL, 'users', '', 'change_user_password', '1', 'Admin Changes Password', 'Specifies whether to allow changing user password by Admin', 'bool', '', '', '', '', 0),
(NULL, 'users', '', 'approval_type', 'automatic', 'Approval Type', 'Specifies which type of approval is required for user registration', 'enum', 'by_admin,by_email,automatic', '', '', '', 0),
(NULL, 'users', '', 'removal_type', 'physical', 'Remove Account Type', 'Specifies the type of user account removal: logical, physical or both', 'enum', 'logical,physical,physical_or_logical', '', '', '', 0),
(NULL, 'users', 'Account Login', 'allow_login', '1', 'Allow Users to Login', 'Specifies whether to allow existing users to login', 'bool', '', '', '', '', 0),
(NULL, 'users', 'Account Login', 'allow_remember_me', '1', 'Allow Remember Me', 'Specifies whether to allow Remember Me feature', 'bool', '', '', '', '', 0),
(NULL, 'users', 'Account Login', 'modulelink', 'users/login', 'User Login Link', 'This link leads to the page where user can login to the site', 'label', '', '', '', '', 0),
(NULL, 'users', 'Account Login', 'moduleblock', 'drawLoginBlock', 'Login Block', 'Draws login side block', 'label', '', '', '', '', 0),
(NULL, 'users', 'Account Registration', 'allow_registration', '1', 'Allow Users to Register', 'Specifies whether to allow new users to register', 'bool', '', '', '', '', 0),
(NULL, 'users', 'Account Registration', 'new_registration_alert', '1', 'New Registration Admin Alert', 'Specifies whether to alert admin on new user registration', 'bool', '', '', '', '', 0),
(NULL, 'users', 'Account Registration', 'modulelink', 'users/registration', 'User Registration Link', 'This link leads to the page where user can register to the site', 'label', '', '', '', '', 0),
(NULL, 'users', 'Account Restore Password', 'allow_restore_password', '1', 'Allow Restore Password', 'Specifies whether to allow users to restore their passwords', 'bool', '', '', '', '', 0),
(NULL, 'users', 'Account Restore Password', 'modulelink', 'users/restorePassword', 'Restore Password Link', 'This link leads to the page where user may restore forgotten password', 'label', '', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_first_name', 'allow-required', 'First Name Field', 'Defines whether to allow a First Name field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_last_name', 'allow-required', 'Last Name Field', 'Defines whether to allow a Last Name field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_birth_date', 'allow-optional', 'Birth Date Field', 'Defines whether to allow Birth Date field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_website', 'allow-optional', 'Website Field', 'Defines whether to allow Website field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_company', 'allow-optional', 'Company Field', 'Defines whether to allow Company field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_phone', 'allow-optional', 'Phone Field', 'Defines whether to allow a Phone field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_fax', 'allow-optional', 'Fax Field', 'Defines whether to allow a Fax field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_email', 'allow-required', 'Email Field', 'Defines whether to allow an Email field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_address', 'allow-optional', 'Address Field', 'Defines whether to allow Address field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_address_2', 'allow-optional', 'Address 2 Field', 'Defines whether to allow Address 2 field on user profile', 'enum', 'allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_city', 'allow-optional', 'City Field', 'Defines whether to allow City field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_zip_code', 'allow-optional', 'Zip/Postal Code Field', 'Defines whether to allow Zip/Postal Code field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_country', 'allow-optional', 'Country Field', 'Defines whether to allow Country field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_state', 'allow-optional', 'State/Province  Field', 'Defines whether to allow State/Province field on user profile', 'enum', 'allow-required,allow-optional,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_notifications', 'allow', 'Notifications Field', 'Defines whether to allow Notifications field on user profile', 'enum', 'allow,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_username', 'allow', 'Username Field', 'Defines whether to allow Username field on user profile', 'enum', 'allow', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_password', 'allow', 'Password Field', 'Defines whether to allow Password field on user profile', 'enum', 'allow', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_confirm_password', 'allow', 'Confirm Password Field', 'Defines whether to allow Confirm Password field on user profile', 'enum', 'allow,no', '', '', '', 0),
(NULL, 'users', 'Profile Fields', 'field_captcha', 'allow', 'Captcha Validation', 'Defines whether to allow Captcha validation on user registration page', 'enum', 'allow,no', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'users', 'users', 'add', 'Add Users', 'Add users on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'users', 'users', 'edit', 'Edit Users', 'Edit users on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'users', 'users', 'delete', 'Delete Users', 'Delete users from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'users', 'drawLoginBlock', '', 'right', 0, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Users' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>users_accounts`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>users_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `birth_date` date NULL DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address_2` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zip_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>users_groups`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>users_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_created_auto_approval', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_created_auto_approval', code, 'New user account created (auto approval)', 'Your account has been created and activated', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nYou may follow the link below to log into your account:\r\n<a href={SITE_URL}users/login>Login Here</a>\r\n\r\nP.S. Remember, we will never sell or pass to someone else your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_created_admin_approval', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_created_admin_approval', code, 'New user account created (admin approval)', 'Your account has been created (admin approval required)', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nAfter your registration is approved by administrator, you could log into your account with a following link:\r\n<a href={SITE_URL}users/login>Login Here</a>\r\n\r\nP.S. Remember, we will never sell or pass to someone else your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_created_email_confirmation', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_created_email_confirmation', code, 'New user account created (email confirmation)', 'Your account has been created (email confirmation required)', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nIn order to become authorized member, you will need to confirm your registration. You may follow the link below to access the confirmation page:\r\n<a href="{SITE_URL}users/confirmRegistration/code/{REGISTRATION_CODE}">Confirm Registration</a>\r\n\r\nP.S. Remember, we will never sell or pass to someone else your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_created_by_admin', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_created_by_admin', code, 'New user account created (by admin)', 'Your account has been created by site administrator', 'Dear <b>{FIRST_NAME} {LAST_NAME}!</b>\r\n\r\nThe {WEB_SITE} administrator has created a new account for you.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nYou will need to visit {WEB_SITE} website and reset the temporary password to a permanent password. Please follow the link below to log into your account: <a href={SITE_URL}users/login>Login</a>.\r\n\r\nEnjoy!\r\n-\r\nSincerely, \r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_created_notify_admin', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_created_notify_admin', code, 'New user account created (notify admin)', 'New user account has been created', 'Hello Admin!\r\n\r\nA new user has been registered on your site.\r\n\r\nThis email contains a user account details:\r\n\r\nName: {FIRST_NAME} {LAST_NAME}\r\nEmail: {USER_EMAIL}\r\nUsername: {USERNAME}\r\n\r\nP.S. Please check if it doesn''t require your approval for activation.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_approved_by_admin', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_approved_by_admin', code, 'New user account approved (by admin)', 'Your account has been approved', 'Dear <b>{FIRST_NAME} {LAST_NAME}!</b>\r\n\r\nCongratulations! This e-mail is to confirm that your registration at {WEB_SITE} has been approved.\r\n\r\nYou may now <a href={SITE_URL}users/login>log into</a> your account.\r\n\r\nThank you for choosing {WEB_SITE}.\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_password_forgotten', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_password_forgotten', code, 'Restore forgotten password', 'Forgotten Password', 'Hello!\r\n\r\nYou or someone else asked to restore your login info on our site:\r\n<a href={SITE_URL}users/login>{WEB_SITE}</a>\r\n\r\nYour new login:\r\n---------------\r\nUsername: {USERNAME}\r\nPassword: {PASSWORD}\r\n\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_password_changed_by_admin', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_password_changed_by_admin', code, 'Password changed (by admin)', 'Your password has been changed by admin', 'Hello <b>{FIRST_NAME} {LAST_NAME}!</b>\r\n\r\nYour password has been changed by administrator of the site:\r\n{WEB_SITE}\r\n\r\nBelow your new login info:\r\n-\r\nUsername: {USERNAME} \r\nPassword: {PASSWORD}\r\n\r\n-\r\nBest Regards,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'users_account_removed_by_user', 'users', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'users_account_removed_by_user', code, 'Account removed (by user)', 'Your account has been removed', 'Dear {USERNAME}!\r\n\r\nYour account has been successfully removed according to your request.\r\n\r\n-\r\nBest Regards,\r\nAdministration' FROM `<DB_PREFIX>languages`;