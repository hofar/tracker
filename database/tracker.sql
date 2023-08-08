-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2023 at 08:51 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `user_agent`, `timestamp`, `data`) VALUES
('f98ppfjmfujcnb405v5rt1epnpans0fd', '::1', '', 1691476991, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639313437363736373b757365725f69647c733a353a2261646d696e223b757365725f6e616d617c733a353a2241646d696e223b69735f73757065727c733a313a2231223b);

-- --------------------------------------------------------

--
-- Table structure for table `history_keterangan`
--

CREATE TABLE `history_keterangan` (
  `id` int(11) NOT NULL,
  `id_program_kerja` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `type` enum('Network','Aplikasi') NOT NULL,
  `status` enum('Completed','In Progress','Not Started') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `history_keterangan`
--

INSERT INTO `history_keterangan` (`id`, `id_program_kerja`, `keterangan`, `type`, `status`, `created_at`) VALUES
(1, 2, 'test 1', 'Network', 'In Progress', '2023-07-25 21:53:37'),
(2, 3, 'test 2', 'Network', 'In Progress', '2023-07-25 22:35:57'),
(3, 14, 'test awal keterangan', 'Aplikasi', 'In Progress', '2023-07-25 23:16:40'),
(4, 10, 'test', 'Aplikasi', 'In Progress', '2023-07-26 15:04:27'),
(6, 12, 'test baru', 'Aplikasi', 'Completed', '2023-07-28 23:30:21'),
(7, 11, 'test 76', 'Network', 'In Progress', '2023-07-30 22:40:35'),
(8, 11, 'test 96', 'Network', 'In Progress', '2023-07-30 22:41:31'),
(9, 11, 'test 100', 'Network', 'Completed', '2023-07-30 22:42:27'),
(10, 11, 'test 100 end_date', 'Network', 'Completed', '2023-07-30 22:46:14'),
(11, 11, 'test 99', 'Network', 'In Progress', '2023-07-30 23:23:33'),
(12, 40, 'test edit 2', 'Network', 'In Progress', '2023-07-30 23:54:47'),
(13, 41, 'test not start', 'Network', 'Not Started', '2023-07-30 23:55:26'),
(14, 40, 'tambah data', 'Network', 'In Progress', '2023-07-30 23:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `program_kerja`
--

CREATE TABLE `program_kerja` (
  `id` int(11) NOT NULL,
  `id_user` int(5) NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` int(5) NOT NULL,
  `type` enum('Network','Aplikasi') NOT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `status` enum('Completed','In Progress','Not Started') NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program_kerja`
--

INSERT INTO `program_kerja` (`id`, `id_user`, `name`, `value`, `type`, `keterangan`, `status`, `start_date`, `end_date`) VALUES
(1, 2, 'Pemenuhan Kebutuhan PC, NB & Printer', 71, 'Network', NULL, 'In Progress', '2023-07-24 17:48:09', NULL),
(2, 2, 'Penyediaan Penguat Sinyal GSM', 100, 'Network', NULL, 'Completed', '2023-07-24 17:47:14', '2023-07-24 17:47:45'),
(3, 2, 'Upgrade Radio Analog ke Digital', 57, 'Network', NULL, 'In Progress', '2023-07-24 17:48:20', NULL),
(4, 2, 'Backup Power Supply', 100, 'Network', NULL, 'Completed', '2023-07-24 17:47:24', '2023-07-24 17:47:42'),
(5, 2, 'Relokasi Tower dari HO ke PIT E', 57, 'Network', NULL, 'In Progress', '2023-07-24 17:48:23', NULL),
(6, 2, 'Upgrade Bandwidth Internet', 100, 'Network', NULL, 'Completed', '2023-07-24 17:47:29', '2023-07-24 17:47:39'),
(7, 2, 'Migrasi email @satriabahana.co.id to Cloud', 71, 'Network', NULL, 'In Progress', '2023-07-24 17:48:13', NULL),
(8, 2, 'CCTV PKO PIT E', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:35', NULL),
(9, 2, 'CCTV Tarahan', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:49', NULL),
(10, 2, 'CCTV View Poin PITE', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:53', NULL),
(11, 2, 'CCTV Hauling PITE', 99, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:57', NULL),
(12, 2, 'CCTV Disposal PITE', 100, 'Aplikasi', 'test baru', 'Completed', '2023-07-24 17:48:03', '2023-07-28 23:29:00'),
(13, 2, 'Command Center', 100, 'Aplikasi', 'Contoh Keterangan', 'Completed', '2023-07-14 22:07:20', '2023-07-14 22:07:24'),
(14, 2, 'GPS Tracking (Map Operation)', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-13 08:04:11', '2023-07-14 22:04:24'),
(37, 2, 'test edit', 0, 'Network', 'test 2', 'Not Started', '2023-07-26 18:24:00', '2023-07-26 18:25:00'),
(40, 2, 'test edit 2', 80, 'Aplikasi', 'test edit 2', 'In Progress', '2023-07-30 23:54:00', NULL),
(41, 3, 'test not start', 0, 'Network', 'test not start', 'Not Started', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `qr_code`
--

CREATE TABLE `qr_code` (
  `id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `qr_code`
--

INSERT INTO `qr_code` (`id`, `token`, `flag`, `created_at`) VALUES
(1, 'RjnRXm8vwAGzPVa9eOgj8GyfYAvEgRlyzoPy2g5ceHJmjBjl5K', 0, '2023-08-07 20:47:13'),
(2, 'rghJa960h5wgGD4RuVRJX6OydFTMcLIzD1vaaybweNgXn3wAx5', 0, '2023-08-07 20:47:24'),
(3, 'Enb2F68TRlKYehSFIVhEuKSW8QJE1LeeypxsEMwRkKvNSnN3Wh', 0, '2023-08-07 20:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) NOT NULL,
  `role_id` int(1) NOT NULL,
  `user_id` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `gambar` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role_id`, `user_id`, `password`, `nama`, `gambar`, `is_active`, `create_at`) VALUES
(0, 2, 'test ', '$2y$10$9zxLYrxW3AYIEDhS.ayh8.iLsz9WFp3rze8vfxcyyzr.Oc2IbNXJu', 'test 2', 'default.jpg', 1, '2023-07-22 07:53:13'),
(1, 1, 'hofar', '$2y$10$npd2rQYWQg2GLRXLD/BzkuSGJKOWiVmtGTS0.ynbo9XPfLwoU5rwy', 'Hofar Ismail', 'default.jpg', 1, '2019-08-30 22:20:18'),
(2, 1, 'admin', '$2y$10$ZPUdSONgksImO0.PI.A7.eM0dZU5kR.sjOytNJArMlNO9XMsk6e7i', 'Admin', 'default.jpg', 1, '2019-08-31 16:55:43'),
(3, 2, 'user', '$2y$10$HuPSqF5hvJSdle8eromvmuB22wZKsDi6t2Zsf41B7jOSS5OpJj1we', 'User', 'default.jpg', 1, '2019-08-31 16:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_super` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `name`, `is_super`) VALUES
(1, 'Administrator', 1),
(2, 'User', 0),
(12, 'test 2', 0),
(13, 'test sdfsdf', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `history_keterangan`
--
ALTER TABLE `history_keterangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_keterangan` (`id_program_kerja`);

--
-- Indexes for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `constraint_user_id` (`id_user`);

--
-- Indexes for table `qr_code`
--
ALTER TABLE `qr_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `contraint_role` (`role_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history_keterangan`
--
ALTER TABLE `history_keterangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `program_kerja`
--
ALTER TABLE `program_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `qr_code`
--
ALTER TABLE `qr_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history_keterangan`
--
ALTER TABLE `history_keterangan`
  ADD CONSTRAINT `history_keterangan` FOREIGN KEY (`id_program_kerja`) REFERENCES `program_kerja` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD CONSTRAINT `constraint_user_id` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `contraint_role` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
