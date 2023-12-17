-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2023 at 05:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doorlocksystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int NOT NULL,
  `serial_number` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_uidCard` int DEFAULT NULL,
  `action_type` int DEFAULT NULL COMMENT '0: RFID Card, 1: Button Emergency, 2: Button Exit',
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uid_card`
--

CREATE TABLE `uid_card` (
  `id` int NOT NULL,
  `serial_number` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL UNIQUE,
  `id_owner` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `lastmodified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `uid_card`
--

INSERT INTO `uid_card` (`id`, `serial_number`, `id_owner`, `created_at`, `lastmodified_at`) VALUES
(1, NULL, 2, '2023-12-05 17:30:00', '2023-12-05 17:30:00'),
(2, '00000000', 1, '2023-12-05 17:30:00', '2023-12-05 17:30:00'),
(3, '99999999', 1, '2023-12-05 17:30:00', '2023-12-05 17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `full_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `username` varchar(128) NOT NULL UNIQUE,
  `password` varchar(128) NOT NULL,
  `position` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `department` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `division` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `role` int DEFAULT NULL COMMENT '0: superadmin, 1: admin, 2: member, 3: guest',
  `created_at` datetime NOT NULL,
  `lastmodified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `full_name`, `username`, `password`, `position`, `department`, `division`, `role`, `created_at`, `lastmodified_at`) VALUES
(1, 'System', 'system', 'SYSTEM', 'System', 'Teknologi Informasi', 'Teknologi Informasi', 0, '2023-11-25 00:00:00', '2023-11-25 00:00:00'),
(2, 'Guest', 'guest', 'GUEST', 'Guest', NULL, NULL, 3, '2023-11-25 00:00:00', '2023-11-25 00:00:00'),
(3, 'Super Admin', 'superadmin', '$2y$10$ZSb7C7iNFD8wpzNoKocjdO29OpLIQ7low18Gy8gJEcf5pvnor47t2', 'Super Admin', 'Teknologi Informasi', 'Teknologi Informasi', 0, '2023-11-25 00:00:00', '2023-11-25 00:00:00');
--
-- Indexes for dumped tables
--

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_history_uidCard` (`id_uidCard`);

--
-- Indexes for table `uid_card`
--
ALTER TABLE `uid_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner` (`id_owner`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uid_card`
--
ALTER TABLE `uid_card`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_uidCard` FOREIGN KEY (`id_uidCard`) REFERENCES `uid_card` (`id`);

--
-- Constraints for table `uid_card`
--
ALTER TABLE `uid_card`
  ADD CONSTRAINT `fk_uidCard_user` FOREIGN KEY (`id_owner`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
