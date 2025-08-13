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

-- Dumping structure for table jarti.budgets
CREATE TABLE IF NOT EXISTS `budgets` (
  `budget_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `budget_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget_wbs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget_nilai` decimal(10,2) NOT NULL,
  `budget_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`budget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.budgets: ~0 rows (approximately)
DELETE FROM `budgets`;

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
	('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1755057138),
	('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1755057138;', 1755057138);

-- Dumping structure for table jarti.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table jarti.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `documents_user_id_foreign` (`user_id`),
  CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.documents: ~0 rows (approximately)
DELETE FROM `documents`;

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.migrations: ~8 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000001_create_cache_table', 1),
	(2, '0001_01_01_000002_create_jobs_table', 1),
	(3, '2025_08_11_053022_create_roles_table', 1),
	(4, '2025_08_11_053023_create_users_table', 1),
	(5, '2025_08_11_064309_create_vendors_table', 2),
	(12, '2025_08_11_074321_create_pops_table', 3),
	(13, '2025_08_12_072449_create_documents_table', 3),
	(14, '2025_08_12_072558_create_budgets_table', 3),
	(15, '2025_08_13_013305_create_permissions_table', 3),
	(16, '2025_08_13_013824_create_role_permissions_table', 3),
	(17, '2025_08_13_021020_create_substations_table', 3);

-- Dumping structure for table jarti.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table jarti.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.permissions: ~7 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`permission_id`, `permission_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'documents', NULL, NULL, NULL),
	(2, 'users', NULL, NULL, NULL),
	(3, 'vendors', NULL, NULL, NULL),
	(4, 'pops', NULL, NULL, NULL),
	(5, 'budgets', NULL, NULL, NULL),
	(6, 'substations', NULL, NULL, NULL),
	(7, 'roles', NULL, NULL, NULL);

-- Dumping structure for table jarti.pops
CREATE TABLE IF NOT EXISTS `pops` (
  `pop_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pop_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pop_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.pops: ~0 rows (approximately)
DELETE FROM `pops`;
INSERT INTO `pops` (`pop_id`, `pop_name`, `pop_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'MUKA KUNING', 1, '2025-08-12 20:46:40', '2025-08-12 20:46:40', NULL);

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

-- Dumping structure for table jarti.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.role_permissions: ~9 rows (approximately)
DELETE FROM `role_permissions`;
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
	(1, 6, 1, NULL, NULL),
	(2, 1, 1, NULL, NULL),
	(3, 1, 2, NULL, NULL),
	(4, 1, 3, NULL, NULL),
	(5, 1, 4, NULL, NULL),
	(6, 1, 5, NULL, NULL),
	(7, 1, 6, NULL, NULL),
	(8, 1, 7, NULL, NULL),
	(9, 2, 6, NULL, NULL);

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

