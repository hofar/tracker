-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2023 at 12:57 PM
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
  `type` enum('Network','Aplikasi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program_kerja`
--

INSERT INTO `program_kerja` (`id`, `name`, `value`, `type`) VALUES
(1, 'Pemenuhan Kebutuhan PC, NB & Printer', 71, 'Network'),
(2, 'Penyediaan Penguat Sinyal GSM', 100, 'Network'),
(3, 'Upgrade Radio Analog ke Digital', 57, 'Network'),
(4, 'Backup Power Supply', 100, 'Network'),
(5, 'Relokasi Tower dari HO ke PIT E', 57, 'Network'),
(6, 'Upgrade Bandwidth Internet', 100, 'Network'),
(7, 'Migrasi email @satriabahana.co.id to Cloud', 71, 'Network'),
(8, 'CCTV PKO PIT E', 86, 'Aplikasi'),
(9, 'CCTV Tarahan', 86, 'Aplikasi'),
(10, 'CCTV View Poin PITE', 86, 'Aplikasi'),
(11, 'CCTV Hauling PITE', 86, 'Aplikasi'),
(12, 'CCTV Disposal PITE', 86, 'Aplikasi'),
(13, 'Command Center', 71, 'Aplikasi'),
(14, 'GPS Tracking (Map Operation)', 86, 'Aplikasi');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `program_kerja`
--
ALTER TABLE `program_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
