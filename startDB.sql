--
-- DB start (2016-03-28)
-- By Miguel Jimenez Garcia
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL
);

-- One by user with password in md5, by default the password is admin
INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

CREATE TABLE IF NOT EXISTS `idioms` (
  `id` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL
);

-- Fill with the languages available on the web
INSERT INTO `idioms` (`id`, `name`) VALUES 
(1, 'Espa&ntilde;ol'),
(2, 'English');

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL,
  `idblog` tinyint(4) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idioms_id` int(11) NOT NULL
);

-- Fill with the blogs has the web in all idioms
INSERT INTO `blogs` (`id`, `idblog`, `title`, `slug`, `idioms_id`) VALUES
(1, 1, 'Noticias', 'noticias', 1),
(2, 1, 'News', 'news', 2);

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` decimal(8,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL,
  `telephone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pinterest` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
);


INSERT INTO `company` (`id`, `name`, `address`, `latitude`, `longitude`, `telephone`, `telephone2`, `email`, `facebook`, `twitter`, `youtube`, `linkedin`, `pinterest`, `instagram`) VALUES
(1, '', '', '0.00000', '0.00000', '', '', '', '', '', '', '', '', '');


CREATE TABLE IF NOT EXISTS `company_content` (
  `id` int(11) NOT NULL,
  `slogan` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idioms_id` int(11) NOT NULL
);

-- One by idiom
INSERT INTO `company_content` (`id`, `slogan`, `description`, `keywords`, `idioms_id`) VALUES
(1, '', '', '', 1),
(2, '', '', '', 2);


CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL
);

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(25) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `text` varchar(160) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `slider` tinyint(4) NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_header` tinyint(4) NOT NULL,
  `link_order` tinyint(4) NOT NULL DEFAULT '1',
  `parent_id` int(11) NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS `links_content` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `idioms_id` int(11) NOT NULL,
  `links_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `links_has_blogs` (
  `links_id` int(11) NOT NULL,
  `blogs_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `links_has_lists` (
  `links_id` int(11) NOT NULL,
  `lists_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `links_has_pages` (
  `links_id` int(11) NOT NULL,
  `pages_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `lists` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `lists_content` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idioms_id` int(11) NOT NULL,
  `lists_id` int(11) NOT NULL,
  `description` varchar(160) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `keywords` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
);

CREATE TABLE IF NOT EXISTS `lists_has_pages` (
  `lists_id` int(11) NOT NULL,
  `pages_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image` tinyint(4) NOT NULL,
  `home` tinyint(4) NOT NULL DEFAULT '0',
  `featured_image` tinyint(4) NOT NULL,
  `images_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `pages_content` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idioms_id` int(11) NOT NULL,
  `pages_id` int(11) NOT NULL,
  `description` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `pages_has_images` (
  `pages_id` int(11) NOT NULL,
  `images_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image` tinyint(4) NOT NULL,
  `blogs_id` int(11) NOT NULL,
  `images_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `posts_content` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `idioms_id` int(11) NOT NULL,
  `posts_id` int(11) NOT NULL,
  `description` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `posts_has_images` (
  `posts_id` int(11) NOT NULL,
  `images_id` int(11) NOT NULL
);

ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`), ADD KEY `idioms_id` (`idioms_id`);

ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `company_content`
  ADD PRIMARY KEY (`id`), ADD KEY `idioms_id` (`idioms_id`);

ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `address` (`address`);

ALTER TABLE `idioms`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `links_content`
  ADD PRIMARY KEY (`id`), ADD KEY `idioms_id` (`idioms_id`), ADD KEY `links_id` (`links_id`);

ALTER TABLE `links_has_blogs`
  ADD PRIMARY KEY (`links_id`,`blogs_id`);

ALTER TABLE `links_has_lists`
  ADD PRIMARY KEY (`links_id`,`lists_id`);

ALTER TABLE `links_has_pages`
  ADD PRIMARY KEY (`links_id`,`pages_id`);

ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `lists_content`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`), ADD KEY `idioms_id` (`idioms_id`), ADD KEY `lists_id` (`lists_id`);

ALTER TABLE `lists_has_pages`
  ADD PRIMARY KEY (`lists_id`,`pages_id`);

ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pages_content`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`), ADD UNIQUE KEY `slug_2` (`slug`), ADD KEY `idioms_id` (`idioms_id`), ADD KEY `pages_id` (`pages_id`);

ALTER TABLE `pages_has_images`
  ADD PRIMARY KEY (`pages_id`,`images_id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`), ADD KEY `blogs_id` (`blogs_id`);

ALTER TABLE `posts_content`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`), ADD KEY `idioms_id` (`idioms_id`), ADD KEY `posts_id` (`posts_id`);

ALTER TABLE `posts_has_images`
  ADD PRIMARY KEY (`posts_id`,`images_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `company_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `idioms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `links_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `lists_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `pages_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `posts_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;
