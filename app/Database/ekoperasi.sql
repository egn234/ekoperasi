-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2023 at 03:31 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ekoperasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `notification_log`
--

CREATE TABLE `notification_log` (
  `id` int(11) NOT NULL,
  `cicilan_id` int(11) DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `bendahara_id` int(11) DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `parameter_id` int(11) DEFAULT NULL,
  `pinjaman_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `group_type` enum('1','2','3','4') NOT NULL,
  `status` varchar(10) DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_cicilan`
--

CREATE TABLE `tb_cicilan` (
  `idcicilan` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `bunga` int(11) NOT NULL,
  `provisi` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `idpinjaman` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_deposit`
--

CREATE TABLE `tb_deposit` (
  `iddeposit` int(11) NOT NULL,
  `jenis_pengajuan` enum('penarikan','penyimpanan') NOT NULL,
  `jenis_deposit` enum('pokok','wajib','manasuka','manasuka free') NOT NULL,
  `cash_in` decimal(10,2) DEFAULT 0.00,
  `cash_out` decimal(10,2) DEFAULT 0.00,
  `deskripsi` text DEFAULT NULL,
  `status` enum('upload bukti','diproses bendahara','diproses admin','diproses','diterima','ditolak') DEFAULT NULL,
  `bukti_transfer` text DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `idanggota` int(11) DEFAULT NULL,
  `idadmin` int(11) DEFAULT NULL,
  `idbendahara` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_deposit`
--

INSERT INTO `tb_deposit` (`iddeposit`, `jenis_pengajuan`, `jenis_deposit`, `cash_in`, `cash_out`, `deskripsi`, `status`, `bukti_transfer`, `date_created`, `date_updated`, `idanggota`, `idadmin`, `idbendahara`) VALUES
(1, 'penyimpanan', 'pokok', '100000.00', '0.00', 'saldo pokok', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 12, NULL, NULL),
(2, 'penyimpanan', 'wajib', '3000000.00', '0.00', 'saldo wajib', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 12, NULL, NULL),
(3, 'penyimpanan', 'manasuka', '6000000.00', '0.00', 'saldo manasuka', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 12, NULL, NULL),
(4, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 13, NULL, NULL),
(5, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 13, NULL, NULL),
(6, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 14, NULL, NULL),
(7, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 14, NULL, NULL),
(8, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 15, NULL, NULL),
(9, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 15, NULL, NULL),
(10, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 16, NULL, NULL),
(11, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 16, NULL, NULL),
(12, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 17, NULL, NULL),
(13, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 17, NULL, NULL),
(14, 'penyimpanan', 'pokok', '100000.00', '0.00', 'saldo pokok', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 18, NULL, NULL),
(15, 'penyimpanan', 'wajib', '4250000.00', '0.00', 'saldo wajib', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 18, NULL, NULL),
(16, 'penyimpanan', 'manasuka', '17900000.00', '0.00', 'saldo manasuka', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 18, NULL, NULL),
(17, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 19, NULL, NULL),
(18, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 19, NULL, NULL),
(19, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 20, NULL, NULL),
(20, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 20, NULL, NULL),
(21, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 21, NULL, NULL),
(22, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 21, NULL, NULL),
(23, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 22, NULL, NULL),
(24, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 22, NULL, NULL),
(25, 'penyimpanan', 'pokok', '100000.00', '0.00', 'saldo pokok', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 23, NULL, NULL),
(26, 'penyimpanan', 'wajib', '8800000.00', '0.00', 'saldo wajib', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 23, NULL, NULL),
(27, 'penyimpanan', 'manasuka', '26800000.00', '0.00', 'saldo manasuka', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 23, NULL, NULL),
(28, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 24, NULL, NULL),
(29, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 24, NULL, NULL),
(30, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 25, NULL, NULL),
(31, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 25, NULL, NULL),
(32, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 26, NULL, NULL),
(33, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 26, NULL, NULL),
(34, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 27, NULL, NULL),
(35, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 27, NULL, NULL),
(36, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 28, NULL, NULL),
(37, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 28, NULL, NULL),
(38, 'penyimpanan', 'pokok', '100000.00', '0.00', 'saldo pokok', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 29, NULL, NULL),
(39, 'penyimpanan', 'wajib', '2350000.00', '0.00', 'saldo wajib', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 29, NULL, NULL),
(40, 'penyimpanan', 'manasuka', '8690000.00', '0.00', 'saldo manasuka', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 29, NULL, NULL),
(41, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 30, NULL, NULL),
(42, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 30, NULL, NULL),
(43, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 31, NULL, NULL),
(44, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 31, NULL, NULL),
(45, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 32, NULL, NULL),
(46, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 32, NULL, NULL),
(47, 'penyimpanan', 'pokok', '100000.00', '0.00', 'saldo pokok', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 33, NULL, NULL),
(48, 'penyimpanan', 'wajib', '300000.00', '0.00', 'saldo wajib', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 33, NULL, NULL),
(49, 'penyimpanan', 'manasuka', '900000.00', '0.00', 'saldo manasuka', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 33, NULL, NULL),
(50, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 34, NULL, NULL),
(51, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 34, NULL, NULL),
(52, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 35, NULL, NULL),
(53, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 35, NULL, NULL),
(54, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 36, NULL, NULL),
(55, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 36, NULL, NULL),
(56, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 37, NULL, NULL),
(57, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 37, NULL, NULL),
(58, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 38, NULL, NULL),
(59, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 38, NULL, NULL),
(60, 'penyimpanan', 'pokok', '100000.00', '0.00', 'saldo pokok', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 39, NULL, NULL),
(61, 'penyimpanan', 'wajib', '7000000.00', '0.00', 'saldo wajib', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 39, NULL, NULL),
(62, 'penyimpanan', 'manasuka', '15000000.00', '0.00', 'saldo manasuka', 'diterima', NULL, '2023-02-10 09:17:47', NULL, 39, NULL, NULL),
(63, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 40, NULL, NULL),
(64, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 40, NULL, NULL),
(65, 'penyimpanan', 'pokok', '200000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 41, NULL, NULL),
(66, 'penyimpanan', 'pokok', '100000.00', '0.00', 'biaya awal registrasi', 'diproses', NULL, '2023-02-10 09:17:47', NULL, 41, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_group`
--

CREATE TABLE `tb_group` (
  `idgroup` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `flag` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_group`
--

INSERT INTO `tb_group` (`idgroup`, `keterangan`, `created`, `flag`) VALUES
(1, 'Admin', '2022-12-11 06:36:13', 1),
(2, 'Bendahara', '2022-12-11 06:36:13', 1),
(3, 'Ketua', '2022-12-11 06:36:13', 1),
(4, 'Anggota', '2022-12-11 06:36:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_monthly_report`
--

CREATE TABLE `tb_monthly_report` (
  `idreportm` int(11) NOT NULL,
  `date_monthly` date NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file` text DEFAULT NULL,
  `flag` binary(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_parameter`
--

CREATE TABLE `tb_parameter` (
  `idparameter` int(11) NOT NULL,
  `parameter` varchar(255) NOT NULL,
  `nilai` double NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_parameter`
--

INSERT INTO `tb_parameter` (`idparameter`, `parameter`, `nilai`, `deskripsi`, `created`, `updated`) VALUES
(1, 'Simpanan Pokok (Rp.)', 200000, 'Parameter default untuk simpanan pokok pada pakta bersatuan Rupiah', '2022-09-20 03:28:52', '2022-12-15 10:23:15'),
(2, 'Simpanan Wajib (Rp.)', 100000, 'Parameter default untuk simpanan wajib pada pakta bersatuan Rupiah', '2022-09-20 03:28:52', '2022-12-15 10:23:15'),
(3, 'Simpanan Manasuka (Rp.)', 500000, 'Parameter default untuk simpanan manasuka bersatuan Rupiah', '2022-09-20 03:28:52', '2022-09-20 03:10:14'),
(4, 'Jasa Koperasi (%)', 10, 'persentase untuk pemotongan jasa koperasi', '2022-09-20 03:28:52', '2022-12-15 10:24:48'),
(5, 'Provisi (%)', 0.5, 'persentase untuk pemotongan provisi', '2022-09-20 03:28:52', '2022-10-22 12:27:36'),
(6, 'Penalty (%)', 5, 'persentase untuk pemotongan penalty', '2022-09-20 03:28:52', '2022-12-15 10:24:28'),
(7, 'Bulan Minimal bebas penalty', 6, 'tanggal tetap untuk pemotongan penalty', '2022-09-20 03:28:52', '2022-12-15 10:23:30'),
(8, 'Tanggal cut-off', 10, 'tanggal untuk cut-off', '2022-09-20 03:28:52', '2022-11-01 10:57:51'),
(9, 'Bunga (%)', 1, 'Parameter pemotongan bunga umum', '2022-10-22 07:28:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_parameter_history`
--

CREATE TABLE `tb_parameter_history` (
  `idparameterhistory` int(11) NOT NULL,
  `parameter` varchar(255) NOT NULL,
  `nilai` double NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `idparameter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_param_manasuka`
--

CREATE TABLE `tb_param_manasuka` (
  `idmnskparam` int(11) NOT NULL,
  `nilai` decimal(10,2) DEFAULT 0.00,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idanggota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_param_manasuka`
--

INSERT INTO `tb_param_manasuka` (`idmnskparam`, `nilai`, `created`, `updated`, `idanggota`) VALUES
(1, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 12),
(2, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 13),
(3, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 14),
(4, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 15),
(5, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 16),
(6, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 17),
(7, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 18),
(8, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 19),
(9, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 20),
(10, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 21),
(11, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 22),
(12, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 23),
(13, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 24),
(14, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 25),
(15, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 26),
(16, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 27),
(17, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 28),
(18, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 29),
(19, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 30),
(20, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 31),
(21, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 32),
(22, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 33),
(23, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 34),
(24, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 35),
(25, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 36),
(26, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 37),
(27, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 38),
(28, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 39),
(29, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 40),
(30, '500000.00', '2023-02-10 02:17:47', '2023-02-10 02:17:47', 41);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pinjaman`
--

CREATE TABLE `tb_pinjaman` (
  `idpinjaman` int(11) NOT NULL,
  `nominal` double NOT NULL,
  `tipe_permohonan` enum('pinjaman','pengadaan barang','lain-lain') DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `status` int(1) NOT NULL,
  `form_bukti` text DEFAULT NULL,
  `slip_gaji` text DEFAULT NULL,
  `alasan_tolak` text DEFAULT NULL,
  `bln_perdana` int(2) DEFAULT NULL,
  `tanggal_bayar` int(2) NOT NULL,
  `angsuran_bulanan` int(2) NOT NULL,
  `idanggota` int(11) DEFAULT NULL,
  `idadmin` int(11) DEFAULT NULL,
  `idbendahara` int(11) DEFAULT NULL,
  `idketua` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `iduser` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `pass` text NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `unit_kerja` varchar(255) NOT NULL,
  `status_pegawai` enum('tetap','kontrak') NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `no_rek` varchar(50) DEFAULT NULL,
  `profil_pic` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `closebook_request` enum('closebook') DEFAULT NULL,
  `closebook_request_date` datetime DEFAULT NULL,
  `closebook_last_updated` datetime DEFAULT NULL,
  `closebook_param_count` int(11) DEFAULT NULL,
  `flag` int(1) NOT NULL,
  `idgroup` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`iduser`, `username`, `pass`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `instansi`, `unit_kerja`, `status_pegawai`, `nomor_telepon`, `email`, `nama_bank`, `no_rek`, `profil_pic`, `created`, `updated`, `closebook_request`, `closebook_request_date`, `closebook_last_updated`, `closebook_param_count`, `flag`, `idgroup`) VALUES
(1, 'GIAT0001', '25d55ad283aa400af464c76d713c07ad', '5667062724210552', 'ADMIN', 'Bandung', '1998-11-19', '233 Iris St\r\nWest Yellowstone, Montana(MT), 59758', 'Universitas Telkom', 'Pegawai', 'tetap', '748121967790', 'admin@example.xyz', 'MANDIRI', '98381354824953', 'image.jpg', '2023-02-09 18:17:03', '2023-02-10 00:28:51', NULL, NULL, NULL, 0, 1, 1),
(2, 'GIAT0002', '25d55ad283aa400af464c76d713c07ad', '2423579537873169', 'BENDAHARA', 'Ciwidey', '1995-11-19', '12 Forest Row\r\nGreat Neck, New York(NY), 11023', 'Universitas Telkom', 'Pegawai', 'tetap', '604792159595', 'bendahara@example.xyz', 'MANDIRI', '33690819764963', 'image.jpg', '2023-02-10 00:25:58', '2023-02-10 00:28:51', NULL, NULL, NULL, 0, 1, 2),
(3, 'GIAT0003', '25d55ad283aa400af464c76d713c07ad', '4406538940533129', 'KETUA', 'Cicaheum', '1981-11-19', '6 Short Hill Ln #9\r\nErlanger, Kentucky(KY), 41018', 'Universitas Telkom', 'KETUA GIAT', 'tetap', '5164870839', 'ketua@example.xyz', 'MANDIRI', '96025030241411', 'image.jpg', '2023-02-10 00:39:04', '2023-02-10 00:39:04', NULL, NULL, NULL, 0, 1, 3),
(12, 'GIAT0004', '25d55ad283aa400af464c76d713c07ad', '6069511748567553', 'ABDIAWIPA ROFADALINY', 'Bandung', '1970-01-01', 'Goldendale, Washington(WA), 98620', 'YPT', 'Dosen', 'tetap', '651175353455', 'Abdiawipa@example.xyz', 'MANDIRI', '8973412185256', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(13, 'GIAT0005', '25d55ad283aa400af464c76d713c07ad', '5285540357912539', 'ADE IRMA SUSANTY', 'Soreang', '1970-01-01', 'Southern Pines, North Carolina(NC), 28387', 'YPT', 'Dosen', 'tetap', '480752907696', 'Ade@example.xyz', 'BNI', '61372209996171', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(14, 'GIAT0006', '25d55ad283aa400af464c76d713c07ad', '8018332991044541', 'ADITYA WARDHANA', 'Bogor', '1970-01-01', 'Nesbit, Mississippi(MS), 38672', 'YPT', 'Dosen', 'kontrak', '546226281475', 'Aditya@example.xyz', 'BNI', '23490541605653', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(15, 'GIAT0007', '25d55ad283aa400af464c76d713c07ad', '1582459469870161', 'ABD. RAHMAN N', 'Kuningan', '1970-01-01', 'Monticello, Georgia(GA), 31064', 'YPT', 'Staff', 'tetap', '181685777457', 'Abd@example.xyz', 'MANDIRI', '16408384536548', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(16, 'GIAT0008', '25d55ad283aa400af464c76d713c07ad', '6966460351355263', 'AFRIANA NUR MULYA', 'Karawang', '1970-01-01', 'Duquesne, Pennsylvania(PA), 15110', 'YPT', 'Staff', 'kontrak', '173456103627', 'Afriana@example.xyz', 'BJB', '17134378715338', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(17, 'GIAT0009', '25d55ad283aa400af464c76d713c07ad', '2454999531687672', 'AGUS APRIANTI ', 'Bekasi', '1970-01-01', 'Port Royal, South Carolina(SC), 29902', 'Universitas Telkom', 'Dosen', 'tetap', '211438113687', 'Agus@example.xyz', 'BRI', '48635174677329', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(18, 'GIAT0010', '25d55ad283aa400af464c76d713c07ad', '4380470020838526', 'AGUNG ABDIRAHMAN', 'Ciwidey', '1970-01-01', 'Hartford, South Dakota(SD), 57033', 'Universitas Telkom', 'Staff', 'tetap', '459817178233', 'Agung@example.xyz', 'BCA', '17269450465244', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(19, 'GIAT0011', '25d55ad283aa400af464c76d713c07ad', '1733848561608020', 'AGUNG WIBISANA', 'Cirebon', '1970-01-01', 'Knoxville, Maryland(MD), 21758', 'Universitas Telkom', 'Dosen', 'kontrak', '782363862244', 'Agung@example.xyz', 'BCA', '59071292725591', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(20, 'GIAT0012', '25d55ad283aa400af464c76d713c07ad', '2114331932143001', 'AGUS HERMAWAN', 'Sumedang', '1970-01-01', 'Liberty, New York(NY), 12754', 'Universitas Telkom', 'Dosen', 'tetap', '352967517576', 'Agus@example.xyz', 'MANDIRI', '48962848753663', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(21, 'GIAT0013', '25d55ad283aa400af464c76d713c07ad', '7601548902064496', 'AGUS MAOLANA HIDAYAT', 'Buahbatu', '1970-01-01', 'Hilliard, Ohio(OH), 43026', 'Universitas Telkom', 'Staff', 'kontrak', '344185965618', 'Agus@example.xyz', 'MANDIRI', '84528109202754', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(22, 'GIAT0014', '25d55ad283aa400af464c76d713c07ad', '4368535514921890', 'AHMAD DARAJAT BASALLAMA', 'Bandung', '1970-01-01', 'Goldendale, Washington(WA), 98621', 'Trengginas Jaya', 'Dosen', 'tetap', '805738845472', 'Ahmad@example.xyz', 'BNI', '14809075804134', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(23, 'GIAT0015', '25d55ad283aa400af464c76d713c07ad', '9810937012804708', 'AHMAD FAUZAN', 'Soreang', '1970-01-01', 'Southern Pines, North Carolina(NC), 28388', 'Trengginas Jaya', 'Dosen', 'tetap', '361283802690', 'Ahmad@example.xyz', 'BNI', '24500735272104', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(24, 'GIAT0016', '25d55ad283aa400af464c76d713c07ad', '3926523180385049', 'AHMAD NUR SHEHA GUNAWAN', 'Bogor', '1970-01-01', 'Nesbit, Mississippi(MS), 38673', 'Trengginas Jaya', 'Dosen', 'kontrak', '981302124493', 'Ahmad@example.xyz', 'MANDIRI', '47373818290243', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(25, 'GIAT0017', '25d55ad283aa400af464c76d713c07ad', '8608537224573259', 'ALEX WINARNO', 'Kuningan', '1970-01-01', 'Monticello, Georgia(GA), 31065', 'Trengginas Jaya', 'Staff', 'tetap', '981302124493', 'Alex@example.xyz', 'BJB', '12659921879961', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(26, 'GIAT0018', '25d55ad283aa400af464c76d713c07ad', '9317593879702463', 'AI KARLINA', 'Karawang', '1970-01-01', 'Duquesne, Pennsylvania(PA), 15111', 'Trengginas Jaya', 'Staff', 'kontrak', '669077827613', 'Ai@example.xyz', 'BRI', '15066064977676', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(27, 'GIAT0019', '25d55ad283aa400af464c76d713c07ad', '3603663875383124', 'AIDA ANDRIANAWATI', 'Bekasi', '1970-01-01', 'Port Royal, South Carolina(SC), 29903', 'BUT', 'Dosen', 'tetap', '142082445083', 'Aida@example.xyz', 'BCA', '40533548178891', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(28, 'GIAT0020', '25d55ad283aa400af464c76d713c07ad', '3346004376862511', 'AISYI SYAFIKARANI', 'Ciwidey', '1970-01-01', 'Hartford, South Dakota(SD), 57034', 'BUT', 'Staff', 'tetap', '138559578199', 'Aisyi@example.xyz', 'BCA', '99756692820082', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(29, 'GIAT0021', '25d55ad283aa400af464c76d713c07ad', '7441443388491144', 'AJENG LUTHFIYATUL FARIDA', 'Cirebon', '1970-01-01', 'Knoxville, Maryland(MD), 21759', 'BUT', 'Dosen', 'kontrak', '318410785353', 'Ajeng@example.xyz', 'MANDIRI', '91355487786265', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(30, 'GIAT0022', '25d55ad283aa400af464c76d713c07ad', '6773577591818296', 'AJENG PARAMITA', 'Sumedang', '1970-01-01', 'Liberty, New York(NY), 12755', 'BUT', 'Dosen', 'tetap', '882884618626', 'Ajeng@example.xyz', 'MANDIRI', '59238780653499', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(31, 'GIAT0023', '25d55ad283aa400af464c76d713c07ad', '8850920979328777', 'AJI SUHENDAR', 'Buahbatu', '1970-01-01', 'Hilliard, Ohio(OH), 43027', 'BUT', 'Staff', 'kontrak', '465584087513', 'Aji@example.xyz', 'BNI', '18902214798752', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(32, 'GIAT0024', '25d55ad283aa400af464c76d713c07ad', '6940059501984885', 'AJID AWALUDIN', 'Bandung', '1970-01-01', 'Goldendale, Washington(WA), 98622', 'Telkom', 'Dosen', 'tetap', '757308772001', 'Ajid@example.xyz', 'BNI', '98793811104621', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(33, 'GIAT0025', '25d55ad283aa400af464c76d713c07ad', '8975523840763832', 'AKBAR ATHOILA', 'Soreang', '1970-01-01', 'Southern Pines, North Carolina(NC), 28389', 'Telkom', 'Dosen', 'tetap', '411118690924', 'Akbar@example.xyz', 'MANDIRI', '92999669132624', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(34, 'GIAT0026', '25d55ad283aa400af464c76d713c07ad', '3459519953244207', 'AKHMAD YUNANI', 'Bogor', '1970-01-01', 'Nesbit, Mississippi(MS), 38674', 'Telkom', 'Dosen', 'kontrak', '944253817114', 'Akhmad@example.xyz', 'BJB', '76425961854572', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(35, 'GIAT0027', '25d55ad283aa400af464c76d713c07ad', '3298133445120981', 'AKHMADI', 'Kuningan', '1970-01-01', 'Monticello, Georgia(GA), 31066', 'Telkom', 'Staff', 'tetap', '769716725238', 'Akhmadi@example.xyz', 'BRI', '43862017655410', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(36, 'GIAT0028', '25d55ad283aa400af464c76d713c07ad', '6676395530354750', 'ALDI HENDRAWAN', 'Karawang', '1970-01-01', 'Duquesne, Pennsylvania(PA), 15112', 'Telkom', 'Staff', 'kontrak', '727016292950', 'Aldi@example.xyz', 'BCA', '38508553536044', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(37, 'GIAT0029', '25d55ad283aa400af464c76d713c07ad', '1339519725504356', 'ALILA PRAMIYANTI', 'Bekasi', '1970-01-01', 'Port Royal, South Carolina(SC), 29904', 'GIAT', 'Dosen', 'tetap', '773856083561', 'Alila@example.xyz', 'BCA', '91886097435397', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(38, 'GIAT0030', '25d55ad283aa400af464c76d713c07ad', '9723344912860897', 'ANAK AGUNG GDE AGUNG', 'Ciwidey', '1970-01-01', 'Hartford, South Dakota(SD), 57035', 'GIAT', 'Staff', 'tetap', '363467688057', 'Anak@example.xyz', 'MANDIRI', '75070031765239', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(39, 'GIAT0031', '25d55ad283aa400af464c76d713c07ad', '8333665859402651', 'ANDI TRI CHRISMA', 'Cirebon', '1970-01-01', 'Knoxville, Maryland(MD), 21760', 'GIAT', 'Dosen', 'kontrak', '401562699825', 'Andi@example.xyz', 'MANDIRI', '65966388344684', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(40, 'GIAT0032', '25d55ad283aa400af464c76d713c07ad', '9141357102705734', 'ANDINA PUSPITA', 'Sumedang', '1970-01-01', 'Liberty, New York(NY), 12756', 'GIAT', 'Dosen', 'tetap', '250818147071', 'Andina@example.xyz', 'BNI', '43893849082140', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4),
(41, 'GIAT0033', '25d55ad283aa400af464c76d713c07ad', '4201071083168713', 'ANDRI MAHARANA PUTRA', 'Buahbatu', '1970-01-01', 'Hilliard, Ohio(OH), 43028', 'GIAT', 'Staff', 'kontrak', '183596608486', 'Andri@example.xyz', 'BNI', '48405837881565', 'image.jpg', '2023-02-10 02:17:47', '2023-02-10 02:17:47', NULL, NULL, NULL, 0, 1, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notification_log`
--
ALTER TABLE `notification_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_cicilan`
--
ALTER TABLE `tb_cicilan`
  ADD PRIMARY KEY (`idcicilan`),
  ADD KEY `idpinjaman` (`idpinjaman`);

--
-- Indexes for table `tb_deposit`
--
ALTER TABLE `tb_deposit`
  ADD PRIMARY KEY (`iddeposit`),
  ADD KEY `fk_admin_user` (`idadmin`),
  ADD KEY `fk_bendahara_user` (`idbendahara`),
  ADD KEY `fk_anggota_user` (`idanggota`);

--
-- Indexes for table `tb_group`
--
ALTER TABLE `tb_group`
  ADD PRIMARY KEY (`idgroup`);

--
-- Indexes for table `tb_monthly_report`
--
ALTER TABLE `tb_monthly_report`
  ADD PRIMARY KEY (`idreportm`);

--
-- Indexes for table `tb_parameter`
--
ALTER TABLE `tb_parameter`
  ADD PRIMARY KEY (`idparameter`);

--
-- Indexes for table `tb_parameter_history`
--
ALTER TABLE `tb_parameter_history`
  ADD PRIMARY KEY (`idparameterhistory`),
  ADD KEY `idparameter` (`idparameter`);

--
-- Indexes for table `tb_param_manasuka`
--
ALTER TABLE `tb_param_manasuka`
  ADD PRIMARY KEY (`idmnskparam`),
  ADD KEY `fk_parammanasuka_anggota_user` (`idanggota`);

--
-- Indexes for table `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  ADD PRIMARY KEY (`idpinjaman`),
  ADD KEY `idanggota` (`idanggota`),
  ADD KEY `idadmin` (`idadmin`),
  ADD KEY `idbendahara` (`idbendahara`),
  ADD KEY `idketua` (`idketua`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`iduser`),
  ADD KEY `idgroup` (`idgroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notification_log`
--
ALTER TABLE `notification_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_cicilan`
--
ALTER TABLE `tb_cicilan`
  MODIFY `idcicilan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_deposit`
--
ALTER TABLE `tb_deposit`
  MODIFY `iddeposit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tb_group`
--
ALTER TABLE `tb_group`
  MODIFY `idgroup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_monthly_report`
--
ALTER TABLE `tb_monthly_report`
  MODIFY `idreportm` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_parameter`
--
ALTER TABLE `tb_parameter`
  MODIFY `idparameter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_parameter_history`
--
ALTER TABLE `tb_parameter_history`
  MODIFY `idparameterhistory` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_param_manasuka`
--
ALTER TABLE `tb_param_manasuka`
  MODIFY `idmnskparam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  MODIFY `idpinjaman` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_cicilan`
--
ALTER TABLE `tb_cicilan`
  ADD CONSTRAINT `tb_cicilan_ibfk_1` FOREIGN KEY (`idpinjaman`) REFERENCES `tb_pinjaman` (`idpinjaman`);

--
-- Constraints for table `tb_deposit`
--
ALTER TABLE `tb_deposit`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`idadmin`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `fk_anggota_user` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `fk_bendahara_user` FOREIGN KEY (`idbendahara`) REFERENCES `tb_user` (`iduser`);

--
-- Constraints for table `tb_parameter_history`
--
ALTER TABLE `tb_parameter_history`
  ADD CONSTRAINT `tb_parameter_history_ibfk_1` FOREIGN KEY (`idparameter`) REFERENCES `tb_parameter` (`idparameter`);

--
-- Constraints for table `tb_param_manasuka`
--
ALTER TABLE `tb_param_manasuka`
  ADD CONSTRAINT `fk_parammanasuka_anggota_user` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`);

--
-- Constraints for table `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  ADD CONSTRAINT `tb_pinjaman_ibfk_1` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `tb_pinjaman_ibfk_2` FOREIGN KEY (`idadmin`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `tb_pinjaman_ibfk_4` FOREIGN KEY (`idbendahara`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `tb_pinjaman_ibfk_5` FOREIGN KEY (`idketua`) REFERENCES `tb_user` (`iduser`);

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `tb_group` (`idgroup`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
