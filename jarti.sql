-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for jarti
CREATE DATABASE IF NOT EXISTS `jarti` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `jarti`;

-- Dumping structure for table jarti.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.cache: ~2 rows (approximately)
DELETE FROM `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1754965120),
	('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1754965120;', 1754965120);

-- Dumping structure for table jarti.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table jarti.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Dumping data for table jarti.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table jarti.gardus
CREATE TABLE IF NOT EXISTS `gardus` (
  `gardus_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gardu_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_feeder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_motorized` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_jarkom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_proritas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_fo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_pop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_terdekat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_kabel_fa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_kabel_fig` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_petik_core` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_rab` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardu_perizinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gardus_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`gardus_id`),
  KEY `gardus_user_id_foreign` (`user_id`),
  CONSTRAINT `gardus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.gardus: ~0 rows (approximately)
DELETE FROM `gardus`;

-- Dumping structure for table jarti.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
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

-- Dumping data for table jarti.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table jarti.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table jarti.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.migrations: ~7 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000001_create_cache_table', 1),
	(2, '0001_01_01_000002_create_jobs_table', 1),
	(3, '2025_08_11_053022_create_roles_table', 1),
	(4, '2025_08_11_053023_create_users_table', 1),
	(5, '2025_08_11_064309_create_vendors_table', 2),
	(6, '2025_08_11_064802_create_gardus_table', 3),
	(7, '2025_08_11_074321_create_pops_table', 4);

-- Dumping structure for table jarti.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table jarti.pops
CREATE TABLE IF NOT EXISTS `pops` (
  `pop_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pop_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pop_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.pops: ~23 rows (approximately)
