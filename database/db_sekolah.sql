/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 90300 (9.3.0)
 Source Host           : localhost:3306
 Source Schema         : db_sekolah

 Target Server Type    : MySQL
 Target Server Version : 90300 (9.3.0)
 File Encoding         : 65001

 Date: 09/02/2026 23:47:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_bk_jenis
-- ----------------------------
DROP TABLE IF EXISTS `mst_bk_jenis`;
CREATE TABLE `mst_bk_jenis`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_bk_kategori
-- ----------------------------
DROP TABLE IF EXISTS `mst_bk_kategori`;
CREATE TABLE `mst_bk_kategori`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_buku
-- ----------------------------
DROP TABLE IF EXISTS `mst_buku`;
CREATE TABLE `mst_buku`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `judul` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `penulis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `penerbit` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tahun` int NULL DEFAULT NULL,
  `stok` int NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_guru
-- ----------------------------
DROP TABLE IF EXISTS `mst_guru`;
CREATE TABLE `mst_guru`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NOT NULL,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_lahir` date NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nip`(`nip` ASC) USING BTREE,
  INDEX `sys_user_id`(`sys_user_id` ASC) USING BTREE,
  CONSTRAINT `mst_guru_ibfk_1` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_guru_mapel
-- ----------------------------
DROP TABLE IF EXISTS `mst_guru_mapel`;
CREATE TABLE `mst_guru_mapel`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_guru_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_mapel_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_gm`(`mst_guru_id` ASC, `mst_mapel_id` ASC) USING BTREE,
  INDEX `mst_mapel_id`(`mst_mapel_id` ASC) USING BTREE,
  CONSTRAINT `mst_guru_mapel_ibfk_1` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_guru_mapel_ibfk_2` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_kelas
