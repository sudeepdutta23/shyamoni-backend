-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 04, 2022 at 07:27 PM
-- Server version: 5.7.36
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `mail_messages`
--

DROP TABLE IF EXISTS `mail_messages`;
CREATE TABLE IF NOT EXISTS `mail_messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_subject` text COLLATE utf8mb4_unicode_ci,
  `message_body` text COLLATE utf8mb4_unicode_ci,
  `constant` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mail_messages`
--

INSERT INTO `mail_messages` (`id`, `message_subject`, `message_body`, `constant`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'User Register Subject', '<p style=\"color:black;\"><em><strong>Congratulations {{name}}, you have successfully registered to shyamoni\n</strong></em></p>', 1, NULL, '2022-05-29 11:26:29', '2022-05-29 11:31:58'),
(2, 'User OTP', '<p><em><strong>Your OTP is {{digits}}. Thank You.</strong></em></p>', 2, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38'),
(5, 'Order Create', '<p><em><strong>Your Order Created. Order Number is {{randomOrderID}}</strong></em></p>\n', 5, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38'),
(3, 'User Feedback', '<p><em><strong>{{email}}. <br> {{comment}}. <br> Thanks and regards.</strong></em></p>', 3, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38'),
(4, 'Order Cancel', '{{randomOrderID}}', 4, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38'),
(7, 'Order Not Created', '<p><em><strong>Order Number {{randomOrderID}} is not created. Payment Will Be Refunded within Seven Days!</strong></em></p>\r\n', 7, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38'),
(6, 'Customer Order Cancel', '<p><em><strong>Order Number {{randomOrderID}} is Cancelled.</strong></em></p>\n', 6, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38'),
(8, 'password update', '<p><em><strong>Your Password updated Successfully!</strong></em></p>\n', 8, NULL, '2022-05-29 11:26:38', '2022-05-29 11:26:38');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
