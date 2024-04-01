-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2024 at 03:23 AM
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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(36) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `items_ordered` text DEFAULT NULL,
  `item_quantity` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `date_of_purchase` date DEFAULT NULL,
  `status` enum('Pending','Processing','Shipped','Delivered') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `firstname`, `lastname`, `address`, `items_ordered`, `item_quantity`, `total`, `date_of_purchase`, `status`) VALUES
('66078e95dde1f', 2, 'Ivan', 'Castro', '54D Magsalin Compound Caingin Street Quezon City Metro Manila 1116', 'Naruto', '1', '600.00', '2024-03-30', 'Pending'),
('6607902142cae', 2, 'Ivan', 'Castro', '54D Magsalin Compound Caingin Street Quezon City Metro Manila 1116', 'Zoro', '1', '500.00', '2024-03-30', 'Delivered'),
('66079051e7f9b', 1, 'Ivan', 'Castro', 'Goodhaven Compound 2 Urbano Street Quezon City Metro Manila 1116', 'Naruto, Zoro', '6, 6', '1800.00', '2024-03-30', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