-- Dumping data for table jarti.sessions: ~14 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('ZGMBAY6o7Ri3oNj3oz9KKb5FGVS3azw05KVmrmi1', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiejlPMGFwZE9FMEJMc05DY2pxV0xraGlRZ3FUTXZrNVJDTTlxVXJWZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9qYXJ0aS50ZXN0L2FkbWluL3N1YnN0YXRpb25zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRUdHpWZGkyc2xObXZYalloc3RYajIuQkN3aDV2MUQ1RDV1WEJ5bFlHUzdWMnZRR0V4L05iUyI7fQ==', 1755057080);

-- Dumping structure for table jarti.substations
CREATE TABLE IF NOT EXISTS `substations` (
  `substation_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `substation_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_feeder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_motorized` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_jarkom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_fo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_terdekat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_cable_fa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_cable_fig` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_petik_core` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_work` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_rab` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_licensing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `substation_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `pop_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`substation_id`),
  KEY `substations_user_id_foreign` (`user_id`),
  KEY `substations_pop_id_foreign` (`pop_id`),
  CONSTRAINT `substations_pop_id_foreign` FOREIGN KEY (`pop_id`) REFERENCES `pops` (`pop_id`) ON DELETE CASCADE,
  CONSTRAINT `substations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.substations: ~0 rows (approximately)
DELETE FROM `substations`;
INSERT INTO `substations` (`substation_id`, `substation_name`, `substation_feeder`, `substation_motorized`, `substation_jarkom`, `substation_priority`, `substation_fo`, `substation_terdekat`, `substation_cable_fa`, `substation_cable_fig`, `substation_petik_core`, `substation_work`, `substation_rab`, `substation_licensing`, `substation_status`, `created_at`, `updated_at`, `deleted_at`, `user_id`, `pop_id`) VALUES
	(1, 'a', 'a', 'a', NULL, '1', 'Survey', '-', '0', '0', 'a', 'a', 'a', 'a', 0, '2025-08-12 20:50:36', '2025-08-12 20:50:59', NULL, 7, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table jarti.users: ~6 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`user_id`, `name`, `email`, `email_verified_at`, `password`, `status`, `role_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$WigTzjZsJTre7wzaqyAJ1e99fTHG9VFHZnItSAndlVtwFTJqggIGe', 1, 1, 'YCOIVzb4Hmm0vXOku6X7wHeYMcbaXqshZLgQyyvzHiK2AscfIsfxHIMBINon', NULL, '2025-08-12 20:35:45', NULL),
	(2, 'Aktivasi', 'aktivasi@mail.com', NULL, '$2y$12$b9fjsDXCxadYQKxquMeaWu9v9ttP33YJgGMEO1Z2AGBsy8CCYeS5y', 1, 2, NULL, '2025-08-10 23:41:52', '2025-08-12 20:03:53', NULL),
	(3, 'Staff IT Network', 'sitnetwork@mail.com', NULL, '$2y$12$pgSddfRn7H32cLeG0ryn8e.KIjUdDsdulaZbo5G9cAB11X0pp2cd.', 1, 3, NULL, '2025-08-11 00:08:48', '2025-08-11 00:08:48', NULL),
	(4, 'Asisten Manajer Network', 'asmanetwork@mail.com', NULL, '$2y$12$SRNef4nRj45G/j7H5IIWYO7V.njGrUjXzVe4Q43Bak8agkL9Js65O', 0, 4, NULL, '2025-08-11 00:09:53', '2025-08-11 00:09:53', NULL),
	(5, 'Infra Manager', 'inframanager@mail.com', NULL, '$2y$12$8LgBaUVs5eWtJw2f7DQNKufH4odV.fkxOG/hpgV6m1gosFMI/.Fku', 0, 5, NULL, '2025-08-11 00:10:28', '2025-08-11 18:37:11', NULL),
	(6, 'Senior Manager', 'Senior.Manager@mail.com', NULL, '$2y$12$zz3KDQlZqOaQUQLnRk4cNuBy8EhMi2w41Lbu/IIe64V3oApYaJsp.', 1, 6, NULL, '2025-08-11 00:12:13', '2025-08-12 20:38:58', NULL),
	(7, 'Riska', 'admininfra1@plnbatam.com', NULL, '$2y$12$A8M0qPJbH5NyNRcpeGx5ou9Hpk/MZqiDJ3yYEE3j0LjWeU.nJqWDK', 1, 1, NULL, '2025-08-12 20:40:55', '2025-08-12 20:40:55', NULL),
	(8, 'April', 'antoniapril@gmail.com', NULL, '$2y$12$Bj6BdwZ0eKKPDT57lswc6eILZrVaZNyYG91hsFqfVQlWjZzp.8Lfm', 1, 2, NULL, '2025-08-12 20:41:26', '2025-08-12 20:41:26', NULL),
	(9, 'Tarsius', 'tarsiusderbyanto@gmail.com', NULL, '$2y$12$5hHT812H/mriLfSkYW80pO/elIEROuRovAPIZkuZ0GeaSC7slRH7.', 1, 2, NULL, '2025-08-12 20:42:05', '2025-08-12 20:42:05', NULL),
	(10, 'Wawan', 'darel.darmawan@gmail.com', NULL, '$2y$12$TtzVdi2slNmvXjYhstXj2.BCwh5v1D5D5uXBylYGS7V2vQGEx/NbS', 1, 2, NULL, '2025-08-12 20:42:26', '2025-08-12 20:42:26', NULL);

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
