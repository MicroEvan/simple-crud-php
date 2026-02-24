-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 06:32 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- 
-- Database: `tutorial`
--

-- --------------------------------------------------------

-- 
-- Drop the table if it exists
--
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `certificate`;
DROP TABLE IF EXISTS `certificate_history`;

-- 
-- Table structure for table `user`
--
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin', 'user') NOT NULL DEFAULT 'user',
  `status` enum('active', 'inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 
-- Table structure for table `certificate`
--
CREATE TABLE `certificate` (
  `certificate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(255) NOT NULL,
  `registration_number` varchar(255) NOT NULL,
  `vin_number` varchar(255) NOT NULL,
  `tank_description` text NOT NULL,
  `trailer_compartments` int(11) NOT NULL,
  `job_number` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, -- New timestamp column
  `user_id` int(11) NOT NULL, -- New user ID column
  PRIMARY KEY (`certificate_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) -- Foreign key constraint
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 
-- Table structure for table `certificate_history`
--
CREATE TABLE `certificate_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_id` int(11) NOT NULL,
  `customer` varchar(255),
  `registration_number` varchar(255),
  `vin_number` varchar(255),
  `tank_description` text,
  `trailer_compartments` int(11),
  `job_number` int(11),
  `expiry_date` date,
  `edited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`history_id`),
  FOREIGN KEY (`certificate_id`) REFERENCES `certificate`(`certificate_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert admin user with hashed password
INSERT INTO `user` (`name`, `email`, `password`, `role`, `status`) VALUES 
('admin', 'admin@localhost.com', 'admin', 'admin', 'active'); -- Default password is 'admin'

-- AUTO_INCREMENT for table `user`
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- AUTO_INCREMENT for table `certificate`
ALTER TABLE `certificate`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- AUTO_INCREMENT for table `certificate_history`
ALTER TABLE `certificate_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
