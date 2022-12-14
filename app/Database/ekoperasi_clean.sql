-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2022 at 05:23 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

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
  `jenis_deposit` enum('pokok','wajib','manasuka') NOT NULL,
  `cash_in` float DEFAULT NULL,
  `cash_out` float DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('diproses','diterima','ditolak') DEFAULT NULL,
  `bukti_transfer` text DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `idanggota` int(11) DEFAULT NULL,
  `idadmin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `date_monthly` datetime NOT NULL,
  `file` text DEFAULT NULL,
  `flag` binary(1) NOT NULL
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
(1, 'Simpanan Pokok (Rp.)', 300000, 'Parameter default untuk simpanan pokok pada pakta bersatuan Rupiah', '2022-09-20 03:28:52', '2022-09-22 21:05:15'),
(2, 'Simpanan Wajib (Rp.)', 400000, 'Parameter default untuk simpanan wajib pada pakta bersatuan Rupiah', '2022-09-20 03:28:52', '2022-09-20 03:10:14'),
(3, 'Simpanan Manasuka (Rp.)', 500000, 'Parameter default untuk simpanan manasuka bersatuan Rupiah', '2022-09-20 03:28:52', '2022-09-20 03:10:14'),
(4, 'Jasa Koperasi (%)', 20, 'persentase untuk pemotongan jasa koperasi', '2022-09-20 03:28:52', '2022-10-22 12:27:29'),
(5, 'Provisi (%)', 1, 'persentase untuk pemotongan provisi', '2022-09-20 03:28:52', '2022-10-22 12:27:36'),
(6, 'Penalty (%)', 50, 'persentase untuk pemotongan penalty', '2022-09-20 03:28:52', '2022-09-20 03:12:34'),
(7, 'Bulan Minimal bebas penalty', 5, 'tanggal tetap untuk pemotongan penalty', '2022-09-20 03:28:52', '2022-10-02 17:33:51'),
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
  `nilai` double NOT NULL,
  `created` date NOT NULL,
  `updated` date DEFAULT NULL,
  `idanggota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `bln_perdana` int(2) DEFAULT NULL,
  `tanggal_bayar` int(2) NOT NULL,
  `angsuran_bulanan` int(2) NOT NULL,
  `idanggota` int(11) DEFAULT NULL,
  `idadmin` int(11) DEFAULT NULL,
  `idbendahara` int(11) DEFAULT NULL
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
  `tanggal_lahir` datetime NOT NULL,
  `alamat` text NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `unit_kerja` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `profil_pic` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
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

INSERT INTO `tb_user` (`iduser`, `username`, `pass`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `instansi`, `unit_kerja`, `nomor_telepon`, `email`, `profil_pic`, `created`, `updated`, `closebook_request`, `closebook_request_date`, `closebook_last_updated`, `closebook_param_count`, `flag`, `idgroup`) VALUES
(1, 'admin', '25d55ad283aa400af464c76d713c07ad', '3535699772655202', 'Jane Doe', 'Bandung', '1991-12-10 00:00:00', 'Cisangkuy', 'GIAT', 'Bank', '02274129679711', 'admin@gmail.com', 'image.jpg', '2022-09-16 03:50:28', NULL, NULL, NULL, NULL, 0, 1, 1),
(2, 'bendahara', '25d55ad283aa400af464c76d713c07ad', '3204441911980006', 'Egan Kusmaya Putra', 'Bandung', '1998-11-19 00:00:00', 'Bumi Parahyangan Kencana', 'BUT', 'Programmer', '082215204919', 'bendahara@gmail.com', 'image.jpg', '2022-09-19 14:22:04', NULL, NULL, NULL, NULL, 0, 1, 2),
(3, 'ketua', '25d55ad283aa400af464c76d713c07ad', '3204441911980017', 'John Doe', 'Chicago', '1995-02-14 00:00:00', 'Seattle, Orlando US', 'Trengginas Jaya', 'Translator', '10287303884', 'ketua@gmail.com', 'image.jpg', '2022-09-21 22:09:34', NULL, NULL, NULL, NULL, 0, 1, 3),
(4, 'anggota', '25d55ad283aa400af464c76d713c07ad', '3338757898809569', 'Muhammad Amien Fadhillah', 'Banjarmasin', '1998-12-11 00:00:00', 'Bumi Parahyangan Kencana', 'Telkom University', 'Staff IT', '082215204919', 'anggota@gmail.com', 'image.jpg', '2022-12-11 12:44:05', NULL, NULL, NULL, NULL, 0, 1, 4);

--
-- Indexes for dumped tables
--

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
  ADD KEY `idanggota` (`idanggota`),
  ADD KEY `idadmin` (`idadmin`);

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
  ADD KEY `idanggota` (`idanggota`);

--
-- Indexes for table `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  ADD PRIMARY KEY (`idpinjaman`),
  ADD KEY `idanggota` (`idanggota`),
  ADD KEY `idadmin` (`idadmin`),
  ADD KEY `idbendahara` (`idbendahara`);

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
-- AUTO_INCREMENT for table `tb_cicilan`
--
ALTER TABLE `tb_cicilan`
  MODIFY `idcicilan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_deposit`
--
ALTER TABLE `tb_deposit`
  MODIFY `iddeposit` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `idmnskparam` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  MODIFY `idpinjaman` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

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
  ADD CONSTRAINT `tb_deposit_ibfk_1` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `tb_deposit_ibfk_2` FOREIGN KEY (`idadmin`) REFERENCES `tb_user` (`iduser`);

--
-- Constraints for table `tb_parameter_history`
--
ALTER TABLE `tb_parameter_history`
  ADD CONSTRAINT `tb_parameter_history_ibfk_1` FOREIGN KEY (`idparameter`) REFERENCES `tb_parameter` (`idparameter`);

--
-- Constraints for table `tb_param_manasuka`
--
ALTER TABLE `tb_param_manasuka`
  ADD CONSTRAINT `tb_param_manasuka_ibfk_1` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`);

--
-- Constraints for table `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  ADD CONSTRAINT `tb_pinjaman_ibfk_1` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `tb_pinjaman_ibfk_2` FOREIGN KEY (`idadmin`) REFERENCES `tb_user` (`iduser`),
  ADD CONSTRAINT `tb_pinjaman_ibfk_4` FOREIGN KEY (`idbendahara`) REFERENCES `tb_user` (`iduser`);

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `tb_group` (`idgroup`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
