
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'reports', 'Reports', 'Reports', 'This module provides a feature-rich and user-friendly web interface for managing reports', '0.0.3', 'icon.png', 1, 1, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));

INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`)VALUES
(NULL, 'reports', '', 'show_report_tabs', '1', 'Show Reports as Tabs', 'Specifies whether to show all reports as separate tabs', 'bool', '', '', '', '', 0),
(NULL, 'reports', '', 'enable_wysiwyg_editors', '1', 'Enable Rich Text Editors', 'Specifies whether to enable rich text (WYSIWYG) editors for textarea fields', 'bool', '', '', '', '', 0),
(NULL, 'reports', '', 'report_pdf_type', 'LandScape', 'Switch PDF orientation', 'Switch between Landscape and Portrait PDF orientation', 'enum', 'Portrait,LandScape', '', '', '', 0),
(NULL, 'reports', '', 'report_calculate', 'readonly', 'Calculated Fields Access', 'Define access to edit calculated fields', 'enum', 'readonly, writable', '', '', '', 0);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'reports', 'add', 'Add Reports', 'Add Reports on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'reports', 'edit', 'Edit Reports', 'Edit Reports on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'reports', 'delete', 'Delete Reports', 'Delete Reports from the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'weeklyschedule', 'view', 'View Weekly Schedule Reports', 'View Weekly Schedule Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'weeklyschedule', 'edit', 'Edit Weekly Schedule Reports', 'Edit Weekly Schedule Reports on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'weeklyschedule', 'approve', 'Approve Weekly Schedule Reports', 'Approve Weekly Schedule Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'projectupdate', 'view', 'View Project Update Reports', 'View Project Update Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'projectupdate', 'edit', 'Edit Project Update Reports', 'Edit Project Update Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'projectupdate', 'approve', 'Approve Project Update Reports', 'Approve Project Update Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'clientprojectfin1', 'view', 'View Client Project Financing 1 Reports', 'View Client Project Financing 1 Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'clientprojectfin1', 'edit', 'Edit Client Project Financing 1 Reports', 'Edit Client Project Financing 1 Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'clientprojectfin1', 'approve', 'Approve Client Project Financing 1 Reports', 'Approve Client Project Financing 1 Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'clientprojectfin2', 'view', 'View Client Project Financing 2 Reports', 'View Client Project Financing 2 Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'clientprojectfin2', 'edit', 'Edit Client Project Financing 2 Reports', 'Edit Client Project Financing 2 Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'clientprojectfin2', 'approve', 'Approve Client Project Financing 2 Reports', 'Approve Client Project Financing 2 Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'design', 'view', 'View Design Reports', 'View Design Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'design', 'edit', 'Edit Design Reports', 'Edit Design Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'design', 'approve', 'Approve Design Reports', 'Approve Design Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'additional', 'view', 'View Additional Work Reports', 'View Additional Work Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'additional', 'edit', 'Edit Additional Work Reports', 'Edit Additional Work Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'reports', 'additional', 'approve', 'Approve Additional Work Reports', 'Approve Additional Work Reports in the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1);


INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, 0, '', 'reports', 'reports.png', 0, 1, 6);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Reports' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'reports' AND bm.parent_id = 0), '<SITE_BO_URL>modules/settings/code/reports', 'reports', '', 0, 1, 0);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Settings' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'reports' AND bm.parent_id = 0), 'reportsProjects/manage', 'reports', '', 0, 1, 1);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Projects' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'reports' AND bm.parent_id = 0), 'reportsTypes/manage', 'reports', '', 0, 1, 2);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Report Types' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>reports_entities`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>reports_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL DEFAULT '0',
  `type_id` int(10) NOT NULL DEFAULT '0',
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `related_report` int(10) NOT NULL DEFAULT '0',
  `sort_order` smallint(5) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`) USING BTREE,
  KEY `type_id` (`type_id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22;

INSERT INTO `<DB_PREFIX>reports_entities` VALUES 
(1,1,"1","","0","1","1"),
(2,1,"2","","0","0","1"),
(3,1,"3","","24","2","1"),
(4,1,"4","","0","3","1"),
(5,1,"5","","0","4","1"),
(6,1,"7","","0","5","1"),
(7,2,"1","","0","1","1"),
(8,2,"2","","0","0","1"),
(9,2,"3","","0","2","1"),
(10,2,"4","","0","3","1"),
(11,3,"2","","0","0","1"),
(12,3,"4","","0","3","1"),
(13,3,"5","","0","4","1"),
(14,4,"1","","0","1","1"),
(15,4,"2","","0","0","1"),
(16,4,"5","","0","4","1"),
(17,4,"7","","0","5","1"),
(18,4,"1","","0","1","1"),
(19,4,"2","","0","0","1"),
(20,5,"2","","0","0","1"),
(21,6,"2","","0","1","1");


