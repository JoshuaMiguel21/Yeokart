-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2024 at 04:16 AM
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
-- Table structure for table `contacts_icons`
--

CREATE TABLE `contacts_icons` (
  `icon_id` int(11) NOT NULL,
  `icon_name` varchar(255) NOT NULL,
  `icon_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contacts_icons`
--

INSERT INTO `contacts_icons` (`icon_id`, `icon_name`, `icon_link`) VALUES
(1, 'Email', '<i class=''bx bxl-gmail''></i>'),
(2, 'Facebook', '<i class=''bx bxl-facebook-circle''></i>'),
(3, 'Instagram', '<i class=''bx bxl-instagram-alt''></i>'),
(4, 'Phone', '<i class=''bx bxs-phone''></i>'),
(5, 'Address', '<i class=''bx bx-current-location''></i>'),
(6, 'Twitter', '<i class=''bx bxl-twitter''></i>'),
(7, 'Tiktok', '<i class=''bx bxl-tiktok''></i>');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts_icons`
--
ALTER TABLE `contacts_icons`
  ADD PRIMARY KEY (`icon_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts_icons`
--
ALTER TABLE `contacts_icons`
  MODIFY `icon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
