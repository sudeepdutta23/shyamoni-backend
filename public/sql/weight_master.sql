-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 01, 2022 at 03:10 AM
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
-- Table structure for table `weight_master`
--

DROP TABLE IF EXISTS `weight_master`;
CREATE TABLE IF NOT EXISTS `weight_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weight` bigint(20) DEFAULT NULL,
  `deliveryCharge` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `weight_master`
--

INSERT INTO `weight_master` (`id`, `weight`, `deliveryCharge`, `created_at`, `updated_at`) VALUES
(1, 100, 50, '2022-06-10 16:45:50', '2022-06-10 16:45:50'),
(2, 200, 60, '2022-06-10 16:45:50', '2022-06-10 16:45:50'),
(3, 150, 79, '2022-06-10 16:46:09', '2022-06-10 16:46:09'),
(4, 250, 80, '2022-06-10 16:46:09', '2022-06-10 16:46:09');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
