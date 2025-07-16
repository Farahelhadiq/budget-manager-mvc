-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 09:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ========================================
-- Database: `gestion_budget`
-- ========================================

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `type` enum('revenu','depense') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `categories`
-- --------------------------------------------------------

INSERT INTO `categories` (`id`, `nom`, `type`) VALUES
(1, 'Salaire', 'revenu'),
(2, 'Bourse', 'revenu'),
(3, 'Ventes', 'revenu'),
(4, 'Autres', 'revenu'),
(5, 'Logement', 'depense'),
(6, 'Transport', 'depense'),
(7, 'Alimentation', 'depense'),
(8, 'Santé', 'depense'),
(9, 'Divertissement', 'depense'),
(10, 'Éducation', 'depense'),
(11, 'Autres', 'depense');

-- --------------------------------------------------------
-- Table structure for table `transactions`
-- --------------------------------------------------------

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `date_transaction` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `transactions`
-- --------------------------------------------------------

INSERT INTO `transactions` (`id`, `user_id`, `category_id`, `montant`, `description`, `date_transaction`) VALUES
(1, 1, 1, 12000.00, 'LE SALAIRE DE MOIN', '2025-04-01'),
(3, 1, 6, -200.00, 'BIS', '2025-04-01');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `nom`, `email`, `password`, `created_at`) VALUES
(1, 'EL HADIQ', 'farahelhadiq9@gmail.com', '$2y$10$zfI16yn7FfwSEYawwFqC0.bhC9tL2s4h0w/9lnM2Y0FlhWsVP1.qy', '2025-04-25 19:02:57');

-- --------------------------------------------------------
-- Indexes for dumped tables
-- --------------------------------------------------------

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

-- --------------------------------------------------------
-- AUTO_INCREMENT for dumped tables
-- --------------------------------------------------------

ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Constraints for dumped tables
-- --------------------------------------------------------

ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

COMMIT;

