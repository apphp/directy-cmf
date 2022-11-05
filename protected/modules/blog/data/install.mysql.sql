
INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `class_code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `installed_at`, `updated_at`, `has_test_data`, `sort_order`) VALUES
(NULL, 'blog', 'Blog', 'Blog', 'This module allows you to manage a simple blog on yuor site', '0.0.2', 'icon.png', 0, 0, 1, 0, 1, '<CURRENT_DATETIME>', NULL, 0, (SELECT COUNT(m.id) + 1 FROM `<DB_PREFIX>modules` m WHERE m.is_system = 0));


INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `property_length`, `append_text`, `trigger_condition`, `is_required`) VALUES
(NULL, 'blog', '', 'modulelink', 'posts/viewAll', 'All Posts Link', 'This link leads to the page with all blog posts', 'label', '', '', '', '', 0),
(NULL, 'blog', '', 'posts_per_page', '10', 'Posts Per Page', 'Defines how many posts will be shown per page', 'range', '1-100', '', '', '', 1),
(NULL, 'blog', '', 'post_preview_length', '512', 'Post Preview Length', 'Defines the maximum length of the post in characters that will be shown on the Frontend', 'range', '10-2000', '', '', '', 1),
(NULL, 'blog', '', 'post_link_format', 'posts/view/id/ID', 'Post Link Format', 'Defines a SEO format for post links that will be used in the blog', 'enum', 'posts/view/id/ID,posts/view/id/ID/Name,posts/view/ID,posts/view/ID/Name,posts/ID,posts/ID/Name', '', '', '', 0),
(NULL, 'blog', 'Display Options', 'posts_count', '8', 'Posts Count', 'Defines how many posts will be shown in the block', 'range', '1-10', '', '', '', 1),
(NULL, 'blog', 'Display Options', 'view_all_link', '1', 'View All Posts', 'Specifies whether to show View All posts link in the block', 'bool', '', '', '', '', 1),
(NULL, 'blog', 'Display Options', 'posts_show_item', '2', 'Count Posts for show', 'Defines how many posts will be shown in the block in same time', 'range', '1-8', '', '', '', 1),
(NULL, 'blog', 'Display Options', 'posts_show_time', '5000', 'Time to switch Posts', 'Defines how many time will be spent for switch posts', 'range', '1000-25000', '', '', '', 1),
(NULL, 'blog', 'Display Options', 'posts_pagination', '0', 'Posts Pagination', 'Specifies whether to show pagination', 'bool', '', '', '', '', 1),
(NULL, 'blog', 'Display Options', 'posts_arrows', '0', 'Posts Arrows', 'Specifies whether to show control arrows', 'bool', '', '', '', '', 1);


INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'blog', 'posts', 'add', 'Add Post', 'Add post on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'blog', 'posts', 'edit', 'Edit Post', 'Edit post on the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'blog', 'posts', 'delete', 'Delete Post', 'Delete post from the site'); 
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);


INSERT INTO `<DB_PREFIX>search_categories` (`id`, `module_code`, `category_code`, `category_name`, `callback_class`, `callback_method`, `items_count`, `sort_order`, `is_active`) VALUES
(NULL, 'blog', 'blog', 'Posts', 'Modules\\Blog\\Models\\Posts', 'search', '20', (SELECT COUNT(sc.id) + 1 FROM `<DB_PREFIX>search_categories` sc), 1);


DROP TABLE IF EXISTS `<DB_PREFIX>blog_posts`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  `modified_at` datetime NULL DEFAULT NULL,
  `finish_publishing_at` date NULL DEFAULT NULL,
  `publish_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - draft, 1 - published',
  `access_level` enum('public','registered') CHARACTER SET latin1 NOT NULL DEFAULT 'public',
  `post_header` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_text` text COLLATE utf8_unicode_ci,
  `post_image` varchar (255) NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `publish_status` (`publish_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `<DB_PREFIX>blog_posts` (`id`, `created_by`, `created_at`, `modified_at`, `finish_publishing_at`, `publish_status`, `access_level`, `post_header`, `post_text`, `post_image`, `views`) VALUES
(NULL, 'system', '2014-01-01 00:00:00', NULL, NULL, 1, 'public', 'Welcome Post!', 'This is an example of dummy post. It''s generated by the system for demo purposes only. You may remove it or rewrite Administrator Area.', '', 0);


DROP TABLE IF EXISTS `<DB_PREFIX>blog_post_comments`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>blog_post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_post_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(80) CHARACTER SET latin1 NOT NULL,
  `comment_text` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - awaiting, 1 - approved, 2 - denied',
  `created_at` datetime NULL DEFAULT NULL,
  `changed_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_post_id` (`blog_post_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

