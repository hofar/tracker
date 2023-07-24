-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2023 at 12:48 PM
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
-- Table structure for table `program_kerja`
--

CREATE TABLE `program_kerja` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` int(5) NOT NULL,
  `type` enum('Network','Aplikasi') NOT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `status` enum('Complete','In Progress','Not Started') NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program_kerja`
--

INSERT INTO `program_kerja` (`id`, `name`, `value`, `type`, `keterangan`, `status`, `start_date`, `end_date`) VALUES
(1, 'Pemenuhan Kebutuhan PC, NB & Printer', 71, 'Network', NULL, 'In Progress', '2023-07-24 17:48:09', NULL),
(2, 'Penyediaan Penguat Sinyal GSM', 100, 'Network', NULL, 'Complete', '2023-07-24 17:47:14', '2023-07-24 17:47:45'),
(3, 'Upgrade Radio Analog ke Digital', 57, 'Network', NULL, 'In Progress', '2023-07-24 17:48:20', NULL),
(4, 'Backup Power Supply', 100, 'Network', NULL, 'Complete', '2023-07-24 17:47:24', '2023-07-24 17:47:42'),
(5, 'Relokasi Tower dari HO ke PIT E', 57, 'Network', NULL, 'In Progress', '2023-07-24 17:48:23', NULL),
(6, 'Upgrade Bandwidth Internet', 100, 'Network', NULL, 'Complete', '2023-07-24 17:47:29', '2023-07-24 17:47:39'),
(7, 'Migrasi email @satriabahana.co.id to Cloud', 71, 'Network', NULL, 'In Progress', '2023-07-24 17:48:13', NULL),
(8, 'CCTV PKO PIT E', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:35', NULL),
(9, 'CCTV Tarahan', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:49', NULL),
(10, 'CCTV View Poin PITE', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:53', NULL),
(11, 'CCTV Hauling PITE', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:47:57', NULL),
(12, 'CCTV Disposal PITE', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-24 17:48:03', NULL),
(13, 'Command Center', 100, 'Aplikasi', 'Contoh Keterangan', 'Complete', '2023-07-14 22:07:20', '2023-07-14 22:07:24'),
(14, 'GPS Tracking (Map Operation)', 86, 'Aplikasi', NULL, 'In Progress', '2023-07-13 08:04:11', '2023-07-14 22:04:24'),
(37, 'test edit', 0, 'Network', 'test 2', 'Not Started', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `program_kerja`
--
ALTER TABLE `program_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
