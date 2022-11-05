INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'tickets', 'Tickets', 'Tickets', 'This module allows you to manage your customer support tickets', '0.0.1', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'tickets', '', 'general_admin_email', '', 'General Admin Email', 'The general email address used to receive submitted information', 'email', '', '', '', '', 1),
(NULL, 'tickets', '', 'email_on_new_ticket', '1', 'Email on New Ticket', 'Specifies whether to send email to admin when new ticket was created', 'bool', '', '', '', '', 0),
(NULL, 'tickets', '', 'email_on_admin_reply', '1', 'Email on Admin Reply', 'Specifies whether to send email to user when admin replies to the ticket', 'bool', '', '', '', '', 0),
(NULL, 'tickets', '', 'email_on_member_reply', '1', 'Email on Member Reply', 'Specifies whether to send email to admin when mamber replies to the ticket', 'bool', '', '', '', '', 0),
(NULL, 'tickets', '', 'email_on_closing_ticket', '1', 'Email on Closing Ticket', 'Specifies whether to send email to admin when ticket was closed', 'bool', '', '', '', '', 0);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES
(NULL, 'tickets', 'tickets', 'edit', 'Edit tickets', 'Edit tickets on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES
(NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1),
(NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1),
(NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES
(NULL, 'tickets', 'tickets', 'delete', 'Delete tickets', 'Delete tickets from the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES
(NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1),
(NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1),
(NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES
(NULL, 'tickets', 'tickets', 'answer', 'Answer tickets', 'Answer tickets on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES
(NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1),
(NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1),
(NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES
(NULL, 0, 'pagelink', 'tickets', 'tickets/userManageTickets', '', 'top', 0, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Support' FROM `<DB_PREFIX>languages`;

DROP TABLE IF EXISTS `<DB_PREFIX>tickets`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>tickets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account_id` int(10) NOT NULL DEFAULT 0,
  `account_role` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `topic` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `departments` int(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0 - Opened, 1- Needs reply by Admin, 2 - Needs reply by User, 3 - Closed',
  `date_created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `<DB_PREFIX>tickets` (`id`, `account_id`, `account_role`, `topic`, `date_created`, `email`, `message`, `status`, `departments`) VALUES
(NULL, 1, 'user', 'Test', '2018-02-22 10:00', 'test1@example.com', 'Test 1', 0, 0),
(NULL, 2, 'user', 'Test', '2018-02-23 10:00', 'test2@example.com', 'Test 2', 0, 0);


DROP TABLE IF EXISTS `<DB_PREFIX>tickets_replies`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>tickets_replies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(10) NOT NULL DEFAULT 0,
  `file` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(10) NOT NULL DEFAULT 0,
  `account_role` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date_created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'new_ticket', 'tickets', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`)
SELECT NULL, 'new_ticket', code, 'New ticket created by user', 'User created a new ticket', 'Admin,\r\n\r\nNew ticket #{TICKET_ID} created.\r\n-------------------\r\n\Name: {FULL_NAME}\r\nEmail: {EMAIL}\r\nDept: {DEPT}\r\n\r\n{CONTENT}\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'closing_ticket', 'tickets', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`)
SELECT NULL, 'closing_ticket', code, 'Ticket status was changed to "closed"', 'Your ticket status was changed to "closed"', 'Dear <b>{FULL_NAME}!</b>\r\n\r\nYour ticket #{TICKET_ID} ({TOPIC}), has changed the status of "closed".\r\n-------------------\r\n\r\nSincerely, \r\nAdministration.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'admin_reply', 'tickets', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`)
SELECT NULL, 'admin_reply', code, 'New reply on ticket by admin', 'Admin replied to your ticket', 'Dear <b>{FULL_NAME}!</b>\r\n\r\nThe {WEB_SITE} administrator left a new message in your ticket #{TICKET_ID} ({TOPIC})\r\n\r\n{CONTENT}\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system. \r\n\r\nSincerely,\r\nAdministration.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES (NULL, 'member_reply', 'tickets', 1);
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`)
SELECT NULL, 'member_reply', code, 'New reply on ticket by member', 'Member replied to the ticket', 'Admin,\r\n\r\nMember left a new message in ticket #{TICKET_ID}\r\n-------------------\r\n\Name: {FULL_NAME}\r\nEmail: {EMAIL}\r\nDept: {DEPT}\r\n\r\n{CONTENT}\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.' FROM `<DB_PREFIX>languages`;
