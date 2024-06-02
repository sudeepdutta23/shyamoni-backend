-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 08, 2022 at 11:51 AM
-- Server version: 8.0.27
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easydocs`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing_plans`
--

DROP TABLE IF EXISTS `billing_plans`;
CREATE TABLE IF NOT EXISTS `billing_plans` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(13,2) DEFAULT NULL,
  `currency` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$',
  `interval` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'month',
  `interval_count` int NOT NULL DEFAULT '1',
  `parent_id` int DEFAULT NULL,
  `legacy_permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recommended` tinyint(1) NOT NULL DEFAULT '0',
  `free` tinyint(1) NOT NULL DEFAULT '0',
  `show_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `position` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `available_space` bigint UNSIGNED DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `billing_plans_hidden_index` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `commentable_id` int UNSIGNED NOT NULL,
  `commentable_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `comments_parent_id_index` (`parent_id`),
  KEY `comments_path_index` (`path`),
  KEY `comments_user_id_index` (`user_id`),
  KEY `comments_commentable_id_index` (`commentable_id`),
  KEY `comments_commentable_type_index` (`commentable_type`),
  KEY `comments_deleted_index` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `css_themes`
--

DROP TABLE IF EXISTS `css_themes`;
CREATE TABLE IF NOT EXISTS `css_themes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_dark` tinyint(1) NOT NULL DEFAULT '0',
  `default_light` tinyint(1) NOT NULL DEFAULT '0',
  `default_dark` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `colors` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `css_themes_name_unique` (`name`),
  KEY `css_themes_default_light_index` (`default_light`),
  KEY `css_themes_default_dark_index` (`default_dark`),
  KEY `css_themes_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `css_themes`
--

INSERT INTO `css_themes` (`id`, `name`, `is_dark`, `default_light`, `default_dark`, `user_id`, `colors`, `created_at`, `updated_at`) VALUES
(1, 'Dark', 1, 0, 1, 1, '{\"--be-primary-lighter\":\"#3f3f3f\",\"--be-primary-default\":\"#1D1D1D\",\"--be-primary-darker\":\"#181c26\",\"--be-accent-default\":\"#8AB2E0\",\"--be-accent-lighter\":\"#B9D1EC\",\"--be-accent-contrast\":\"rgba(255, 255, 255, 1)\",\"--be-accent-emphasis\":\"rgba(185, 209, 236, 0.1)\",\"--be-foreground-base\":\"#fff\",\"--be-text\":\"#fff\",\"--be-hint-text\":\"rgba(255, 255, 255, 0.5)\",\"--be-secondary-text\":\"rgba(255, 255, 255, 0.7)\",\"--be-label\":\"rgba(255, 255, 255, 0.7)\",\"--be-background\":\"#1D1D1D\",\"--be-background-alternative\":\"#121212\",\"--be-divider-lighter\":\"rgba(255, 255, 255, 0.06)\",\"--be-divider-default\":\"rgba(255, 255, 255, 0.12)\",\"--be-disabled-button-text\":\"rgba(255, 255, 255, 0.3)\",\"--be-disabled-toggle\":\"#000\",\"--be-chip\":\"#616161\",\"--be-hover\":\"rgba(255, 255, 255, 0.04)\",\"--be-selected-button\":\"#212121\",\"--be-disabled-button\":\"rgba(255, 255, 255, 0.12)\",\"--be-raised-button\":\"#424242\",\"--be-backdrop\":\"#BDBDBD\",\"--be-link\":\"#c5cae9\"}', '2022-02-16 02:38:29', '2022-02-16 02:38:29'),
(2, 'Light', 0, 1, 0, 1, '{\"--be-primary-lighter\":\"#3e4a66\",\"--be-primary-default\":\"rgba(56,35,139,1)\",\"--be-primary-darker\":\"#181c26\",\"--be-accent-default\":\"rgba(133,144,239,1)\",\"--be-accent-lighter\":\"#B9D1EC\",\"--be-accent-contrast\":\"rgba(255, 255, 255, 1)\",\"--be-accent-emphasis\":\"rgba(185, 209, 236, 0.15)\",\"--be-background\":\"rgba(255,255,255,1)\",\"--be-background-alternative\":\"rgba(233,233,233,1)\",\"--be-foreground-base\":\"black\",\"--be-text\":\"rgba(0, 0, 0, 0.87)\",\"--be-hint-text\":\"rgba(0, 0, 0, 0.38)\",\"--be-secondary-text\":\"rgba(0, 0, 0, 0.54)\",\"--be-label\":\"rgba(0, 0, 0, 0.87)\",\"--be-disabled-button-text\":\"rgba(0, 0, 0, 0.26)\",\"--be-divider-lighter\":\"rgba(0, 0, 0, 0.06)\",\"--be-divider-default\":\"rgba(0, 0, 0, 0.12)\",\"--be-hover\":\"rgba(0,0,0,0.04)\",\"--be-selected-button\":\"rgb(224, 224, 224)\",\"--be-chip\":\"#e0e0e0\",\"--be-link\":\"#3f51b5\",\"--be-backdrop\":\"black\",\"--be-raised-button\":\"#fff\",\"--be-disabled-toggle\":\"rgb(238, 238, 238)\",\"--be-disabled-button\":\"rgba(0, 0, 0, 0.12)\"}', '2022-02-16 02:38:29', '2022-04-27 02:33:43');

-- --------------------------------------------------------

--
-- Table structure for table `csv_exports`
--

DROP TABLE IF EXISTS `csv_exports`;
CREATE TABLE IF NOT EXISTS `csv_exports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cache_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  `download_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `csv_exports_cache_name_unique` (`cache_name`),
  KEY `csv_exports_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `csv_exports`
--

