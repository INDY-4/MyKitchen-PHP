-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2024 at 11:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mykitchen`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `address_owner` int(11) NOT NULL COMMENT 'FK to user or kitchen',
  `address_type` varchar(10) NOT NULL COMMENT 'user | kitchen',
  `address_line1` varchar(100) NOT NULL,
  `address_line2` varchar(100) NOT NULL,
  `address_city` varchar(50) NOT NULL,
  `address_state` varchar(30) NOT NULL,
  `address_zip` varchar(15) NOT NULL,
  `address_phone` varchar(30) NOT NULL COMMENT 'if user',
  `address_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kitchens`
--

CREATE TABLE `kitchens` (
  `kitchen_id` int(11) NOT NULL,
  `kitchen_owner` int(11) NOT NULL COMMENT 'FK to user_id',
  `kitchen_name` varchar(100) NOT NULL,
  `kitchen_working_hours` varchar(750) DEFAULT NULL,
  `kitchen_is_active` tinyint(1) NOT NULL DEFAULT 1,
  `kitchen_uses_cash` tinyint(1) NOT NULL DEFAULT 1,
  `kitchen_uses_card` tinyint(1) NOT NULL DEFAULT 1,
  `kitchen_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This holds data about the Kitchens in the site.';

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_delivery_methods`
--

CREATE TABLE `kitchen_delivery_methods` (
  `kdm_id` int(11) NOT NULL,
  `kdm_owner` int(11) NOT NULL COMMENT 'fk to kitchen',
  `kdm_type` varchar(30) NOT NULL COMMENT 'delivery | local pickup',
  `kdm_range` tinyint(3) UNSIGNED NOT NULL COMMENT '0-255 miles',
  `kdm_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_kitchen_id` int(11) NOT NULL COMMENT 'FK to kitchen',
  `order_user_id` int(11) NOT NULL COMMENT 'FK to user',
  `order_products` varchar(1000) NOT NULL,
  `order_total` float NOT NULL,
  `order_status` varchar(30) NOT NULL,
  `order_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_kitchen_id` int(11) NOT NULL COMMENT 'FK to kitchen',
  `product_title` varchar(255) NOT NULL,
  `product_desc` varchar(1000) DEFAULT NULL,
  `product_price` float NOT NULL,
  `product_category` varchar(100) DEFAULT NULL,
  `product_tags` varchar(500) DEFAULT NULL,
  `product_image_url` varchar(255) DEFAULT NULL,
  `product_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_pass` varchar(32) NOT NULL COMMENT 'md5''d user password',
  `user_banner_url` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `kitchens`
--
ALTER TABLE `kitchens`
  ADD PRIMARY KEY (`kitchen_id`),
  ADD KEY `kitchen_owner` (`kitchen_owner`);

--
-- Indexes for table `kitchen_delivery_methods`
--
ALTER TABLE `kitchen_delivery_methods`
  ADD PRIMARY KEY (`kdm_id`),
  ADD KEY `kdm_owner` (`kdm_owner`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kitchens`
--
ALTER TABLE `kitchens`
  MODIFY `kitchen_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kitchen_delivery_methods`
--
ALTER TABLE `kitchen_delivery_methods`
  MODIFY `kdm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kitchens`
--
ALTER TABLE `kitchens`
  ADD CONSTRAINT `kitchens_ibfk_1` FOREIGN KEY (`kitchen_owner`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kitchen_delivery_methods`
--
ALTER TABLE `kitchen_delivery_methods`
  ADD CONSTRAINT `kitchen_delivery_methods_ibfk_1` FOREIGN KEY (`kdm_owner`) REFERENCES `kitchens` (`kitchen_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
