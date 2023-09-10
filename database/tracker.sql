-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 10 Sep 2023 pada 19.20
-- Versi server: 8.0.31
-- Versi PHP: 7.4.33

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
-- Struktur dari tabel `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `timestamp` int UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `user_agent`, `timestamp`, `data`) VALUES
('3pjofamn74uvbh6n5fqjl5ghrnkrhjj7', '::1', '', 1693744900, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639333734343930303b),
('4uh5hhbj5pq9cpvpbdqk26l5fufsgti5', '::1', '', 1693742743, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639333734323734333b),
('8iht6ap7dr8r7rkanq4bmd18iir8gkeo', '::1', '', 1693744484, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639333734343438343b),
('qat6mvlflr696flli9g1bjgie80g7v1g', '::1', '', 1693745169, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639333734343930303b757365725f69647c733a353a2261646d696e223b757365725f6e616d617c733a353a2241646d696e223b69735f73757065727c733a313a2231223b);

-- --------------------------------------------------------

--
-- Struktur dari tabel `history_keterangan`
--

DROP TABLE IF EXISTS `history_keterangan`;
CREATE TABLE IF NOT EXISTS `history_keterangan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_program_kerja` int NOT NULL,
  `keterangan` text NOT NULL,
  `type` enum('Network','Aplikasi') NOT NULL,
  `status` enum('Completed','In Progress','Not Started') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `history_keterangan` (`id_program_kerja`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `history_keterangan`
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
-- Struktur dari tabel `pegawai`
--

DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE IF NOT EXISTS `pegawai` (
  `employee_id` bigint NOT NULL,
  `surname` varchar(50) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `emp_status_desc` enum('Active Employees') NOT NULL,
  `lokasi_kerja` enum('TMO','HO') NOT NULL,
  `divisi` enum('ASET','FAMIS') NOT NULL,
  `department` enum('Aset&Perencanaan','SM Penunjang Prod','Akuntansi & Pajak') NOT NULL,
  `kode_posisi` varchar(15) NOT NULL,
  `description_jabatan` varchar(25) NOT NULL,
  `grade` enum('A','B','C','D','E') NOT NULL,
  `level_` enum('SUPERVISOR','STAFF B') NOT NULL,
  `status_pegawai` enum('PEGAWAI TETAP','KONTRAK') NOT NULL,
  `personal_email` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `status` enum('KAWIN','BELUM KAWIN') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`employee_id`, `surname`, `jenis_kelamin`, `emp_status_desc`, `lokasi_kerja`, `divisi`, `department`, `kode_posisi`, `description_jabatan`, `grade`, `level_`, `status_pegawai`, `personal_email`, `no_hp`, `status`) VALUES
(10160136, 'ABDIANSYAH AKBAR', 'L', 'Active Employees', 'TMO', 'ASET', 'Aset&Perencanaan', '321110022A', 'SUPV B. UTAMA & KOMP EXCH', 'D', 'SUPERVISOR', 'PEGAWAI TETAP', '', '1298412', 'KAWIN'),
(10160114, 'ABDUR RACHMAN', 'L', 'Active Employees', 'TMO', 'ASET', 'SM Penunjang Prod', '420110022A', 'SUPERVISOR RENTAL', 'D', 'SUPERVISOR', 'PEGAWAI TETAP', '', '241241', 'BELUM KAWIN'),
(10200001, 'ADELIA RAHMAWATI', 'P', 'Active Employees', 'HO', 'FAMIS', 'Akuntansi & Pajak', '212AAAA21A', 'AKUNTING JR OFFICER', 'B', 'STAFF B', 'KONTRAK', '', '', 'KAWIN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_kerja`
--

DROP TABLE IF EXISTS `program_kerja`;
CREATE TABLE IF NOT EXISTS `program_kerja` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` int NOT NULL,
  `type` enum('Network','Aplikasi') NOT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `status` enum('Completed','In Progress','Not Started') NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `constraint_user_id` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `program_kerja`
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
-- Struktur dari tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `user_id` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `gambar` varchar(128) NOT NULL,
  `is_active` int NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `contraint_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `role_id`, `user_id`, `password`, `nama`, `gambar`, `is_active`, `create_at`) VALUES
(0, 2, 'test ', '$2y$10$9zxLYrxW3AYIEDhS.ayh8.iLsz9WFp3rze8vfxcyyzr.Oc2IbNXJu', 'test 2', 'default.jpg', 1, '2023-07-22 07:53:13'),
(1, 1, 'hofar', '$2y$10$npd2rQYWQg2GLRXLD/BzkuSGJKOWiVmtGTS0.ynbo9XPfLwoU5rwy', 'Hofar Ismail', 'default.jpg', 1, '2019-08-30 22:20:18'),
(2, 1, 'admin', '$2y$10$ZPUdSONgksImO0.PI.A7.eM0dZU5kR.sjOytNJArMlNO9XMsk6e7i', 'Admin', 'default.jpg', 1, '2019-08-31 16:55:43'),
(3, 2, 'user', '$2y$10$HuPSqF5hvJSdle8eromvmuB22wZKsDi6t2Zsf41B7jOSS5OpJj1we', 'User', 'default.jpg', 1, '2019-08-31 16:55:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `is_super` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_role`
--

INSERT INTO `user_role` (`id`, `name`, `is_super`) VALUES
(1, 'Administrator', 1),
(2, 'User', 0),
(12, 'test 2', 0),
(13, 'test sdfsdf', 0);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `history_keterangan`
--
ALTER TABLE `history_keterangan`
  ADD CONSTRAINT `history_keterangan` FOREIGN KEY (`id_program_kerja`) REFERENCES `program_kerja` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD CONSTRAINT `constraint_user_id` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `contraint_role` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
