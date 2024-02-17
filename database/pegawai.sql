-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 15 Sep 2023 pada 22.52
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
