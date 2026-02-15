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

 Date: 15/02/2026 20:19:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `jobs_queue_index` (`queue`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_bk_jenis
-- ----------------------------
DROP TABLE IF EXISTS `mst_bk_jenis`;
CREATE TABLE `mst_bk_jenis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_bk_kategori
-- ----------------------------
DROP TABLE IF EXISTS `mst_bk_kategori`;
CREATE TABLE `mst_bk_kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_buku
-- ----------------------------
DROP TABLE IF EXISTS `mst_buku`;
CREATE TABLE `mst_buku` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `judul` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `penulis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `penerbit` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `stok` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_guru
-- ----------------------------
DROP TABLE IF EXISTS `mst_guru`;
CREATE TABLE `mst_guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jenis_kelamin` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori jenis_kelamin',
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nip` (`nip`) USING BTREE,
  KEY `sys_user_id` (`sys_user_id`) USING BTREE,
  CONSTRAINT `mst_guru_ibfk_1` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_gm` (`mst_guru_id`,`mst_mapel_id`) USING BTREE,
  KEY `mst_mapel_id` (`mst_mapel_id`) USING BTREE,
  CONSTRAINT `mst_guru_mapel_ibfk_1` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_guru_mapel_ibfk_2` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_kelas
-- ----------------------------
DROP TABLE IF EXISTS `mst_kelas`;
CREATE TABLE `mst_kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tingkat` int DEFAULT NULL,
  `tahun_ajaran` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `wali_guru_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_mapel
-- ----------------------------
DROP TABLE IF EXISTS `mst_mapel`;
CREATE TABLE `mst_mapel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_mapel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `kode_mapel` (`kode_mapel`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_siswa
-- ----------------------------
DROP TABLE IF EXISTS `mst_siswa`;
CREATE TABLE `mst_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jenis_kelamin` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori jenis_kelamin',
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_siswa',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nis` (`nis`) USING BTREE,
  KEY `sys_user_id` (`sys_user_id`) USING BTREE,
  KEY `mst_kelas_id` (`mst_kelas_id`) USING BTREE,
  KEY `idx_siswa_kelas_status` (`mst_kelas_id`,`status`),
  KEY `idx_siswa_nama` (`nama`),
  KEY `idx_siswa_jk` (`jenis_kelamin`),
  CONSTRAINT `mst_siswa_ibfk_1` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_siswa_ibfk_2` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_sw` (`mst_siswa_id`,`mst_wali_id`) USING BTREE,
  KEY `mst_wali_id` (`mst_wali_id`) USING BTREE,
  CONSTRAINT `mst_siswa_wali_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_siswa_wali_ibfk_2` FOREIGN KEY (`mst_wali_id`) REFERENCES `mst_wali` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_tarif_spp
-- ----------------------------
DROP TABLE IF EXISTS `mst_tarif_spp`;
CREATE TABLE `mst_tarif_spp` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Contoh: 2023/2024',
  `nominal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `mst_kelas_id` (`mst_kelas_id`) USING BTREE,
  CONSTRAINT `mst_tarif_spp_ibfk_1` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_wali
-- ----------------------------
DROP TABLE IF EXISTS `mst_wali`;
CREATE TABLE `mst_wali` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `sys_user_id` (`sys_user_id`) USING BTREE,
  CONSTRAINT `mst_wali_ibfk_1` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for mst_wali_murid
-- ----------------------------
DROP TABLE IF EXISTS `mst_wali_murid`;
CREATE TABLE `mst_wali_murid` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_mst_wali_user` (`sys_user_id`) USING BTREE,
  CONSTRAINT `fk_mst_wali_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `sessions_user_id_index` (`user_id`) USING BTREE,
  KEY `sessions_last_activity_index` (`last_activity`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_activity_logs`;
CREATE TABLE `sys_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reference_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_log_user` (`sys_user_id`) USING BTREE,
  KEY `idx_logs_user` (`sys_user_id`,`created_at`),
  KEY `idx_logs_module` (`module`,`created_at`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_error_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_error_logs`;
CREATE TABLE `sys_error_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori level_error',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `line` int DEFAULT NULL,
  `trace` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_login_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_login_logs`;
CREATE TABLE `sys_login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint unsigned DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_login',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_login_user` (`sys_user_id`) USING BTREE,
  CONSTRAINT `fk_login_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_permissions`;
CREATE TABLE `sys_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_references
-- ----------------------------
DROP TABLE IF EXISTS `sys_references`;
CREATE TABLE `sys_references` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori` varchar(50) NOT NULL COMMENT 'Contoh: JENIS_KELAMIN, STATUS_SISWA, STATUS_BAYAR',
  `kode` varchar(20) NOT NULL COMMENT 'Contoh: L, P, aktif, lunas',
  `nama` varchar(100) NOT NULL COMMENT 'Label yang muncul di UI',
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_role_permission` (`sys_role_id`,`sys_permission_id`) USING BTREE,
  KEY `fk_rp_permission` (`sys_permission_id`) USING BTREE,
  CONSTRAINT `fk_rp_permission` FOREIGN KEY (`sys_permission_id`) REFERENCES `sys_permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`sys_role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE `sys_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_user_role` (`sys_user_id`,`sys_role_id`) USING BTREE,
  KEY `fk_ur_role` (`sys_role_id`) USING BTREE,
  CONSTRAINT `fk_ur_role` FOREIGN KEY (`sys_role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_ur_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sys_users
-- ----------------------------
DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE `sys_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` tinyint NOT NULL COMMENT 'Referensi ke sys_roles id',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_absensi_guru
-- ----------------------------
DROP TABLE IF EXISTS `trx_absensi_guru`;
CREATE TABLE `trx_absensi_guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_guru_id` bigint unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_absensi',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_ag` (`mst_guru_id`,`tanggal`) USING BTREE,
  CONSTRAINT `trx_absensi_guru_ibfk_1` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_absensi_siswa
-- ----------------------------
DROP TABLE IF EXISTS `trx_absensi_siswa`;
CREATE TABLE `trx_absensi_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_absensi',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_as` (`mst_siswa_id`,`tanggal`) USING BTREE,
  KEY `idx_absensi_siswa_tanggal` (`mst_siswa_id`,`tanggal`),
  KEY `idx_absensi_siswa_status` (`status`),
  CONSTRAINT `trx_absensi_siswa_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_bk_hasil
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_hasil`;
CREATE TABLE `trx_bk_hasil` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned DEFAULT NULL,
  `hasil` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `rekomendasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `trx_bk_kasus_id` (`trx_bk_kasus_id`) USING BTREE,
  CONSTRAINT `trx_bk_hasil_ibfk_1` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  `judul_kasus` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deskripsi_masalah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_bk',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `mst_siswa_id` (`mst_siswa_id`) USING BTREE,
  KEY `mst_guru_id` (`mst_guru_id`) USING BTREE,
  KEY `mst_bk_kategori_id` (`mst_bk_kategori_id`) USING BTREE,
  KEY `mst_bk_jenis_id` (`mst_bk_jenis_id`) USING BTREE,
  KEY `idx_bk_kasus_siswa` (`mst_siswa_id`,`status`),
  KEY `idx_bk_kasus_guru` (`mst_guru_id`),
  CONSTRAINT `trx_bk_kasus_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_ibfk_2` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_ibfk_3` FOREIGN KEY (`mst_bk_kategori_id`) REFERENCES `mst_bk_kategori` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_ibfk_4` FOREIGN KEY (`mst_bk_jenis_id`) REFERENCES `mst_bk_jenis` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_bk_lampiran
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_lampiran`;
CREATE TABLE `trx_bk_lampiran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_bk_lampiran_kasus` (`trx_bk_kasus_id`) USING BTREE,
  CONSTRAINT `fk_bk_lampiran_kasus` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_bk_sesi
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_sesi`;
CREATE TABLE `trx_bk_sesi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `metode` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori metode_bk',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `trx_bk_kasus_id` (`trx_bk_kasus_id`) USING BTREE,
  CONSTRAINT `trx_bk_sesi_ibfk_1` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_bk_tindakan
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_tindakan`;
CREATE TABLE `trx_bk_tindakan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint unsigned DEFAULT NULL,
  `deskripsi_tindakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `trx_bk_kasus_id` (`trx_bk_kasus_id`) USING BTREE,
  CONSTRAINT `trx_bk_tindakan_ibfk_1` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_bk_wali_kasus` (`trx_bk_kasus_id`) USING BTREE,
  KEY `fk_bk_wali_mst_wali` (`mst_wali_murid_id`) USING BTREE,
  CONSTRAINT `fk_bk_wali_kasus` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_bk_wali_mst_wali` FOREIGN KEY (`mst_wali_murid_id`) REFERENCES `mst_wali_murid` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_nilai` (`trx_ujian_id`,`mst_siswa_id`) USING BTREE,
  KEY `mst_siswa_id` (`mst_siswa_id`) USING BTREE,
  KEY `idx_nilai_ujian_siswa` (`trx_ujian_id`,`mst_siswa_id`),
  KEY `idx_nilai_siswa` (`mst_siswa_id`),
  CONSTRAINT `trx_nilai_ibfk_1` FOREIGN KEY (`trx_ujian_id`) REFERENCES `trx_ujian` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_nilai_ibfk_2` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_pembayaran_spp
-- ----------------------------
DROP TABLE IF EXISTS `trx_pembayaran_spp`;
CREATE TABLE `trx_pembayaran_spp` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned NOT NULL,
  `mst_tarif_spp_id` bigint unsigned NOT NULL,
  `bulan` tinyint unsigned NOT NULL COMMENT '1=Januari, 12=Desember',
  `tahun` year NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `status` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori status_bayar',
  `metode_pembayaran` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori metode_pembayaran',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `petugas_id` bigint unsigned DEFAULT NULL COMMENT 'User yang mencatat pembayaran',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_spp_bayar` (`mst_siswa_id`,`bulan`,`tahun`) USING BTREE,
  KEY `mst_tarif_spp_id` (`mst_tarif_spp_id`) USING BTREE,
  KEY `petugas_id` (`petugas_id`) USING BTREE,
  CONSTRAINT `trx_pembayaran_spp_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_pembayaran_spp_ibfk_2` FOREIGN KEY (`mst_tarif_spp_id`) REFERENCES `mst_tarif_spp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_pembayaran_spp_ibfk_3` FOREIGN KEY (`petugas_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_trx_pinjam_buku` (`mst_buku_id`) USING BTREE,
  KEY `fk_trx_pinjam_siswa` (`mst_siswa_id`) USING BTREE,
  KEY `idx_pinjam_siswa_status` (`mst_siswa_id`,`status`),
  KEY `idx_pinjam_buku_status` (`mst_buku_id`,`status`),
  CONSTRAINT `fk_trx_pinjam_buku` FOREIGN KEY (`mst_buku_id`) REFERENCES `mst_buku` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_trx_pinjam_siswa` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  KEY `trx_rapor_id` (`trx_rapor_id`) USING BTREE,
  KEY `mst_kelas_id` (`mst_kelas_id`) USING BTREE,
  CONSTRAINT `trx_ranking_ibfk_1` FOREIGN KEY (`trx_rapor_id`) REFERENCES `trx_rapor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_ranking_ibfk_2` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_rapor
-- ----------------------------
DROP TABLE IF EXISTS `trx_rapor`;
CREATE TABLE `trx_rapor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint unsigned DEFAULT NULL,
  `semester` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori_semester',
  `tahun_ajaran` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `total_nilai` decimal(6,2) DEFAULT NULL,
  `rata_rata` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `mst_siswa_id` (`mst_siswa_id`) USING BTREE,
  KEY `idx_rapor_siswa_semester` (`mst_siswa_id`,`semester`,`tahun_ajaran`),
  CONSTRAINT `trx_rapor_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_rd` (`trx_rapor_id`,`mst_mapel_id`) USING BTREE,
  KEY `mst_mapel_id` (`mst_mapel_id`) USING BTREE,
  CONSTRAINT `trx_rapor_detail_ibfk_1` FOREIGN KEY (`trx_rapor_id`) REFERENCES `trx_rapor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_rapor_detail_ibfk_2` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trx_ujian
-- ----------------------------
DROP TABLE IF EXISTS `trx_ujian`;
CREATE TABLE `trx_ujian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mst_mapel_id` bigint unsigned DEFAULT NULL,
  `mst_kelas_id` bigint unsigned DEFAULT NULL,
  `jenis` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori jenis_ujian',
  `semester` tinyint unsigned NOT NULL COMMENT 'Referensi ke sys_references dengan kategori_semester',
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `mst_mapel_id` (`mst_mapel_id`) USING BTREE,
  KEY `mst_kelas_id` (`mst_kelas_id`) USING BTREE,
  CONSTRAINT `trx_ujian_ibfk_1` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_ujian_ibfk_2` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
