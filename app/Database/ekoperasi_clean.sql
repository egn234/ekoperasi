-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2023 at 03:52 AM
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
(3, 'GIAT0003', '25d55ad283aa400af464c76d713c07ad', '4406538940533129', 'KETUA', 'Cicaheum', '1981-11-19', '6 Short Hill Ln #9\r\nErlanger, Kentucky(KY), 41018', 'Universitas Telkom', 'KETUA GIAT', 'tetap', '5164870839', 'ketua@example.xyz', 'MANDIRI', '96025030241411', 'image.jpg', '2023-02-10 00:39:04', '2023-02-10 00:39:04', NULL, NULL, NULL, 0, 1, 3);

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
