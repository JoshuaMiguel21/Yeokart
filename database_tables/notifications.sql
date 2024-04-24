-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 01:40 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yeokart_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_status` tinyint(1) DEFAULT 0,
  `order_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `customer_id`, `title`, `message`, `created_at`, `read_status`, `order_id`) VALUES
(1, 1, 'Order Status Update', 'Your order (ID: 6623f4396cc16) is still pending. We will update you once it progresses.', '2024-04-23 16:26:51', 1, '6623f4396cc16'),
(2, 1, 'Order Status Update', 'Your order (ID: 6623f4396cc16) is currently being processed.', '2024-04-23 16:29:13', 1, '6623f4396cc16'),
(3, 1, 'Order Status Update', 'Your order (ID: 6623f4396cc16) has been shipped and is on its way to you.', '2024-04-23 16:34:59', 1, '6623f4396cc16'),
(4, 1, 'Order Status Update', 'The proof of payment for your order (ID: 6627e3c059817) has been found to be invalid. Please upload a valid proof of payment or contact support.', '2024-04-23 16:37:52', 1, '6627e3c059817'),
(5, 1, 'Order Status Update', 'Your order (ID: 6627e3c059817) is currently being processed.', '2024-04-23 16:38:11', 1, '6627e3c059817'),
(6, 1, 'Order Status Update', 'Your order (ID: 6623f4396cc16) has been delivered. Thank you for shopping with us!', '2024-04-23 16:50:44', 1, '6623f4396cc16'),
(7, 1, 'Order Status Update', 'Your order (ID: 6627e3c059817) has been shipped and is on its way to you.', '2024-04-24 05:59:55', 1, '6627e3c059817'),
(8, 1, 'Order Status Update', 'Your order (ID: 6627e3c059817) is still pending. We will update you once it progresses.', '2024-04-24 06:42:12', 1, '6627e3c059817');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `fk_order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `user_accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
