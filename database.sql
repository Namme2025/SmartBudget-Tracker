-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 14, 2026 at 04:33 PM
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
-- Database: `expense_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `title`, `amount`, `type`, `category`, `created_at`, `user_id`) VALUES
(10, 'Drugs', 10000, 'expense', '', '2026-02-14 14:56:08', 6),
(11, 'Medicine', 20000, 'expense', '', '2026-02-14 14:56:29', 6),
(13, 'Food', 500, 'expense', '', '2026-02-14 14:59:06', 8),
(19, 'Tuition Fee', 100000, 'expense', 'Education', '2026-02-14 15:11:47', 8),
(20, 'Meralco', 10000, 'expense', 'Bills', '2026-02-14 15:12:09', 8),
(21, 'Pamasahe', 900, 'expense', 'Transport', '2026-02-14 15:12:42', 8),
(22, 'Medicine', 20000, 'expense', 'Health', '2026-02-14 15:13:01', 8),
(23, 'Fruits', 10000, 'expense', 'Food', '2026-02-14 15:13:16', 8),
(24, 'Sahod', 5000, 'expense', 'Salary', '2026-02-14 15:18:35', 9),
(25, 'Tuition Fees', 5000, 'expense', 'Education', '2026-02-14 15:19:04', 9),
(26, 'Meralc', 5000, 'expense', 'Bills', '2026-02-14 15:26:42', 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(9, 'admin1', '$2y$10$RIyI2CHACTmxtpWPqdJNZuOZKJihFZ3FXkFRV//b49VpSoXjpBjSK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
