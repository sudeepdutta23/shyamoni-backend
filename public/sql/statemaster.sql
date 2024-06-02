-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 07, 2022 at 08:26 PM
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
-- Table structure for table `statemaster`
--

DROP TABLE IF EXISTS `statemaster`;
CREATE TABLE IF NOT EXISTS `statemaster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `state_name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statemaster`
--

INSERT INTO `statemaster` (`id`, `state_id`, `state_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Andaman and Nicobar Islands', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(2, 2, 'Andhra Pradesh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(3, 3, 'Arunachal Pradesh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(4, 4, 'Assam', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(5, 5, 'Bihar', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(6, 6, 'Chandigarh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(7, 7, 'Chhattisgarh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(8, 8, 'Dadra and Nagar Haveli', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(9, 9, 'Daman and Diu', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(10, 10, 'Delhi', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(11, 11, 'Goa', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(12, 12, 'Gujarat', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(13, 13, 'Haryana', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(14, 14, 'Himachal Pradesh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(15, 15, 'Jammu and Kashmir', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(16, 16, 'Jharkhand', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(17, 17, 'Karnataka', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(18, 19, 'Kerala', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(19, 20, 'Lakshadweep', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(20, 21, 'Madhya Pradesh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(21, 22, 'Maharashtra', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(22, 23, 'Manipur', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(23, 24, 'Meghalaya', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(24, 25, 'Mizoram', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(25, 26, 'Nagaland', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(26, 29, 'Odisha', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(27, 31, 'Pondicherry', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(28, 32, 'Punjab', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(29, 33, 'Rajasthan', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(30, 34, 'Sikkim', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(31, 35, 'Tamil Nadu', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(32, 36, 'Telangana', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(33, 37, 'Tripura', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(34, 38, 'Uttar Pradesh', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(35, 39, 'Uttarakhand', '2022-08-07 17:42:55', '2022-08-07 17:42:55'),
(36, 41, 'West Bengal', '2022-08-07 17:42:55', '2022-08-07 17:42:55');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
