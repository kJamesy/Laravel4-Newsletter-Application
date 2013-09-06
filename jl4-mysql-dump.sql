-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 06, 2013 at 03:09 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `newsletterl4`
--

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE IF NOT EXISTS `lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_subscriber`
--

CREATE TABLE IF NOT EXISTS `list_subscriber` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(10) unsigned NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `list_subscriber_list_id_foreign` (`list_id`),
  KEY `list_subscriber_subscriber_id_foreign` (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2013_08_05_163020_create_users_table', 1),
('2013_08_05_163201_create_users_throttle_table', 1),
('2013_08_05_163219_create_groups_table', 1),
('2013_08_05_163246_create_users_groups_table', 1),
('2013_08_05_163335_create_subscribers_table', 1),
('2013_08_05_163356_create_lists_table', 1),
('2013_08_05_163426_create_emails_table', 1),
('2013_08_05_163517_create_list_subscriber_table', 1),
('2013_08_05_163540_create_trackers_table', 1),
('2013_09_02_180149_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trackers`
--

CREATE TABLE IF NOT EXISTS `trackers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(10) unsigned NOT NULL,
  `email_id` int(10) unsigned NOT NULL,
  `IP_address` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `browser` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `platform` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `bounced` tinyint(1) NOT NULL,
  `unsubscribed` tinyint(1) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `trackers_subscriber_id_foreign` (`subscriber_id`),
  KEY `trackers_email_id_foreign` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `activated`, `activation_code`, `activated_at`, `last_login`, `persist_code`, `reset_password_code`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, 'james@acw.uk.com', '$2y$08$aw8Iiw8hPilOuQGi1l0H9.dUNkvJoft6N4RizNopehAgALL8gyFU6', NULL, 1, NULL, '2013-09-06 15:09:10', NULL, NULL, NULL, 'k', 'Jamesy', '2013-09-06 15:09:10', '2013-09-06 15:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `list_subscriber`
--
ALTER TABLE `list_subscriber`
  ADD CONSTRAINT `list_subscriber_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `list_subscriber_list_id_foreign` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trackers`
--
ALTER TABLE `trackers`
  ADD CONSTRAINT `trackers_email_id_foreign` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trackers_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE;
