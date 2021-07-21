-- Adminer 4.8.1 MySQL 5.5.5-10.3.29-MariaDB-0+deb10u1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

SET NAMES utf8mb4;

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id64` varchar(88) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `username` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `signup_date` bigint(11) NOT NULL DEFAULT 0,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `settings` mediumtext NOT NULL,
  `rank` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `subscribed_comments` tinyint(1) NOT NULL DEFAULT 0,
  `forum_id` int(10) unsigned DEFAULT NULL,
  `forum_psw` varchar(32) DEFAULT NULL,
  `forum_username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `ideas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `ideaid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `count_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addr` varchar(40) NOT NULL,
  `lastvisit` int(11) NOT NULL,
  `domain` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `count_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `page` varchar(255) NOT NULL DEFAULT '',
  `domain` varchar(16) NOT NULL DEFAULT '',
  `visits` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `cpt_connectes` (
  `ip` varchar(255) COLLATE latin1_german2_ci NOT NULL,
  `timestamp` varchar(255) COLLATE latin1_german2_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;


CREATE TABLE `daily_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `visitors` int(11) NOT NULL,
  `domain` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `newsletter_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(80) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `expire` bigint(11) NOT NULL,
  `freq` tinyint(4) NOT NULL,
  `notif_site` tinyint(1) NOT NULL,
  `notif_upd` varchar(2) NOT NULL,
  `confirm` tinyint(1) NOT NULL DEFAULT 0,
  `lastmail` bigint(11) NOT NULL DEFAULT 0,
  `lang` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` bigint(11) NOT NULL,
  `account` int(11) NOT NULL,
  `data` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON data',
  `mail_sent` tinyint(1) NOT NULL DEFAULT 0,
  `unread` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` int(11) NOT NULL,
  `session` varchar(255) CHARACTER SET utf8 NOT NULL,
  `connectid` varchar(64) CHARACTER SET utf8 NOT NULL,
  `expire` bigint(11) NOT NULL,
  `created` bigint(11) NOT NULL,
  `token` varchar(44) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `site_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) NOT NULL,
  `uptype` varchar(8) NOT NULL DEFAULT '',
  `authors` varchar(255) NOT NULL DEFAULT '',
  `codestat` varchar(255) NOT NULL DEFAULT '[-1,-1,-1]',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) NOT NULL,
  `label` varchar(255) NOT NULL,
  `style` text NOT NULL,
  `title` varchar(512) NOT NULL,
  `title_style` text NOT NULL,
  `contain` text NOT NULL,
  `contain_style` text NOT NULL,
  `date` bigint(11) NOT NULL,
  `todo_level` tinyint(4) NOT NULL DEFAULT 2,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `slides_tr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_id` int(11) NOT NULL,
  `lang` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` bigint(11) NOT NULL,
  `title` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contain` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `todo_level` tinyint(11) NOT NULL DEFAULT 2 COMMENT '0:reference, 1:ok, 2:to be checked, 3:to be modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `softwares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date` int(11) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `description` varchar(511) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `keywords` varchar(511) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `note` int(11) NOT NULL DEFAULT 0 COMMENT 'somme des notes sur 10',
  `votes` int(11) NOT NULL DEFAULT 0 COMMENT 'nombre de votes',
  `downloads` int(11) NOT NULL DEFAULT 0,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `archive_after` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  CONSTRAINT `softwares_ibfk_1` FOREIGN KEY (`category`) REFERENCES `softwares_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `softwares_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `softwares_categories_tr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `todo_level` tinyint(11) NOT NULL DEFAULT 2 COMMENT '0:reference, 1:ok, 2:to be checked, 3:to be modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `softwares_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sw_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `pseudo` varchar(31) NOT NULL,
  `text` text NOT NULL,
  `ip` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `softwares_comments_ibfk_1` (`sw_id`),
  CONSTRAINT `softwares_comments_ibfk_1` FOREIGN KEY (`sw_id`) REFERENCES `softwares` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `softwares_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sw_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hash` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filetype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT 0,
  `filesize` int(11) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0,
  `label` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `sw_id` (`sw_id`),
  CONSTRAINT `softwares_files_ibfk_1` FOREIGN KEY (`sw_id`) REFERENCES `softwares` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `softwares_mirrors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sw_id` int(11) NOT NULL,
  `links` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `label` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `softwares_mirrors_ibfk_1` (`sw_id`),
  CONSTRAINT `softwares_mirrors_ibfk_1` FOREIGN KEY (`sw_id`) REFERENCES `softwares` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `softwares_tr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sw_id` int(11) NOT NULL,
  `lang` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` bigint(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `todo_level` tinyint(11) NOT NULL DEFAULT 2 COMMENT '0:reference, 1:ok, 2:to be checked, 3:to be modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;


CREATE TABLE `subscriptions_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` int(11) NOT NULL,
  `article` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `short_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `works` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `twitter` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rights` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `expeditor_email` varchar(255) NOT NULL,
  `expeditor_name` varchar(255) NOT NULL,
  `messages` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `hash` varchar(128) NOT NULL,
  `date` int(11) NOT NULL,
  `lastadmreply` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2021-07-21 14:04:41
