/*
 Navicat Premium Dump SQL

 Source Server         : mylocal
 Source Server Type    : MySQL
 Source Server Version : 80045 (8.0.45)
 Source Host           : localhost:3306
 Source Schema         : db_sekolah

 Target Server Type    : MySQL
 Target Server Version : 80045 (8.0.45)
 File Encoding         : 65001

 Date: 16/02/2026 17:51:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_bk_jenis
-- ----------------------------
DROP TABLE IF EXISTS `mst_bk_jenis`;
CREATE TABLE `mst_bk_jenis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_bk_kategori
-- ----------------------------
DROP TABLE IF EXISTS `mst_bk_kategori`;
CREATE TABLE `mst_bk_kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_buku
-- ----------------------------
DROP TABLE IF EXISTS `mst_buku`;
CREATE TABLE `mst_buku` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penerbit` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_guru
-- ----------------------------
DROP TABLE IF EXISTS `mst_guru`;
CREATE TABLE `mst_guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori jenis_kelamin',
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_guru_nip_unique` (`nip`),
  KEY `mst_guru_sys_user_id_foreign` (`sys_user_id`),
  CONSTRAINT `mst_guru_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_guru_mapel
-- ----------------------------
DROP TABLE IF EXISTS `mst_guru_mapel`;
CREATE TABLE `mst_guru_mapel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_guru_id` bigint unsigned DEFAULT NULL,
  `mst_mapel_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gm` (`mst_guru_id`,`mst_mapel_id`),
  KEY `mst_guru_mapel_mst_mapel_id_foreign` (`mst_mapel_id`),
  CONSTRAINT `mst_guru_mapel_mst_guru_id_foreign` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_guru_mapel_mst_mapel_id_foreign` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_kelas
-- ----------------------------
DROP TABLE IF EXISTS `mst_kelas`;
CREATE TABLE `mst_kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tingkat` int DEFAULT NULL,
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wali_guru_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mst_kelas_wali_guru_id_foreign` (`wali_guru_id`),
  CONSTRAINT `mst_kelas_wali_guru_id_foreign` FOREIGN KEY (`wali_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_mapel
-- ----------------------------
DROP TABLE IF EXISTS `mst_mapel`;
CREATE TABLE `mst_mapel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mapel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_mapel_kode_mapel_unique` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_siswa
-- ----------------------------
DROP TABLE IF EXISTS `mst_siswa`;
CREATE TABLE `mst_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori jenis_kelamin',
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_siswa',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_siswa_nis_unique` (`nis`),
  KEY `idx_siswa_kelas_status` (`mst_kelas_id`,`status`),
  KEY `idx_siswa_nama` (`nama`),
  KEY `idx_siswa_jk` (`jenis_kelamin`),
  KEY `mst_siswa_sys_user_id_foreign` (`sys_user_id`),
  CONSTRAINT `mst_siswa_mst_kelas_id_foreign` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_siswa_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_siswa_wali
-- ----------------------------
DROP TABLE IF EXISTS `mst_siswa_wali`;
CREATE TABLE `mst_siswa_wali` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `mst_wali_id` bigint unsigned DEFAULT NULL,
  `hubungan` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori hubungan_wali',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sw` (`mst_siswa_id`,`mst_wali_id`),
  KEY `mst_siswa_wali_mst_wali_id_foreign` (`mst_wali_id`),
  CONSTRAINT `mst_siswa_wali_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_siswa_wali_mst_wali_id_foreign` FOREIGN KEY (`mst_wali_id`) REFERENCES `mst_wali` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_soal
-- ----------------------------
DROP TABLE IF EXISTS `mst_soal`;
CREATE TABLE `mst_soal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_mapel_id` bigint unsigned NOT NULL,
  `pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('pilihan_ganda','essay') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pilihan_ganda',
  `tingkat_kesulitan` enum('mudah','sedang','sulit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sedang',
  `media_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path untuk gambar/audio soal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mst_soal_mst_mapel_id_foreign` (`mst_mapel_id`),
  CONSTRAINT `mst_soal_mst_mapel_id_foreign` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_soal_opsi
-- ----------------------------
DROP TABLE IF EXISTS `mst_soal_opsi`;
CREATE TABLE `mst_soal_opsi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_soal_id` bigint unsigned NOT NULL,
  `teks_opsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_jawaban` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika ini kunci jawaban',
  `urutan` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'A, B, C, D, atau E',
  PRIMARY KEY (`id`),
  KEY `mst_soal_opsi_mst_soal_id_foreign` (`mst_soal_id`),
  CONSTRAINT `mst_soal_opsi_mst_soal_id_foreign` FOREIGN KEY (`mst_soal_id`) REFERENCES `mst_soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_tarif_spp
-- ----------------------------
DROP TABLE IF EXISTS `mst_tarif_spp`;
CREATE TABLE `mst_tarif_spp` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contoh: 2023/2024',
  `nominal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mst_tarif_spp_mst_kelas_id_foreign` (`mst_kelas_id`),
  CONSTRAINT `mst_tarif_spp_mst_kelas_id_foreign` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_wali
-- ----------------------------
DROP TABLE IF EXISTS `mst_wali`;
CREATE TABLE `mst_wali` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mst_wali_sys_user_id_foreign` (`sys_user_id`),
  CONSTRAINT `mst_wali_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for mst_wali_murid
-- ----------------------------
DROP TABLE IF EXISTS `mst_wali_murid`;
CREATE TABLE `mst_wali_murid` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mst_wali_murid_sys_user_id_foreign` (`sys_user_id`),
  CONSTRAINT `mst_wali_murid_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_activity_logs`;
CREATE TABLE `sys_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_table` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_user` (`sys_user_id`,`created_at`),
  KEY `idx_logs_module` (`module`,`created_at`),
  CONSTRAINT `sys_activity_logs_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_error_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_error_logs`;
CREATE TABLE `sys_error_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori level_error',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line` int DEFAULT NULL,
  `trace` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_login_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_login_logs`;
CREATE TABLE `sys_login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_login',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sys_login_logs_sys_user_id_foreign` (`sys_user_id`),
  CONSTRAINT `sys_login_logs_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_menus`;
CREATE TABLE `sys_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL COMMENT 'ID menu induk untuk sub-menu',
  `sys_permission_id` bigint unsigned DEFAULT NULL COMMENT 'Relasi ke permission untuk akses menu',
  `nama_menu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Class icon (misal: fa-user, bi-grid)',
  `urutan` int NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_menus_parent_id_foreign` (`parent_id`),
  KEY `sys_menus_sys_permission_id_foreign` (`sys_permission_id`),
  CONSTRAINT `sys_menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `sys_menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sys_menus_sys_permission_id_foreign` FOREIGN KEY (`sys_permission_id`) REFERENCES `sys_permissions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_permissions`;
CREATE TABLE `sys_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_permissions_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_references
-- ----------------------------
DROP TABLE IF EXISTS `sys_references`;
CREATE TABLE `sys_references` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contoh: JENIS_KELAMIN, STATUS_SISWA, STATUS_BAYAR',
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contoh: L, P, aktif, lunas',
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label yang muncul di UI',
  `urutan` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_references_kategori_index` (`kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_permissions`;
CREATE TABLE `sys_role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_role_id` bigint unsigned NOT NULL,
  `sys_permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_permission` (`sys_role_id`,`sys_permission_id`),
  KEY `sys_role_permissions_sys_permission_id_foreign` (`sys_permission_id`),
  CONSTRAINT `sys_role_permissions_sys_permission_id_foreign` FOREIGN KEY (`sys_permission_id`) REFERENCES `sys_permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sys_role_permissions_sys_role_id_foreign` FOREIGN KEY (`sys_role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE `sys_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_roles_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_user_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_roles`;
CREATE TABLE `sys_user_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `sys_role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_role` (`sys_user_id`,`sys_role_id`),
  KEY `sys_user_roles_sys_role_id_foreign` (`sys_role_id`),
  CONSTRAINT `sys_user_roles_sys_role_id_foreign` FOREIGN KEY (`sys_role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sys_user_roles_sys_user_id_foreign` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for sys_users
-- ----------------------------
DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE `sys_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint NOT NULL DEFAULT '3' COMMENT 'Referensi ke sys_roles dengan id 2=guru, 3=siswa, 4=wali',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_absensi_guru
-- ----------------------------
DROP TABLE IF EXISTS `trx_absensi_guru`;
CREATE TABLE `trx_absensi_guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_guru_id` bigint unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_absensi',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ag` (`mst_guru_id`,`tanggal`),
  CONSTRAINT `trx_absensi_guru_mst_guru_id_foreign` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_absensi_siswa
-- ----------------------------
DROP TABLE IF EXISTS `trx_absensi_siswa`;
CREATE TABLE `trx_absensi_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_absensi',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_as` (`mst_siswa_id`,`tanggal`),
  KEY `idx_absensi_siswa_tanggal` (`mst_siswa_id`,`tanggal`),
  KEY `idx_absensi_siswa_status` (`status`),
  CONSTRAINT `trx_absensi_siswa_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_bk_hasil
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_hasil`;
CREATE TABLE `trx_bk_hasil` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned DEFAULT NULL,
  `hasil` text COLLATE utf8mb4_unicode_ci,
  `rekomendasi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_bk_hasil_trx_bk_kasus_id_foreign` (`trx_bk_kasus_id`),
  CONSTRAINT `trx_bk_hasil_trx_bk_kasus_id_foreign` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_bk_kasus
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_kasus`;
CREATE TABLE `trx_bk_kasus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `mst_guru_id` bigint unsigned DEFAULT NULL,
  `mst_bk_kategori_id` bigint unsigned DEFAULT NULL,
  `mst_bk_jenis_id` bigint unsigned DEFAULT NULL,
  `judul_kasus` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_masalah` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_bk',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_bk_kasus_siswa` (`mst_siswa_id`,`status`),
  KEY `idx_bk_kasus_guru` (`mst_guru_id`),
  KEY `trx_bk_kasus_mst_bk_kategori_id_foreign` (`mst_bk_kategori_id`),
  KEY `trx_bk_kasus_mst_bk_jenis_id_foreign` (`mst_bk_jenis_id`),
  CONSTRAINT `trx_bk_kasus_mst_bk_jenis_id_foreign` FOREIGN KEY (`mst_bk_jenis_id`) REFERENCES `mst_bk_jenis` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_mst_bk_kategori_id_foreign` FOREIGN KEY (`mst_bk_kategori_id`) REFERENCES `mst_bk_kategori` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_mst_guru_id_foreign` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_bk_lampiran
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_lampiran`;
CREATE TABLE `trx_bk_lampiran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_bk_lampiran_trx_bk_kasus_id_foreign` (`trx_bk_kasus_id`),
  CONSTRAINT `trx_bk_lampiran_trx_bk_kasus_id_foreign` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_bk_sesi
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_sesi`;
CREATE TABLE `trx_bk_sesi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `metode` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori metode_bk',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_bk_sesi_trx_bk_kasus_id_foreign` (`trx_bk_kasus_id`),
  CONSTRAINT `trx_bk_sesi_trx_bk_kasus_id_foreign` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_bk_tindakan
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_tindakan`;
CREATE TABLE `trx_bk_tindakan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned DEFAULT NULL,
  `deskripsi_tindakan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_bk_tindakan_trx_bk_kasus_id_foreign` (`trx_bk_kasus_id`),
  CONSTRAINT `trx_bk_tindakan_trx_bk_kasus_id_foreign` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_bk_wali
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_wali`;
CREATE TABLE `trx_bk_wali` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned NOT NULL,
  `mst_wali_murid_id` bigint unsigned NOT NULL,
  `peran` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori peran_wali_bk',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_bk_wali_trx_bk_kasus_id_foreign` (`trx_bk_kasus_id`),
  KEY `trx_bk_wali_mst_wali_murid_id_foreign` (`mst_wali_murid_id`),
  CONSTRAINT `trx_bk_wali_mst_wali_murid_id_foreign` FOREIGN KEY (`mst_wali_murid_id`) REFERENCES `mst_wali_murid` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_wali_trx_bk_kasus_id_foreign` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_nilai
-- ----------------------------
DROP TABLE IF EXISTS `trx_nilai`;
CREATE TABLE `trx_nilai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_ujian_id` bigint unsigned DEFAULT NULL,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nilai` (`trx_ujian_id`,`mst_siswa_id`),
  KEY `idx_nilai_ujian_siswa` (`trx_ujian_id`,`mst_siswa_id`),
  KEY `idx_nilai_siswa` (`mst_siswa_id`),
  CONSTRAINT `trx_nilai_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_nilai_trx_ujian_id_foreign` FOREIGN KEY (`trx_ujian_id`) REFERENCES `trx_ujian` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_pembayaran_spp
-- ----------------------------
DROP TABLE IF EXISTS `trx_pembayaran_spp`;
CREATE TABLE `trx_pembayaran_spp` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned NOT NULL,
  `mst_tarif_spp_id` bigint unsigned NOT NULL,
  `bulan` tinyint NOT NULL COMMENT '1=Januari, 12=Desember',
  `tahun` year NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_bayar',
  `metode_pembayaran` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori metode_pembayaran',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `petugas_id` bigint unsigned DEFAULT NULL COMMENT 'User yang mencatat pembayaran',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_spp_bayar` (`mst_siswa_id`,`bulan`,`tahun`),
  KEY `trx_pembayaran_spp_mst_tarif_spp_id_foreign` (`mst_tarif_spp_id`),
  KEY `trx_pembayaran_spp_petugas_id_foreign` (`petugas_id`),
  CONSTRAINT `trx_pembayaran_spp_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_pembayaran_spp_mst_tarif_spp_id_foreign` FOREIGN KEY (`mst_tarif_spp_id`) REFERENCES `mst_tarif_spp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_pembayaran_spp_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_peminjaman_buku
-- ----------------------------
DROP TABLE IF EXISTS `trx_peminjaman_buku`;
CREATE TABLE `trx_peminjaman_buku` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_buku_id` bigint unsigned NOT NULL,
  `mst_siswa_id` bigint unsigned NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_pinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pinjam_siswa_status` (`mst_siswa_id`,`status`),
  KEY `idx_pinjam_buku_status` (`mst_buku_id`,`status`),
  CONSTRAINT `trx_peminjaman_buku_mst_buku_id_foreign` FOREIGN KEY (`mst_buku_id`) REFERENCES `mst_buku` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_peminjaman_buku_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_ranking
-- ----------------------------
DROP TABLE IF EXISTS `trx_ranking`;
CREATE TABLE `trx_ranking` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_rapor_id` bigint unsigned DEFAULT NULL,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `peringkat` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_ranking_trx_rapor_id_foreign` (`trx_rapor_id`),
  KEY `trx_ranking_mst_kelas_id_foreign` (`mst_kelas_id`),
  CONSTRAINT `trx_ranking_mst_kelas_id_foreign` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_ranking_trx_rapor_id_foreign` FOREIGN KEY (`trx_rapor_id`) REFERENCES `trx_rapor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_rapor
-- ----------------------------
DROP TABLE IF EXISTS `trx_rapor`;
CREATE TABLE `trx_rapor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `semester` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori semester',
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_nilai` decimal(6,2) DEFAULT NULL,
  `rata_rata` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rapor_siswa_semester` (`mst_siswa_id`,`semester`,`tahun_ajaran`),
  CONSTRAINT `trx_rapor_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_rapor_detail
-- ----------------------------
DROP TABLE IF EXISTS `trx_rapor_detail`;
CREATE TABLE `trx_rapor_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_rapor_id` bigint unsigned DEFAULT NULL,
  `mst_mapel_id` bigint unsigned DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rd` (`trx_rapor_id`,`mst_mapel_id`),
  KEY `trx_rapor_detail_mst_mapel_id_foreign` (`mst_mapel_id`),
  CONSTRAINT `trx_rapor_detail_mst_mapel_id_foreign` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_rapor_detail_trx_rapor_id_foreign` FOREIGN KEY (`trx_rapor_id`) REFERENCES `trx_rapor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_ujian
-- ----------------------------
DROP TABLE IF EXISTS `trx_ujian`;
CREATE TABLE `trx_ujian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_mapel_id` bigint unsigned DEFAULT NULL,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `jenis` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori jenis_ujian',
  `semester` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori semester',
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trx_ujian_mst_mapel_id_foreign` (`mst_mapel_id`),
  KEY `trx_ujian_mst_kelas_id_foreign` (`mst_kelas_id`),
  CONSTRAINT `trx_ujian_mst_kelas_id_foreign` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_ujian_mst_mapel_id_foreign` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_ujian_jawaban
-- ----------------------------
DROP TABLE IF EXISTS `trx_ujian_jawaban`;
CREATE TABLE `trx_ujian_jawaban` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_ujian_user_id` bigint unsigned NOT NULL,
  `mst_soal_id` bigint unsigned NOT NULL,
  `mst_soal_opsi_id` bigint unsigned DEFAULT NULL COMMENT 'ID opsi yang dipilih jika pilihan ganda',
  `jawaban_teks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Jika soal essay',
  `is_benar` tinyint(1) DEFAULT '0',
  `ragu_ragu` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `trx_ujian_jawaban_trx_ujian_user_id_foreign` (`trx_ujian_user_id`),
  KEY `trx_ujian_jawaban_mst_soal_id_foreign` (`mst_soal_id`),
  CONSTRAINT `trx_ujian_jawaban_mst_soal_id_foreign` FOREIGN KEY (`mst_soal_id`) REFERENCES `mst_soal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trx_ujian_jawaban_trx_ujian_user_id_foreign` FOREIGN KEY (`trx_ujian_user_id`) REFERENCES `trx_ujian_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for trx_ujian_user
-- ----------------------------
DROP TABLE IF EXISTS `trx_ujian_user`;
CREATE TABLE `trx_ujian_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_ujian_id` bigint unsigned NOT NULL,
  `mst_siswa_id` bigint unsigned NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1: Belum mulai, 2: Mengerjakan, 3: Selesai',
  `sisa_waktu` int DEFAULT NULL COMMENT 'Dalam hitungan detik',
  `total_benar` int DEFAULT '0',
  `total_salah` int DEFAULT '0',
  `nilai_akhir` decimal(5,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ujian_siswa` (`trx_ujian_id`,`mst_siswa_id`),
  KEY `trx_ujian_user_mst_siswa_id_foreign` (`mst_siswa_id`),
  CONSTRAINT `trx_ujian_user_mst_siswa_id_foreign` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trx_ujian_user_trx_ujian_id_foreign` FOREIGN KEY (`trx_ujian_id`) REFERENCES `trx_ujian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
