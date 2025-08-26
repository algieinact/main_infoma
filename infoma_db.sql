-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 02:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infoma_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('seminar','webinar','mentoring','lomba','workshop','training') NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_free` tinyint(1) NOT NULL DEFAULT 1,
  `location` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `format` enum('online','offline','hybrid') NOT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `registration_deadline` datetime NOT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`benefits`)),
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `max_participants` int(11) NOT NULL,
  `current_participants` int(11) NOT NULL DEFAULT 0,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `provider_id`, `category_id`, `title`, `slug`, `description`, `type`, `price`, `is_free`, `location`, `city`, `province`, `format`, `meeting_link`, `start_date`, `end_date`, `registration_deadline`, `requirements`, `benefits`, `images`, `max_participants`, `current_participants`, `rating`, `total_reviews`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Seminar Masak', 'seminar masak', 'Seminar masak di bandung', 'seminar', 100000.00, 0, 'Uber', 'Bandung', 'Jawa Barat', 'offline', NULL, '2025-06-12 03:24:47', '2025-06-20 03:24:47', '2025-06-11 03:24:47', NULL, NULL, NULL, 12, 1, 0.00, 0, 1, 0, NULL, NULL),
(2, 2, 4, 'Lomba Test Aja', 'lomba-test-aja', 'Lomba test', 'lomba', 0.00, 1, 'Bojong Soang', 'Bandung', 'Jawa BArat', 'hybrid', NULL, '2025-06-13 04:05:00', '2025-06-17 04:05:00', '2025-06-11 04:05:00', '[\"kreatif\",\"inovatif\"]', '[\"sertifikat\",\"skills\"]', NULL, 12, 3, 0.00, 0, 1, 0, NULL, '2025-06-10 15:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bookable_type` varchar(255) NOT NULL,
  `bookable_id` bigint(20) UNSIGNED NOT NULL,
  `booking_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`booking_data`)),
  `files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`files`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `booking_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `bookable_type`, `bookable_id`, `booking_data`, `files`, `status`, `booking_date`, `start_date`, `end_date`, `total_amount`, `discount_amount`, `final_amount`, `notes`, `cancellation_reason`, `cancelled_at`, `created_at`, `updated_at`) VALUES
(1, 'INF-NNBBJLSU', 5, 'App\\Models\\Residence', 10, '{\"full_name\":\"user\",\"phone\":\"089999\",\"emergency_contact\":\"Algie Swargani\",\"emergency_phone\":\"089617910066\",\"occupation\":\"Engineer\"}', '{\"ktp\":\"bookings\\/5\\/lG53Hg7G968cVeAw9Dy6OzhJTPT9BaXb3TxTj8pb.png\"}', 'waiting_provider_approval', '2025-06-24 17:14:16', '2025-06-27 00:00:00', '2026-10-27 00:00:00', 323000000.00, 0.00, 323000000.00, NULL, NULL, NULL, '2025-06-24 10:14:16', '2025-06-24 10:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bookmarkable_type` varchar(255) NOT NULL,
  `bookmarkable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('residence','activity') NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#3B82F6',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `type`, `icon`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kost Putra', 'kost-putra', 'Kost khusus untuk mahasiswa putra', 'residence', 'male', '#2563eb', 1, '2025-06-07 04:51:58', '2025-06-07 04:51:58'),
(2, 'Kost Putri', 'kost-putri', 'Kost khusus untuk mahasiswa putri', 'residence', 'female', '#d946ef', 1, '2025-06-07 04:51:58', '2025-06-07 04:51:58'),
(3, 'Seminar', 'seminar', 'Kegiatan seminar dan workshop', 'activity', 'chalkboard-teacher', '#f59e42', 1, '2025-06-07 04:51:58', '2025-06-07 04:51:58'),
(4, 'Lomba', 'lomba', 'Berbagai jenis lomba kampus', 'activity', 'trophy', '#22c55e', 1, '2025-06-07 04:51:58', '2025-06-07 04:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `value` decimal(12,2) NOT NULL,
  `min_amount` decimal(12,2) DEFAULT NULL,
  `max_discount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `discountable_type` varchar(255) NOT NULL,
  `discountable_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_29_084631_create_categories_table', 1),
(5, '2025_05_29_084631_create_residences_table', 1),
(6, '2025_05_29_084632_create_activities_table', 1),
(7, '2025_05_29_084632_create_bookings_table', 1),
(8, '2025_05_29_084633_create_bookmarks_table', 1),
(9, '2025_05_29_084633_create_discounts_table', 1),
(10, '2025_05_29_084633_create_transactions_table', 1),
(11, '2025_05_29_084634_create_reviews_table', 1),
(12, '2025_05_29_084634_create_user_activities_table', 1),
(13, '2025_05_29_084635_create_notifications_table', 1),
(14, '2025_05_29_084635_create_settings_table', 1),
(15, '2025_06_01_092656_create_sessions_table', 1),
(16, '2025_06_01_171934_update_bookings_status_column', 1),
(17, '2025_06_07_013252_create_personal_access_tokens_table', 1),
(18, '2024_03_01_000000_add_timestamps_to_user_activities_table', 2),
(19, '2024_03_01_000001_add_images_to_residences_table', 2),
(20, '2024_03_01_000002_add_images_to_activities_table', 2),
(21, '2024_03_21_create_vouchers_table', 3),
(22, '2025_06_08_000001_make_booking_id_nullable_on_reviews_table', 4),
(23, '2025_06_08_100000_alter_booking_id_nullable_reviews_table', 5),
(24, '2024_03_19_add_role_to_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `data`, `action_url`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Booking Baru', 'Ada booking baru untuk Kost Bangsawan', 'new_booking', NULL, NULL, 0, NULL, '2025-06-24 10:14:16', '2025-06-24 10:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', 5, 'auth_token', '5f9333cffc03271c012c66c048ca1dc4a2797efc767d1fdc56a6376fcb567f5d', '[\"*\"]', NULL, NULL, '2025-06-08 04:40:47', '2025-06-08 04:40:47'),
(3, 'App\\Models\\User', 5, 'auth_token', 'b820fdf7fd4fda507ee55c54d01a947dd75430a69df27571bb9d7051ccb2602f', '[\"*\"]', NULL, NULL, '2025-06-08 04:40:59', '2025-06-08 04:40:59'),
(4, 'App\\Models\\User', 5, 'auth_token', '704ea369111eb7db37a5601cb8212522edca63949a700887eb02762c99bb32b2', '[\"*\"]', NULL, NULL, '2025-06-08 04:41:11', '2025-06-08 04:41:11'),
(5, 'App\\Models\\User', 3, 'auth_token', '1f7d8f7c87557d7623bb6cc235f041d50af76d2b8f7718eea3d03b7cd5e87a3f', '[\"*\"]', NULL, NULL, '2025-06-08 04:44:33', '2025-06-08 04:44:33'),
(6, 'App\\Models\\User', 3, 'auth_token', 'f48e18743e36ea9d5dd63cbc14fa6c9eeb4019de443f85610cbd760b29847adf', '[\"*\"]', NULL, NULL, '2025-06-08 04:45:30', '2025-06-08 04:45:30'),
(7, 'App\\Models\\User', 3, 'auth_token', '3c43527c527b5232f6ae21356c9f384f5564aa50d4e99f04be1a6e66785523b6', '[\"*\"]', NULL, NULL, '2025-06-08 04:46:03', '2025-06-08 04:46:03'),
(9, 'App\\Models\\User', 3, 'auth_token', '6e4418d11957ee9a32a995e2f66295912a04725b0c65f8cb490add2febcd30bf', '[\"*\"]', NULL, NULL, '2025-06-08 04:50:20', '2025-06-08 04:50:20'),
(10, 'App\\Models\\User', 3, 'auth_token', '9f3f7fb577b421da275c779a748fb08abef9917ca8c97ae2fc9120a79d250310', '[\"*\"]', NULL, NULL, '2025-06-08 04:53:19', '2025-06-08 04:53:19'),
(11, 'App\\Models\\User', 3, 'auth_token', '1dc3f99543aa38eeae03c211eeef310d61be6a7887102ea50ac8e2ff05f5cadc', '[\"*\"]', '2025-06-09 14:09:23', NULL, '2025-06-09 14:08:03', '2025-06-09 14:09:23'),
(12, 'App\\Models\\User', 3, 'auth_token', '84ace2cfa606bb8aebd245ae42f59e1c71ee8b5f9593335905cf6dbe875f111c', '[\"*\"]', NULL, NULL, '2025-06-09 14:12:57', '2025-06-09 14:12:57'),
(13, 'App\\Models\\User', 3, 'auth_token', '1d444b9de8d514ec8ceaac6f07b1917baf6c5436cb8ec51b822b66d11165be99', '[\"*\"]', NULL, NULL, '2025-06-09 14:15:27', '2025-06-09 14:15:27'),
(14, 'App\\Models\\User', 3, 'auth_token', '5adbccaf8f6930c2b0eb3c074cbc5c3078feaf7d9e2dc8653ac4ca98ed92ec76', '[\"*\"]', NULL, NULL, '2025-06-09 14:21:08', '2025-06-09 14:21:08'),
(16, 'App\\Models\\User', 3, 'auth_token', '97313cbdbc1d47671542494756357bfab562b7dfca6d759ca0122055d4afe8e4', '[\"*\"]', NULL, NULL, '2025-06-09 15:56:58', '2025-06-09 15:56:58'),
(17, 'App\\Models\\User', 3, 'auth_token', '5c40c4ee7caef2cfdc09b0b04b27396ee2a9134fc2b4ba93a627549afd8aa783', '[\"*\"]', NULL, NULL, '2025-06-09 15:57:49', '2025-06-09 15:57:49'),
(18, 'App\\Models\\User', 3, 'auth_token', '53bb867542df4ebd9d93a2ee406bac733ece4c5fe6cd618adcf6086e413a2648', '[\"*\"]', NULL, NULL, '2025-06-09 15:59:57', '2025-06-09 15:59:57'),
(19, 'App\\Models\\User', 3, 'auth_token', '20251cf8bf039cccd08eda823d4d57ad61d7ad92820b2cc543624c462807a451', '[\"*\"]', NULL, NULL, '2025-06-09 16:04:27', '2025-06-09 16:04:27'),
(20, 'App\\Models\\User', 3, 'auth_token', 'ea952f85b0fabac486f6cbb61721a0524cba057973f176ce2189466b7de6a129', '[\"*\"]', NULL, NULL, '2025-06-09 16:06:47', '2025-06-09 16:06:47'),
(21, 'App\\Models\\User', 3, 'auth_token', '4db9cbcaaa0e1363dea776b2d52bd2b31fc2dbef31104e92ab4e1c4325370e16', '[\"*\"]', NULL, NULL, '2025-06-09 17:27:06', '2025-06-09 17:27:06'),
(22, 'App\\Models\\User', 3, 'auth_token', '5fe84c192d2f7e902bf6cb936d4d1863c9e7a5da027859eebf89ebeaedd6bcd8', '[\"*\"]', NULL, NULL, '2025-06-09 17:29:59', '2025-06-09 17:29:59'),
(23, 'App\\Models\\User', 3, 'auth_token', '7a56e80216b3ffc61aa4945a9be3b70286b4b64fa863f11201fd2d0e96085fb0', '[\"*\"]', NULL, NULL, '2025-06-09 17:31:18', '2025-06-09 17:31:18'),
(24, 'App\\Models\\User', 3, 'auth_token', '85d1dcd4808358fdcaf17ed018d5eba50cd1f3593322e21dcc79ec98a0f18839', '[\"*\"]', NULL, NULL, '2025-06-09 17:32:27', '2025-06-09 17:32:27'),
(25, 'App\\Models\\User', 3, 'auth_token', 'cdc4ad93ac0c0e5bce358f457ac15d287663e4dd7426b805c671fc35167c83da', '[\"*\"]', NULL, NULL, '2025-06-09 17:37:25', '2025-06-09 17:37:25'),
(26, 'App\\Models\\User', 3, 'auth_token', '1d049acc6bfa9e6fbfbfef07eafc4c257e0866347fd7d219473339775e5e9b9c', '[\"*\"]', NULL, NULL, '2025-06-09 17:40:07', '2025-06-09 17:40:07'),
(27, 'App\\Models\\User', 3, 'auth_token', 'e5d02060c61c287474b21e2f2cb5c74373d3c7a3e98258eecb6bfe1e6eed4f29', '[\"*\"]', NULL, NULL, '2025-06-09 17:44:28', '2025-06-09 17:44:28'),
(28, 'App\\Models\\User', 3, 'auth_token', '533f7e24b11f0541c96fc18eab6351b9d2d313c632f92e79d16eca31d98cddaa', '[\"*\"]', NULL, NULL, '2025-06-09 17:45:00', '2025-06-09 17:45:00'),
(29, 'App\\Models\\User', 3, 'auth_token', '8d4fd9ee4321c324e254fc462cfa4e01efda453d5049e67921635bb02f4c3113', '[\"*\"]', NULL, NULL, '2025-06-09 17:47:34', '2025-06-09 17:47:34'),
(30, 'App\\Models\\User', 3, 'auth_token', '1265a9bcd670367da9245273197e07e9de4a95fa48a427971c227469962adf05', '[\"*\"]', NULL, NULL, '2025-06-09 17:49:42', '2025-06-09 17:49:42'),
(31, 'App\\Models\\User', 3, 'auth_token', '7b88b03cf1f64868ee00a4328156f7426431bfc1a06700a451ce7d622d6eb6fa', '[\"*\"]', '2025-06-09 18:22:40', NULL, '2025-06-09 17:51:36', '2025-06-09 18:22:40'),
(33, 'App\\Models\\User', 3, 'auth_token', 'ca7343fe640f4decdc17bbdc3352e7abeed3fa229a516c116b1ee21896d8365b', '[\"*\"]', NULL, NULL, '2025-06-10 12:32:42', '2025-06-10 12:32:42'),
(36, 'App\\Models\\User', 3, 'auth_token', 'fe3d63fadca223e5bcc3984b2e7299e4e9d3f61ecba1dc16274ce86887829b26', '[\"*\"]', '2025-06-10 14:42:41', NULL, '2025-06-10 12:55:03', '2025-06-10 14:42:41'),
(39, 'App\\Models\\User', 2, 'auth_token', '524a2e0318930b246643038cf54f511739471dffa791ce6bd57d82a9e9652527', '[\"*\"]', '2025-06-10 14:07:32', NULL, '2025-06-10 14:05:22', '2025-06-10 14:07:32'),
(40, 'App\\Models\\User', 2, 'auth_token', 'ecec4dd144d9258f33e53d7e11abcca031bd0cb596ed0ff73b0bd334c78ec816', '[\"*\"]', '2025-06-10 14:12:32', NULL, '2025-06-10 14:09:05', '2025-06-10 14:12:32'),
(41, 'App\\Models\\User', 2, 'auth_token', '3b223e43870ed65c5d5701a6cf0735eb68f488707cd90416460c9aef711516d8', '[\"*\"]', '2025-06-10 14:15:19', NULL, '2025-06-10 14:13:33', '2025-06-10 14:15:19'),
(42, 'App\\Models\\User', 2, 'auth_token', '8780cdd202014dc64a4feb288e9bd626c5aa3cf0c6abdb4c1eadc9dc531f0e7b', '[\"*\"]', '2025-06-10 14:38:52', NULL, '2025-06-10 14:37:41', '2025-06-10 14:38:52'),
(43, 'App\\Models\\User', 2, 'auth_token', '8cd8d4d7a24140ab8ebe1360a32ec1e76b5b499f279f95e4ca5cca5e748674f7', '[\"*\"]', '2025-06-10 14:50:47', NULL, '2025-06-10 14:42:14', '2025-06-10 14:50:47'),
(44, 'App\\Models\\User', 2, 'auth_token', '68bd21227a1cf8ca69116baee768bb1faafa474e451f75e1330c118026392d6c', '[\"*\"]', '2025-06-10 14:52:09', NULL, '2025-06-10 14:52:08', '2025-06-10 14:52:09'),
(45, 'App\\Models\\User', 2, 'auth_token', '4fc0f2f522739f30223d1696ecb9cff20541f504abdfd3965d45602e0a7753ea', '[\"*\"]', '2025-06-10 15:02:06', NULL, '2025-06-10 14:59:29', '2025-06-10 15:02:06'),
(47, 'App\\Models\\User', 2, 'auth_token', '61b211fa9f12d5358790bd59959a1114d6b7cb8b9bef2eb9135136bc82e069f4', '[\"*\"]', '2025-06-10 15:06:18', NULL, '2025-06-10 15:06:04', '2025-06-10 15:06:18'),
(48, 'App\\Models\\User', 2, 'auth_token', '4d5a3b1f3d3b817780f3e3e4c1b212f234012fbd1120af76db84dbedf72f4fd4', '[\"*\"]', '2025-06-10 15:27:54', NULL, '2025-06-10 15:06:39', '2025-06-10 15:27:54'),
(49, 'App\\Models\\User', 2, 'auth_token', '2101f87777d1daad1ca0031984129ba9f43a30d573a318a931932abecf452f91', '[\"*\"]', '2025-06-10 15:47:53', NULL, '2025-06-10 15:18:46', '2025-06-10 15:47:53'),
(50, 'App\\Models\\User', 2, 'auth_token', '4d0ffe63accecc7e7dea53f75d13ec83ca921d16711dad679835487229041be7', '[\"*\"]', '2025-06-10 20:10:10', NULL, '2025-06-10 19:54:41', '2025-06-10 20:10:10'),
(52, 'App\\Models\\User', 3, 'auth_token', 'b5b7b914dd7f826b4366c3311c2b374d126984cfef8f8aaf337851e11ae30a3b', '[\"*\"]', '2025-06-11 13:03:09', NULL, '2025-06-11 12:27:20', '2025-06-11 13:03:09'),
(54, 'App\\Models\\User', 2, 'auth_token', 'f0d992136774c24f77b79750ad2a9786d0388cbb14b2ec1fa34400d407c9aacf', '[\"*\"]', '2025-06-11 12:46:50', NULL, '2025-06-11 12:46:48', '2025-06-11 12:46:50'),
(59, 'App\\Models\\User', 2, 'auth_token', '7d3637a49edb53c42cf2b5975f56f969601d01864bbaea218b7723fedccce7d2', '[\"*\"]', '2025-06-11 13:22:15', NULL, '2025-06-11 13:01:49', '2025-06-11 13:22:15'),
(65, 'App\\Models\\User', 2, 'auth_token', '643a0cfbd50fda3af76bcc5e2b633041001038975f1af5db809f45f19149f981', '[\"*\"]', '2025-06-11 13:19:53', NULL, '2025-06-11 13:19:51', '2025-06-11 13:19:53'),
(72, 'App\\Models\\User', 2, 'auth_token', 'a37a08c209126be000cf16bb7f21c988462bae58cc95c83d9d32d66ae386deda', '[\"*\"]', '2025-06-11 13:39:11', NULL, '2025-06-11 13:38:07', '2025-06-11 13:39:11'),
(73, 'App\\Models\\User', 2, 'auth_token', 'c371c2d40210b77a3d68eac5868641a65ad05035b392b3c1ce4726b6d42908c2', '[\"*\"]', '2025-06-12 03:25:53', NULL, '2025-06-12 03:24:51', '2025-06-12 03:25:53'),
(74, 'App\\Models\\User', 2, 'auth_token', '5f194b2847e924e093e199b17e30d1740230e89d05d05a73dc0900f2a1b63981', '[\"*\"]', NULL, NULL, '2025-06-12 03:51:59', '2025-06-12 03:51:59'),
(75, 'App\\Models\\User', 2, 'auth_token', 'ad98013133c6c633f13fd199b377ef5890cae10f6cf6c3e59bad2884772392fb', '[\"*\"]', '2025-06-12 03:53:44', NULL, '2025-06-12 03:53:10', '2025-06-12 03:53:44'),
(76, 'App\\Models\\User', 5, 'auth_token', '58eec7aaf61dbdc9959983c38c64907d0e377327f528980da50fe05cf635ad7a', '[\"*\"]', '2025-06-24 10:35:12', NULL, '2025-06-24 10:35:05', '2025-06-24 10:35:12');

-- --------------------------------------------------------

--
-- Table structure for table `residences`
--

CREATE TABLE `residences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('kost','kontrakan') NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_period` enum('daily','weekly','monthly','yearly') NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rules`)),
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `total_rooms` int(11) NOT NULL,
  `available_rooms` int(11) NOT NULL,
  `gender_type` enum('male','female','mixed') NOT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `available_from` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `residences`
