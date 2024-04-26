-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2024 at 09:23 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question`, `answer`, `created_at`) VALUES
(1, 'How do I place an order? ', 'To place an order, simply browse our website and add the desired items to your cart. Once you\'re ready to checkout, proceed to the checkout page, fill in your shipping details, and then confirm your order.', '2024-04-26 00:27:20'),
(2, 'What payment methods do you accept? ', 'We accept payments via GCash only. All transactions are encrypted and secured to ensure your information is protected. To avoid any fraudulent actions that may arise, we are requiring each buyer to present their receipts as well as the reference number of the transfer so we can check on our end. If you’re given receipt is deemed fraudulent or incorrect, you will be given a chance to correct your image upload or else your order will be deemed invalid and canceled.', '2024-04-26 00:27:48'),
(3, 'How long can I wait before I pay for my order?', 'Unpaid orders will only be held for 24 hours. If an order remains unpaid after the given period, the order will automatically be canceled, and the items will be removed from holding. Please note that you are required to upload the screenshot of your payment within 24 hours, and failure to do so will result in forfeiture of the order, and you will not be refunded.', '2024-04-26 00:28:10'),
(4, 'How long will it take to receive my order?', 'Shipping times may vary depending on your location and the shipping method selected at checkout. Typically, orders are processed within the week of ordering, and shipping times range from 1 to 7 business days, depending on location.', '2024-04-26 00:28:37'),
(5, 'Where do you ship?', 'We ship to almost anywhere in the Phillippines. Shipping costs and delivery times may vary depending on the destination. Please refer to our shipping policy for more details.', '2024-04-26 07:19:55'),
(6, 'Shipping Costs', 'Shipping Fee\r\n\r\nThese are the rates for the Shipping fee in Luzon Region.\r\n\r\nSmall to Medium Items\r\n\r\n• Metro Manila P100\r\n• Provinces in Luzon-P180\r\n\r\nLarge Items\r\n\r\n• Metro Manila - P120\r\n• Provinces in Luzon-P220\r\n\r\nFor Visayas and Mindanao Region.\r\n\r\nSmall Items\r\n\r\n• Provinces in Visayas and Mindanao - P180\r\n\r\nMedium Items\r\n\r\n• Provinces in Visayas and Mindanao - P260\r\n\r\nLarge Items\r\n\r\n• Provinces in Visayas and Mindanao - P320', '2024-04-26 06:56:50'),
(7, 'How can I track my order?', ' Once an order is shipped, you will find your package’s tracking number when you click “view order” on the specific order. This is found on our order history page accessible via your customer profile or by clicking your order’s notification. You may also receive an email from us with more information regarding the shipping of your order. ', '2024-04-26 07:13:40'),
(8, 'What is your return/exchange policy?', 'We want you to be completely satisfied with your purchase. If your order arrives damaged because of mispackaging, please contact us at Yeokartstore@gmail.com within 3 days of receiving your order to arrange for a return. Please note that we are requiring the order ID and an unboxing video for every dispute. Without the ID and video, we cannot proceed to refund said damages or accept the return and refund option.\r\n', '2024-04-26 00:30:52'),
(9, 'Delivery Address Accuracy', 'Please ensure that the shipping address provided during checkout is accurate and complete to avoid any delays or delivery issues. We are not responsible for orders shipped to incorrect addresses provided by the customer.', '2024-04-26 00:31:11'),
(10, ' Are your products authentic? ', ' Yes, all of our products are 100% authentic and sourced directly from official distributors and licensed manufacturers. We take pride in offering high-quality merchandise to our customers.', '2024-04-26 00:31:33'),
(11, 'How can I contact customer support?', 'If you have any questions or concerns, our team is here to help. You can reach us via email at Yeokartstore@gmail.com or through our Contact Us page on the website. We strive to respond to all inquiries within 24 hours.', '2024-04-26 00:31:52'),
(12, 'Can I cancel or modify my order after it has been placed?', 'Unpaid orders will automatically be canceled after 24 hours. Please note that once an order has been paid for, it cannot be canceled or modified. We aim to process orders quickly to ensure prompt delivery to our customers.', '2024-04-26 00:32:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
