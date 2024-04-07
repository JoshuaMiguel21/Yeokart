-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2024 at 10:57 AM
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
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contacts_id` int(11) NOT NULL,
  `contacts_name` varchar(255) NOT NULL,
  `icon_link` varchar(255) NOT NULL,
  `contacts_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contacts_id`, `contacts_name`, `icon_link`, `contacts_description`) VALUES
(1, 'Facebook', '<i class=\'bx bxl-facebook-circle\'></i>', 'https://www.facebook.com/joshuamiguel.embestro.3'),
(2, 'Instagram', '<i class=\'bx bxl-instagram-alt\'></i>', 'https://www.instagram.com/joshua_miguel21/'),
(3, 'Email', '<i class=\'bx bxl-gmail\'></i>', 'joshuamiguel.embestro.cics@ust.edu.ph'),
(4, 'Phone', '<i class=\'bx bxs-phone\'></i>', '(0956) 449 9196'),
(5, 'Address', '<i class=\'bx bx-current-location\'></i>', 'Oro Vista Royale Mayamot, Antipolo City'),
(12, 'GCash', '<i class=\'fa-solid fa-peso-sign\'></i>', 'Rachel Falcis (0912-345-6789)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contacts_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contacts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