INSERT INTO `csv_exports` (`id`, `cache_name`, `user_id`, `download_name`, `uuid`, `created_at`, `updated_at`) VALUES
(4, 'users', 1, 'users.csv', '5b8bccbe-875f-4983-afbb-b5795a796c24', '2022-03-13 00:14:17', '2022-03-13 00:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `custom_domains`
--

DROP TABLE IF EXISTS `custom_domains`;
CREATE TABLE IF NOT EXISTS `custom_domains` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `host` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `global` tinyint(1) NOT NULL DEFAULT '0',
  `resource_id` int UNSIGNED DEFAULT NULL,
  `resource_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workspace_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `custom_domains_host_unique` (`host`),
  KEY `custom_domains_user_id_index` (`user_id`),
  KEY `custom_domains_created_at_index` (`created_at`),
  KEY `custom_domains_updated_at_index` (`updated_at`),
  KEY `custom_domains_global_index` (`global`),
  KEY `custom_domains_resource_id_index` (`resource_id`),
  KEY `custom_domains_resource_type_index` (`resource_type`),
  KEY `custom_domains_workspace_id_index` (`workspace_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_pages`
--

DROP TABLE IF EXISTS `custom_pages`;
CREATE TABLE IF NOT EXISTS `custom_pages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `hide_nav` tinyint(1) NOT NULL DEFAULT '0',
  `workspace_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_type_index` (`type`),
  KEY `pages_user_id_index` (`user_id`),
  KEY `custom_pages_workspace_id_index` (`workspace_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_pages`
--

INSERT INTO `custom_pages` (`id`, `title`, `body`, `slug`, `meta`, `type`, `created_at`, `updated_at`, `user_id`, `hide_nav`, `workspace_id`) VALUES
(1, 'Privacy Policy', '<h1>Example Privacy Policy</h1><p>The standard Lorem Ipsum passage, used since the 1500s\n    \"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"</p>\n\n<p>Section 1.10.32 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC\n    \"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?\"</p>\n\n<p>1914 translation by H. Rackham\n    \"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"</p>\n\n<p>Section 1.10.33 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC\n    \"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"</p>\n\n<p>1914 translation by H. Rackham\n    \"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.\"</p>', 'privacy-policy', NULL, 'default', '2022-02-16 02:38:29', '2022-02-16 02:38:29', NULL, 0, NULL),
(2, 'Terms of Service', '<h1>Example Terms of Service</h1><p>The standard Lorem Ipsum passage, used since the 1500s\n    \"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"</p>\n\n<p>Section 1.10.32 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC\n    \"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?\"</p>\n\n<p>1914 translation by H. Rackham\n    \"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"</p>\n\n<p>Section 1.10.33 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC\n    \"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"</p>\n\n<p>1914 translation by H. Rackham\n    \"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.\"</p>', 'terms-of-service', NULL, 'default', '2022-02-16 02:38:29', '2022-02-16 02:38:29', NULL, 0, NULL),
(3, 'About Us', '<h1>Example About Us</h1><p>The standard Lorem Ipsum passage, used since the 1500s\n    \"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"</p>\n\n<p>Section 1.10.32 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC\n    \"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?\"</p>\n\n<p>1914 translation by H. Rackham\n    \"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"</p>\n\n<p>Section 1.10.33 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC\n    \"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"</p>\n\n<p>1914 translation by H. Rackham\n    \"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.\"</p>', 'about-us', NULL, 'default', '2022-02-16 02:38:29', '2022-02-16 02:38:29', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fcm_tokens`
--

DROP TABLE IF EXISTS `fcm_tokens`;
CREATE TABLE IF NOT EXISTS `fcm_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fcm_tokens_device_id_user_id_unique` (`device_id`,`user_id`),
  KEY `fcm_tokens_device_id_index` (`device_id`),
  KEY `fcm_tokens_token_index` (`token`),
  KEY `fcm_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_entries`
--

DROP TABLE IF EXISTS `file_entries`;
CREATE TABLE IF NOT EXISTS `file_entries` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `path` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `disk_prefix` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `preview_token` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` tinyint(1) NOT NULL DEFAULT '0',
  `workspace_id` int UNSIGNED DEFAULT NULL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `meta_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MetaSignedBy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metaDate` date DEFAULT NULL,
  `metaDueDate` date DEFAULT NULL,
  `metaNotes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MetaTypeValue` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_user_id_index` (`user_id`),
  KEY `files_folder_id_index` (`parent_id`),
  KEY `file_entries_name_index` (`name`),
  KEY `file_entries_path_index` (`path`),
  KEY `file_entries_type_index` (`type`),
  KEY `file_entries_public_index` (`public`),
  KEY `file_entries_description_index` (`description`),
  KEY `file_entries_workspace_id_index` (`workspace_id`),
  KEY `file_entries_owner_id_index` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_entries`
--

INSERT INTO `file_entries` (`id`, `name`, `description`, `file_name`, `mime`, `file_size`, `user_id`, `parent_id`, `created_at`, `updated_at`, `deleted_at`, `path`, `disk_prefix`, `type`, `extension`, `public`, `preview_token`, `thumbnail`, `workspace_id`, `owner_id`, `meta_name`, `meta_tags`, `MetaSignedBy`, `metaDate`, `metaDueDate`, `metaNotes`, `MetaTypeValue`) VALUES
(42, 'testing', NULL, 'testing', NULL, 0, NULL, NULL, '2022-09-07 00:14:03', '2022-09-07 00:14:03', NULL, '16', NULL, 'folder', NULL, 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'testing2', NULL, 'testing2', NULL, 0, NULL, NULL, '2022-09-07 00:24:34', '2022-09-07 00:24:34', NULL, '17', NULL, 'folder', NULL, 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'Screenshot (14) - Copy.png', NULL, 'bwSDq6GrQM8UtLgAFWEiSpE1zebiyFrimNrPeAi7', 'image/png', 246065, NULL, 28, '2022-08-07 10:05:20', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 's/u', NULL, 'image', 'png', 0, NULL, 0, NULL, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '1.png', NULL, 'mSw3y2rMaJgE90zVOQOxb4ttkYtC2mN4Ylu1esNV', 'image/png', 202773, NULL, 28, '2022-08-07 08:47:11', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 's/t', NULL, 'image', 'png', 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'admin3', NULL, 'admin3', NULL, 518771, NULL, NULL, '2022-08-07 08:46:06', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 's', NULL, 'folder', NULL, 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'admin2', NULL, 'admin2', NULL, 0, NULL, NULL, '2022-08-07 07:13:31', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 'r', NULL, 'folder', NULL, 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'ppppp', NULL, 'ppppp', NULL, 0, NULL, NULL, '2022-09-07 01:35:39', '2022-09-07 02:12:19', '2022-09-07 02:12:19', '18', NULL, 'folder', NULL, 0, NULL, 0, NULL, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'jiaulahmed', NULL, 'jiaulahmed', NULL, 0, NULL, NULL, '2022-07-31 06:34:51', '2022-07-31 06:34:51', NULL, 'f', NULL, 'folder', NULL, 0, NULL, 0, NULL, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'qqqq', NULL, 'qqqq', NULL, 0, NULL, NULL, '2022-09-07 01:42:18', '2022-09-07 01:42:18', NULL, '19', NULL, 'folder', NULL, 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'uuuu', NULL, 'uuuu', NULL, 0, NULL, NULL, '2022-09-07 02:12:31', '2022-09-07 02:13:55', '2022-09-07 02:13:55', '1a', NULL, 'folder', NULL, 0, NULL, 0, NULL, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '42.png', NULL, 'ZUKiOWsV4viWAxgEsaqjVhcy4CxdvZLdUzm49coL', 'image/png', 72893, NULL, 23, '2022-08-05 04:22:59', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 'l/n/o', NULL, 'image', 'png', 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'lfree_docs', NULL, 'lfree_docs', NULL, 518818, NULL, 21, '2022-08-05 04:20:35', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 'l/n', NULL, 'folder', NULL, 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '1.png', NULL, 'bRXvNTlsOUJAZS4aS20u8iPd36I2mkoSW40uRER6', 'image/png', 202773, NULL, 21, '2022-08-05 04:19:10', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 'l/m', NULL, 'image', 'png', 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'admin', NULL, 'admin', NULL, 721591, NULL, NULL, '2022-08-05 04:17:47', '2022-08-16 04:29:02', '2022-08-16 04:29:02', 'l', NULL, 'folder', NULL, 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'activity_logs.sql', NULL, 'Nrgv59u3i3N020g54Y4V2HOIkNAnicn9UuIUsLd3', 'text/plain', 1342, NULL, NULL, '2022-08-17 11:59:46', '2022-08-17 11:59:46', NULL, '15', NULL, 'text', 'sql', 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'jiaul', NULL, 'jiaul', NULL, 3035423, NULL, NULL, '2022-08-15 21:19:11', '2022-08-16 04:28:58', '2022-08-16 04:28:58', 'x', NULL, 'folder', NULL, 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Untitled Diagram.drawio.png', NULL, '1NH3hUvW6fmZK8HKhBDkMSBwQyFsJhuTZV94KxVa', 'image/png', 59357, NULL, NULL, '2022-08-16 04:52:03', '2022-08-16 04:52:03', NULL, '14', NULL, 'image', 'png', 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'APDCL - Instant Bill Payment.pdf', NULL, 'qQKWI2t6yKVtsgd3qPmc2sVLGOqqbnmgyCFbMazR', 'application/pdf', 381285, NULL, NULL, '2022-08-16 04:49:58', '2022-08-16 04:49:58', NULL, '13', NULL, 'pdf', 'pdf', 0, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'pexels-lilartsy-1111597.jpg', NULL, '3bGyC9fnBY6kmIxmqQRJmLILqBrGI5mBI5tj81Nq', 'image/jpeg', 456620, NULL, NULL, '2022-08-16 04:30:22', '2022-08-16 04:49:29', '2022-08-16 04:49:29', '12', NULL, 'image', 'jpg', 0, NULL, 0, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'pexels-element-digital-1293260.jpg', NULL, 'qTeFr9P34jbxt8O4L3JYU3IvHHtKEGFoGQ9EEs0H', 'image/jpeg', 1140788, NULL, NULL, '2022-08-16 04:29:43', '2022-08-16 04:49:51', '2022-08-16 04:49:51', '11', NULL, 'image', 'jpg', 0, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'ppppppp', NULL, 'ppppppp', NULL, 0, NULL, NULL, '2022-09-07 02:14:01', '2022-09-07 02:15:14', '2022-09-07 02:15:14', '1b', NULL, 'folder', NULL, 0, NULL, 0, NULL, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'kkkkk', NULL, 'kkkkk', NULL, 0, NULL, NULL, '2022-09-07 02:15:20', '2022-09-07 02:15:20', NULL, '1c', NULL, 'folder', NULL, 0, NULL, 0, NULL, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file_entry_models`
--

DROP TABLE IF EXISTS `file_entry_models`;
CREATE TABLE IF NOT EXISTS `file_entry_models` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_entry_id` int UNSIGNED NOT NULL,
  `model_id` int UNSIGNED NOT NULL,
  `model_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `owner` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uploadables_upload_id_uploadable_id_uploadable_type_unique` (`file_entry_id`,`model_id`,`model_type`),
  KEY `file_entry_models_owner_index` (`owner`)
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_entry_models`
--

INSERT INTO `file_entry_models` (`id`, `file_entry_id`, `model_id`, `model_type`, `created_at`, `updated_at`, `owner`, `permissions`) VALUES
(157, 43, 43, 'App\\User', '2022-09-07 00:25:28', '2022-09-07 00:25:28', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(126, 30, 2, 'App\\User', '2022-08-07 10:05:21', '2022-08-07 10:05:21', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(125, 30, 5, 'App\\User', '2022-08-07 10:05:21', '2022-08-07 10:05:21', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(124, 30, 42, 'App\\User', '2022-08-07 10:05:21', '2022-08-07 10:05:21', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(123, 30, 43, 'App\\User', '2022-08-07 10:05:21', '2022-08-07 10:05:21', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(122, 30, 1, 'App\\User', '2022-08-07 10:05:21', '2022-08-07 10:05:21', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(121, 30, 41, 'App\\User', '2022-08-07 10:05:20', '2022-08-07 10:05:20', 1, NULL),
(120, 29, 2, 'App\\User', '2022-08-07 08:47:11', '2022-08-07 08:47:11', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(119, 29, 5, 'App\\User', '2022-08-07 08:47:11', '2022-08-07 08:47:11', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(118, 29, 42, 'App\\User', '2022-08-07 08:47:11', '2022-08-07 08:47:11', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(117, 29, 43, 'App\\User', '2022-08-07 08:47:11', '2022-08-07 08:47:11', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(116, 29, 41, 'App\\User', '2022-08-07 08:47:11', '2022-08-07 08:47:11', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(115, 29, 1, 'App\\User', '2022-08-07 08:47:11', '2022-08-07 08:47:11', 1, NULL),
(114, 28, 41, 'App\\User', '2022-08-07 08:46:13', '2022-08-07 08:46:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(113, 28, 43, 'App\\User', '2022-08-07 08:46:13', '2022-08-07 08:46:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(112, 28, 42, 'App\\User', '2022-08-07 08:46:13', '2022-08-07 08:46:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(111, 28, 5, 'App\\User', '2022-08-07 08:46:13', '2022-08-07 08:46:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(109, 28, 1, 'App\\User', '2022-08-07 08:46:06', '2022-08-07 08:46:06', 1, NULL),
(110, 28, 2, 'App\\User', '2022-08-07 08:46:13', '2022-08-07 08:46:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(103, 27, 1, 'App\\User', '2022-08-07 07:13:31', '2022-08-07 07:13:31', 1, NULL),
(158, 43, 41, 'App\\User', '2022-09-07 00:25:28', '2022-09-07 00:25:28', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(159, 43, 2, 'App\\User', '2022-09-07 00:25:28', '2022-09-07 00:25:28', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(160, 44, 41, 'App\\User', '2022-09-07 01:35:39', '2022-09-07 01:35:39', 1, NULL),
(161, 44, 1, 'App\\User', '2022-09-07 01:35:51', '2022-09-07 01:35:51', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(162, 44, 43, 'App\\User', '2022-09-07 01:35:51', '2022-09-07 01:35:51', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(95, 24, 1, 'App\\User', '2022-08-05 04:22:59', '2022-08-05 04:22:59', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(94, 24, 41, 'App\\User', '2022-08-05 04:22:59', '2022-08-05 04:22:59', 0, '{\"edit\":true,\"view\":true,\"download\":true}'),
(93, 24, 43, 'App\\User', '2022-08-05 04:22:59', '2022-08-05 04:22:59', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(92, 24, 42, 'App\\User', '2022-08-05 04:22:59', '2022-08-05 04:22:59', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(91, 24, 2, 'App\\User', '2022-08-05 04:22:59', '2022-08-05 04:22:59', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(90, 24, 5, 'App\\User', '2022-08-05 04:22:59', '2022-08-05 04:22:59', 1, NULL),
(89, 23, 2, 'App\\User', '2022-08-05 04:20:35', '2022-08-05 04:20:35', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(88, 23, 42, 'App\\User', '2022-08-05 04:20:35', '2022-08-05 04:20:35', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(49, 15, 43, 'App\\User', '2022-07-31 06:34:51', '2022-07-31 06:34:51', 1, NULL),
(87, 23, 43, 'App\\User', '2022-08-05 04:20:35', '2022-08-05 04:20:35', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(86, 23, 41, 'App\\User', '2022-08-05 04:20:35', '2022-08-05 04:20:35', 0, '{\"edit\":true,\"view\":true,\"download\":true}'),
(85, 23, 1, 'App\\User', '2022-08-05 04:20:35', '2022-08-05 04:20:35', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(84, 23, 5, 'App\\User', '2022-08-05 04:20:35', '2022-08-05 04:20:35', 1, NULL),
(83, 22, 2, 'App\\User', '2022-08-05 04:19:10', '2022-08-05 04:19:10', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(82, 22, 42, 'App\\User', '2022-08-05 04:19:10', '2022-08-05 04:19:10', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(81, 22, 43, 'App\\User', '2022-08-05 04:19:10', '2022-08-05 04:19:10', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(80, 22, 41, 'App\\User', '2022-08-05 04:19:10', '2022-08-05 04:19:10', 0, '{\"edit\":true,\"view\":true,\"download\":true}'),
(79, 22, 1, 'App\\User', '2022-08-05 04:19:10', '2022-08-05 04:19:10', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(78, 22, 5, 'App\\User', '2022-08-05 04:19:10', '2022-08-05 04:19:10', 1, NULL),
(77, 21, 41, 'App\\User', '2022-08-05 04:18:13', '2022-08-05 04:18:13', 0, '{\"edit\":true,\"view\":true,\"download\":true}'),
(76, 21, 43, 'App\\User', '2022-08-05 04:18:13', '2022-08-05 04:18:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(75, 21, 42, 'App\\User', '2022-08-05 04:18:13', '2022-08-05 04:18:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(74, 21, 5, 'App\\User', '2022-08-05 04:18:13', '2022-08-05 04:18:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(73, 21, 2, 'App\\User', '2022-08-05 04:18:13', '2022-08-05 04:18:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(72, 21, 1, 'App\\User', '2022-08-05 04:17:47', '2022-08-05 04:17:47', 1, NULL),
(151, 41, 1, 'App\\User', '2022-08-17 12:00:36', '2022-08-17 12:00:36', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(152, 42, 5, 'App\\User', '2022-09-07 00:14:03', '2022-09-07 00:14:03', 1, NULL),
(154, 41, 41, 'App\\User', '2022-09-07 00:23:21', '2022-09-07 00:23:21', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(155, 43, 5, 'App\\User', '2022-09-07 00:24:34', '2022-09-07 00:24:34', 1, NULL),
(156, 43, 1, 'App\\User', '2022-09-07 00:25:28', '2022-09-07 00:25:28', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(150, 41, 5, 'App\\User', '2022-08-17 11:59:46', '2022-08-17 11:59:46', 1, NULL),
(149, 40, 5, 'App\\User', '2022-08-16 04:52:15', '2022-08-16 04:52:15', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(135, 33, 1, 'App\\User', '2022-08-15 21:19:11', '2022-08-15 21:19:11', 1, NULL),
(136, 33, 41, 'App\\User', '2022-08-15 21:19:24', '2022-08-15 21:19:24', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(148, 40, 1, 'App\\User', '2022-08-16 04:52:03', '2022-08-16 04:52:03', 1, NULL),
(147, 39, 5, 'App\\User', '2022-08-16 04:50:37', '2022-08-16 04:50:37', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(146, 39, 1, 'App\\User', '2022-08-16 04:49:58', '2022-08-16 04:49:58', 1, NULL),
(144, 38, 5, 'App\\User', '2022-08-16 04:30:22', '2022-08-16 04:30:22', 1, NULL),
(143, 37, 1, 'App\\User', '2022-08-16 04:29:43', '2022-08-16 04:29:43', 1, NULL),
(163, 44, 5, 'App\\User', '2022-09-07 01:35:51', '2022-09-07 01:35:51', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(164, 44, 2, 'App\\User', '2022-09-07 01:35:51', '2022-09-07 01:35:51', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(165, 45, 5, 'App\\User', '2022-09-07 01:42:18', '2022-09-07 01:42:18', 1, NULL),
(166, 45, 1, 'App\\User', '2022-09-07 01:42:33', '2022-09-07 01:42:33', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(167, 45, 43, 'App\\User', '2022-09-07 01:42:33', '2022-09-07 01:42:33', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(168, 45, 41, 'App\\User', '2022-09-07 01:42:33', '2022-09-07 01:42:33', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(169, 45, 2, 'App\\User', '2022-09-07 01:42:33', '2022-09-07 01:42:33', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(170, 46, 41, 'App\\User', '2022-09-07 02:12:31', '2022-09-07 02:12:31', 1, NULL),
(171, 46, 5, 'App\\User', '2022-09-07 02:12:44', '2022-09-07 02:12:44', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(172, 47, 41, 'App\\User', '2022-09-07 02:14:01', '2022-09-07 02:14:01', 1, NULL),
(173, 47, 5, 'App\\User', '2022-09-07 02:14:13', '2022-09-07 02:14:13', 0, '{\"view\":true,\"edit\":true,\"download\":true}'),
(174, 48, 41, 'App\\User', '2022-09-07 02:15:20', '2022-09-07 02:15:20', 1, NULL),
(175, 48, 5, 'App\\User', '2022-09-07 02:15:37', '2022-09-07 02:15:37', 0, '{\"view\":true,\"edit\":true,\"download\":true}');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE IF NOT EXISTS `folders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `folder_id` int DEFAULT NULL,
  `share_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `folders_user_id_index` (`user_id`),
  KEY `folders_share_id_index` (`share_id`),
  KEY `folders_folder_id_index` (`folder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_id` int NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `uuid` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_subscription_id_index` (`subscription_id`),
  KEY `invoices_uuid_index` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labelables`
--

DROP TABLE IF EXISTS `labelables`;
CREATE TABLE IF NOT EXISTS `labelables` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `label_id` int NOT NULL,
  `labelable_id` int NOT NULL,
  `labelable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `labelables_label_id_labelable_id_labelable_type_unique` (`label_id`,`labelable_id`,`labelable_type`),
  KEY `labelables_labelable_id_index` (`labelable_id`),
  KEY `labelables_label_id_index` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

DROP TABLE IF EXISTS `labels`;
CREATE TABLE IF NOT EXISTS `labels` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `labels_name_unique` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `localizations`
--

DROP TABLE IF EXISTS `localizations`;
CREATE TABLE IF NOT EXISTS `localizations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `localizations_name_index` (`name`),
  KEY `localizations_language_index` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `localizations`
--

INSERT INTO `localizations` (`id`, `name`, `created_at`, `updated_at`, `language`) VALUES
(1, 'english', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `meta_tags`
--

DROP TABLE IF EXISTS `meta_tags`;
CREATE TABLE IF NOT EXISTS `meta_tags` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signedBy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `OCRLanguage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DocumentNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meta_tags`
--

INSERT INTO `meta_tags` (`id`, `file_id`, `name`, `tags`, `signedBy`, `date`, `dueDate`, `OCRLanguage`, `DocumentNumber`, `notes`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 141, 'favicon', '[\"pppp\"]', '[\"admin\"]', '2022-03-24', '2022-03-24', NULL, NULL, 'ppppp', NULL, NULL, '2022-03-24 05:31:59', '2022-03-24 05:31:59'),
(2, 142, 'add_vhost', '[\"ppp\"]', '[\"admin\"]', '2022-03-24', '2022-03-24', NULL, NULL, 'tyttt', NULL, NULL, '2022-03-24 07:08:23', '2022-03-24 07:08:23'),
(3, 72, NULL, '[]', '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 07:08:57', '2022-03-24 07:08:57'),
(5, NULL, NULL, '[]', '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 15:45:31', '2022-03-24 15:45:31'),
(6, 142, NULL, '[]', '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-24 15:45:33', '2022-03-24 15:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2015_04_127_156842_create_social_profiles_table', 1),
(4, '2015_04_127_156842_create_users_oauth_table', 1),
(5, '2015_04_13_140047_create_files_models_table', 1),
(6, '2015_04_18_134312_create_folders_table', 1),
(7, '2015_05_05_131439_create_labels_table', 1),
(8, '2015_05_29_131549_create_settings_table', 1),
(9, '2015_08_08_131451_create_labelables_table', 1),
(10, '2016_04_06_140017_add_folder_id_index_to_files_table', 1),
(11, '2016_05_12_190852_create_tags_table', 1),
(12, '2016_05_12_190958_create_taggables_table', 1),
(13, '2016_05_26_170044_create_uploads_table', 1),
(14, '2016_05_27_143158_create_uploadables_table', 1),
(15, '2016_07_14_153703_create_groups_table', 1),
(16, '2016_07_14_153921_create_user_group_table', 1),
(17, '2016_10_17_152159_add_space_available_column_to_users_table', 1),
(18, '2017_07_02_120142_create_pages_table', 1),
(19, '2017_07_11_122825_create_localizations_table', 1),
(20, '2017_08_26_131330_add_private_field_to_settings_table', 1),
(21, '2017_09_17_144728_add_columns_to_users_table', 1),
(22, '2017_09_17_152854_make_password_column_nullable', 1),
(23, '2017_09_30_152855_make_settings_value_column_nullable', 1),
(24, '2017_10_01_152897_add_public_column_to_uploads_table', 1),
(25, '2017_12_04_132911_add_avatar_column_to_users_table', 1),
(26, '2018_01_10_140732_create_subscriptions_table', 1),
(27, '2018_01_10_140746_add_billing_to_users_table', 1),
(28, '2018_01_10_161706_create_billing_plans_table', 1),
(29, '2018_06_05_142932_rename_files_table_to_file_entries', 1),
(30, '2018_06_06_141629_rename_file_entries_table_columns', 1),
(31, '2018_06_07_141630_merge_files_and_folders_tables', 1),
(32, '2018_07_03_114346_create_shareable_links_table', 1),
(33, '2018_07_24_113757_add_available_space_to_billing_plans_table', 1),
(34, '2018_07_24_124254_add_available_space_to_users_table', 1),
(35, '2018_07_26_142339_rename_groups_to_roles', 1),
(36, '2018_07_26_142842_rename_user_role_table_columns_to_roles', 1),
(37, '2018_08_07_124200_rename_uploads_to_file_entries', 1),
(38, '2018_08_07_124327_refactor_file_entries_columns', 1),
(39, '2018_08_07_130653_add_folder_path_column_to_file_entries_table', 1),
(40, '2018_08_07_140328_delete_legacy_root_folders', 1),
(41, '2018_08_07_140330_move_folders_into_file_entries_table', 1),
(42, '2018_08_07_140440_migrate_file_entry_users_to_many_to_many', 1),
(43, '2018_08_10_142251_update_users_table_to_v2', 1),
(44, '2018_08_15_132225_move_uploads_into_subfolders', 1),
(45, '2018_08_16_111525_transform_file_entries_records_to_v2', 1),
(46, '2018_08_31_104145_rename_uploadables_table', 1),
(47, '2018_08_31_104325_rename_file_entry_models_table_columns', 1),
(48, '2018_11_26_171703_add_type_and_title_columns_to_pages_table', 1),
(49, '2018_12_01_144233_change_unique_index_on_tags_table', 1),
(50, '2019_02_16_150049_delete_old_seo_settings', 1),
(51, '2019_02_24_141457_create_jobs_table', 1),
(52, '2019_03_11_162627_add_preview_token_to_file_entries_table', 1),
(53, '2019_03_12_160803_add_thumbnail_column_to_file_entries_table', 1),
(54, '2019_03_16_161836_add_paypal_id_column_to_billing_plans_table', 1),
(55, '2019_05_14_120930_index_description_column_in_file_entries_table', 1),
(56, '2019_06_08_120504_create_custom_domains_table', 1),
(57, '2019_06_13_140318_add_user_id_column_to_pages_table', 1),
(58, '2019_06_15_114320_rename_pages_table_to_custom_pages', 1),
(59, '2019_06_18_133933_create_permissions_table', 1),
(60, '2019_06_18_134203_create_permissionables_table', 1),
(61, '2019_06_18_135822_rename_permissions_columns', 1),
(62, '2019_06_25_133852_move_inline_permissions_to_separate_table', 1),
(63, '2019_07_08_122001_create_css_themes_table', 1),
(64, '2019_07_20_141752_create_invoices_table', 1),
(65, '2019_08_19_121112_add_global_column_to_custom_domains_table', 1),
(66, '2019_09_13_141123_change_plan_amount_to_float', 1),
(67, '2019_10_14_171943_add_index_to_username_column', 1),
(68, '2019_10_20_143522_create_comments_table', 1),
(69, '2019_10_23_134520_create_notifications_table', 1),
(70, '2019_11_21_144956_add_resource_id_and_type_to_custom_domains_table', 1),
(71, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(72, '2019_12_14_194512_rename_public_path_column_to_disk_prefix', 1),
(73, '2019_12_24_165237_change_file_size_column_default_value_to_0', 1),
(74, '2019_12_28_190836_update_file_entry_models_table_to_v2', 1),
(75, '2019_12_28_191105_move_user_file_entry_table_records_to_file_entry_models', 1),
(76, '2020_01_26_143733_create_notification_subscriptions_table', 1),
(77, '2020_03_03_140720_add_language_col_to_localizations_table', 1),
(78, '2020_03_03_143142_add_lang_code_to_existing_localizations', 1),
(79, '2020_04_14_163347_add_hidden_column_to_plans_table', 1),
(80, '2020_06_27_180040_add_verified_at_column_to_users_table', 1),
(81, '2020_06_27_180253_move_confirmed_column_to_email_verified_at', 1),
(82, '2020_07_15_144024_fix_issues_with_migration_to_laravel_7', 1),
(83, '2020_07_22_165126_create_workspaces_table', 1),
(84, '2020_07_23_145652_create_workspace_invites_table', 1),
(85, '2020_07_23_164502_create_workspace_user_table', 1),
(86, '2020_07_26_165349_add_columns_to_roles_table', 1),
(87, '2020_07_29_141418_add_workspace_id_column_to_workspaceable_models', 1),
(88, '2020_07_30_152330_add_type_column_to_permissions_table', 1),
(89, '2020_08_29_165057_add_hide_nav_column_to_custom_pages_table', 1),
(90, '2020_12_14_155112_create_table_fcm_tokens', 1),
(91, '2020_12_17_124109_subscribe_users_to_notifications', 1),
(92, '2021_04_22_172459_add_internal_columm_to_roles_table', 1),
(93, '2021_05_03_173446_add_deleted_column_to_comments_table', 1),
(94, '2021_05_12_164940_add_advanced_column_to_permissions_table', 1),
(95, '2021_06_04_143405_add_workspace_id_col_to_custom_domains_table', 1),
(96, '2021_06_04_143406_add_workspace_id_col_to_custom_pages_table', 1),
(97, '2021_06_05_182202_create_csv_exports_table', 1),
(98, '2021_06_18_161030_rename_gateway_col_in_subscriptions_table', 1),
(99, '2021_06_19_111939_add_owner_id_column_to_file_entries_table', 1),
(100, '2021_06_19_112035_materialize_owner_id_in_file_entries_table', 1),
(101, '2021_07_06_144837_migrate_landing_page_config_to_20', 1),
(102, '2021_07_17_093454_add_created_at_col_to_user_role_table', 1),
(103, '2022_03_16_110319_create_meta_tags_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('dfcad7ca-c7a7-4e7d-9aab-7c83c27678b0', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 41, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"admin shared a file with you:\"},{\"icon\":\"image\",\"content\":\"pexels-yurii-hlei-1365795.jpg\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-08-15 21:25:50', '2022-08-15 21:17:27', '2022-08-15 21:25:50'),
('1f02fa63-5c9d-4810-8e4f-00faec8745d1', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 41, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"admin shared a file with you:\"},{\"icon\":\"folder\",\"content\":\"jiaul\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-08-15 21:25:50', '2022-08-15 21:19:24', '2022-08-15 21:25:50'),
('ecd3d46e-c853-4278-a9af-d0244816b8df', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 5, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"admin shared a file with you:\"},{\"icon\":\"image\",\"content\":\"pexels-element-digital-1293260.jpg\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-08-16 04:53:06', '2022-08-16 04:43:35', '2022-08-16 04:53:06'),
('1b22700d-ad63-46e1-b8cd-20a0796fb2e1', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 5, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"admin shared a file with you:\"},{\"icon\":\"pdf\",\"content\":\"APDCL - Instant Bill Payment.pdf\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-08-16 04:53:06', '2022-08-16 04:50:37', '2022-08-16 04:53:06'),
('f861e047-f6b9-4f15-bb91-57a438f9b16e', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 5, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"admin shared a file with you:\"},{\"icon\":\"image\",\"content\":\"Untitled Diagram.drawio.png\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-08-16 04:53:06', '2022-08-16 04:52:15', '2022-08-16 04:53:06'),
('6fa74885-64dc-4b67-b4de-fabe777df72b', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 1, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"lfree shared a file with you:\"},{\"icon\":\"text\",\"content\":\"activity_logs.sql\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-08-17 12:01:13', '2022-08-17 12:00:38', '2022-08-17 12:01:13'),
('8e9e543e-d9d7-49d3-a4da-4e0ef1a882c4', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 41, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"lfree shared a file with you:\"},{\"icon\":\"folder\",\"content\":\"testing\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-09-07 00:14:55', '2022-09-07 00:14:35', '2022-09-07 00:14:55'),
('b44415e5-49a3-4b2a-8483-b7686787504a', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 1, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"lfree shared a file with you:\"},{\"icon\":\"folder\",\"content\":\"testing2\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', NULL, '2022-09-07 00:25:28', '2022-09-07 00:25:28'),
('b7c11300-5723-4da3-9728-888a5c2b8c67', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 1, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"jiaulh shared a file with you:\"},{\"icon\":\"folder\",\"content\":\"ppppp\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', NULL, '2022-09-07 01:37:13', '2022-09-07 01:37:13'),
('3161a3d9-d5f6-42dd-a451-7f64d888ee6a', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 1, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"lfree shared a file with you:\"},{\"icon\":\"folder\",\"content\":\"qqqq\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', NULL, '2022-09-07 01:42:33', '2022-09-07 01:42:33'),
('6205afbb-78ca-4a68-be28-6f8380cfa565', 'App\\Notifications\\FileEntrySharedNotif', 'App\\User', 5, '{\"image\":\"people\",\"mainAction\":{\"action\":\"\"},\"lines\":[{\"content\":\"jiaulh shared a file with you:\"},{\"icon\":\"folder\",\"content\":\"kkkkk\",\"action\":{\"action\":\"\\/drive\\/shares\"}}]}', '2022-09-07 02:17:37', '2022-09-07 02:16:13', '2022-09-07 02:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `notification_subscriptions`
--

DROP TABLE IF EXISTS `notification_subscriptions`;
CREATE TABLE IF NOT EXISTS `notification_subscriptions` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notif_id` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `channels` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_subscriptions_notif_id_index` (`notif_id`),
  KEY `notification_subscriptions_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_subscriptions`
--

INSERT INTO `notification_subscriptions` (`id`, `notif_id`, `user_id`, `channels`) VALUES
('62c3fa73-c1e5-412d-93c5-d369ee593a6b', 'W01', 42, '{\"0\":\"email\",\"1\":\"browser\",\"2\":\"mobile\",\"email\":true,\"browser\":true,\"mobile\":true}'),
('122d7e05-1533-4fd2-a72e-36e13d7cfca9', 'A01', 42, '{\"0\":\"email\",\"1\":\"browser\",\"2\":\"mobile\",\"email\":true,\"browser\":true,\"mobile\":true}'),
('431d8505-a598-46ad-b101-2ca7249cf59a', 'W01', 43, '{\"0\":\"email\",\"1\":\"browser\",\"2\":\"mobile\",\"email\":true,\"browser\":true,\"mobile\":true}'),
('e3a1e225-a7f8-46c7-b161-c3f03eccb053', 'A01', 43, '{\"0\":\"email\",\"1\":\"browser\",\"2\":\"mobile\",\"email\":true,\"browser\":true,\"mobile\":true}'),
('475bc8aa-f7ac-486d-839c-717a674e5df4', 'W01', 41, '{\"email\":false,\"browser\":true,\"mobile\":false}'),
('faa586e9-2677-4f38-a724-e554c7b61564', 'A01', 41, '{\"email\":false,\"browser\":true,\"mobile\":false}'),
('9ea0dfb4-1582-4d7a-8116-d099ca77cac7', 'W01', 1, '{\"email\":false,\"browser\":true,\"mobile\":false}'),
('43dd4028-0832-433c-9cfe-b79c06cfe352', 'A01', 1, '{\"email\":false,\"browser\":true,\"mobile\":false}'),
('c2787469-2566-4c13-bb7f-2201d09683ab', 'W01', 5, '{\"email\":false,\"browser\":true,\"mobile\":false}'),
('5c37838f-8019-44c8-b7bd-329ed88563de', 'A01', 5, '{\"email\":false,\"browser\":true,\"mobile\":false}');

-- --------------------------------------------------------

--
-- Table structure for table `notification_table`
--

DROP TABLE IF EXISTS `notification_table`;
CREATE TABLE IF NOT EXISTS `notification_table` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `file_id` bigint DEFAULT NULL,
  `is_read` int DEFAULT '1',
  `message` varchar(191) DEFAULT '{{owner_name}} shared {{fileName}} with you',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_table`
--

INSERT INTO `notification_table` (`id`, `user_id`, `owner_id`, `file_id`, `is_read`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 6, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 05:16:02', '2022-07-31 05:16:02'),
(2, 2, 5, 6, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 05:16:02', '2022-07-31 05:16:02'),
(3, 41, 5, 6, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 05:16:02', '2022-07-31 05:16:02'),
(4, 1, 5, 7, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:28:38', '2022-07-31 06:28:38'),
(5, 2, 5, 7, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:28:38', '2022-07-31 06:28:38'),
(6, 41, 5, 7, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:28:38', '2022-07-31 06:28:38'),
(7, 1, 5, 8, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:30:40', '2022-07-31 06:30:40'),
(8, 2, 5, 8, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:30:40', '2022-07-31 06:30:40'),
(9, 41, 5, 8, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:30:40', '2022-07-31 06:30:40'),
(10, 5, 1, 9, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:32:36', '2022-07-31 06:32:36'),
(11, 2, 1, 9, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:32:36', '2022-07-31 06:32:36'),
(12, 41, 1, 9, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:32:36', '2022-07-31 06:32:36'),
(13, 5, 1, 10, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:53:20', '2022-07-31 06:53:20'),
(14, 2, 1, 10, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:53:20', '2022-07-31 06:53:20'),
(15, 41, 1, 10, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:53:20', '2022-07-31 06:53:20'),
(16, 1, 5, 11, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:55:04', '2022-07-31 06:55:04'),
(17, 2, 5, 11, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:55:04', '2022-07-31 06:55:04'),
(18, 41, 5, 11, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:55:04', '2022-07-31 06:55:04'),
(19, 5, 1, 12, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:55:56', '2022-07-31 06:55:56'),
(20, 2, 1, 12, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:55:56', '2022-07-31 06:55:56'),
(21, 41, 1, 12, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 06:55:56', '2022-07-31 06:55:56'),
(22, 1, 5, 13, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 07:05:31', '2022-07-31 07:05:31'),
(23, 2, 5, 13, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 07:05:31', '2022-07-31 07:05:31'),
(24, 41, 5, 13, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 07:05:31', '2022-07-31 07:05:31'),
(25, 42, 1, 14, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 10:46:29', '2022-07-31 10:46:29'),
(26, 43, 1, 14, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 12:06:42', '2022-07-31 12:06:42'),
(27, 43, 1, 16, 1, '{{owner_name}} shared {{fileName}} with you', '2022-07-31 12:08:29', '2022-07-31 12:08:29'),
(28, 2, 1, 16, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:30:30', '2022-08-05 08:30:30'),
(29, 5, 1, 16, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:30:30', '2022-08-05 08:30:30'),
(30, 42, 1, 16, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:30:30', '2022-08-05 08:30:30'),
(31, 43, 1, 16, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:30:30', '2022-08-05 08:30:30'),
(32, 41, 1, 16, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:30:30', '2022-08-05 08:30:30'),
(33, 1, 5, 19, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:31:36', '2022-08-05 08:31:36'),
(34, 2, 5, 19, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:31:36', '2022-08-05 08:31:36'),
(35, 42, 5, 19, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:31:36', '2022-08-05 08:31:36'),
(36, 43, 5, 19, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:31:36', '2022-08-05 08:31:36'),
(37, 41, 5, 19, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:31:36', '2022-08-05 08:31:36'),
(38, 2, 1, 20, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:32:11', '2022-08-05 08:32:11'),
(39, 5, 1, 20, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:32:11', '2022-08-05 08:32:11'),
(40, 42, 1, 20, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:32:11', '2022-08-05 08:32:11'),
(41, 43, 1, 20, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:32:11', '2022-08-05 08:32:11'),
(42, 41, 1, 20, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 08:32:11', '2022-08-05 08:32:11'),
(43, 2, 1, 21, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:48:13', '2022-08-05 09:48:13'),
(44, 5, 1, 21, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:48:13', '2022-08-05 09:48:13'),
(45, 42, 1, 21, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:48:13', '2022-08-05 09:48:13'),
(46, 43, 1, 21, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:48:13', '2022-08-05 09:48:13'),
(47, 41, 1, 21, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:48:13', '2022-08-05 09:48:13'),
(48, 1, 5, 22, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:49:10', '2022-08-05 09:49:10'),
(49, 41, 5, 22, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:49:10', '2022-08-05 09:49:10'),
(50, 43, 5, 22, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:49:10', '2022-08-05 09:49:10'),
(51, 42, 5, 22, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:49:10', '2022-08-05 09:49:10'),
(52, 2, 5, 22, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:49:10', '2022-08-05 09:49:10'),
(53, 1, 5, 23, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:50:35', '2022-08-05 09:50:35'),
(54, 41, 5, 23, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:50:35', '2022-08-05 09:50:35'),
(55, 43, 5, 23, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:50:35', '2022-08-05 09:50:35'),
(56, 42, 5, 23, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:50:35', '2022-08-05 09:50:35'),
(57, 2, 5, 23, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:50:35', '2022-08-05 09:50:35'),
(58, 2, 5, 24, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:52:59', '2022-08-05 09:52:59'),
(59, 42, 5, 24, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:52:59', '2022-08-05 09:52:59'),
(60, 43, 5, 24, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:52:59', '2022-08-05 09:52:59'),
(61, 41, 5, 24, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:52:59', '2022-08-05 09:52:59'),
(62, 1, 5, 24, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:52:59', '2022-08-05 09:52:59'),
(63, 2, 1, 25, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:53:43', '2022-08-05 09:53:43'),
(64, 42, 1, 25, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:53:43', '2022-08-05 09:53:43'),
(65, 43, 1, 25, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:53:43', '2022-08-05 09:53:43'),
(66, 41, 1, 25, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:53:43', '2022-08-05 09:53:43'),
(67, 5, 1, 25, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-05 09:53:43', '2022-08-05 09:53:43'),
(68, 2, 1, 27, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 12:43:38', '2022-08-07 12:43:38'),
(69, 5, 1, 27, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 12:43:38', '2022-08-07 12:43:38'),
(70, 42, 1, 27, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 12:43:38', '2022-08-07 12:43:38'),
(71, 43, 1, 27, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 12:43:38', '2022-08-07 12:43:38'),
(72, 41, 1, 27, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 12:43:38', '2022-08-07 12:43:38'),
(73, 2, 1, 28, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:16:13', '2022-08-07 14:16:13'),
(74, 5, 1, 28, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:16:13', '2022-08-07 14:16:13'),
(75, 42, 1, 28, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:16:13', '2022-08-07 14:16:13'),
(76, 43, 1, 28, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:16:13', '2022-08-07 14:16:13'),
(77, 41, 1, 28, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:16:13', '2022-08-07 14:16:13'),
(78, 41, 1, 29, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:17:11', '2022-08-07 14:17:11'),
(79, 43, 1, 29, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:17:11', '2022-08-07 14:17:11'),
(80, 42, 1, 29, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:17:11', '2022-08-07 14:17:11'),
(81, 5, 1, 29, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:17:11', '2022-08-07 14:17:11'),
(82, 2, 1, 29, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 14:17:11', '2022-08-07 14:17:11'),
(83, 1, 41, 30, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:35:21', '2022-08-07 15:35:21'),
(84, 43, 41, 30, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:35:21', '2022-08-07 15:35:21'),
(85, 42, 41, 30, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:35:21', '2022-08-07 15:35:21'),
(86, 5, 41, 30, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:35:21', '2022-08-07 15:35:21'),
(87, 2, 41, 30, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:35:21', '2022-08-07 15:35:21'),
(88, 41, 1, 31, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:37:50', '2022-08-07 15:37:50'),
(89, 43, 1, 31, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:37:50', '2022-08-07 15:37:50'),
(90, 42, 1, 31, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:37:50', '2022-08-07 15:37:50'),
(91, 5, 1, 31, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:37:50', '2022-08-07 15:37:50'),
(92, 2, 1, 31, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-07 15:37:50', '2022-08-07 15:37:50'),
(93, 41, 1, 32, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 02:47:26', '2022-08-16 02:47:26'),
(94, 41, 1, 33, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 02:49:24', '2022-08-16 02:49:24'),
(95, 41, 1, 34, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 02:56:11', '2022-08-16 02:56:11'),
(96, 41, 1, 35, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 02:56:14', '2022-08-16 02:56:14'),
(97, 41, 1, 36, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 03:01:20', '2022-08-16 03:01:20'),
(98, 5, 1, 37, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 10:13:34', '2022-08-16 10:13:34'),
(99, 5, 1, 39, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 10:20:37', '2022-08-16 10:20:37'),
(100, 5, 1, 40, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-16 10:22:15', '2022-08-16 10:22:15'),
(101, 1, 5, 41, 1, '{{owner_name}} shared {{fileName}} with you', '2022-08-17 17:30:36', '2022-08-17 17:30:36');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissionables`
--

DROP TABLE IF EXISTS `permissionables`;
CREATE TABLE IF NOT EXISTS `permissionables` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` int NOT NULL,
  `permissionable_id` int NOT NULL,
  `permissionable_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `restrictions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissionable_unique` (`permission_id`,`permissionable_id`,`permissionable_type`),
  KEY `permissionables_permission_id_index` (`permission_id`),
  KEY `permissionables_permissionable_id_index` (`permissionable_id`),
  KEY `permissionables_permissionable_type_index` (`permissionable_type`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissionables`
--

INSERT INTO `permissionables` (`id`, `permission_id`, `permissionable_id`, `permissionable_type`, `restrictions`) VALUES
(1, 1, 1, 'App\\User', '[]'),
(2, 5, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(3, 10, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(4, 19, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(5, 23, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(6, 27, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(7, 33, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(8, 38, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(10, 49, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(11, 50, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(12, 53, 1, 'Common\\Auth\\Roles\\Role', '[]'),
(13, 10, 2, 'Common\\Auth\\Roles\\Role', '[]'),
(14, 49, 2, 'Common\\Auth\\Roles\\Role', '[]'),
(15, 27, 2, 'Common\\Auth\\Roles\\Role', '[]'),
(16, 33, 2, 'Common\\Auth\\Roles\\Role', '[]'),
(17, 38, 2, 'Common\\Auth\\Roles\\Role', '[]'),
(18, 23, 2, 'Common\\Auth\\Roles\\Role', '[]'),
(19, 18, 3, 'Common\\Auth\\Roles\\Role', NULL),
(20, 19, 3, 'Common\\Auth\\Roles\\Role', NULL),
(21, 20, 3, 'Common\\Auth\\Roles\\Role', NULL),
(22, 21, 3, 'Common\\Auth\\Roles\\Role', NULL),
(23, 22, 3, 'Common\\Auth\\Roles\\Role', NULL),
(24, 46, 3, 'Common\\Auth\\Roles\\Role', NULL),
(25, 47, 3, 'Common\\Auth\\Roles\\Role', NULL),
(26, 48, 3, 'Common\\Auth\\Roles\\Role', NULL),
(27, 18, 4, 'Common\\Auth\\Roles\\Role', NULL),
(28, 19, 4, 'Common\\Auth\\Roles\\Role', NULL),
(29, 20, 4, 'Common\\Auth\\Roles\\Role', NULL),
(30, 21, 4, 'Common\\Auth\\Roles\\Role', NULL),
(31, 22, 4, 'Common\\Auth\\Roles\\Role', NULL),
(32, 18, 5, 'Common\\Auth\\Roles\\Role', NULL),
(33, 18, 2, 'App\\User', '[]'),
(34, 19, 2, 'App\\User', '[]'),
(35, 20, 2, 'App\\User', '[]'),
(42, 1, 25, 'App\\User', '[]'),
(41, 1, 20, 'App\\User', '[]'),
(43, 1, 28, 'App\\User', '[]'),
(50, 1, 39, 'App\\User', '[]'),
(49, 1, 38, 'App\\User', '[]'),
(51, 1, 40, 'App\\User', '[]'),
(52, 1, 41, 'App\\User', '[]'),
(53, 2, 41, 'App\\User', '[]'),
(54, 3, 41, 'App\\User', '[]'),
(55, 4, 41, 'App\\User', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `restrictions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sitewide',
  `advanced` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  KEY `permissions_advanced_index` (`advanced`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `group`, `restrictions`, `created_at`, `updated_at`, `type`, `advanced`) VALUES
(1, 'admin', 'Super Admin', 'Give all permissions to user.', 'admin', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(2, 'admin.access', 'Access Admin', 'Required in order to access any admin area page.', 'admin', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(3, 'appearance.update', 'Update Appearance', 'Allows access to appearance editor.', 'admin', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(4, 'reports.view', 'View Reports', 'Allows access to analytics page in admin area.', 'admin', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(5, 'api.access', 'Access Api', 'Required in order for users to be able to use the API.', 'api', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(6, 'roles.view', 'View Roles', 'Allow viewing ALL roles.', 'roles', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(7, 'roles.create', 'Create Roles', 'Allow creating new roles.', 'roles', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(8, 'roles.update', 'Update Roles', 'Allow updating ALL roles.', 'roles', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(9, 'roles.delete', 'Delete Roles', 'Allow deleting ALL roles.', 'roles', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(10, 'custom_pages.view', 'View Custom Pages', 'Allow viewing of all pages on the site, regardless of who created them. User can view their own pages without this permission.', 'custom_pages', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(11, 'custom_pages.create', 'Create Custom Pages', 'Allow creating new custom pages.', 'custom_pages', '[{\"name\":\"count\",\"type\":\"number\",\"description\":\"Maximum number of pages user will be able to create. Leave empty for unlimited.\"}]', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(12, 'custom_pages.update', 'Update Custom Pages', 'Allow editing of all pages on the site, regardless of who created them. User can edit their own pages without this permission.', 'custom_pages', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(13, 'custom_pages.delete', 'Delete Custom Pages', 'Allow deleting of all pages on the site, regardless of who created them. User can delete their own pages without this permission.', 'custom_pages', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(14, 'custom_domains.view', 'View Custom Domains', 'Allow viewing all domains on the site, regardless of who created them. User can view their own domains without this permission.', 'custom_domains', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(15, 'custom_domains.create', 'Create Custom Domains', 'Allow user to connect their own custom domains.', 'custom_domains', '[{\"name\":\"count\",\"type\":\"number\",\"description\":\"Maximum number of domains user will be able to create. Leave empty for unlimited.\"}]', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(16, 'custom_domains.update', 'Update Custom Domains', 'Allow editing all domains on the site, regardless of who created them. User can edit their own domains without this permission.', 'custom_domains', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(17, 'custom_domains.delete', 'Delete Custom Domains', 'Allow deleting all domains on the site, regardless of who created them. User can delete their own domains without this permission.', 'custom_domains', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(18, 'files.view', 'View Files', 'Allow viewing all uploaded files on the site. Users can view their own uploads without this permission.', 'files', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(19, 'files.create', 'Create Files', 'Allow uploading files on the site. This permission is used by any page where it is possible for user to upload files.', 'files', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(20, 'files.update', 'Update Files', 'Allow editing all uploaded files on the site. Users can edit their own uploads without this permission.', 'files', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(21, 'files.delete', 'Delete Files', 'Allow deleting all uploaded files on the site. Users can delete their own uploads (where applicable) without this permission.', 'files', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(22, 'files.download', 'Download Files', 'Allow downloading all uploaded files on the site. Users can download their own uploads (where applicable) without this permission.', 'files', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(23, 'users.view', 'View Users', 'Allow viewing user profile pages on the site. User can view their own profile without this permission.', 'users', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(24, 'users.create', 'Create Users', 'Allow creating users from admin area. Users can register for new accounts without this permission. Registration can be disabled from settings page.', 'users', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(25, 'users.update', 'Update Users', 'Allow editing details of any user on the site. User can edit their own details without this permission.', 'users', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(26, 'users.delete', 'Delete Users', 'Allow deleting any user on the site. User can request deletion of their own account without this permission.', 'users', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(27, 'localizations.view', 'View Localizations', 'Allow viewing ALL localizations.', 'localizations', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(28, 'localizations.create', 'Create Localizations', 'Allow creating new localizations.', 'localizations', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(29, 'localizations.update', 'Update Localizations', 'Allow updating ALL localizations.', 'localizations', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(30, 'localizations.delete', 'Delete Localizations', 'Allow deleting ALL localizations.', 'localizations', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(31, 'settings.view', 'View Settings', 'Allow viewing ALL settings.', 'settings', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(32, 'settings.update', 'Update Settings', 'Allow updating ALL settings.', 'settings', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(33, 'plans.view', 'View Plans', 'Allow viewing ALL plans.', 'plans', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(34, 'plans.create', 'Create Plans', 'Allow creating new plans.', 'plans', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(35, 'plans.update', 'Update Plans', 'Allow updating ALL plans.', 'plans', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(36, 'plans.delete', 'Delete Plans', 'Allow deleting ALL plans.', 'plans', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(37, 'invoices.view', 'View Invoices', 'Allow viewing ALL invoices.', 'invoices', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(38, 'tags.view', 'View Tags', 'Allow viewing ALL tags.', 'tags', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(39, 'tags.create', 'Create Tags', 'Allow creating new tags.', 'tags', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(40, 'tags.update', 'Update Tags', 'Allow updating ALL tags.', 'tags', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(41, 'tags.delete', 'Delete Tags', 'Allow deleting ALL tags.', 'tags', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 1),
(42, 'workspaces.view', 'View Workspaces', 'Allow viewing ALL workspaces.', 'workspaces', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(43, 'workspaces.create', 'Create Workspaces', 'Allow creating new workspaces.', 'workspaces', '[{\"name\":\"count\",\"type\":\"number\",\"description\":\"Maximum number of workspaces user will be able to create. Leave empty for unlimited.\"},{\"name\":\"member_count\",\"type\":\"number\",\"description\":\"Maximum number of members workspace is allowed to have.\"}]', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(44, 'workspaces.update', 'Update Workspaces', 'Allow updating ALL workspaces.', 'workspaces', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(45, 'workspaces.delete', 'Delete Workspaces', 'Allow deleting ALL workspaces.', 'workspaces', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(46, 'workspace_members.invite', 'Invite Members', 'Allow user to invite new members into a workspace.', 'workspace_members', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'workspace', 0),
(47, 'workspace_members.update', 'Update Members', 'Allow user to change role of other members.', 'workspace_members', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'workspace', 0),
(48, 'workspace_members.delete', 'Delete Members', 'Allow user to remove members from workspace.', 'workspace_members', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'workspace', 0),
(49, 'links.view', 'View Links', 'Allow viewing ALL links.', 'links', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(50, 'links.create', 'Create Links', 'Allow creating new links.', 'links', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(51, 'links.update', 'Update Links', 'Allow updating ALL links.', 'links', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(52, 'links.delete', 'Delete Links', 'Allow deleting ALL links.', 'links', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0),
(53, 'notifications.subscribe', 'Subscribe Notifications', 'Allows agents to subscribe to various conversation notifications.', 'notifications', NULL, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'sitewide', 0);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legacy_permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `default` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `guests` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sitewide',
  `internal` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`),
  KEY `groups_default_index` (`default`),
  KEY `groups_guests_index` (`guests`),
  KEY `roles_internal_index` (`internal`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `legacy_permissions`, `default`, `guests`, `created_at`, `updated_at`, `description`, `type`, `internal`) VALUES
(1, 'users', NULL, 1, 0, '2022-02-16 02:38:29', '2022-02-16 02:38:29', NULL, 'sitewide', 0),
(2, 'guests', NULL, 0, 1, '2022-02-16 02:38:29', '2022-02-16 02:38:29', NULL, 'sitewide', 0),
(3, 'Workspace Admin', NULL, 0, 0, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'Manage workspace content, members, settings and invite new members.', 'workspace', 0),
(4, 'Workspace Editor', NULL, 0, 0, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'Add, edit, move and delete workspace files.', 'workspace', 0),
(5, 'Workspace Contributor', NULL, 0, 0, '2022-02-16 02:38:29', '2022-02-16 02:38:29', 'Add and edit files.', 'workspace', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_name_unique` (`name`),
  KEY `settings_private_index` (`private`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`, `private`) VALUES
(1, 'dates.format', 'yyyy-MM-dd', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(2, 'dates.locale', 'en_US', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(3, 'social.google.enable', 'false', '2022-02-16 02:38:29', '2022-03-10 13:43:34', 0),
(4, 'social.twitter.enable', 'false', '2022-02-16 02:38:29', '2022-03-10 13:43:34', 0),
(5, 'social.facebook.enable', 'false', '2022-02-16 02:38:29', '2022-03-10 13:43:34', 0),
(6, 'realtime.enable', 'false', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(7, 'registration.disable', 'false', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(8, 'branding.favicon', 'client/favicon/icon-144x144.png', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(9, 'branding.logo_dark', 'storage/branding_media/p4n9y40viZlFCylHbTEeevi3CZXIBHLCSfCIJ5pH.png', '2022-02-16 02:38:29', '2022-05-24 01:22:12', 0),
(10, 'branding.logo_light', 'storage/branding_media/c88GjdppklGojauyP6NP6farD2XWA4OZSvgqt35F.png', '2022-02-16 02:38:29', '2022-05-24 01:22:12', 0),
(11, 'i18n.default_localization', 'en', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(12, 'i18n.enable', 'true', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(13, 'logging.sentry_public', '', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(14, 'realtime.pusher_key', '', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(15, 'homepage.type', 'default', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(16, 'themes.default_mode', 'light', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(17, 'themes.user_change', 'true', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(18, 'billing.enable', 'false', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(19, 'billing.paypal_test_mode', 'true', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(20, 'billing.stripe_test_mode', 'true', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(21, 'billing.stripe.enable', 'false', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(22, 'billing.paypal.enable', 'false', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(23, 'billing.accepted_cards', '[\"visa\",\"mastercard\",\"american-express\",\"discover\"]', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(24, 'custom_domains.default_host', '', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(25, 'uploads.chunk', 'true', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(26, 'cookie_notice.enable', 'true', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(27, 'cookie_notice.position', 'bottom', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(28, 'branding.site_name', 'BeDrive', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(29, 'cache.report_minutes', '60', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(30, 'site.force_https', '0', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(31, 'menus', '[{\"position\":\"drive-sidebar\",\"items\":[{\"type\":\"route\",\"condition\":null,\"target\":null,\"activeExact\":false,\"order\":1,\"label\":\"Shared with me\",\"action\":\"drive/shares\",\"icon\":\"people\",\"id\":491},{\"type\":\"route\",\"condition\":null,\"target\":null,\"activeExact\":false,\"order\":2,\"label\":\"Recent\",\"action\":\"drive/recent\",\"icon\":\"access-time\",\"id\":827},{\"type\":\"route\",\"condition\":null,\"target\":null,\"activeExact\":false,\"order\":3,\"label\":\"Starred\",\"action\":\"drive/starred\",\"icon\":\"star\",\"id\":183},{\"type\":\"route\",\"condition\":null,\"target\":null,\"activeExact\":false,\"order\":4,\"label\":\"Trash\",\"action\":\"drive/trash\",\"icon\":\"delete\",\"id\":358}],\"name\":\"Drive Sidebar\"},{\"position\":\"footer-secondary\",\"items\":[],\"name\":\"Footer Social\"}]', '2022-02-16 02:38:29', '2022-04-27 02:27:46', 0),
(32, 'uploads.max_size', '53687091200', '2022-02-16 02:38:29', '2022-02-16 02:56:14', 0),
(33, 'uploads.chunk_size', '15728640', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(34, 'uploads.available_space', '104857600', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(35, 'uploads.blocked_extensions', '[\"exe\",\"application/x-msdownload\",\"x-dosexec\"]', '2022-02-16 02:38:29', '2022-02-16 02:56:14', 0),
(36, 'homepage.appearance', '{\"headerTitle\":\"JAA Repository\",\"headerSubtitle\":\"Register or Login now to upload, backup, manage and access your files on any device, from anywhere, free.\",\"headerImage\":\"storage\\/homepage\\/fZQnhWWNJFKPxnF2wfOfL9ZswPOEGwa334UE1R4W.jpg\",\"headerImageOpacity\":0.7,\"headerOverlayColor1\":\"rgba(62,62,62,1)\",\"headerOverlayColor2\":\"rgba(0,0,0,1)\",\"footerTitle\":\"Get started with Easy Docs\",\"footerSubtitle\":null,\"footerImage\":\"client\\/assets\\/images\\/homepage\\/homepage-footer-bg.svg\",\"actions\":{\"cta1\":\"Register Now\",\"cta2\":null,\"cta3\":\"Sign up for free\"},\"primaryFeatures\":[{\"title\":\"Store any file\",\"subtitle\":\"Keep documents and files.\",\"image\":\"storage\\/homepage\\/FBL4XQDCv1ctujMJAUentcRle7IAj8guID4hv73V.png\"},{\"title\":\"See your stuff anywhere\",\"subtitle\":\"Your files in JAA Repository can be reached from any smartphone, tablet, or computer.\",\"image\":\"storage\\/homepage\\/4tJDuenC5Wh6gtKfyNFpFdXSCWzVkeuS0YuXhm5a.png\"},{\"title\":\"Share files and folders\",\"subtitle\":\"You can quickly invite others to view, download, and collaborate on all the files you want.\",\"image\":\"storage\\/homepage\\/WUkZ5osSrhIUkLhtb2LoYvZKYK3L2v8txl9CdR1j.png\"}],\"secondaryFeatures\":[{\"title\":\"Uploading file made easy\",\"subtitle\":null,\"image\":\"storage\\/homepage\\/7y8SZt5QILb19HJ8pEEYGecfSeWB1Sg3jGcCLlLw.jpg\",\"description\":\"making your file uploading in easy step process. keeping files in one place access.\"},{\"title\":\"Robust storage and fast transfers\",\"subtitle\":null,\"image\":\"storage\\/homepage\\/Qi9GkByuZFjPFbP0nW9prtEfZQ0i7fVUT63pBrVi.jpg\",\"description\":\"Secure cloud storage simple and convenient.\"},{\"title\":null,\"subtitle\":null,\"image\":null,\"description\":null}]}', '2022-02-16 02:38:29', '2022-05-24 01:21:47', 0),
(37, 'drive.default_view', 'grid', '2022-02-16 02:38:29', '2022-02-16 02:38:29', 0),
(38, 'drive.send_share_notification', 'true', '2022-02-16 02:38:29', '2022-08-15 21:16:27', 0),
(39, 'share.suggest_emails', 'true', '2022-02-16 02:38:29', '2022-08-07 08:42:59', 0),
(40, 'uploads.allowed_extensions', '[]', '2022-02-16 02:56:14', '2022-02-16 02:56:14', 0),
(41, 'branding.site_description', 'jaarepository', '2022-03-10 13:41:17', '2022-05-24 01:22:50', 0),
(42, 'single_device_login', 'true', '2022-03-10 13:43:34', '2022-03-10 13:43:34', 0),
(43, 'seo.home.show.og:title', 'TechSavy\'s DocuZilla', '2022-05-04 17:07:43', '2022-05-04 17:07:43', 0),
(44, 'seo.home.show.og:description', 'TechSavy\'s DocuZilla\'s secure cloud storage for your photos, videos, music, and other files.', '2022-05-04 17:07:43', '2022-05-04 17:08:13', 0),
(45, 'seo.home.show.keywords', 'files, secure, cloud, storage', '2022-05-04 17:07:43', '2022-05-04 17:07:43', 0),
(46, 'seo.custom-page.show.og:title', '{{page.title}} - {{site_name}}', '2022-05-04 17:07:43', '2022-05-04 17:07:43', 0),
(47, 'seo.custom-page.show.og:description', '{{page.body}}', '2022-05-04 17:07:43', '2022-05-04 17:07:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shareable_links`
--

DROP TABLE IF EXISTS `shareable_links`;
CREATE TABLE IF NOT EXISTS `shareable_links` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `hash` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `entry_id` int UNSIGNED NOT NULL,
  `allow_edit` tinyint(1) NOT NULL DEFAULT '0',
  `allow_download` tinyint(1) NOT NULL DEFAULT '1',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shareable_links_hash_unique` (`hash`),
  KEY `shareable_links_user_id_index` (`user_id`),
  KEY `shareable_links_entry_id_index` (`entry_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_profiles`
--

DROP TABLE IF EXISTS `social_profiles`;
CREATE TABLE IF NOT EXISTS `social_profiles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `service_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_service_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_profiles_user_id_service_name_unique` (`user_id`,`service_name`),
  UNIQUE KEY `social_profiles_service_name_user_service_id_unique` (`service_name`,`user_service_id`),
  KEY `social_profiles_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `plan_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gateway_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `quantity` int NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `renews_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_index` (`user_id`),
  KEY `subscriptions_plan_id_index` (`plan_id`),
  KEY `subscriptions_gateway_index` (`gateway_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taggables`
--

DROP TABLE IF EXISTS `taggables`;
CREATE TABLE IF NOT EXISTS `taggables` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag_id` int UNSIGNED NOT NULL,
  `taggable_id` int UNSIGNED NOT NULL,
  `taggable_type` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taggables_tag_id_taggable_id_user_id_taggable_type_unique` (`tag_id`,`taggable_id`,`user_id`,`taggable_type`),
  KEY `taggables_tag_id_index` (`tag_id`),
  KEY `taggables_taggable_id_index` (`taggable_id`),
  KEY `taggables_taggable_type_index` (`taggable_type`),
  KEY `taggables_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_type_unique` (`name`,`type`),
  KEY `tags_type_index` (`type`),
  KEY `tags_created_at_index` (`created_at`),
  KEY `tags_updated_at_index` (`updated_at`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `display_name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'starred', 'Starred', 'label', '2022-02-16 02:38:29', '2022-02-16 02:38:29'),
(2, 'dsad', 'dsad', 'custom', '2022-03-13 00:11:39', '2022-03-13 00:11:39');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(191) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uploads_file_name_unique` (`file_name`),
  KEY `uploads_name_index` (`name`),
  KEY `uploads_user_id_index` (`user_id`),
  KEY `uploads_public_index` (`public`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `space_available` bigint UNSIGNED DEFAULT NULL,
  `language` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_space` bigint UNSIGNED DEFAULT NULL,
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legacy_permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `card_brand` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_four` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_login` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_username_index` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `space_available`, `language`, `country`, `timezone`, `avatar`, `stripe_id`, `available_space`, `first_name`, `last_name`, `legacy_permissions`, `card_brand`, `card_last_four`, `email_verified_at`, `is_login`) VALUES
(1, 'admin', 'admin@gmail.com', '$2a$12$uJAmLnSj3zbM0emLoJzZve.O1KOPVU4PcfmYcJYejSc.cmWRBX1aa', 'fUxFpUBM4YeORab2mjW6xwnN70l2NKjJA68dbnRXrRb53FUNIFlwA2fW6gAz', '2022-02-16 02:38:29', '2022-08-17 13:36:21', NULL, 'english', NULL, NULL, 'avatars/dKrWaPKqJuXIl1DExsrKxJLisQaYHIsmezTJ19WG.png', NULL, NULL, 'admin', NULL, NULL, NULL, NULL, '2022-05-31 04:49:57', 1),
(2, 'nabajit', 'nabajitbb@gmail.com', '$2a$12$uJAmLnSj3zbM0emLoJzZve.O1KOPVU4PcfmYcJYejSc.cmWRBX1aa', '1EDw4Y9GpjJP8rzJzLCczbvlWfMF2nKVJPg7AIZk9WLVqgD2rjGTSLFb9phH', '2022-02-16 02:42:31', '2022-02-16 02:42:31', NULL, 'english', NULL, NULL, NULL, NULL, 21474836480, 'Nabajit', 'Borah', NULL, NULL, NULL, '2022-02-16 02:42:30', NULL),
(5, 'lfree', 'lfree4857@gmail.com', '$2y$10$LvBagkkHltwc4JRBjBPf4eza7R0NgOwX.sJND7YOCScIVHaOEBo0a', 'vS8vhIEMenZ3TQVLu0hlKNleYewGca2sdbaMzLCVhS84VIjT7ouOaXjx5MuI', '2022-03-10 16:19:11', '2022-09-07 02:17:08', NULL, 'english', NULL, NULL, 'https://www.gravatar.com/avatar/4b17cf466ffec8d43fc403bdbfc7e92e?s=&d=retro', NULL, 1048576000, 'lfree', NULL, NULL, NULL, NULL, '2022-03-11 00:08:21', NULL),
(43, 'jiaulahmed', 'jiaulahmed030@gmail.com', '$2a$12$uJAmLnSj3zbM0emLoJzZve.O1KOPVU4PcfmYcJYejSc.cmWRBX1aa', 'FNwOIIXujSRhnXr4t4OlQA7SUShNgdSBiW2h06Dspo1aiXHGSNh530wykvBf', '2022-07-31 06:32:20', '2022-07-31 06:33:42', NULL, 'english', NULL, NULL, NULL, NULL, NULL, 'Jiaul', 'Haque', NULL, NULL, NULL, '2022-07-31 06:32:20', 0),
(41, 'jiaulh', 'jiaulh138@gmail.com', '$2y$10$LRiZfhMzO4aJUY74qtcEG.91FTsXRWAdV5eyHupw5Ec9/gamcBgiq', '7115tI5pHbSNBVKgSlTtQiqeNUtNA00RZJ86mVNbXMw3ftTf8qsu7RV8sTOS', '2022-06-06 02:36:43', '2022-09-07 02:10:58', NULL, 'english', NULL, NULL, 'https://www.gravatar.com/avatar/721bab9ae6f4877004b8039e8b663967?s=&d=retro', NULL, NULL, 'Jiaul', 'Haque', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_oauth`
--

DROP TABLE IF EXISTS `users_oauth`;
CREATE TABLE IF NOT EXISTS `users_oauth` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `service` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_oauth_user_id_service_unique` (`user_id`,`service`),
  UNIQUE KEY `users_oauth_token_unique` (`token`),
  KEY `users_oauth_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_group_user_id_group_id_unique` (`user_id`,`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `created_at`) VALUES
(1, 1, 1, '2022-02-16 02:38:29'),
(2, 2, 1, NULL),
(5, 4, 3, NULL),
(6, 5, 1, NULL),
(7, 6, 1, NULL),
(8, 7, 1, NULL),
(9, 11, 1, NULL),
(10, 11, 4, NULL),
(11, 11, 5, NULL),
(12, 13, 1, NULL),
(13, 16, 1, NULL),
(14, 17, 1, NULL),
(15, 18, 1, NULL),
(16, 19, 1, NULL),
(17, 20, 1, NULL),
(18, 21, 1, NULL),
(19, 22, 1, NULL),
(20, 23, 1, NULL),
(21, 24, 1, NULL),
(22, 25, 1, NULL),
(23, 26, 1, NULL),
(24, 28, 1, NULL),
(25, 29, 1, NULL),
(26, 30, 1, NULL),
(30, 38, 1, NULL),
(31, 39, 1, NULL),
(32, 40, 1, NULL),
(33, 41, 1, NULL),
(34, 42, 1, NULL),
(35, 43, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

DROP TABLE IF EXISTS `workspaces`;
CREATE TABLE IF NOT EXISTS `workspaces` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workspaces_owner_id_index` (`owner_id`),
  KEY `workspaces_created_at_index` (`created_at`),
  KEY `workspaces_updated_at_index` (`updated_at`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workspaces`
--

INSERT INTO `workspaces` (`id`, `name`, `owner_id`, `created_at`, `updated_at`) VALUES
(3, 'IT Department', 1, '2022-03-04 02:30:24', '2022-03-04 02:30:24'),
(4, 'gplus', 1, '2022-05-21 14:49:51', '2022-05-21 14:49:51');

-- --------------------------------------------------------

--
-- Table structure for table `workspace_invites`
--

DROP TABLE IF EXISTS `workspace_invites`;
CREATE TABLE IF NOT EXISTS `workspace_invites` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workspace_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workspace_invites_workspace_id_index` (`workspace_id`),
  KEY `workspace_invites_user_id_index` (`user_id`),
  KEY `workspace_invites_email_index` (`email`),
  KEY `workspace_invites_role_id_index` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_user`
--

DROP TABLE IF EXISTS `workspace_user`;
CREATE TABLE IF NOT EXISTS `workspace_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `workspace_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED DEFAULT NULL,
  `is_owner` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workspace_user_workspace_id_user_id_unique` (`workspace_id`,`user_id`),
  KEY `workspace_user_user_id_index` (`user_id`),
  KEY `workspace_user_workspace_id_index` (`workspace_id`),
  KEY `workspace_user_role_id_index` (`role_id`),
  KEY `workspace_user_is_owner_index` (`is_owner`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workspace_user`
--

INSERT INTO `workspace_user` (`id`, `user_id`, `workspace_id`, `role_id`, `is_owner`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, '2022-03-04 01:01:05', '2022-03-04 01:01:05'),
(2, 1, 2, NULL, 1, '2022-03-04 01:49:11', '2022-03-04 01:49:11'),
(3, 1, 3, NULL, 1, '2022-03-04 02:30:24', '2022-03-04 02:30:24'),
(4, 1, 4, NULL, 1, '2022-05-21 14:49:51', '2022-05-21 14:49:51');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
