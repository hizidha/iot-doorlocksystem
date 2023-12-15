-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 05, 2023 at 12:35 PM
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

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `serial_number`, `id_uidCard`, `action_type`, `timestamp`) VALUES
(1, 'PW 3E R5 31', 1, 0, '2023-12-03 17:00:00'),
(2, 'PW 3E R5 31', 1, 0, '2023-12-03 17:00:20'),
(3, 'PW 3E R5 31', 1, 0, '2023-12-04 17:27:29'),
(4, '23 E4 5Q PT', 2, 0, '2023-12-05 17:27:29'),
(5, '23 E4 5Q PT', 2, 0, '2023-12-05 17:27:34'),
(6, 'E5 Y7 U4 CF', 3, 0, '2023-12-04 17:00:00'),
(7, 'E5 Y7 U4 CF', 3, 0, '2023-12-05 17:00:00'),
(8, 'SR 53 TG E4', 5, 0, '2023-12-03 17:33:59'),
(9, 'SR 53 TG E4', 5, 0, '2023-12-04 17:33:59'),
(10, '23 R5 7T 44', 5, 0, '2023-12-04 17:35:33'),
(11, '23 R5 7T 44', 5, 0, '2023-12-05 17:34:33'),
(12, '23 R5 7T 44', 5, 0, '2023-12-05 17:36:33'),
(13, '00 00 00 00', 6, 1, '2023-12-04 18:08:25'),
(14, '00 00 00 00', 6, 1, '2023-12-05 18:08:25'),
(15, '00 00 00 00', 6, 1, '2023-12-05 18:08:30'),
(16, '99 99 99 99', 7, 2, '2023-12-04 18:08:25'),
(17, '99 99 99 99', 7, 2, '2023-12-05 18:09:27'),
(18, '23 E4 5Q PT', 2, 0, '2023-12-05 19:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `uid_card`
--

CREATE TABLE `uid_card` (
  `id` int NOT NULL,
  `serial_number` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_owner` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `lastmodified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `uid_card`
--

INSERT INTO `uid_card` (`id`, `serial_number`, `id_owner`, `created_at`, `lastmodified_at`) VALUES
(1, 'PW 3E R5 31', 4, '2023-12-05 17:23:50', '2023-12-05 17:25:13'),
(2, '23 E4 5Q PT', 5, '2023-12-05 17:23:51', '2023-12-05 17:23:51'),
(3, 'E5 Y7 U4 CF', 6, '2023-12-05 17:23:52', '2023-12-05 17:23:52'),
(5, NULL, 2, '2023-12-05 17:30:00', '2023-12-05 17:30:00'),
(6, '00 00 00 00', 1, '2023-12-05 17:30:00', '2023-12-05 17:30:00'),
(7, '99 99 99 99', 1, '2023-12-05 17:30:00', '2023-12-05 17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `full_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `username` varchar(128) NOT NULL,
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
(3, 'Super Admin', 'superadmin', '$2y$10$ZSb7C7iNFD8wpzNoKocjdO29OpLIQ7low18Gy8gJEcf5pvnor47t2', 'Super Admin', 'Teknologi Informasi', 'Teknologi Informasi', 0, '2023-11-25 00:00:00', '2023-11-25 00:00:00'),
(4, 'Adisaputra Zidha N', '2023041901', '$2y$10$4TyLX1OoDETb7RB8HbO.fOWkH3fh3d1F4UZ6Ieex5OK77dw7fG9Ly', 'Pelaksana', 'Pemeliharaan TI', 'Teknologi Informasi', 1, '2023-11-23 07:58:18', '2023-12-05 17:12:35'),
(5, 'Muhammad Yazid F', '2023082101', '$2y$10$sC3pPPLLXBbECI6yeX9pbO9JrP.P29CXAd8f6Hhdh5KZx.8owQc8a', 'Pelaksana', 'Administrasi TI', 'Teknologi Informasi', 1, '2023-11-23 15:11:54', '2023-12-05 17:12:46'),
(6, 'William Johan P', '2023129210', '$2y$10$Q7YlpJnP7n1xMuMX.IqS1eaffj/cPuAb1sMeyNRKHt1G/8/vafYNm', 'Pelaksana', 'Pengembangan TI', 'Teknologi Informasi', 2, '2023-11-27 11:07:08', '2023-12-05 17:11:51');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `uid_card`
--
ALTER TABLE `uid_card`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
