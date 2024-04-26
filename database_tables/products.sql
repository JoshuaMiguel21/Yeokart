-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2024 at 02:32 PM
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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `item_description` varchar(500) NOT NULL,
  `item_quantity` int(255) NOT NULL,
  `artist_name` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `item_size` enum('Small','Medium','Large','') DEFAULT NULL,
  `item_image1` varchar(250) NOT NULL,
  `item_image2` varchar(250) NOT NULL,
  `item_image3` varchar(250) NOT NULL,
  `is_featured` tinyint(1) NOT NULL,
  `times_sold` int(11) NOT NULL,
  `is_archive` tinyint(1) NOT NULL,
  `archive_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`item_id`, `item_name`, `item_price`, `item_description`, `item_quantity`, `artist_name`, `category_name`, `item_size`, `item_image1`, `item_image2`, `item_image3`, `is_featured`, `times_sold`, `is_archive`, `archive_timestamp`) VALUES
(2, 'Test Product 2 ', '200.00', 'Test Product 2 description', 1, 'Twice', 'Photocards', 'Small', 'soyeon special heat.jpg', '', '', 0, 3, 1, '2024-04-21 08:54:24'),
(3, 'Test Product 3 - Albums', '600.75', 'Test product 3 description', 6, 'ITZY', 'Album', 'Large', 'twice_album_3b.jpg', 'twice_album_3c.jpg', '', 1, 13, 0, NULL),
(4, 'Test Product 4 - Lightstick', '2500.00', 'Test Product 4 description', 0, 'Twice', 'Lightsticks', 'Medium', 'lightstick1.jpg', '', '', 1, 5, 1, '2024-04-25 08:25:25'),
(5, 'Test Product 5', '2300.00', 'Test Product 5 description', 1, 'Seventeen', 'Lightsticks', 'Medium', 'lightstick2.jpg', '', '', 0, 4, 0, NULL),
(6, 'Test Product 6 - Photocard', '150.00', 'This is Test Product 6 description', 0, 'Seventeen', 'Photocards', 'Small', 'soyeon I trust.jpg', 'soyeon special heat.jpg', '', 1, 5, 1, '2024-04-21 08:54:40'),
(7, 'Product 7', '150.00', 'This is product 7', 45, 'ITZY', 'Photocards', 'Large', 'soyeon special heat.jpg', 'soyeon.jpg', '', 0, 5, 0, NULL),
(8, 'Product 8', '200.00', 'Test Product 8 description', 8, 'Twice', 'Album', 'Small', 'soyeon.jpg', 'Soyeon heat.jpg', 'soyeon butterfly.jpg', 1, 3, 0, NULL),
(9, 'Product 9 - Lightstick', '2300000.00', 'Lightstick description', 34, 'ITZY', 'Lightsticks', 'Large', 'lebron.jpg', '', '', 1, 6, 1, '2024-04-21 08:54:34'),
(10, 'Test Product 10', '400.00', 'Test Product 10', 40, 'Twice', 'Album', 'Small', 'jordan.jpg', '', '', 1, 2, 0, NULL),
(11, 'test product 11', '800.00', 'Test Product 11 description', 4, 'Seventeen', 'Photocards', 'Medium', 'twice_album_3b.jpg', '', '', 0, 0, 0, NULL),
(12, 'test product 12', '900.50', 'Test Product 12 description.', 39, 'Blackpink', 'Poster', 'Large', 'soyeon special heat.jpg', 'soyeon.jpg', '', 0, 1, 0, NULL),
(13, 'Product 13', '800.50', 'This is Test product 13 description', 30, 'Seventeen', 'Album', 'Medium', 'lightstick1.jpg', '', '', 0, 0, 0, NULL),
(14, 'Product 14', '500.00', 'Test product 14 description', 20, 'Twice', 'Album', 'Small', 'soyeon butterfly.jpg', '', '', 0, 0, 0, NULL),
(15, 'Test Product 15', '300.00', 'description', 30, 'Seventeen', 'Photocards', 'Small', 'Soyeon heat.jpg', '', '', 0, 0, 1, '2024-04-25 08:27:53'),
(16, 'Test Product 16', '200.00', 'the description', 20, 'ITZY', 'Lightsticks', 'Medium', 'lightstick2.jpg', '', '', 0, 0, 0, NULL),
(17, 'Test Product 175645645', '600.00', 'description', 17, 'Twice', 'Poster', 'Large', 'soyeon.jpg', '', '', 0, 3, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
