-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2023 at 06:33 PM
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
(6, 12, 'test baru', 'Aplikasi', 'Completed', '2023-07-28 23:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `program_kerja`
--

CREATE TABLE `program_kerja` (
  `id` int(11) NOT NULL,
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

INSERT INTO `program_kerja` (`id`, `name`, `value`, `type`, `keterangan`, `status`, `start_date`, `end_date`) VALUES
(1, 'Pemenuhan Kebutuhan PC, NB & Printer', 71, 'Network', NULL, 'In Progress', '2023-07-24 17:48:09', NULL),
(2, 'Penyediaan Penguat Sinyal GSM', 100, 'Network', NULL, 'Completed', '2023-07-24 17:47:14', '2023-07-24 17:47:45'),
(3, 'Upgrade Radio Analog ke Digital', 57, 'Network', NULL, 'In Progress', '2023-07-24 17:48:20', NULL),
(4, 'Backup Power Supply', 100, 'Network', NULL, 'Completed', '2023-07-24 17:47:24', '2023-07-24 17:47:42'),
(5, 'Relokasi Tower dari HO ke PIT E', 57, 'Network', NULL, 'In Progress', '2023-07-24 17:48:23', NULL),
(6, 'Upgrade Bandwidth Internet', 100, 'Network', NULL, 'Completed', '2023-07-24 17:47:29', '2023-07-24 17:47:39'),
(7, 'Migrasi email @satriabahana.co.id to Cloud', 71, 'Network', NULL, 'In Progress', '2023-07-24 17:48:13', NULL),
(8, 'CCTV PKO PIT E', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:35', NULL),
(9, 'CCTV Tarahan', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:49', NULL),
(10, 'CCTV View Poin PITE', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:53', NULL),
(11, 'CCTV Hauling PITE', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:57', NULL),
(12, 'CCTV Disposal PITE', 100, 'Aplikasi', 'test baru', 'Completed', '2023-07-24 17:48:03', '2023-07-28 23:29:00'),
(13, 'Command Center', 100, 'Aplikasi', 'Contoh Keterangan', 'Completed', '2023-07-14 22:07:20', '2023-07-14 22:07:24'),
(14, 'GPS Tracking (Map Operation)', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-13 08:04:11', '2023-07-14 22:04:24'),
(37, 'test edit', 0, 'Network', 'test 2', 'Not Started', '2023-07-26 18:24:00', '2023-07-26 18:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `user_id` varchar(128) NOT NULL,
  `gambar` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `is_active` int(1) NOT NULL,
  `role_id` int(1) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `user_id`, `gambar`, `password`, `is_active`, `role_id`, `create_at`) VALUES
(0, 'test 2', 'test ', 'default.jpg', '$2y$10$9zxLYrxW3AYIEDhS.ayh8.iLsz9WFp3rze8vfxcyyzr.Oc2IbNXJu', 1, 2, '2023-07-22 07:53:13'),
(1, 'Hofar Ismail', 'hofar', 'default.jpg', '$2y$10$npd2rQYWQg2GLRXLD/BzkuSGJKOWiVmtGTS0.ynbo9XPfLwoU5rwy', 1, 1, '2019-08-30 22:20:18'),
(2, 'Admin', 'admin', 'default.jpg', '$2y$10$ZPUdSONgksImO0.PI.A7.eM0dZU5kR.sjOytNJArMlNO9XMsk6e7i', 1, 1, '2019-08-31 16:55:43'),
(3, 'User', 'user', 'default.jpg', '$2y$10$HuPSqF5hvJSdle8eromvmuB22wZKsDi6t2Zsf41B7jOSS5OpJj1we', 1, 2, '2019-08-31 16:55:56');

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
-- Indexes for table `history_keterangan`
--
ALTER TABLE `history_keterangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_keterangan` (`id_program_kerja`);

--
-- Indexes for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `program_kerja`
--
ALTER TABLE `program_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  ADD CONSTRAINT `history_keterangan` FOREIGN KEY (`id_program_kerja`) REFERENCES `program_kerja` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
