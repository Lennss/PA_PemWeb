-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 06:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbcafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`) VALUES
(2, 'Kucing', '25.000.000', 'meow meow meow', 'EDIT.jpg'),
(6, 'aspa', '30000', 'aspa', 'IMG_20231009_165310_051.webp');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_pesanan`
--

CREATE TABLE `riwayat_pesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `qr_code_url` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_pesanan`
--

INSERT INTO `riwayat_pesanan` (`id`, `user_id`, `product_id`, `product_name`, `product_price`, `qr_code_url`, `transaction_id`, `order_date`) VALUES
(42, 14, 2, 'Kucing', 25000000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D782095%26product_id%3D2%26name%3DKucing%26price%3D25000000&size=200x200', '782095', '2024-11-08 05:02:50'),
(43, 14, 2, 'Kucing', 25000000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D889384%26product_id%3D2%26name%3DKucing%26price%3D25000000&size=200x200', '889384', '2024-11-08 05:02:59'),
(44, 14, 2, 'Kucing', 25000000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D754792%26product_id%3D2%26name%3DKucing%26price%3D25000000&size=200x200', '754792', '2024-11-08 05:03:43'),
(45, 16, 2, 'Kucing', 25000000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D384039%26product_id%3D2%26name%3DKucing%26price%3D25000000&size=200x200', '384039', '2024-11-08 05:21:09'),
(46, 16, 2, 'Kucing', 25000000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D886656%26product_id%3D2%26name%3DKucing%26price%3D25000000&size=200x200', '886656', '2024-11-08 05:21:48'),
(47, 16, 2, 'Kucing', 25000000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D417589%26product_id%3D2%26name%3DKucing%26price%3D25000000&size=200x200', '417589', '2024-11-08 05:34:23'),
(48, 16, 6, 'aspa', 30000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D368736%26product_id%3D6%26name%3Daspa%26price%3D30000&size=200x200', '368736', '2024-11-08 05:34:27'),
(49, 16, 6, 'aspa', 30000.00, 'https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fexample.com%2Fpayment%3Ftransaction_id%3D147143%26product_id%3D6%26name%3Daspa%26price%3D30000&size=200x200', '147143', '2024-11-08 05:34:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `role` enum('Admin','User') DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nomor_hp`, `role`) VALUES
(12, 'aspa', '$2y$10$aEsMhCGIMSg2xb/J2Tp4/eQRDd2JoCxuku6QjfZ3djNio.y6w/7O.', '0812345678', 'User'),
(14, 'coba', '$2y$10$HsLDY3K9pw0dOUphThc6UeGdj/I3THOjqzL7PLnhVH4q4Lp6BotOm', '1234567890', 'User'),
(15, 'coba1', '$2y$10$S2a3BIDvGFHXEg3pHy1tve4Sp5Mnp0mJiBi7aAiZgImqFh3tRyh0S', '12345678910', 'User'),
(16, 'aaa', '$2y$10$ipxGucjeS8cRic7qB18sKenApWr0rs55A8fKHh2IoXuk5HgxE9cSO', '12345678910', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riwayat_pesanan`
--
ALTER TABLE `riwayat_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `riwayat_pesanan`
--
ALTER TABLE `riwayat_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `riwayat_pesanan`
--
ALTER TABLE `riwayat_pesanan`
  ADD CONSTRAINT `riwayat_pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
