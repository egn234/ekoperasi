/*
 Navicat Premium Dump SQL

 Source Server         : mysql-local
 Source Server Type    : MySQL
 Source Server Version : 80043 (8.0.43)
 Source Host           : localhost:3306
 Source Schema         : ekoperasi

 Target Server Type    : MySQL
 Target Server Version : 80043 (8.0.43)
 File Encoding         : 65001

 Date: 15/11/2025 08:43:08
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions`  (
  `id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` blob NOT NULL,
  INDEX `ci_sessions_timestamp`(`timestamp` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for notification_log
-- ----------------------------
DROP TABLE IF EXISTS `notification_log`;
CREATE TABLE `notification_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `cicilan_id` int NULL DEFAULT NULL,
  `deposit_id` int NULL DEFAULT NULL,
  `admin_id` int NULL DEFAULT NULL,
  `bendahara_id` int NULL DEFAULT NULL,
  `ketua_id` int NULL DEFAULT NULL,
  `anggota_id` int NULL DEFAULT NULL,
  `parameter_id` int NULL DEFAULT NULL,
  `pinjaman_id` int NULL DEFAULT NULL,
  `closebook` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_type` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'unread',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6242 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_asuransi_pinjaman
-- ----------------------------
DROP TABLE IF EXISTS `tb_asuransi_pinjaman`;
CREATE TABLE `tb_asuransi_pinjaman`  (
  `idasuransi` int NOT NULL AUTO_INCREMENT,
  `idpinjaman` int NOT NULL,
  `bulan_kumulatif` int NOT NULL,
  `nilai_asuransi` decimal(20, 2) NOT NULL,
  `status` enum('aktif','klaim','hangus') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'aktif',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idasuransi`) USING BTREE,
  INDEX `idpinjaman`(`idpinjaman` ASC) USING BTREE,
  CONSTRAINT `tb_asuransi_pinjaman_ibfk_1` FOREIGN KEY (`idpinjaman`) REFERENCES `tb_pinjaman` (`idpinjaman`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_cicilan
-- ----------------------------
DROP TABLE IF EXISTS `tb_cicilan`;
CREATE TABLE `tb_cicilan`  (
  `idcicilan` int NOT NULL AUTO_INCREMENT,
  `nominal` decimal(20, 2) NOT NULL,
  `bunga` decimal(20, 2) NOT NULL,
  `provisi` decimal(20, 2) NULL DEFAULT 0.00,
  `tipe_bayar` enum('otomatis','langsung') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'otomatis',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idpinjaman` int NOT NULL,
  PRIMARY KEY (`idcicilan`) USING BTREE,
  INDEX `idpinjaman`(`idpinjaman` ASC) USING BTREE,
  CONSTRAINT `tb_cicilan_ibfk_1` FOREIGN KEY (`idpinjaman`) REFERENCES `tb_pinjaman` (`idpinjaman`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7303 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_deposit
-- ----------------------------
DROP TABLE IF EXISTS `tb_deposit`;
CREATE TABLE `tb_deposit`  (
  `iddeposit` int NOT NULL AUTO_INCREMENT,
  `jenis_pengajuan` enum('penarikan','penyimpanan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_deposit` enum('pokok','wajib','manasuka','manasuka free') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cash_in` decimal(20, 2) NULL DEFAULT 0.00,
  `cash_out` decimal(20, 2) NULL DEFAULT 0.00,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` enum('upload bukti','diproses bendahara','diproses admin','diproses','diterima','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alasan_tolak` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `bukti_transfer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idanggota` int NULL DEFAULT NULL,
  `idadmin` int NULL DEFAULT NULL,
  `idbendahara` int NULL DEFAULT NULL,
  PRIMARY KEY (`iddeposit`) USING BTREE,
  INDEX `fk_admin_user`(`idadmin` ASC) USING BTREE,
  INDEX `fk_bendahara_user`(`idbendahara` ASC) USING BTREE,
  INDEX `fk_anggota_user`(`idanggota` ASC) USING BTREE,
  CONSTRAINT `fk_admin_user` FOREIGN KEY (`idadmin`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_anggota_user` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_bendahara_user` FOREIGN KEY (`idbendahara`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 38555 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_group
-- ----------------------------
DROP TABLE IF EXISTS `tb_group`;
CREATE TABLE `tb_group`  (
  `idgroup` int NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created` datetime NOT NULL,
  `flag` int NOT NULL,
  PRIMARY KEY (`idgroup`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

--
-- Dumping data for table `tb_group`
--

INSERT INTO `tb_group` (`idgroup`, `keterangan`, `created`, `flag`) VALUES
(1, 'Admin', '2022-12-11 06:36:13', 1),
(2, 'Bendahara', '2022-12-11 06:36:13', 1),
(3, 'Ketua', '2022-12-11 06:36:13', 1),
(4, 'Anggota', '2022-12-11 06:36:13', 1);

-- ----------------------------
-- Table structure for tb_monthly_report
-- ----------------------------
DROP TABLE IF EXISTS `tb_monthly_report`;
CREATE TABLE `tb_monthly_report`  (
  `idreportm` int NOT NULL AUTO_INCREMENT,
  `date_monthly` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `flag` binary(1) NULL DEFAULT 0x31,
  PRIMARY KEY (`idreportm`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_param_manasuka
-- ----------------------------
DROP TABLE IF EXISTS `tb_param_manasuka`;
CREATE TABLE `tb_param_manasuka`  (
  `idmnskparam` int NOT NULL AUTO_INCREMENT,
  `nilai` decimal(20, 2) NULL DEFAULT 0.00,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idanggota` int NOT NULL,
  PRIMARY KEY (`idmnskparam`) USING BTREE,
  INDEX `fk_parammanasuka_anggota_user`(`idanggota` ASC) USING BTREE,
  CONSTRAINT `fk_parammanasuka_anggota_user` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 853 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_param_manasuka_log
-- ----------------------------
DROP TABLE IF EXISTS `tb_param_manasuka_log`;
CREATE TABLE `tb_param_manasuka_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nominal` decimal(20, 2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idmnskparam` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_mnsk_param_log_1`(`idmnskparam` ASC) USING BTREE,
  CONSTRAINT `fk_mnsk_param_log_1` FOREIGN KEY (`idmnskparam`) REFERENCES `tb_param_manasuka` (`idmnskparam`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 168 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_parameter
-- ----------------------------
DROP TABLE IF EXISTS `tb_parameter`;
CREATE TABLE `tb_parameter`  (
  `idparameter` int NOT NULL AUTO_INCREMENT,
  `parameter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai` double NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idparameter`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_parameter
-- ----------------------------
INSERT INTO `tb_parameter` VALUES (1, 'Simpanan Pokok (Rp.)', 200000, 'Parameter default untuk simpanan pokok pada pakta bersatuan Rupiah', '2022-09-20 03:28:52', '2022-12-15 10:23:15');
INSERT INTO `tb_parameter` VALUES (2, 'Simpanan Wajib (Rp.)', 100000, 'Parameter default untuk simpanan wajib pada pakta bersatuan Rupiah', '2022-09-20 03:28:52', '2022-12-15 10:23:15');
INSERT INTO `tb_parameter` VALUES (3, 'Simpanan Manasuka (Rp.)', 500000, 'Parameter default untuk simpanan manasuka bersatuan Rupiah', '2022-09-20 03:28:52', '2022-09-20 03:10:14');
INSERT INTO `tb_parameter` VALUES (4, 'Jasa Koperasi (%)', 10, 'persentase untuk pemotongan jasa koperasi', '2022-09-20 03:28:52', '2022-12-15 10:24:48');
INSERT INTO `tb_parameter` VALUES (5, 'Provisi (%)', 0.5, 'persentase untuk pemotongan provisi', '2022-09-20 03:28:52', '2022-10-22 12:27:36');
INSERT INTO `tb_parameter` VALUES (6, 'Penalty (%)', 5, 'persentase untuk pemotongan penalty', '2022-09-20 03:28:52', '2022-12-15 10:24:28');
INSERT INTO `tb_parameter` VALUES (7, 'Bulan Minimal bebas penalty', 6, 'tanggal tetap untuk pemotongan penalty', '2022-09-20 03:28:52', '2022-12-15 10:23:30');
INSERT INTO `tb_parameter` VALUES (8, 'Tanggal cut-off', 10, 'tanggal untuk cut-off', '2022-09-20 03:28:52', '2023-03-15 11:08:07');
INSERT INTO `tb_parameter` VALUES (9, 'Bunga (%)', 1, 'Parameter pemotongan bunga umum', '2022-10-22 07:28:01', NULL);
INSERT INTO `tb_parameter` VALUES (10, 'Batas Bulan Minimal Pinjaman Kontrak', 6, 'Parameter untuk batas minimal pinjaman setelah akun tipe kontrak dibuat', '2025-11-16 06:39:20', NULL);
INSERT INTO `tb_parameter` VALUES (11, 'Batas Bulan Minimal Pinjaman Tetap', 12, 'Parameter untuk batas minimal pinjaman setelah akun tipe tetap dibuat', '2025-11-16 06:39:20', NULL);
INSERT INTO `tb_parameter` VALUES (12, 'Bulan Kelipatan Asuransi', 12, 'Parameter untuk bulan kelipatan asuransi', '2025-11-16 06:42:51', NULL);
INSERT INTO `tb_parameter` VALUES (13, 'Nominal Asuransi', 50000, 'Parameter untuk nominal asuransi per kelipatan bulan', '2025-11-16 06:42:54', NULL);

-- ----------------------------
-- Table structure for tb_parameter_history
-- ----------------------------
DROP TABLE IF EXISTS `tb_parameter_history`;
CREATE TABLE `tb_parameter_history`  (
  `idparameterhistory` int NOT NULL AUTO_INCREMENT,
  `parameter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai` double NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `update_date` datetime NULL DEFAULT NULL,
  `idparameter` int NULL DEFAULT NULL,
  PRIMARY KEY (`idparameterhistory`) USING BTREE,
  INDEX `idparameter`(`idparameter` ASC) USING BTREE,
  CONSTRAINT `tb_parameter_history_ibfk_1` FOREIGN KEY (`idparameter`) REFERENCES `tb_parameter` (`idparameter`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_pinjaman
-- ----------------------------
DROP TABLE IF EXISTS `tb_pinjaman`;
CREATE TABLE `tb_pinjaman`  (
  `idpinjaman` int NOT NULL AUTO_INCREMENT,
  `nominal` decimal(20, 2) NOT NULL,
  `potongan_topup` decimal(20, 2) NOT NULL,
  `penalty` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tipe_permohonan` enum('pinjaman','pengadaan barang','lain-lain') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NULL DEFAULT NULL,
  `status` int NOT NULL,
  `form_bukti` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `slip_gaji` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `form_kontrak` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `bukti_tf` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `alasan_tolak` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `bln_perdana` int NULL DEFAULT NULL,
  `tanggal_bayar` int NOT NULL,
  `angsuran_bulanan` int NOT NULL,
  `idanggota` int NULL DEFAULT NULL,
  `idadmin` int NULL DEFAULT NULL,
  `idbendahara` int NULL DEFAULT NULL,
  `idketua` int NULL DEFAULT NULL,
  PRIMARY KEY (`idpinjaman`) USING BTREE,
  INDEX `idanggota`(`idanggota` ASC) USING BTREE,
  INDEX `idadmin`(`idadmin` ASC) USING BTREE,
  INDEX `idbendahara`(`idbendahara` ASC) USING BTREE,
  INDEX `idketua`(`idketua` ASC) USING BTREE,
  CONSTRAINT `tb_pinjaman_ibfk_1` FOREIGN KEY (`idanggota`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_pinjaman_ibfk_2` FOREIGN KEY (`idadmin`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_pinjaman_ibfk_4` FOREIGN KEY (`idbendahara`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_pinjaman_ibfk_5` FOREIGN KEY (`idketua`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1118 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user`  (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `instansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `unit_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_pegawai` enum('tetap','kontrak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_telepon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `nama_bank` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_rek` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `profil_pic` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ktp_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `pass_reset_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `pass_reset_status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `closebook_request` enum('closebook') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `closebook_request_date` datetime NULL DEFAULT NULL,
  `closebook_last_updated` datetime NULL DEFAULT NULL,
  `closebook_param_count` int NULL DEFAULT NULL,
  `verified` int NULL DEFAULT 0,
  `flag` int NOT NULL,
  `idgroup` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`iduser`) USING BTREE,
  INDEX `idgroup`(`idgroup` ASC) USING BTREE,
  CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`idgroup`) REFERENCES `tb_group` (`idgroup`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 889 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` VALUES (1, 'admingiat', '$2y$10$5kphsJ3A4f1EUWvV4sPOfezXW9U88465VtBEUcn8B3GK.Vw7h2AAm', '1111111111111111', '11111111', 'ADMIN', '-', '1999-02-02', 'bojongsoang', 'YPT', 'Pegawai', 'tetap', '0822123495810', 'admin@giat.id', 'MANDIRI', '0192839012380', '1691193913_740b8302c749f65dd5c0.jpg', NULL, '2023-09-28 10:36:39', '2025-10-15 12:08:00', NULL, 0, NULL, NULL, NULL, 0, 1, 1, 1);
INSERT INTO `tb_user` VALUES (3, 'ketuagiat', '$2y$10$01PxcARlM/HY27TEsruxxeG/Eu5CVc9nv00JhYssU.aru1qzBcPwG', '1111111111111112', NULL, 'KETUA', '-', '1999-02-02', 'bojongsoang', 'Telkom University', 'Pegawai', 'tetap', '0822123495810', 'ketua@giat.id', 'MANDIRI', '0192839012380', '1691193913_740b8302c749f65dd5c0.jpg', NULL, '2023-09-28 10:45:31', '2025-10-15 12:08:00', NULL, 0, NULL, NULL, NULL, 0, 1, 1, 3);
INSERT INTO `tb_user` VALUES (4, 'bendaharagiat', '$2y$10$tJ6DszBlGyDq9ehGTrlN9uUITDi99X24iIGvl8bLM/ejmFCfIsgma', '1111111111111113', NULL, 'BENDAHARA', '-', '1999-02-02', 'bojongsoang', 'Telkom University', 'Pegawai', 'tetap', '0822123495810', 'admin@giat.id', 'MANDIRI', '0192839012380', '1691193913_740b8302c749f65dd5c0.jpg', NULL, '2023-09-28 10:46:11', '2025-10-15 12:08:00', NULL, 0, NULL, NULL, NULL, 0, 1, 1, 2);

-- ----------------------------
-- New Tables for mini CMS module
-- ----------------------------

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'Ringkasan singkat untuk preview',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `featured_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'Path gambar utama post',
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Kategori post: pengumuman, berita, tutorial, dll',
  `author_id` int NOT NULL COMMENT 'Hanya admin dan bendahara yang bisa membuat post',
  `is_published` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=draft, 1=published',
  `is_public` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=semua user bisa lihat, 0=terbatas sesuai post_targets',
  `views_count` int NOT NULL DEFAULT 0 COMMENT 'Jumlah view/pembaca',
  `published_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu post dipublish',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Soft delete timestamp',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `slug_unique` (`slug`) USING BTREE,
  INDEX `author_id_index` (`author_id` ASC) USING BTREE,
  INDEX `is_published_index` (`is_published` ASC) USING BTREE,
  INDEX `created_at_index` (`created_at` ASC) USING BTREE,
  INDEX `category_index` (`category` ASC) USING BTREE,
  INDEX `deleted_at_index` (`deleted_at` ASC) USING BTREE,
  CONSTRAINT `posts_author_fk` FOREIGN KEY (`author_id`) REFERENCES `tb_user` (`iduser`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=Dynamic COMMENT='Tabel untuk menyimpan posts/artikel CMS';

-- ----------------------------
-- Table structure for post_targets
-- ----------------------------
DROP TABLE IF EXISTS `post_targets`;
CREATE TABLE `post_targets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `target_type` enum('group','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'group' COMMENT 'Tipe target: group atau individual user',
  `target_group_id` int NULL DEFAULT NULL COMMENT 'ID group jika target_type=group (1=Admin, 2=Bendahara, 3=Ketua, 4=Anggota)',
  `target_user_id` int NULL DEFAULT NULL COMMENT 'ID user jika target_type=user (untuk targeting individual)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `post_target_unique` (`post_id`, `target_type`, `target_group_id`, `target_user_id`) USING BTREE,
  INDEX `post_id_index` (`post_id` ASC) USING BTREE,
  INDEX `target_group_id_index` (`target_group_id` ASC) USING BTREE,
  INDEX `target_user_id_index` (`target_user_id` ASC) USING BTREE,
  CONSTRAINT `post_targets_post_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_targets_group_fk` FOREIGN KEY (`target_group_id`) REFERENCES `tb_group` (`idgroup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_targets_user_fk` FOREIGN KEY (`target_user_id`) REFERENCES `tb_user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=Dynamic COMMENT='Tabel untuk menentukan siapa saja yang dapat melihat post tertentu (Validasi target_type vs target_id dilakukan di aplikasi)';

-- ----------------------------
-- Table structure for post_media
-- ----------------------------
DROP TABLE IF EXISTS `post_media`;
CREATE TABLE `post_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `media_type` enum('image','video','document','attachment') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Path file di server',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Nama file asli',
  `file_size` bigint NULL DEFAULT NULL COMMENT 'Ukuran file dalam bytes',
  `display_order` int NOT NULL DEFAULT 0 COMMENT 'Urutan tampilan media',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `post_id_index` (`post_id` ASC) USING BTREE,
  INDEX `media_type_index` (`media_type` ASC) USING BTREE,
  CONSTRAINT `post_media_post_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=Dynamic COMMENT='Tabel untuk menyimpan media/attachment yang terkait dengan post';

-- ----------------------------
-- Table structure for post_views (optional - untuk tracking siapa saja yang sudah baca)
-- ----------------------------
DROP TABLE IF EXISTS `post_views`;
CREATE TABLE `post_views` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `post_user_unique` (`post_id`, `user_id`) USING BTREE,
  INDEX `post_id_index` (`post_id` ASC) USING BTREE,
  INDEX `user_id_index` (`user_id` ASC) USING BTREE,
  CONSTRAINT `post_views_post_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_views_user_fk` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=Dynamic COMMENT='Tabel untuk tracking user mana saja yang sudah membaca post';