-- ----------------------------
DROP TABLE IF EXISTS `mst_kelas`;
CREATE TABLE `mst_kelas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tingkat` int NULL DEFAULT NULL,
  `tahun_ajaran` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `wali_guru_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_mapel
-- ----------------------------
DROP TABLE IF EXISTS `mst_mapel`;
CREATE TABLE `mst_mapel`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama_mapel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_mapel`(`kode_mapel` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_siswa
-- ----------------------------
DROP TABLE IF EXISTS `mst_siswa`;
CREATE TABLE `mst_siswa`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NOT NULL,
  `nis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_lahir` date NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `mst_kelas_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('aktif','lulus','pindah') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'aktif',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nis`(`nis` ASC) USING BTREE,
  INDEX `sys_user_id`(`sys_user_id` ASC) USING BTREE,
  INDEX `mst_kelas_id`(`mst_kelas_id` ASC) USING BTREE,
  CONSTRAINT `mst_siswa_ibfk_1` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_siswa_ibfk_2` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_siswa_wali
-- ----------------------------
DROP TABLE IF EXISTS `mst_siswa_wali`;
CREATE TABLE `mst_siswa_wali`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_wali_id` bigint UNSIGNED NULL DEFAULT NULL,
  `hubungan` enum('ayah','ibu','wali') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_sw`(`mst_siswa_id` ASC, `mst_wali_id` ASC) USING BTREE,
  INDEX `mst_wali_id`(`mst_wali_id` ASC) USING BTREE,
  CONSTRAINT `mst_siswa_wali_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `mst_siswa_wali_ibfk_2` FOREIGN KEY (`mst_wali_id`) REFERENCES `mst_wali` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_wali
-- ----------------------------
DROP TABLE IF EXISTS `mst_wali`;
CREATE TABLE `mst_wali`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sys_user_id`(`sys_user_id` ASC) USING BTREE,
  CONSTRAINT `mst_wali_ibfk_1` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mst_wali_murid
-- ----------------------------
DROP TABLE IF EXISTS `mst_wali_murid`;
CREATE TABLE `mst_wali_murid`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_mst_wali_user`(`sys_user_id` ASC) USING BTREE,
  CONSTRAINT `fk_mst_wali_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_activity_logs`;
CREATE TABLE `sys_activity_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reference_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_log_user`(`sys_user_id` ASC) USING BTREE,
  CONSTRAINT `fk_log_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_error_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_error_logs`;
CREATE TABLE `sys_error_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `level` enum('info','warning','error','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `line` int NULL DEFAULT NULL,
  `trace` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_login_logs
-- ----------------------------
DROP TABLE IF EXISTS `sys_login_logs`;
CREATE TABLE `sys_login_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` enum('success','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_login_user`(`sys_user_id` ASC) USING BTREE,
  CONSTRAINT `fk_login_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_permissions`;
CREATE TABLE `sys_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_permissions`;
CREATE TABLE `sys_role_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_role_id` bigint UNSIGNED NOT NULL,
  `sys_permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_role_permission`(`sys_role_id` ASC, `sys_permission_id` ASC) USING BTREE,
  INDEX `fk_rp_permission`(`sys_permission_id` ASC) USING BTREE,
  CONSTRAINT `fk_rp_permission` FOREIGN KEY (`sys_permission_id`) REFERENCES `sys_permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`sys_role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_roles`;
CREATE TABLE `sys_roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_user_roles
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_roles`;
CREATE TABLE `sys_user_roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sys_user_id` bigint UNSIGNED NOT NULL,
  `sys_role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_user_role`(`sys_user_id` ASC, `sys_role_id` ASC) USING BTREE,
  INDEX `fk_ur_role`(`sys_role_id` ASC) USING BTREE,
  CONSTRAINT `fk_ur_role` FOREIGN KEY (`sys_role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_ur_user` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_users
-- ----------------------------
DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE `sys_users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('admin','guru','siswa','wali') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_absensi_guru
-- ----------------------------
DROP TABLE IF EXISTS `trx_absensi_guru`;
CREATE TABLE `trx_absensi_guru`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_guru_id` bigint UNSIGNED NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpha') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_ag`(`mst_guru_id` ASC, `tanggal` ASC) USING BTREE,
  CONSTRAINT `trx_absensi_guru_ibfk_1` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_absensi_siswa
-- ----------------------------
DROP TABLE IF EXISTS `trx_absensi_siswa`;
CREATE TABLE `trx_absensi_siswa`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpha') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_as`(`mst_siswa_id` ASC, `tanggal` ASC) USING BTREE,
  CONSTRAINT `trx_absensi_siswa_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_bk_hasil
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_hasil`;
CREATE TABLE `trx_bk_hasil`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint UNSIGNED NULL DEFAULT NULL,
  `hasil` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `rekomendasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `trx_bk_kasus_id`(`trx_bk_kasus_id` ASC) USING BTREE,
  CONSTRAINT `trx_bk_hasil_ibfk_1` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_bk_kasus
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_kasus`;
CREATE TABLE `trx_bk_kasus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_guru_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_bk_kategori_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_bk_jenis_id` bigint UNSIGNED NULL DEFAULT NULL,
  `judul_kasus` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `deskripsi_masalah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `status` enum('dibuka','proses','selesai','dirujuk') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_mulai` date NULL DEFAULT NULL,
  `tanggal_selesai` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mst_siswa_id`(`mst_siswa_id` ASC) USING BTREE,
  INDEX `mst_guru_id`(`mst_guru_id` ASC) USING BTREE,
  INDEX `mst_bk_kategori_id`(`mst_bk_kategori_id` ASC) USING BTREE,
  INDEX `mst_bk_jenis_id`(`mst_bk_jenis_id` ASC) USING BTREE,
  CONSTRAINT `trx_bk_kasus_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_ibfk_2` FOREIGN KEY (`mst_guru_id`) REFERENCES `mst_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_ibfk_3` FOREIGN KEY (`mst_bk_kategori_id`) REFERENCES `mst_bk_kategori` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_bk_kasus_ibfk_4` FOREIGN KEY (`mst_bk_jenis_id`) REFERENCES `mst_bk_jenis` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_bk_lampiran
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_lampiran`;
CREATE TABLE `trx_bk_lampiran`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_bk_lampiran_kasus`(`trx_bk_kasus_id` ASC) USING BTREE,
  CONSTRAINT `fk_bk_lampiran_kasus` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_bk_sesi
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_sesi`;
CREATE TABLE `trx_bk_sesi`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint UNSIGNED NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `metode` enum('tatap_muka','online','telepon') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `trx_bk_kasus_id`(`trx_bk_kasus_id` ASC) USING BTREE,
  CONSTRAINT `trx_bk_sesi_ibfk_1` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_bk_tindakan
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_tindakan`;
CREATE TABLE `trx_bk_tindakan`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint UNSIGNED NULL DEFAULT NULL,
  `deskripsi_tindakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `trx_bk_kasus_id`(`trx_bk_kasus_id` ASC) USING BTREE,
  CONSTRAINT `trx_bk_tindakan_ibfk_1` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_bk_wali
-- ----------------------------
DROP TABLE IF EXISTS `trx_bk_wali`;
CREATE TABLE `trx_bk_wali`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_bk_kasus_id` bigint UNSIGNED NOT NULL,
  `mst_wali_murid_id` bigint UNSIGNED NOT NULL,
  `peran` enum('dipanggil','pendamping','informasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_bk_wali_kasus`(`trx_bk_kasus_id` ASC) USING BTREE,
  INDEX `fk_bk_wali_mst_wali`(`mst_wali_murid_id` ASC) USING BTREE,
  CONSTRAINT `fk_bk_wali_kasus` FOREIGN KEY (`trx_bk_kasus_id`) REFERENCES `trx_bk_kasus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_bk_wali_mst_wali` FOREIGN KEY (`mst_wali_murid_id`) REFERENCES `mst_wali_murid` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_nilai
-- ----------------------------
DROP TABLE IF EXISTS `trx_nilai`;
CREATE TABLE `trx_nilai`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_ujian_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_siswa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `nilai` decimal(5, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_nilai`(`trx_ujian_id` ASC, `mst_siswa_id` ASC) USING BTREE,
  INDEX `mst_siswa_id`(`mst_siswa_id` ASC) USING BTREE,
  CONSTRAINT `trx_nilai_ibfk_1` FOREIGN KEY (`trx_ujian_id`) REFERENCES `trx_ujian` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_nilai_ibfk_2` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_peminjaman_buku
-- ----------------------------
DROP TABLE IF EXISTS `trx_peminjaman_buku`;
CREATE TABLE `trx_peminjaman_buku`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_buku_id` bigint UNSIGNED NOT NULL,
  `mst_siswa_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NULL DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan','hilang') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_trx_pinjam_buku`(`mst_buku_id` ASC) USING BTREE,
  INDEX `fk_trx_pinjam_siswa`(`mst_siswa_id` ASC) USING BTREE,
  CONSTRAINT `fk_trx_pinjam_buku` FOREIGN KEY (`mst_buku_id`) REFERENCES `mst_buku` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_trx_pinjam_siswa` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_ranking
-- ----------------------------
DROP TABLE IF EXISTS `trx_ranking`;
CREATE TABLE `trx_ranking`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_rapor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_kelas_id` bigint UNSIGNED NULL DEFAULT NULL,
  `peringkat` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `trx_rapor_id`(`trx_rapor_id` ASC) USING BTREE,
  INDEX `mst_kelas_id`(`mst_kelas_id` ASC) USING BTREE,
  CONSTRAINT `trx_ranking_ibfk_1` FOREIGN KEY (`trx_rapor_id`) REFERENCES `trx_rapor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_ranking_ibfk_2` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_rapor
-- ----------------------------
DROP TABLE IF EXISTS `trx_rapor`;
CREATE TABLE `trx_rapor`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_siswa_id` bigint UNSIGNED NULL DEFAULT NULL,
  `semester` enum('ganjil','genap') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tahun_ajaran` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `total_nilai` decimal(6, 2) NULL DEFAULT NULL,
  `rata_rata` decimal(5, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mst_siswa_id`(`mst_siswa_id` ASC) USING BTREE,
  CONSTRAINT `trx_rapor_ibfk_1` FOREIGN KEY (`mst_siswa_id`) REFERENCES `mst_siswa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_rapor_detail
-- ----------------------------
DROP TABLE IF EXISTS `trx_rapor_detail`;
CREATE TABLE `trx_rapor_detail`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trx_rapor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_mapel_id` bigint UNSIGNED NULL DEFAULT NULL,
  `nilai_akhir` decimal(5, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_rd`(`trx_rapor_id` ASC, `mst_mapel_id` ASC) USING BTREE,
  INDEX `mst_mapel_id`(`mst_mapel_id` ASC) USING BTREE,
  CONSTRAINT `trx_rapor_detail_ibfk_1` FOREIGN KEY (`trx_rapor_id`) REFERENCES `trx_rapor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_rapor_detail_ibfk_2` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trx_ujian
-- ----------------------------
DROP TABLE IF EXISTS `trx_ujian`;
CREATE TABLE `trx_ujian`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `mst_mapel_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mst_kelas_id` bigint UNSIGNED NULL DEFAULT NULL,
  `jenis` enum('harian','uts','uas') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `semester` enum('ganjil','genap') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mst_mapel_id`(`mst_mapel_id` ASC) USING BTREE,
  INDEX `mst_kelas_id`(`mst_kelas_id` ASC) USING BTREE,
  CONSTRAINT `trx_ujian_ibfk_1` FOREIGN KEY (`mst_mapel_id`) REFERENCES `mst_mapel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx_ujian_ibfk_2` FOREIGN KEY (`mst_kelas_id`) REFERENCES `mst_kelas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