--

INSERT INTO `residences` (`id`, `provider_id`, `category_id`, `title`, `slug`, `description`, `type`, `price`, `price_period`, `address`, `city`, `province`, `latitude`, `longitude`, `facilities`, `rules`, `images`, `total_rooms`, `available_rooms`, `gender_type`, `rating`, `total_reviews`, `is_active`, `is_featured`, `available_from`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 'Kost Uber', 'kost-uber', 'Kost di uber', 'kost', 20000.00, 'monthly', 'Jl. Telekomunikasi. 1, Terusan Buahbatu - Bojongsoang, Telkom University, Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257', 'Kabupaten Bandung', 'Jawa Barat', NULL, NULL, '[\"Lemari\",\"meja\",\"tempat makan\",\"kasur\",\"handuk\"]', '[\"dilarang rokok\",\"dilarang mabuk\",\"taat ibadah\"]', '[\"residences\\/AYF8NSI9sLbyvMNeRgGE8iCfFfOxpq1mqivnWyYF.jpg\"]', 14, 3, 'male', 0.00, 0, 1, 0, NULL, '2025-06-09 18:08:36', '2025-06-11 13:15:53'),
(8, 2, 1, 'Bandung Kost', 'bandung-kost', 'Kost nyaman dan strategis di Bandung', 'kost', 10000000.00, 'monthly', 'Jl. Riau No.11', 'Bandung', 'Jawa Barat', NULL, NULL, '[\"Wifi\",\"Kamar Mandi Dalam\",\"AC\",\"Kasur\",\"Lemari\"]', '[\"Tidak boleh merokok\",\"Tidak boleh membawa tamu malam\"]', '[\"residences\\/AYF8NSI9sLbyvMNeRgGE8iCfFfOxpq1mqivnWyYF.jpg\"]', 14, 3, 'male', 0.00, 0, 1, 0, NULL, '2025-06-24 03:50:12', '2025-06-24 03:50:12'),
(10, 2, 1, 'Kost Bangsawan', 'kost-bangsawan', 'Kost untuk para bangsawan', 'kost', 19000000.00, 'monthly', 'Jl. Jakarta No.118', 'Kabupaten Bandung', 'Jawa Barat', NULL, NULL, '[\"Wifi\",\"lemari\",\"dapur\"]', '[\"Jangan merokok\"]', '[\"residences\\/xonGgVv5RV0uIvcUeLxVXCKyHdD7foG1zSboUSpr.jpg\"]', 15, 1, 'male', 0.00, 0, 1, 0, NULL, '2025-06-24 03:58:04', '2025-06-24 03:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `booking_id`, `reviewable_type`, `reviewable_id`, `rating`, `comment`, `images`, `is_anonymous`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'App\\\\Models\\\\Residence', 2, 4, 'bagus', NULL, 0, '2025-06-07 23:43:03', '2025-06-07 23:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('HbUEFCbC4mLHN07LMzpaLY6Z6uMLRBhdExTwgHxb', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYXd6d3NUenh3TXptRzNaOGk4OWx5eUx2OVVXc0RWaEprNEl4TUszaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib29raW5ncy8xIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1750785257);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('payment','refund') NOT NULL,
  `method` enum('bank_transfer','e_wallet','credit_card','cash') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','success','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_data`)),
  `payment_reference` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_code`, `booking_id`, `user_id`, `type`, `method`, `amount`, `status`, `payment_data`, `payment_reference`, `paid_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'TRX-9PI0MIN6', 1, 5, 'payment', 'bank_transfer', 323000000.00, 'pending', NULL, NULL, NULL, NULL, '2025-06-24 10:14:16', '2025-06-24 10:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','provider','admin') NOT NULL DEFAULT 'user',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `address`, `avatar`, `birth_date`, `gender`, `university`, `major`, `graduation_year`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Infoma', 'admin@infoma.com', NULL, '$2y$12$mKNdKyhjQZYR1WWyDauag.Q5O9uPXQzZfZHZIHpOvKp5YqOMNjGzi', 'admin', '081234567890', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-06-07 04:51:57', '2025-06-07 04:51:57'),
(2, 'Provider Kost', 'provider@infoma.com', NULL, '$2y$12$8lNWpaMoV0V7nUSM4hQLgOAZndR66FnQkU2mLvX6tdLTm6Xbkq4QK', 'provider', '081234567891', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-06-07 04:51:58', '2025-06-07 04:51:58'),
(3, 'Mahasiswa 1', 'user1@infoma.com', NULL, '$2y$12$y9T/qFNgNqvVq3wIjm18k.i/DNt415WrYq9wXSFWBuG81oDiUlZ.2', 'user', '081234567892', NULL, NULL, NULL, NULL, 'Telkom University', 'Teknik Informatika', NULL, 1, NULL, '2025-06-07 04:51:58', '2025-06-07 04:51:58'),
(4, 'Mahasiswa 2', 'user2@infoma.com', NULL, '$2y$12$gKPrS0f4E8OI9nDTvxNpkOn/R7aAYlxWzNOMGpGcR/Fpu36yRcKqK', 'user', '081234567893', NULL, NULL, NULL, NULL, 'Telkom University', 'Sistem Informasi', NULL, 1, NULL, '2025-06-07 04:51:58', '2025-06-07 04:51:58'),
(5, 'user', 'user@example.com', NULL, '$2y$12$d9fcCfIEg31T8etmEXHljez8FkPoHGgIk.WUcd6xaTGXyatSnrL.O', 'user', '089999', NULL, NULL, '2017-02-08', 'male', 'ITB', 'SI', '2024', 1, NULL, '2025-06-07 22:53:59', '2025-06-07 22:53:59'),
(6, 'Nama Lengkap', 'user@email.com', NULL, '$2y$12$Q0ZMsyLHcSFsCVlFcXDhYuZw838Hae2e8YkYC2TSusWAFoH54dPXG', 'user', '08123456789', NULL, NULL, '2000-01-01', 'male', 'Telkom University', 'Informatika', '2025', 1, NULL, '2025-06-10 12:34:38', '2025-06-10 12:34:38'),
(7, 'Yansha', 'yansha@infoma.com', NULL, '$2y$12$knwlty1Jm3xo2wp.iREYxeeNkyAVrhsshJVfktY5GDlAz6ILsFsoK', 'user', '089999', NULL, NULL, '2015-06-11', 'male', 'Telkom University', 'SI', '2026', 1, NULL, '2025-06-10 12:42:30', '2025-06-10 12:42:30'),
(8, 'Algie', 'algie@infoma.com', NULL, '$2y$12$klD5CcIFM7JCzsfOkRa61.gMrpfgfsOvtTiZ6uIBnv2x308jMtqPC', 'user', 'password', NULL, NULL, '2004-10-02', 'male', 'Telkom University', 'Informatika', '2027', 1, NULL, '2025-06-11 12:42:07', '2025-06-11 12:42:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activityable_type` varchar(255) NOT NULL,
  `activityable_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_id`, `activityable_type`, `activityable_id`, `action`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:22:19', '2025-06-07 23:22:19'),
(2, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:25:44', '2025-06-07 23:25:44'),
(3, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:32:49', '2025-06-07 23:32:49'),
(4, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:32:57', '2025-06-07 23:32:57'),
(5, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:36:04', '2025-06-07 23:36:04'),
(6, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:36:58', '2025-06-07 23:36:58'),
(7, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:37:30', '2025-06-07 23:37:30'),
(8, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:40:00', '2025-06-07 23:40:00'),
(9, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:41:22', '2025-06-07 23:41:22'),
(10, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:43:27', '2025-06-07 23:43:27'),
(11, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:43:34', '2025-06-07 23:43:34'),
(12, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:43:42', '2025-06-07 23:43:42'),
(13, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:44:17', '2025-06-07 23:44:17'),
(14, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:44:50', '2025-06-07 23:44:50'),
(15, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:46:00', '2025-06-07 23:46:00'),
(16, 5, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:46:06', '2025-06-07 23:46:06'),
(17, 3, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:49:03', '2025-06-07 23:49:03'),
(18, 3, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-07 23:49:32', '2025-06-07 23:49:32'),
(19, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:20:32', '2025-06-11 12:20:32'),
(20, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:20:40', '2025-06-11 12:20:40'),
(21, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:20:42', '2025-06-11 12:20:42'),
(22, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:34:48', '2025-06-11 12:34:48'),
(23, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:35:09', '2025-06-11 12:35:09'),
(24, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:37:02', '2025-06-11 12:37:02'),
(25, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:38:00', '2025-06-11 12:38:00'),
(26, 3, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-11 12:39:27', '2025-06-11 12:39:27'),
(27, 3, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-11 12:39:41', '2025-06-11 12:39:41'),
(28, 3, 'App\\Models\\Residence', 2, 'view', NULL, '2025-06-11 12:40:42', '2025-06-11 12:40:42'),
(29, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 12:54:00', '2025-06-11 12:54:00'),
(30, 3, 'App\\Models\\Residence', 3, 'view', NULL, '2025-06-11 13:18:41', '2025-06-11 13:18:41'),
(31, 5, 'App\\Models\\Residence', 10, 'view', NULL, '2025-06-24 09:28:23', '2025-06-24 09:28:23'),
(32, 5, 'App\\Models\\Residence', 10, 'view', NULL, '2025-06-24 10:13:28', '2025-06-24 10:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `discountable_type` varchar(255) NOT NULL,
  `discountable_id` bigint(20) UNSIGNED NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `provider_id`, `discountable_type`, `discountable_id`, `discount_type`, `discount_value`, `min_purchase`, `max_discount`, `start_date`, `end_date`, `usage_limit`, `used_count`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'WVQZS4YV', 2, 'App\\Models\\Residence', 2, 'percentage', 12.00, NULL, NULL, '2025-06-09 17:00:00', '2025-06-10 17:00:00', NULL, 0, 1, NULL, '2025-06-07 23:17:56', '2025-06-07 23:17:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activities_slug_unique` (`slug`),
  ADD KEY `activities_provider_id_foreign` (`provider_id`),
  ADD KEY `activities_category_id_foreign` (`category_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_bookable_type_bookable_id_index` (`bookable_type`,`bookable_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookmarks_user_id_bookmarkable_id_bookmarkable_type_unique` (`user_id`,`bookmarkable_id`,`bookmarkable_type`),
  ADD KEY `bookmarks_bookmarkable_type_bookmarkable_id_index` (`bookmarkable_type`,`bookmarkable_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discounts_code_unique` (`code`),
  ADD KEY `discounts_discountable_type_discountable_id_index` (`discountable_type`,`discountable_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `residences`
--
ALTER TABLE `residences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `residences_slug_unique` (`slug`),
  ADD KEY `residences_provider_id_foreign` (`provider_id`),
  ADD KEY `residences_category_id_foreign` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_transaction_code_unique` (`transaction_code`),
  ADD KEY `transactions_booking_id_foreign` (`booking_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activities_activityable_type_activityable_id_index` (`activityable_type`,`activityable_id`),
  ADD KEY `user_activities_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`),
  ADD KEY `vouchers_provider_id_foreign` (`provider_id`),
  ADD KEY `vouchers_discountable_type_discountable_id_index` (`discountable_type`,`discountable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `residences`
--
ALTER TABLE `residences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `residences`
--
ALTER TABLE `residences`
  ADD CONSTRAINT `residences_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `residences_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