DELETE FROM `pops`;
INSERT INTO `pops` (`pop_id`, `pop_name`, `pop_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'BALOI', 1, '2025-08-11 19:41:55', '2025-08-11 19:41:55', NULL),
	(2, 'SBU', 1, '2025-08-11 19:42:03', '2025-08-11 19:42:03', NULL),
	(3, 'BATU BESAR', 1, '2025-08-11 19:42:52', '2025-08-11 20:05:31', NULL),
	(4, 'RAJAWALI', 1, '2025-08-11 19:43:02', '2025-08-11 19:43:46', NULL),
	(5, 'OMA BTS', 1, '2025-08-11 19:43:07', '2025-08-11 19:45:48', NULL),
	(6, 'NONGSA', 1, '2025-08-11 19:43:13', '2025-08-11 19:45:53', NULL),
	(7, 'KABIL', 1, '2025-08-11 19:43:19', '2025-08-11 19:45:57', NULL),
	(8, 'TANJUNG KASAM', 1, '2025-08-11 19:43:26', '2025-08-11 19:46:01', NULL),
	(9, 'MUKA KUNING', 1, '2025-08-11 19:43:38', '2025-08-11 19:46:06', NULL),
	(10, 'PANARAN', 1, '2025-08-11 19:47:54', '2025-08-11 19:48:07', NULL),
	(11, 'KAV SERAYA', 1, '2025-08-11 19:48:48', '2025-08-11 19:48:48', NULL),
	(12, 'BATU AJI', 1, '2025-08-11 19:48:55', '2025-08-11 19:48:55', NULL),
	(13, 'TANJUNG UNCANG', 1, '2025-08-11 19:49:04', '2025-08-11 19:49:04', NULL),
	(14, 'SAGUNGLUNG', 1, '2025-08-11 19:49:13', '2025-08-11 19:49:13', NULL),
	(15, 'SEI HARAPAN', 1, '2025-08-11 19:49:21', '2025-08-11 19:49:21', NULL),
	(16, 'TIBAN', 1, '2025-08-11 19:49:31', '2025-08-11 19:49:31', NULL),
	(17, 'NAGOYA', 1, '2025-08-11 19:49:35', '2025-08-11 19:49:35', NULL),
	(18, 'WIRAMUSTIKA', 1, '2025-08-11 20:03:50', '2025-08-11 20:03:50', NULL),
	(19, 'MARINA PARK', 1, '2025-08-11 20:03:58', '2025-08-11 20:03:58', NULL),
	(20, 'BATU AMPAR', 1, '2025-08-11 20:04:06', '2025-08-11 20:04:06', NULL),
	(21, 'BENGKONG JAYA', 1, '2025-08-11 20:04:15', '2025-08-11 20:04:15', NULL),
	(22, 'SENGKUANG', 1, '2025-08-11 20:04:26', '2025-08-11 20:04:26', NULL),
	(23, 'BATAM CENTER', 1, '2025-08-11 20:04:51', '2025-08-11 20:04:51', NULL);

-- Dumping structure for table jarti.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.roles: ~6 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`role_id`, `role_name`, `role_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Admin', 1, NULL, NULL, NULL),
	(2, 'Aktivasi', 1, '2025-08-10 22:52:54', '2025-08-10 22:52:54', NULL),
	(3, 'Staff IT Network', 1, '2025-08-10 23:35:46', '2025-08-10 23:41:00', NULL),
	(4, 'Asisten Manajer Network', 1, '2025-08-10 23:40:15', '2025-08-10 23:40:49', NULL),
	(5, 'Infra Manager', 1, '2025-08-10 23:41:10', '2025-08-10 23:41:10', NULL),
	(6, 'Senior Manager', 1, '2025-08-10 23:41:18', '2025-08-11 18:28:53', NULL);

-- Dumping structure for table jarti.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.sessions: ~10 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('bffZSSLumD69oES9BnHgzxLlQn1gamP4Pp8pgm0x', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3h0Tmw3SzY5WU1ZQjZqQ0xLcWtkTTFmQ0FKNVk4R2QxOGMyeXRDZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9qYXJ0aS50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754963294),
	('CI0rUmVUeFXpjUrMn80X3rCI4BOypxSxMvlnBHVs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHY3T0NRTnhQU29mOG9aQzlhNlZHNlNpbm9FT1dCeGlGSm02emRLdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9qYXJ0aS50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754963131),
	('EiU05NXDqTlytHOjGMEs1Ek1AQDm5JQ6Y7CxLVe2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibW1vWTJuT3hGVHBCaDZlVGRQTVd1NGp6WEZ4ZVZrWTlLcURJQlZ3bSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9qYXJ0aS50ZXN0L2FkbWluL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754963295),
	('Ho3qeLBVHzFGv4yNMiGhod4RpXFqlNcPCYq1eLvH', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiM05DVjduaUQ3Q2ZGZFUzVGhCdU1Ca3Y3QXp0TmlObkpZQ2FaQ3U3UyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9qYXJ0aS50ZXN0L2FkbWluL3BvcHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkV2lnVHpqWnNKVHJlN3d6YXF5QUoxZTk5ZlRIRzlWRkhabkl0U0FuZGxWdHdGVEpxZ2dJR2UiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1754967963),
	('iCfbyp68ddh99H4F93IIWiMJf49f9h7c3Appaz0T', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicDFZUDBoNmtFNWFzYUs0VTFTQUg3cTlLc09DaHNCRjF2UjRjWHJzaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9qYXJ0aS50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754963225),
	('IcNtDfxrzqyluahOLCEVmWXpBaTEgnIOAOm0O8Sl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiajhpMGpMc3FyR2RFTXBnT0FlOUZ1ZmhCTVRTWkJUaGg0cE5SY21qVSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL2phcnRpLnRlc3QvYWRtaW4iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMzoiaHR0cDovL2phcnRpLnRlc3QvYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754963295),
	('lzUZKOYJQyhPgx2aIbLm4XWoeia1SM52xHjC54Td', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGpUUWJMeWtCYWdDVWhHYzROQk5razg2Zkx5aGRxM1F2ZGxNMVJJTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9qYXJ0aS50ZXN0L2FkbWluL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754963225),
	('R6BSyEORp8TK6dycxwJfA2Fg3GAexpzbBA0y2oy3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSjJXN0tLN1V3MGJDNlpWOW4yNHlIdTY4MmV2N2FtUXBqOTE1UWZmWCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL2phcnRpLnRlc3QvYWRtaW4iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMzoiaHR0cDovL2phcnRpLnRlc3QvYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754963225),
	('Shd4wFE5H986pcaqKqtLlIzfrShjjUCa2nM81uSu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib0JFdk5LaTlsUnU2cnhtT3JJMWFxMkVJb0l6eUpjdDVPc0RPbjZ2MSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL2phcnRpLnRlc3QvYWRtaW4iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMzoiaHR0cDovL2phcnRpLnRlc3QvYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754963131),
	('veyo8S3C5n1ZFOhC8tcnMaEAQTYA3f1pkyh3lNC8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.21.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRlhnS3huWlRFVnFwUTlFRGxWSERVdGY0Z0ZXMjJ1SGN3eGlEaXloUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9qYXJ0aS50ZXN0L2FkbWluL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754963132);

-- Dumping structure for table jarti.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `role_id` bigint unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.users: ~6 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`user_id`, `name`, `email`, `email_verified_at`, `password`, `status`, `role_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$WigTzjZsJTre7wzaqyAJ1e99fTHG9VFHZnItSAndlVtwFTJqggIGe', 1, 1, 'pGeZla08mERrdq9UTI82vz6vWzPGviiyzoQxzm3VXuDuql9jUfnV0aG7voSi', NULL, NULL, NULL),
	(2, 'Aktivasi', 'aktivasi@mail.com', NULL, '$2y$12$NF27i/hSWs7uaHIXU9IMn.qpB9rzyzjSpJkp1gKgMZCVn64laLo1q', 1, 2, NULL, '2025-08-10 23:41:52', '2025-08-10 23:41:52', NULL),
	(3, 'Staff IT Network', 'sitnetwork@mail.com', NULL, '$2y$12$pgSddfRn7H32cLeG0ryn8e.KIjUdDsdulaZbo5G9cAB11X0pp2cd.', 1, 3, NULL, '2025-08-11 00:08:48', '2025-08-11 00:08:48', NULL),
	(4, 'Asisten Manajer Network', 'asmanetwork@mail.com', NULL, '$2y$12$SRNef4nRj45G/j7H5IIWYO7V.njGrUjXzVe4Q43Bak8agkL9Js65O', 0, 4, NULL, '2025-08-11 00:09:53', '2025-08-11 00:09:53', NULL),
	(5, 'Infra Manager', 'inframanager@mail.com', NULL, '$2y$12$8LgBaUVs5eWtJw2f7DQNKufH4odV.fkxOG/hpgV6m1gosFMI/.Fku', 0, 5, NULL, '2025-08-11 00:10:28', '2025-08-11 18:37:11', NULL),
	(6, 'Senior Manager', 'Senior.Manager@mail.com', NULL, '$2y$12$1NZqKHPkxnHCFb7FlcXzve7CX.tca76Sela9IsUSyGGeYEkY1JtX6', 1, 6, NULL, '2025-08-11 00:12:13', '2025-08-11 18:42:09', NULL);

-- Dumping structure for table jarti.vendors
CREATE TABLE IF NOT EXISTS `vendors` (
  `vendor_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.vendors: ~1 rows (approximately)
DELETE FROM `vendors`;
INSERT INTO `vendors` (`vendor_id`, `vendor_year`, `vendor_name`, `vendor_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '2024', 'YAAAA', 1, '2025-08-11 19:44:53', '2025-08-11 19:45:01', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
