
UPDATE `<DB_PREFIX>modules` SET `version` = '0.0.5', `updated_at` = '<CURRENT_DATETIME>' WHERE `code` = 'webforms';

DROP TABLE IF EXISTS `<DB_PREFIX>webforms_messages`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>webforms_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `<DB_PREFIX>webforms_messages` (`id`, `name`, `email`, `phone`, `company`, `message`, `created_at`) VALUES
(1, 'Roberto Corino', 'r.corino@example.com', '9502341221', 'Company ltd.', 'Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.', '2019-01-01 01:02:03');