DROP TABLE IF EXISTS `<DB_PREFIX>reports_entity_items`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>reports_entity_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(10) NOT NULL DEFAULT '0',
  `field_1` text COLLATE utf8_unicode_ci NOT NULL,
  `field_2` text COLLATE utf8_unicode_ci NOT NULL,
  `field_3` text COLLATE utf8_unicode_ci NOT NULL,
  `field_4` text COLLATE utf8_unicode_ci NOT NULL,
  `field_5` text COLLATE utf8_unicode_ci NOT NULL,
  `field_6` text COLLATE utf8_unicode_ci NOT NULL,
  `field_7` text COLLATE utf8_unicode_ci NOT NULL,
  `field_8` text COLLATE utf8_unicode_ci NOT NULL,
  `field_9` text COLLATE utf8_unicode_ci NOT NULL,
  `field_10` text COLLATE utf8_unicode_ci NOT NULL,
  `field_11` text COLLATE utf8_unicode_ci NOT NULL,
  `field_12` text COLLATE utf8_unicode_ci NOT NULL,
  `field_13` text COLLATE utf8_unicode_ci NOT NULL,
  `field_14` text COLLATE utf8_unicode_ci NOT NULL,
  `field_15` text COLLATE utf8_unicode_ci NOT NULL,
  `field_16` text COLLATE utf8_unicode_ci NOT NULL,
  `field_17` text COLLATE utf8_unicode_ci NOT NULL,
  `field_18` text COLLATE utf8_unicode_ci NOT NULL,
  `field_19` text COLLATE utf8_unicode_ci NOT NULL,
  `field_20` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - pending, 1 - approved',
  PRIMARY KEY (`id`),
  KEY `enity_id` (`entity_id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=82;

INSERT INTO `<DB_PREFIX>reports_entity_items` VALUES
(1,1,"1","2014-12-31","2015-02-13","Project Duration","Days Delayed: 25","","","","","","","","","","","","","","","","1"),
(2,1,"2","2014-12-21","2014-12-26","Continue moving uncles, moving air,","Complete","5% upon start of opening for windows","","","","","","","","","","","","","","","1"),
(3,1,"3","2014-12-28","2015-01-02","","Storm","10% upon waterproofing for windows and fixing up walls that were opened","","","","","","","","","","","","","","","1"),
(4,1,"4","2015-01-04","2015-01-09","Sealing the roof","Complete","5% upon installation of windows","","","","","","","","","","","","","","","1"),
(5,1,"5","2015-01-11","2015-01-16","Storage building blocks on-line panels","Roof needs to be moved","5% s upon the start of enlarging porch","","","","","","","","","","","","","","","1"),
(6,1,"6","2015-01-18","2015-01-23","Ceramic flooring, continued construction of the storage, preparation electricity","","25% upon opening for roof, waterproofing area and the start of building the stairs","","","","","","","","","","","","","","","1"),
(7,1,"7","2015-01-25","2015-01-30","Continued storage, thin building, railway fence, building a pergola, opening windows","Have Orel come see before the deck is put down","10% upon completion of stairs and installation of railing","","","","","","","","","","","","","","","1"),
(8,1,"8","2015-02-01","2015-02-06","Painting rooms, completeness around windows, Alumtriss Last-inspection of dimensions","","5% upon finishing porch and all other finishes in house","","","","","","","","","","","","","","","1"),
(9,1,"9","2015-02-08","2015-02-13","Expanding train beginning balcony windows,","Time for merpeset is based on engineer","10% upon completion of all work","","","","","","","","","","","","","","","1"),
(10,1,"10","2015-02-15","2015-02-20","Open stairs Building Stairs","","","","","","","","","","","","","","","","","1"),
(11,1,"14","2015-02-22","2015-02-26","End of the stairs, and train rail","","5% s upon the start of enlarging porch","","","","","","","","","","","","","","","1"),
(12,1,"15","2015-03-01","2015-03-06","Paint finishes, lighting and installation of ventilated roof","","","","","","","","","","","","","","","","","1"),
(13,1,"16","2015-07-29","2015-08-05","extra room","","","","","","","","","","","","","","","","","1"),
(14,2,"2015-09-01","10","2500.00","0","0.00","","2500.00","0","https://www.youtube.com/watch?v=sVZV4TW2b1E","2500.00","","","","","","","","","","","1"),
(15,2,"2015-05-03","12","3000.00","0","0.00","","3000.00","2208","","792.00","","","","","","","","","","","1"),
(16,2,"2015-06-11","6","1500.00","0","0.00","","1500.00","506.72","","993.28","","","","","","","","","","","1"),
(17,2,"2015-11-01","27","6750.00","0","0.00","","6750.00","6647.08","","102.92","","","","","","","","","","","1"),
(18,2,"2016-06-01","10","2500.00","0","0.00","","2500.00","1668.52","","831.48","","","","","","","","","","","1"),
(19,2,"2015-02-01","15","3750.00","0","0.00","","3750.00","0","","3750.00","","","","","","","","","","","1"),
(20,3,"Deposit","25","325.00","2015-06-09","100","225.00","Zero amount paid","","","","","","","","","","","","","","1"),
(21,3,"Demolition","7","91.00","2015-06-12","0","91.00","","","","","","","","","","","","","","","1"),
(22,3,"Framing of walls","7","91.00","","45","46.00","","","","","","","","","","","","","","","1"),
(23,3,"Electric and plumbing lines","10","130.00","","100","30.00","","","","","","","","","","","","","","","1"),
(24,3,"Framing of ceiling ","5","65.00","","60","5.00","","","","","","","","","","","","","","","1"),
(25,3,"Tiling","10","130.00","","12","118.00","","","","","","","","","","","","","","","1"),
(26,4,"Supplier 1","Notes","2015-07-01","2000","500","2500.00","","500","","150","","65","","100","815.00","1685.00","","","","","1"),
(27,4,"Demolition Supplier","Demolition","2015-06-13","1000","0","1000.00","","0","","0","","0","","12","12.00","988.00","Demolition comment","","","","1"),
(28,5,"For later","<p><strong>1.</strong><span>Bathroom 303 framed mirror to be made of iron</span></p>\n","96gb2txah4.jpg","","","","","","","","","","","","","","","","","","1"),
(29,5,"Foyer/Bathroom Tile ","- We had discussed potentially using as a \"carpet\" for the foyer.\n\n<p><strong>1</strong><span>. Just a already made sheet</span></p>\n<p><strong><span>2</span></strong><span>. A design in the center of the foyer and have the border of the foyer area with a tri color strip (a grayish blue &amp; pinkish maroon).</span></p>\n<p><span> </span></p>\n<p><span>- We had discussed using this as a backsplash for the kitchen or behind the range.</span></p>\n<p><span> </span></p>\n<p><span>- Using this in the bathrooms on the floor/wet walls and depending on the cost we  can incorporate a glossy/creamy textured square tile for the rest of the walls.</span></p>","t8j9bpqaon.png","qcjz57capz.png","rylfvdmoea.png","","","","","","","","","","","","","","","","1"),
(30,5,"Patio/ Porch  Tile","<p><strong><span>- </span></strong><span>We had<strong> </strong>discussed potentially using the brick in a herringbone pattern around the house or on the porches.</span></p>\n<p><span> </span></p>\n<p><span>- We had discussed using a mix of </span><span>pattern concrete tile as a large border around the house and then larger area of single matching color tile. (this can be down on the roof as well)</span></p>\n<p><span> </span></p>\n<p><span>- <strong>We have an option to incorporate some of Vered\'s painted tiles for </strong></span><strong><span>potentially a lower costs</span></strong></p>","8dt8n0wdu6.png","q0py4ofqle.png","idqcrcak4q.png","","","","","","","","","","","","","","","","1"),
(31,6,"For later","100","","50","50.00","For later comment","","","","","","","","","","","","","","","1"),
(32,7,"1","2015-07-20","2015-07-21","2015-07-20","","","","","","","","","","","","","","","","","1"),
(33,8,"2015-04-24","70","4130.00","0","0.00","empty","4130.00","0","4130.00","","","","","","","","","","","","1"),
(34,8,"2015-09-01","30","1770.00","0","0.00","empty","1770.00","0","1770.00","","","","","","","","","","","","1"),
(35,9,"Signing of contract and start of work","40","12576.80","2015-07-14","12576.90","-0.10","","","","","","","","","","","","","","","1"),
(36,9,"Upon completion of demolition, tiling, & plaster","40","12576.80","","12576.80","0.00","Need to update date paid","","","","","","","","","","","","","","1"),
(37,9,"Upon completion of project & electric work","20","6288.40","","0","6288.40","","","","","","","","","","","","","","","1"),
(38,10,"Shayish Yashir- kitchen countertop","Materials","2015-07-12","23200","0","23200.00","2015-07-21","11600","2015-08-23","6400","2015-08-23","5200","","0","23200.00","0.00","<p>Caeserstone 5111 + 2 Frankie 60cm sinks</p>\n<p>Payment #3 is a gift from JD and was paid for by JD</p>","","","","1"),
(39,10,"Nagarut Abukrat- Kitchen","Carpentry","2015-07-14","25157","2124","27281.00","2015-07-21","13617","2015-08-13","11540","","0","","0","25157.00","2124.00","<p>Kitchen addition on one side and changing of doors on the other side</p>\n<p>Extra for: Kitchen side panel by fridge and panel under bar</p>","","","","1"),
(40,10,"Beit Karamika- tiles","Materials","2015-07-13","9259","0","9259.00","2015-07-13","9259","","0","","0","","0","9259.00","0.00","Wood floor tile, base molding, bathroom tile, kitchen backsplash","","","","1"),
(41,10,"Cabasso- Lighting","Materials","2015-07-20","0","0","0","","0","","0","","0","","0","0","0","","","","","1"),
(42,11,"2015-07-06","10","7500.00","0","0.00","Upon signing","7500.00","0","7500.00","","","","","","","","","","","","0"),
(43,11,"2015-07-06","10","7500.00","0","0.00","upon receiving first draft of plans","7500.00","0","7500.00","","","","","","","","","","","","0"),
(44,11,"2015-07-06","20","15000.00","0","0.00","upon signing of space plans","15000.00","0","15000.00","","","","","","","","","","","","0"),
(45,11,"2015-07-06","10","7500.00","0","0.00","upon completion of gas, electric, & plumbing plans","7500.00","0","7500.00","","","","","","","","","","","","0"),
(46,11,"2015-07-06","10","7500.00","0","0.00","upon completion of kitchen plans","7500.00","0","7500.00","","","","","","","","","","","","0"),
(47,11,"2015-07-06","15","11250.00","0","0.00","upon completion of bid book","11250.00","0","11250.00","","","","","","","","","","","","0"),
(48,11,"2015-07-06","10","7500.00","0","0.00","upon selecting a contractor for the project","7500.00","0","7500.00","","","","","","","","","","","","0"),
(49,11,"2015-07-06","5","3750.00","0","0.00","upon selection of all non-movable materials","3750.00","0","3750.00","","","","","","","","","","","","0"),
(50,11,"2015-07-06","5","3750.00","0","0.00","upon selection of all furnishing (not including art)","3750.00","0","3750.00","","","","","","","","","","","","0"),
(51,11,"2015-07-06","5","3750.00","0","0.00","upon completion of entire project and acceptance by client","3750.00","0","3750.00","","","","","","","","","","","","0"),
(52,12,"Basement Dig","Dig","2015-06-01","25000","5000","30000.00","","0","","0","","0","","0","0.00","30000.00","Extra for additional dig","","","","1"),
(53,13,"afasdfasdf","","","eb9i37bzg7.jpg","","","","","","","","","","","","","","","","","1"),
(54,15,"2015-07-06","30","56640.00","0","0.00","upon signing agreement with JD","56640.00","0","56640.00","","","","","","","","","","","","0"),
(55,15,"2015-07-06","40","75520.00","0","0.00","mid-way through the project (1 year after starting)","75520.00","0","75520.00","","","","","","","","","","","","0"),
(56,15,"2015-07-06","30","56640.00","0","0.00","upon substantial completion of project and acceptance by client","56640.00","0","56640.00","","","","","","","","","","","","0"),
(57,16,"2015-05-01","17","48144.00","0","0.00","May & June","48144.00","48144.00","0.00","","","","","","","","","","","","1"),
(58,17,"2015-07-01","17","48144.00","0","0.00","July & August","48144.00","48144.00","0.00","","","","","","","","","","","","1"),
(59,18,"2015-09-01","17","48144.00","0","0.00","September & October","48144.00","0","48144.00","","","","","","","","","","","","1"),
(60,19,"2015-07-26","30","2124.00","0","0.00","0","2124.00","0","2124.00","","","","","","","","","","","","1"),
(61,19,"2015-07-26","0","0.00","30","3540.00","0","3540.00","0","3540.00","","","","","","","","","","","","1"),
(62,19,"2015-07-26","0","","0","0","0","0","0","0","","","","","","","","","","","","1"),
(63,19,"2015-07-26","0","","0","0","0","0","0","0","","","","","","","","","","","","1"),
(64,19,"2015-07-26","0","","0","0","0","0","0","0","","","","","","","","","","","","1"),
(65,19,"2015-07-26","0","","0","0","0","0","0","0","","","","","","","","","","","","1"),
(66,20,"2015-11-01","17","48144.00","0","0.00","November & December","48144.00","0","48144.00","","","","","","","","","","","","1"),
(67,20,"2016-01-01","17","48144.00","0","0.00","January & February","48144.00","0","48144.00","","","","","","","","","","","","1"),
(68,20,"2015-07-06","15","42480.00","0","0.00","Upon completion of project","42480.00","0","42480.00","","","","","","","","","","","","1"),
(69,21,"2015-09-09","20","10620.00","20","9440.00","Deposit for design & management","20060.00","0","20060.00","","","","","","","","","","","","1"),
(70,21,"2015-09-09","0","0.00","10","4720.00","Upon receiving first draft of plans","4720.00","0","4720.00","","","","","","","","","","","","1"),
(71,21,"2015-09-09","0","0.00","20","9440.00","Upon signing of space plans","9440.00","0","9440.00","","","","","","","","","","","","1"),
(72,21,"2015-09-09","0","0.00","10","4720.00","Upon completion of gas, electric, & plumbing plans","4720.00","0","4720.00","","","","","","","","","","","","1"),
(73,21,"2015-09-09","0","0.00","10","4720.00","Upon completion of kitchen plans","4720.00","0","4720.00","","","","","","","","","","","","1"),
(74,21,"2015-09-09","0","0.00","10","4720.00","Upon selection of all non-movable materials","4720.00","0","4720.00","","","","","","","","","","","","1"),
(75,21,"2015-09-09","0","0.00","10","4720.00","Upon completion of carpentry plans","4720.00","0","4720.00","","","","","","","","","","","","1"),
(76,21,"2015-09-09","0","0.00","10","4720.00","Upon substantial completion of the design phase (see section 3.ix of the contract)","4720.00","0","4720.00","","","","","","","","","","","","1"),
(77,21,"2015-09-09","10","5310.00","0","0.00","Upon completion of bid book for tender","5310.00","0","5310.00","","","","","","","","","","","","1"),
(78,21,"2015-09-09","10","5310.00","0","0.00","Upon signing of contract with contractor","5310.00","0","5310.00","","","","","","","","","","","","1"),
(79,21,"2015-09-09","20","10620.00","0","0.00","Upon start of construction","10620.00","0","10620.00","","","","","","","","","","","","1"),
(80,21,"2015-09-09","20","10620.00","0","0.00","60 calendar days fro the start of construction","10620.00","0","10620.00","","","","","","","","","","","","1"),
(81,21,"2015-09-09","20","10620.00","0","0.00","Upon completion of the \"punch-list\" (see section 6.b.xvi of the contract)","10620.00","0","10620.00","","","","","","","","","","","","1");


DROP TABLE IF EXISTS `<DB_PREFIX>reports_entities_comments`;
CREATE TABLE `<DB_PREFIX>reports_entities_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `comment_text` text COLLATE utf8_unicode_ci NOT NULL,
  `author` tinyint(4) NOT NULL,
  `posted_date` date NULL DEFAULT NULL,
  `display_date` date NULL DEFAULT NULL,
  `changed_date` date NULL DEFAULT NULL,
  `image_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_4` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `<DB_PREFIX>reports_projects`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>reports_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_price` decimal(11,2) NOT NULL DEFAULT '0.00',  
  `client_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `client_address` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `client_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `client_phone` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  `started_at` date NULL DEFAULT NULL,
  `finished_at` date NULL DEFAULT NULL,
  `sort_order` smallint(5) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `contract_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `project_manage_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `project_design_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4;

INSERT INTO `<DB_PREFIX>reports_projects` (`id`, `project_name`, `project_price`, `client_name`, `client_address`, `client_email`, `client_phone`, `created_at`, `started_at`, `finished_at`, `sort_order`, `is_active`, `contract_price`, `project_manage_price`, `project_design_price`) VALUES
(1, 'Report #1', '25000.00', 'David', 'Ness street, 1/33', 'project1@example.com', '356758890', '2015-07-23 06:12:22', '2015-06-15', '2015-06-20', 0, 1, '1300.00', '25000.00', '0.00'),
(2, 'Report #2', '5900.00', 'Seth & John Smith', '6 Roas map, 99/2, Tower, Apartment 11', 'project2@example.com', '', '2015-07-23 06:13:28', '2015-05-01', NULL, 1, 1, '31442.00', '0.00', '5900.00'),
(3, 'Report #3', '100300.00', 'Sharon Frank', 'st. Gabiron, 10', 'project3@example.com', '0547645168', '2015-09-09 09:11:27', '2015-09-08', NULL, 0, 1, '0.00', '53100.00', '47200.00');


DROP TABLE IF EXISTS `<DB_PREFIX>reports_types`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>reports_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `code` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `template_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `js_event_handler` text COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

INSERT INTO `<DB_PREFIX>reports_types` (`id`, `name`, `code`, `template_name`, `js_event_handler`, `is_active`) VALUES
(1, 'Weekly Schedule', 'weeklyschedule', 'weekly_schedule.tpl', '', 1),
(2, 'Project Update', 'projectupdate', 'project_update.tpl', 'function ProjectUpdateCalculate(){\r\n		var percentIntManage = $(''#frmReportsTypeItemsAdd_field_2'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_2'').val());\r\n		var percentIntDesign = $(''#frmReportsTypeItemsAdd_field_4'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_4'').val());\r\n		\r\n		var percentManage = parseFloat(percentIntManage) / 100;\r\n		var partPriceManage = projectManagePrice * percentManage;\r\n		if(partPriceManage > projectManagePrice){\r\n			partPriceManage = projectManagePrice;\r\n		}\r\n		\r\n		var percentDesign = parseFloat(percentIntDesign) / 100;\r\n		var partPriceDesign = projectDesignPrice * percentDesign;\r\n		if(partPriceDesign > projectDesignPrice){\r\n			partPriceDesign = projectDesignPrice;\r\n		}\r\n		\r\n		\r\n		$(''#frmReportsTypeItemsAdd_field_3'').val(isNaN(partPriceManage) ? 0 : parseFloat(partPriceManage).toFixed(2));\r\n		$(''#frmReportsTypeItemsAdd_field_5'').val(isNaN(partPriceDesign) ? 0 : parseFloat(partPriceDesign).toFixed(2));\r\n		\r\n		var amount = parseFloat(partPriceManage) + parseFloat(partPriceDesign);\r\n		$(''#frmReportsTypeItemsAdd_field_7'').val(isNaN(amount) ? 0 : parseFloat(amount).toFixed(2));\r\n		\r\n		var paid = $(''#frmReportsTypeItemsAdd_field_8'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_8'').val());\r\n		\r\n		var balance = amount - paid;\r\n		$(''#frmReportsTypeItemsAdd_field_9'').val(isNaN(balance) ? 0 : parseFloat(balance).toFixed(2));\r\n    }\r\n	function getPercent(){\r\n	\r\n		var incomManage = $(''#frmReportsTypeItemsAdd_field_3'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_3'').val());\r\n		if(incomManage > projectManagePrice){\r\n			incomManage = projectManagePrice;\r\n			$(''#frmReportsTypeItemsAdd_field_3'').val(projectManagePrice);\r\n		}\r\n		var percentManage = parseFloat(incomManage) / parseFloat(projectManagePrice) * 100;\r\n		percentManage = Math.round(percentManage);\r\n		if(percentManage > 100){\r\n			percentManage = 100;\r\n		}\r\n		\r\n		var incomDesign = $(''#frmReportsTypeItemsAdd_field_5'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_5'').val());\r\n		if(incomDesign > projectDesignPrice){\r\n			incomDesign = projectDesignPrice;\r\n			$(''#frmReportsTypeItemsAdd_field_5'').val(projectDesignPrice);\r\n		}\r\n		var percentDesign = parseFloat(incomDesign) / parseFloat(projectDesignPrice) * 100;\r\n		percentDesign = Math.round(percentDesign);\r\n		if(percentDesign > 100){\r\n			percentDesign = 100;\r\n		}\r\n		\r\n		$(''#frmReportsTypeItemsAdd_field_2'').val(isNaN(percentManage) ? 0 : parseFloat(percentManage));\r\n		$(''#frmReportsTypeItemsAdd_field_4'').val(isNaN(percentDesign) ? 0 : parseFloat(percentDesign));\r\n		\r\n		var amount = parseFloat($(''#frmReportsTypeItemsAdd_field_3'').val()) + parseFloat($(''#frmReportsTypeItemsAdd_field_5'').val());\r\n		$(''#frmReportsTypeItemsAdd_field_7'').val(isNaN(amount) ? 0 : parseFloat(amount).toFixed(2));\r\n		\r\n		var paid = $(''#frmReportsTypeItemsAdd_field_8'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_8'').val());\r\n		var balance = amount - paid;\r\n		$(''#frmReportsTypeItemsAdd_field_9'').val(isNaN(balance) ? 0 : parseFloat(balance).toFixed(2));\r\n	}\r\n\r\n	ProjectUpdateCalculate();\r\n	var paid = $(''#frmReportsTypeItemsAdd_field_5'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_5'').val());\r\n	$(''#frmReportsTypeItemsAdd_field_5'').val(isNaN(paid) ? 0 : parseFloat(paid).toFixed(2));\r\n		\r\n   $(''#frmReportsTypeItemsAdd_field_2'').keyup(function() {\r\n		ProjectUpdateCalculate();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_3'').keyup(function() {\r\n		getPercent();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_4'').keyup(function() {\r\n		ProjectUpdateCalculate();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_5'').keyup(function() {\r\n		getPercent();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_8'').keyup(function() {\r\n		ProjectUpdateCalculate();\r\n    });', 1),
(3, 'Contractor Financing', 'constractor', 'client_project_financing_1.tpl', '	function ContractorCalculate(){\r\n		var percent = $(''#frmReportsTypeItemsAdd_field_2'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_2'').val());\r\n	\r\n		var percentContractor = contractorPrice * (parseFloat(percent) / 100);\r\n		if(percentContractor > contractorPrice){\r\n			percentContractor = contractorPrice;\r\n		}\r\n		$(''#frmReportsTypeItemsAdd_field_3'').val(isNaN(percentContractor) ? 0 :parseFloat(percentContractor).toFixed(2));\r\n		var originalPrice = $(''#frmReportsTypeItemsAdd_field_3'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_3'').val());\r\n		var paid = $(''#frmReportsTypeItemsAdd_field_5'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_5'').val());\r\n		var balance = parseFloat(originalPrice) - parseFloat(paid);\r\n		$(''#frmReportsTypeItemsAdd_field_6'').val(isNaN(balance) ? 0 :parseFloat(balance).toFixed(2));\r\n	}   \r\n	function ContractorCalculatePercent(){\r\n		var incom = $(''#frmReportsTypeItemsAdd_field_3'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_3'').val());\r\n		if(incom > contractorPrice){\r\n			incom = contractorPrice;\r\n			$(''#frmReportsTypeItemsAdd_field_3'').val(contractorPrice);\r\n		}\r\n		var percent = parseFloat(incom) / parseFloat(contractorPrice) * 100;\r\n		percent = Math.round(percent);	\r\n		if(percent > 100){\r\n			percent = 100;\r\n		}\r\n		$(''#frmReportsTypeItemsAdd_field_2'').val(isNaN(percent) ? 0 :parseFloat(percent));\r\n		var originalPrice = $(''#frmReportsTypeItemsAdd_field_3'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_3'').val());\r\n		var paid = $(''#frmReportsTypeItemsAdd_field_5'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_5'').val());\r\n		\r\n		var balance = parseFloat(originalPrice) - parseFloat(paid);\r\n		$(''#frmReportsTypeItemsAdd_field_6'').val(isNaN(balance) ? 0 :parseFloat(balance).toFixed(2));\r\n		\r\n	}  \r\n	ContractorCalculate();\r\n	\r\n	$(''#frmReportsTypeItemsAdd_field_2'').keyup(function() {\r\n		ContractorCalculate();\r\n    });\r\n		$(''#frmReportsTypeItemsAdd_field_3'').keyup(function() {\r\n		ContractorCalculatePercent();\r\n    });\r\n    $(''#frmReportsTypeItemsAdd_field_5'').keyup(function() {\r\n		ContractorCalculate();\r\n    });', 1),
(4, 'Sub-Contractor Financing', 'subconstractor', 'client_project_financing_2.tpl', 'function CF2(){\r\n		var originalPrice = $(''#frmReportsTypeItemsAdd_field_4'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_4'').val());\r\n		var extraPrice = $(''#frmReportsTypeItemsAdd_field_5'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_5'').val());\r\n		var total = originalPrice + extraPrice;\r\n        $(''#frmReportsTypeItemsAdd_field_6'').val(isNaN(total) ? 0 :parseFloat(total).toFixed(2));\r\n		\r\n		var payment1 = $(''#frmReportsTypeItemsAdd_field_8'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_8'').val());\r\n		var payment2 = $(''#frmReportsTypeItemsAdd_field_10'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_10'').val());\r\n		var payment3 = $(''#frmReportsTypeItemsAdd_field_12'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_12'').val());\r\n		var payment4 = $(''#frmReportsTypeItemsAdd_field_14'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_14'').val());\r\n		\r\n		var amount = payment1 + payment2 + payment3 + payment4;\r\n		$(''#frmReportsTypeItemsAdd_field_15'').val(isNaN(amount) ? 0 :parseFloat(amount).toFixed(2));\r\n		var balance = total - amount;\r\n		$(''#frmReportsTypeItemsAdd_field_16'').val(isNaN(balance) ? 0 :parseFloat(balance).toFixed(2));\r\n	}\r\n	$(''#frmReportsTypeItemsAdd_field_4'').keyup(function() {\r\n		CF2();\r\n    });\r\n    $(''#frmReportsTypeItemsAdd_field_5'').keyup(function() {\r\n		CF2();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_8'').keyup(function() {\r\n		CF2();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_10'').keyup(function() {\r\n		CF2();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_12'').keyup(function() {\r\n		CF2();\r\n    });\r\n	$(''#frmReportsTypeItemsAdd_field_14'').keyup(function() {\r\n		CF2();\r\n    });', 1),
(5, 'Design', 'design', 'design.tpl', '', 1),
(6, 'Additional Work', 'additional', 'additional.tpl', 'function AdditionalCalculate()\r\n	{\r\n\r\n		var price = $(''#frmReportsTypeItemsAdd_field_2'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_2'').val());\r\n		var paid = $(''#frmReportsTypeItemsAdd_field_4'').val() == '''' ? 0 : parseFloat($(''#frmReportsTypeItemsAdd_field_4'').val());\r\n        var balance = parseFloat(price) - parseFloat(paid);\r\n        $(''#frmReportsTypeItemsAdd_field_5'').val(isNaN(balance) ? 0 :parseFloat(balance).toFixed(2));\r\n	}\r\n	$(''#frmReportsTypeItemsAdd_field_2'').keyup(function() {\r\n		AdditionalCalculate();\r\n    });\r\n    $(''#frmReportsTypeItemsAdd_field_4'').keyup(function() {\r\n		AdditionalCalculate();\r\n    });', 1);


DROP TABLE IF EXISTS `<DB_PREFIX>reports_type_items`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>reports_type_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(10) NOT NULL DEFAULT '0',
  `field_title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `field_type` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'textbox',
  `field_required` tinyint(1) NOT NULL DEFAULT '0',
  `field_tooltip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `field_validation_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `field_width` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `field_height` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `field_maxlength` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '255',
  `field_placeholder` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `field_default_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `field_prepend_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `field_append_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `show_on_mainview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `internal_use` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint(6) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

INSERT INTO `<DB_PREFIX>reports_type_items` (`id`, `type_id`, `field_title`, `field_type`, `field_required`, `field_tooltip`, `readonly`, `field_validation_type`, `field_width`, `field_height`, `field_maxlength`, `field_placeholder`, `field_default_value`, `field_prepend_code`, `field_append_code`, `show_on_mainview`, `internal_use`, `sort_order`, `is_active`) VALUES
(1, 1, 'Week', 'textbox', 1, '', 0, 'numeric', '80px', '', '3', '', '1', '', '.&nbsp;&nbsp;&nbsp;&nbsp;', 1, 1, 0, 1),
(2, 1, 'Date From', 'datetime', 1, '', 0, 'date', '', '', '10', '', '', '', '', 1, 1, 1, 1),
(3, 1, 'Date To', 'datetime', 1, '', 0, 'date', '', '', '10', '', '', '', '', 1, 1, 2, 1),
(4, 1, 'What''s Happening', 'textarea', 0, '', 0, '', '', '', '255', '', '', '', '',  1, 1, 3, 1),
(5, 1, 'Comments', 'textarea', 0, '', 0, '', '', '', '512', '', '', '', '', 1, 1, 4, 1),
(6, 1, 'Payments to Contractor', 'textarea', 0, '', 0, '', '', '', '512', '', '', '', '', 1, 1, 5, 1),
(7, 2, 'Due Date', 'datetime', 1, '', 0, 'date', '', '', '10', '', '', '', '', 1, 1, 0, 1),
(8, 2, 'Percent of Management Price', 'textbox', 1, '', 0, 'percent', '65px', '', '3', '', '0', '', '%', 1, 0, 2, 1),
(9, 2, 'Milestone', 'textbox', 1, '', 0, '', '', '', '100', '', '', '', '', 1, 1, 5, 1),
(10, 2, 'Amount Due', 'textbox', 1, 'not including VAT', 1, 'float', '100px', '', '10', '', '', '$', '', 1, 1, 3, 1),
(11, 2, 'Amount Paid', 'textbox', 1, 'including VAT', 0, 'float', '100px', '', '10', '', '', '$', '', 1, 1, 4, 1),
(12, 2, 'Video Link', 'videoLink', 0, '', 0, 'url', '200px', '100px', '255', '', '', '', '', 1, 1, 5, 1),
(13, 3, 'Item', 'textbox', 1, '', 0, '', '', '', '10', '', '', '', '', 1, 1, 0, 1),
(14, 3, 'Percent of Contract', 'textbox', 0, '', 1, 'percent', '65px', '', '3', '', '0', '', '%', 1, 1, 1, 1),
(15, 3, 'Original Price', 'textbox', 0, '', 0, 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 2, 1),
(16, 3, 'Date Paid', 'datetime', 0, '', 0, 'date', '', '', '10', '', '', '', '', 1, 1, 3, 1),
(17, 3, 'Amount Paid', 'textbox', 0, '', 0, 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 4, 1),
(18, 3, 'Balance', 'textbox', 0, 'CALCULATED	: Original Price  - Paid', 1, 'float', '100px', '', '10', '', '', '$', '', 1, 1, 5, 1),
(19, 3, 'Comments', 'textarea', 0, '', 0, '', '', '', '1024', '', '', '', '', 1, 1, 6, 1),
(20, 3, 'Pdf Link', 'fileUpload', 0, '', 0, '', '', '', '', '', '', '', '', 1, 1, 7, 1),
(21, 4, 'Supplier Name', 'textbox', 1, 'Supplier / Sub-Contractor Name', 0, '', '', '', '255', '', '', '', '', 1, 1, 0, 1),
(22, 4, 'Item', 'textbox', 1, '', 0, '', '', '', '10', '', '', '', '', 1, 1, 1, 1),
(23, 4, 'Date Ordered', 'datetime', 0, '', 0, 'date', '', '', '10', '', '', '', '', 1, 1, 3, 1),
(24, 4, 'Original Price', 'textbox', 1, '', 0, 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 4, 1),
(25, 4, 'Extra Price', 'textbox', 1, '', 0, 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 5, 1),
(26, 4, 'Total (incl. extras)', 'textbox', 0, '', 1, 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 7, 1),
(28, 4, 'Amount Paid', 'textbox', 0, '', 1, 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 18, 1),
(29, 4, 'Balance', 'textbox', 0, '', 1, 'float', '100px', '', '10', '', '', '$', '', 1, 1, 19, 1),
(30, 4, 'Comments', 'textarea', 0, '', 0, '', '', '', '1024', '', '', '', '', 1, 1, 20, 1),
(31, 5, 'Product', 'textbox', 1, '', 0, '', '', '', '255', '', '', '', '', 1, 1, 0, 1),
(33, 5, 'Product Information', 'textarea', 0, '', 0, '', '', '', '4096', '', '', '', '', 1, 1, 2, 1),
(34, 5, 'Reference Image 1', 'imageUpload', 0, '', 0, '', '', '', '', '', '', '', '', 1, 1, 3, 1),
(35, 5, 'Reference Image 2', 'imageUpload', 0, '', 0, '', '', '', '', '', '', '', '', 1, 1, 4, 1),
(36, 5, 'Reference Image 3', 'imageUpload', 0, '', 0, '', '', '', '', '', '', '', '', 1, 1, 5, 1),
(37, 5, 'Reference Image 4', 'imageUpload', 0, '', 0, '', '', '', '', '', '', '', '', 1, 1, 6, 1),
(38, 7, 'Item', 'textbox', '1', '', '0', '', '400px', '', '50', '', '', '', '', 1, 1, 1, 1),
(39, 7, 'Price', 'textbox', '0', '', '0', 'float', '100px', '', '10', '', '', '$', '', 1, 1, 1, 1),
(40, 7, 'Data Paid', 'datetime', '0', '', '0', 'date', '', '', '10', '', '', '', '', 1, 1, 2, 1),
(41, 7, 'Amount Paid', 'textbox', '0', '', '0', 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 3, 1),
(42, 7, 'Balance', 'textbox', '0', 'CALCULATED	: Original Price  - Paid', '1', 'float', '100px', '', '10', '', '', '$', '', 1, 1, 4, 1),
(43, 7, 'Comments', 'textarea', '0', '', '0', 'text', '', '', '1024', '', '', '', '', 1, 1, 5, 1),
(44, 4, 'Date of Payment 1', 'datetime', '0', '', '0', 'date', '', '', '10', '', '', '', '', 1, 1, 10, 1),
(45, 4, 'Payment 1', 'textbox', '0', '', '0', 'float', '90px', '', '', '', '0', '', '', 1, 1, 11, 1),
(46, 4, 'Date of Payment 2', 'datetime', '0', '', '0', 'date', '100px', '', '10', '', '', '', '', 1, 1, 12, 1),
(47, 4, 'Payment 2', 'textbox', '0', '', '0', 'float', '90px', '', '', '', '0', '', '', 1, 1, 13, 1),
(48, 4, 'Date of Payment 3', 'datetime', '0', '', '0', 'date', '100px', '', '10', '', '', '', '', 1, 1, 14, 1),
(49, 4, 'Payment 3', 'textbox', '0', '', '0', 'float', '90px', '', '', '', '0', '', '', 1, 1, 15, 1),
(50, 4, 'Date of Payment 4', 'datetime', '0', '', '0', 'date', '100px', '', '10', '', '', '', '', 1, 1, 16, 1),
(51, 4, 'Payment 4', 'textbox', '0', '', '0', 'float', '90px', '', '', '', '0', '', '', 1, 1, 17, 1),
(52, 2, 'Balance', 'textbox', '0', '', '1', 'float', '100px', '', '10', '', '0', '$', '', 1, 1, 5, 1),
(53, 2, 'Management Price due', 'textbox', '0', '', '0', 'float', '100px', '', '10', '', '', '$', '', 1, 0, 2, 1),
(54, 2, 'Percent of Design Price', 'textbox', '0', '', '0', 'percent', '65px', '', '4', '', '', '', '%', 1, 0, 3, 1),
(55, 2, 'Design Price due', 'textbox', '0', '', '0', 'float', '100px', '', '10', '', '', '$', '', 1, 0, 4, 1);


