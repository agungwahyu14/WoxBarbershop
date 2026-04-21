-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 19 Jan 2026 pada 02.45
-- Versi server: 9.5.0
-- Versi PHP: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `woxbarbershop`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bentuk_kepala`
--

CREATE TABLE `bentuk_kepala` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bentuk_kepala`
--

INSERT INTO `bentuk_kepala` (`id`, `nama`) VALUES
(1, 'Oval'),
(2, 'Bulat'),
(3, 'Persegi Panjang'),
(4, 'Hati'),
(5, 'Kotak'),
(6, 'Segitiga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `hairstyle_id` bigint UNSIGNED DEFAULT NULL,
  `date_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `shift` enum('morning','afternoon') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_number` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled','expired') COLLATE utf8mb4_unicode_ci DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `name`, `guest_name`, `guest_phone`, `guest_email`, `service_id`, `hairstyle_id`, `date_time`, `shift`, `queue_number`, `description`, `payment_method`, `created_at`, `updated_at`, `total_price`, `status`) VALUES
(1, 55, 'Marcia Lara', NULL, NULL, NULL, 2, 41, '2025-12-04 11:00:00', 'morning', 1, 'Dignissimos qui dign', 'bank', '2025-12-03 20:30:47', '2025-12-03 22:10:22', 50000.00, 'cancelled'),
(2, 55, 'Cameran Franklin', NULL, NULL, NULL, 1, 34, '2025-12-04 11:00:00', 'morning', 2, 'Nihil expedita numqu', 'cash', '2025-12-03 20:35:53', '2025-12-03 22:10:17', 25000.00, 'cancelled'),
(3, 55, 'Zachary Pollard', NULL, NULL, NULL, 1, 37, '2025-12-04 11:00:00', 'morning', 3, 'Sunt minima consecte', 'cash', '2025-12-03 20:36:25', '2025-12-03 22:10:14', 25000.00, 'cancelled'),
(4, 55, 'Cassady Sims', NULL, NULL, NULL, 7, 38, '2025-12-04 11:00:00', 'morning', 4, 'Consectetur vero pr', 'bank', '2025-12-03 20:36:34', '2025-12-03 22:10:10', 85000.00, 'cancelled'),
(5, 55, 'Dalton Foley', NULL, NULL, NULL, 2, 38, '2025-12-04 11:00:00', 'morning', 5, 'Libero adipisicing i', 'bank', '2025-12-03 20:36:55', '2025-12-03 22:10:07', 50000.00, 'cancelled'),
(6, 55, 'Buffy Roy', NULL, NULL, NULL, 2, 39, '2025-12-04 16:00:00', 'afternoon', 6, 'Eu quas quis veritat', 'cash', '2025-12-03 20:38:23', '2025-12-03 22:10:26', 50000.00, 'cancelled'),
(7, 55, 'Finn Middleton', NULL, NULL, NULL, 1, 37, '2025-12-11 16:00:00', 'afternoon', 1, 'Asperiores provident', 'bank', '2025-12-10 18:32:37', '2025-12-10 18:36:35', 25000.00, 'completed'),
(8, 55, 'Catherine Horne', NULL, NULL, NULL, 1, 38, '2025-12-12 16:00:00', 'afternoon', 1, 'Dolor voluptate enim', 'bank', '2025-12-10 23:10:29', '2025-12-10 23:12:10', 25000.00, 'completed'),
(9, 109, 'Agung Wahyu', NULL, NULL, NULL, 7, 40, '2025-12-21 16:00:00', 'afternoon', 1, 'Ullam veniam sunt d', 'cash', '2025-12-19 06:12:12', '2025-12-19 14:20:28', 85000.00, 'completed'),
(10, 45, 'Agung Wahyu', NULL, NULL, NULL, 1, 34, '2025-12-22 16:00:00', 'afternoon', 1, 'dasd', 'cash', '2025-12-21 07:36:16', '2025-12-21 07:37:22', 25000.00, 'completed'),
(11, 45, 'Bianca Pitts', NULL, NULL, NULL, 2, 38, '2025-12-31 16:00:00', 'afternoon', 1, 'Pariatur Voluptas n', 'cash', '2025-12-21 07:36:51', '2025-12-21 07:37:05', 50000.00, 'cancelled'),
(12, 83, 'Quamar Tran', NULL, NULL, NULL, 1, 42, '2025-12-31 16:00:00', 'afternoon', 2, 'Qui quia sed quo ill', 'cash', '2025-12-21 07:37:55', '2025-12-21 08:00:58', 25000.00, 'completed'),
(13, NULL, 'Miriam Sawyer', 'Miriam Sawyer', '+1 (968) 104-6476', NULL, 2, 41, '2025-12-21 11:00:00', 'morning', 2, 'Dignissimos quidem e', 'cash', '2025-12-21 08:01:24', '2025-12-21 08:02:23', 50000.00, 'completed'),
(14, NULL, 'Chaim Rosa', 'Chaim Rosa', '+1 (494) 219-9089', NULL, 1, 40, '2025-12-21 11:00:00', 'morning', 3, 'Natus omnis magni te', 'cash', '2025-12-21 08:02:42', '2025-12-21 08:06:35', 25000.00, 'completed'),
(15, NULL, 'Keane Wells', 'Keane Wells', '+1 (413) 319-8729', NULL, 2, 47, '2025-12-21 11:00:00', 'morning', 4, 'Repellendus Asperna', 'cash', '2025-12-21 08:06:48', '2025-12-21 08:07:11', 50000.00, 'completed'),
(16, NULL, 'Aagung', 'Aagung', '+1 (319) 925-7959', 'vozyxyqo@mailinator.com', 2, 38, '2025-12-30 16:00:00', 'afternoon', 1, 'Sed placeat esse e', 'cash', '2025-12-21 08:26:25', '2025-12-21 08:27:27', 50000.00, 'completed'),
(17, NULL, 'Giacomo Hensley', 'Giacomo Hensley', '+1 (882) 999-1627', 'sabit@mailinator.com', 7, 40, '2025-12-21 16:00:00', 'afternoon', 5, 'Excepteur quam ut is', 'bank', '2025-12-21 08:31:53', '2025-12-21 08:32:15', 85000.00, 'completed'),
(18, NULL, 'Melvin Strong', 'Melvin Strong', '+1 (779) 517-7941', 'capy@mailinator.com', 2, 46, '2025-12-21 11:00:00', 'morning', 6, 'Est animi laborum', 'cash', '2025-12-21 10:08:25', '2025-12-21 10:09:17', 50000.00, 'completed'),
(19, NULL, 'Shelly Wynn', 'Shelly Wynn', '+1 (384) 138-3769', 'lybuqogobu@mailinator.com', 2, 39, '2025-12-21 16:00:00', 'afternoon', 7, 'Itaque hic natus par', 'cash', '2025-12-21 10:15:34', '2025-12-21 10:25:07', 50000.00, 'completed'),
(20, NULL, 'Cameran Hawkins', 'Cameran Hawkins', '+1 (131) 504-7352', 'qojoz@mailinator.com', 1, 37, '2025-12-21 16:00:00', 'afternoon', 8, 'Nostrud praesentium', 'bank', '2025-12-21 10:17:57', '2025-12-24 14:50:17', 25000.00, 'confirmed'),
(21, 56, 'Venus Owens', NULL, NULL, NULL, 2, 36, '2025-12-24 11:00:00', 'morning', 1, 'Est nostrud eu possi', 'bank', '2025-12-24 14:37:59', '2025-12-24 14:50:09', 50000.00, 'completed'),
(22, NULL, 'Macy Key', NULL, NULL, NULL, 1, 47, '2025-12-24 11:00:00', 'morning', 2, 'Eos cupiditate qui a', 'bank', '2025-12-24 14:48:50', '2025-12-24 14:49:16', 25000.00, 'completed'),
(23, 84, 'Anastasia Drake', NULL, NULL, NULL, 7, 38, '2025-12-24 16:00:00', 'afternoon', 3, 'Dolores porro sunt r', 'cash', '2025-12-24 14:49:35', '2025-12-24 14:49:35', 85000.00, 'confirmed'),
(24, 98, 'Tate Stein', NULL, NULL, NULL, 7, 37, '2025-12-24 11:00:00', 'morning', 4, 'Fugit optio exerci', 'bank', '2025-12-24 14:50:45', '2025-12-24 14:50:45', 85000.00, 'pending'),
(25, 91, 'Cullen Ruiz', NULL, NULL, NULL, 1, 45, '2025-12-24 11:00:00', 'morning', 5, 'Repudiandae ea ex qu', 'cash', '2025-12-24 14:53:22', '2025-12-24 14:53:22', 25000.00, 'confirmed'),
(26, 74, 'Kai Schmidt', NULL, NULL, NULL, 1, 41, '2025-12-24 16:00:00', 'afternoon', 6, 'Officia esse vel qu', 'cash', '2025-12-24 14:55:05', '2025-12-24 14:56:09', 25000.00, 'in_progress'),
(27, NULL, 'Yuri Porter', NULL, NULL, NULL, 1, 34, '2025-12-24 16:00:00', 'afternoon', 7, 'Porro veniam et con', 'cash', '2025-12-24 14:58:17', '2025-12-24 14:58:17', 25000.00, 'completed'),
(28, 51, 'Xavier Gonzalez', NULL, NULL, NULL, 2, 36, '2025-12-25 16:00:00', 'afternoon', 1, 'Nihil voluptas magna', 'bank', '2025-12-24 15:01:26', '2025-12-24 15:01:26', 50000.00, 'pending'),
(29, 83, 'Judith Curtis', NULL, NULL, NULL, 7, 42, '2025-12-25 16:00:00', 'afternoon', 2, 'Autem et ut quia sed', 'bank', '2025-12-24 15:07:15', '2025-12-24 15:07:15', 85000.00, 'pending'),
(30, 80, 'Simone Humphrey', NULL, NULL, NULL, 2, 47, '2025-12-24 16:00:00', 'afternoon', 8, 'Est adipisci fugiat', 'bank', '2025-12-24 15:11:26', '2025-12-24 15:11:26', 50000.00, 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `criteria`
--

CREATE TABLE `criteria` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `criteria`
--

INSERT INTO `criteria` (`id`, `name`, `weight`, `created_at`, `updated_at`) VALUES
(8, 'Bentuk Kepala', 0.50026636036223, '2025-09-25 01:31:39', '2026-01-14 03:21:50'),
(9, 'Tipe Rambut', 0.29976029562608, '2025-09-25 01:32:01', '2026-01-14 03:21:50'),
(10, 'Preferensi Gaya', 0.19997334401169, '2025-09-25 01:32:07', '2026-01-14 03:21:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `booking_id`, `rating`, `comment`, `is_public`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 49, 1, 5, 'Rekomendasi gaya rambut yang diberikan sangat cocok dengan bentuk wajah saya. Terima kasih!', 1, 1, '2025-09-20 17:29:33', '2025-12-21 07:38:54'),
(11, 74, 27, 4, 'Pelayanannya cepat dan hasil potongan rambutnya rapi. Akan kembali lagi pasti.', 1, 1, '2025-09-08 17:29:33', '2025-11-06 07:03:06'),
(12, 74, 96, 5, 'Wox Barbershop adalah barbershop terbaik di Gianyar. Highly recommended!', 1, 1, '2025-09-15 17:29:33', '2025-12-21 07:38:52'),
(15, 55, 7, 5, 'Mantap', 1, 1, '2025-12-10 18:39:23', '2025-12-10 18:39:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyles`
--

CREATE TABLE `hairstyles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_in` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hairstyles`
--

INSERT INTO `hairstyles` (`id`, `name`, `description`, `description_in`, `description_en`, `image`, `created_at`, `updated_at`) VALUES
(34, 'French Crop', 'Short haircut with straight bangs, suitable for strong faces.', 'Potongan rambut pendek dengan poni rata ke depan, cocok untuk wajah tegas.', 'Short haircut with straight bangs, suitable for strong faces.', 'hairstyles/fCrkN2rksevxyERGdaHoZqggh2GCvKeAyFg7huV4.png', '2025-09-25 02:33:50', '2025-11-06 05:52:08'),
(36, 'Side Part', 'Hair parted to the side with a clear line.', 'Rambut dibelah ke samping dengan garis jelas.', 'Hair parted to the side with a clear line.', 'hairstyles/1yzwoOysMOWKKcnGZFwfzMNoUYBuEoT4qh6naUcT.png', '2025-09-25 06:06:27', '2025-11-06 05:55:15'),
(37, 'Pompadour', 'Long, voluminous hair on top, combed back.', 'Rambut bagian atas panjang & bervolume, disisir ke belakang.', 'Long, voluminous hair on top, combed back.', 'hairstyles/whYeEVu0NabR6OFh5MtgM3aAWAYlETjAax5D2pcG.png', '2025-09-29 20:00:48', '2025-11-06 05:50:02'),
(38, 'Quiff', 'The front part is lifted up and then directed backward/sideways.', 'Bagian depan diangkat ke atas lalu diarahkan ke belakang/samping.', 'The front part is lifted up and then directed backward/sideways.', 'hairstyles/8cNPuVZihAJKw4YwKXSi3mmr2gbFgEZNsjX4GfUM.png', '2025-09-29 20:02:29', '2025-11-06 05:50:46'),
(39, 'Buzzcut', 'Very short cut with clippers, uniform all over the head.', 'Potongan sangat pendek dengan clipper, seragam di seluruh kepala.', 'Very short cut with clippers, uniform all over the head.', 'hairstyles/EQBnR9CmwQX8SOQ133QDhaZ5sNwvWjCUKtOwm4Hc.png', '2025-09-29 20:04:16', '2025-11-06 05:51:10'),
(40, 'Tapper Fade', 'The hair on the sides and back is gradually shortened (tapered) and then given a smooth fade.', 'Rambut bagian samping & belakang dikecilkan perlahan (taper) lalu dibuat gradasi halus (fade).', 'The hair on the sides and back is gradually shortened (tapered) and then given a smooth fade.', 'hairstyles/jzuV2YmwdTYFFeTJDQo2to4RfYURvrpOlERPt1g6.png', '2025-09-29 20:05:13', '2025-11-06 05:53:55'),
(41, 'Crew Cut', 'The top hair is longer (2–4 cm), the sides are shorter.', 'Rambut atas lebih panjang (2–4 cm), samping lebih pendek.', 'The top hair is longer (2–4 cm), the sides are shorter.', 'hairstyles/ikRxlWSUT1cJnJs5LinIVGBALCshI1e5EWRwXCJs.png', '2025-09-29 20:06:34', '2025-11-06 05:51:48'),
(42, 'Undercut', 'Very short on top, sides, and back without any layering.', 'Atas panjang, samping & belakang sangat pendek tanpa gradasi.', 'Very short on top, sides, and back without any layering.', 'hairstyles/AqNF79XoFFtse8Ukw2t4uGcm7pe4rsakEjEECVJf.png', '2025-09-29 20:07:14', '2025-11-06 05:54:51'),
(43, 'Fringe', 'The fringe falls onto the forehead and can be short or long.', 'Poni jatuh ke dahi, bisa pendek atau panjang.', 'The fringe falls onto the forehead and can be short or long.', 'hairstyles/zWgS4ANr1vp9lMY4K2tWY9wDbnff7GTdgcseUUSl.png', '2025-09-29 20:09:20', '2025-11-06 05:52:51'),
(44, 'Caesar Cut', 'Short, even hair with a short, straight fringe.', 'Rambut atas pendek rata dengan poni pendek lurus ke depan.', 'Short, even hair with a short, straight fringe.', 'hairstyles/cj82DVGjjEQSD0xxhOO6YfxZx90HDaQUjXnJeyU2.png', '2025-09-29 20:10:22', '2025-11-06 05:51:29'),
(45, 'Side Swept Fringe', 'Poni panjang/pendek disapu ke samping.', 'Poni panjang/pendek disapu ke samping.', 'Long/short bangs swept to the side.', 'hairstyles/wUHFwHAhSMS6B81MpaGBT8TlHcAFttDQzOgcHFFl.png', '2025-09-29 20:12:03', '2025-11-06 05:53:33'),
(46, 'Long Fringe', 'Long bangs fall to the eyes, can be straight, slanted, or messy.', 'Poni panjang jatuh sampai mata, bisa lurus, miring, atau messy.', 'Long bangs fall to the eyes, can be straight, slanted, or messy.', 'hairstyles/WJRhOyX8oZwgVgrMgJOgdwqGzcejvnaygNAmLtzu.png', '2025-09-29 20:14:50', '2025-11-06 05:53:15'),
(47, 'Textured Crop', 'Short cut with random texture on top, short fringe to the front.', 'Potongan pendek dengan tekstur acak di atas, poni pendek ke depan.', 'Short cut with random texture on top, short fringe to the front.', 'hairstyles/6cEB3glaogCBoeUC4dtvlb3awD0Vj7VsLxjnc9mk.png', '2025-09-29 20:15:54', '2025-11-06 05:55:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_bentuk_kepala`
--

CREATE TABLE `hairstyle_bentuk_kepala` (
  `hairstyle_id` bigint UNSIGNED NOT NULL,
  `bentuk_kepala_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hairstyle_bentuk_kepala`
--

INSERT INTO `hairstyle_bentuk_kepala` (`hairstyle_id`, `bentuk_kepala_id`) VALUES
(34, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(34, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(34, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(34, 4),
(36, 4),
(37, 4),
(38, 4),
(39, 4),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(34, 5),
(36, 5),
(37, 5),
(38, 5),
(39, 5),
(40, 5),
(41, 5),
(42, 5),
(43, 5),
(44, 5),
(45, 5),
(46, 5),
(47, 5),
(34, 6),
(37, 6),
(38, 6),
(39, 6),
(40, 6),
(41, 6),
(42, 6),
(43, 6),
(44, 6),
(45, 6),
(46, 6),
(47, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_scores`
--

CREATE TABLE `hairstyle_scores` (
  `id` bigint UNSIGNED NOT NULL,
  `hairstyle_id` bigint UNSIGNED NOT NULL,
  `criterion_id` bigint UNSIGNED NOT NULL,
  `sub_criterion_id` bigint UNSIGNED NOT NULL,
  `score` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hairstyle_scores`
--

INSERT INTO `hairstyle_scores` (`id`, `hairstyle_id`, `criterion_id`, `sub_criterion_id`, `score`, `created_at`, `updated_at`) VALUES
(157, 47, 8, 1, 8.00, NULL, NULL),
(158, 47, 8, 2, 7.00, NULL, NULL),
(159, 47, 8, 3, 8.00, NULL, NULL),
(160, 47, 8, 4, 7.00, NULL, NULL),
(161, 47, 8, 5, 6.00, NULL, NULL),
(162, 47, 8, 6, 7.00, NULL, NULL),
(163, 47, 9, 1, 7.00, NULL, NULL),
(164, 47, 9, 2, 8.00, NULL, NULL),
(165, 47, 9, 3, 9.00, NULL, NULL),
(166, 47, 10, 1, 4.00, NULL, NULL),
(167, 47, 10, 2, 9.00, NULL, NULL),
(168, 47, 10, 3, 8.00, NULL, NULL),
(169, 46, 8, 1, 7.00, NULL, NULL),
(170, 46, 8, 2, 5.00, NULL, NULL),
(171, 46, 8, 3, 9.00, NULL, NULL),
(172, 46, 8, 4, 9.00, NULL, NULL),
(173, 46, 8, 5, 6.00, NULL, NULL),
(174, 46, 8, 6, 7.00, NULL, NULL),
(175, 46, 9, 1, 9.00, NULL, NULL),
(176, 46, 9, 2, 7.00, NULL, NULL),
(177, 46, 9, 3, 3.00, NULL, NULL),
(178, 46, 10, 1, 5.00, NULL, NULL),
(179, 46, 10, 2, 8.00, NULL, NULL),
(180, 46, 10, 3, 4.00, NULL, NULL),
(181, 45, 8, 1, 9.00, NULL, NULL),
(182, 45, 8, 2, 6.00, NULL, NULL),
(183, 45, 8, 3, 8.00, NULL, NULL),
(184, 45, 8, 4, 9.00, NULL, NULL),
(185, 45, 8, 5, 7.00, NULL, NULL),
(186, 45, 8, 6, 8.00, NULL, NULL),
(187, 45, 9, 1, 8.00, NULL, NULL),
(188, 45, 9, 2, 9.00, NULL, NULL),
(189, 45, 9, 3, 5.00, NULL, NULL),
(190, 45, 10, 1, 8.00, NULL, NULL),
(191, 45, 10, 2, 7.00, NULL, NULL),
(192, 45, 10, 3, 6.00, NULL, NULL),
(193, 44, 8, 1, 7.00, NULL, NULL),
(194, 44, 8, 2, 3.00, NULL, NULL),
(195, 44, 8, 3, 8.00, NULL, NULL),
(196, 44, 8, 4, 7.00, NULL, NULL),
(197, 44, 8, 5, 5.00, NULL, NULL),
(198, 44, 8, 6, 6.00, NULL, NULL),
(199, 44, 9, 1, 8.00, NULL, NULL),
(200, 44, 9, 2, 7.00, NULL, NULL),
(201, 44, 9, 3, 6.00, NULL, NULL),
(202, 44, 10, 1, 9.00, NULL, NULL),
(203, 44, 10, 2, 6.00, NULL, NULL),
(204, 44, 10, 3, 8.00, NULL, NULL),
(205, 43, 8, 1, 8.00, NULL, NULL),
(206, 43, 8, 2, 5.00, NULL, NULL),
(207, 43, 8, 3, 9.00, NULL, NULL),
(208, 43, 8, 4, 9.00, NULL, NULL),
(209, 43, 8, 5, 6.00, NULL, NULL),
(210, 43, 8, 6, 7.00, NULL, NULL),
(211, 43, 9, 1, 8.00, NULL, NULL),
(212, 43, 9, 2, 8.00, NULL, NULL),
(213, 43, 9, 3, 5.00, NULL, NULL),
(214, 43, 10, 1, 6.00, NULL, NULL),
(215, 43, 10, 2, 8.00, NULL, NULL),
(216, 43, 10, 3, 7.00, NULL, NULL),
(217, 42, 8, 1, 9.00, NULL, NULL),
(218, 42, 8, 2, 8.00, NULL, NULL),
(219, 42, 8, 3, 6.00, NULL, NULL),
(220, 42, 8, 4, 7.00, NULL, NULL),
(221, 42, 8, 5, 8.00, NULL, NULL),
(222, 42, 8, 6, 7.00, NULL, NULL),
(223, 42, 9, 1, 8.00, NULL, NULL),
(224, 42, 9, 2, 9.00, NULL, NULL),
(225, 42, 9, 3, 8.00, NULL, NULL),
(226, 42, 10, 1, 5.00, NULL, NULL),
(227, 42, 10, 2, 9.00, NULL, NULL),
(228, 42, 10, 3, 7.00, NULL, NULL),
(229, 41, 8, 1, 8.00, NULL, NULL),
(230, 41, 8, 2, 4.00, NULL, NULL),
(231, 41, 8, 3, 7.00, NULL, NULL),
(232, 41, 8, 4, 6.00, NULL, NULL),
(233, 41, 8, 5, 7.00, NULL, NULL),
(234, 41, 8, 6, 7.00, NULL, NULL),
(235, 41, 9, 1, 8.00, NULL, NULL),
(236, 41, 9, 2, 8.00, NULL, NULL),
(237, 41, 9, 3, 7.00, NULL, NULL),
(238, 41, 10, 1, 8.00, NULL, NULL),
(239, 41, 10, 2, 6.00, NULL, NULL),
(240, 41, 10, 3, 9.00, NULL, NULL),
(241, 40, 8, 1, 9.00, NULL, NULL),
(242, 40, 8, 2, 7.00, NULL, NULL),
(243, 40, 8, 3, 7.00, NULL, NULL),
(244, 40, 8, 4, 8.00, NULL, NULL),
(245, 40, 8, 5, 8.00, NULL, NULL),
(246, 40, 8, 6, 8.00, NULL, NULL),
(247, 40, 9, 1, 8.00, NULL, NULL),
(248, 40, 9, 2, 8.00, NULL, NULL),
(249, 40, 9, 3, 9.00, NULL, NULL),
(250, 40, 10, 1, 7.00, NULL, NULL),
(251, 40, 10, 2, 9.00, NULL, NULL),
(252, 40, 10, 3, 8.00, NULL, NULL),
(253, 39, 8, 1, 7.00, NULL, NULL),
(254, 39, 8, 2, 2.00, NULL, NULL),
(255, 39, 8, 3, 5.00, NULL, NULL),
(256, 39, 8, 4, 4.00, NULL, NULL),
(257, 39, 8, 5, 8.00, NULL, NULL),
(258, 39, 8, 6, 6.00, NULL, NULL),
(259, 39, 9, 1, 8.00, NULL, NULL),
(260, 39, 9, 2, 8.00, NULL, NULL),
(261, 39, 9, 3, 8.00, NULL, NULL),
(262, 39, 10, 1, 7.00, NULL, NULL),
(263, 39, 10, 2, 5.00, NULL, NULL),
(264, 39, 10, 3, 9.00, NULL, NULL),
(265, 38, 8, 1, 9.00, NULL, NULL),
(266, 38, 8, 2, 9.00, NULL, NULL),
(267, 38, 8, 3, 5.00, NULL, NULL),
(268, 38, 8, 4, 7.00, NULL, NULL),
(269, 38, 8, 5, 8.00, NULL, NULL),
(270, 38, 8, 6, 7.00, NULL, NULL),
(271, 38, 9, 1, 8.00, NULL, NULL),
(272, 38, 9, 2, 9.00, NULL, NULL),
(273, 38, 9, 3, 6.00, NULL, NULL),
(274, 38, 10, 1, 7.00, NULL, NULL),
(275, 38, 10, 2, 9.00, NULL, NULL),
(276, 38, 10, 3, 4.00, NULL, NULL),
(277, 37, 8, 1, 8.00, NULL, NULL),
(278, 37, 8, 2, 9.00, NULL, NULL),
(279, 37, 8, 3, 3.00, NULL, NULL),
(280, 37, 8, 4, 6.00, NULL, NULL),
(281, 37, 8, 5, 7.00, NULL, NULL),
(282, 37, 8, 6, 6.00, NULL, NULL),
(283, 37, 9, 1, 9.00, NULL, NULL),
(284, 37, 9, 2, 7.00, NULL, NULL),
(285, 37, 9, 3, 4.00, NULL, NULL),
(286, 37, 10, 1, 9.00, NULL, NULL),
(287, 37, 10, 2, 8.00, NULL, NULL),
(288, 37, 10, 3, 3.00, NULL, NULL),
(289, 36, 8, 1, 9.00, NULL, NULL),
(290, 36, 8, 2, 6.00, NULL, NULL),
(291, 36, 8, 3, 7.00, NULL, NULL),
(292, 36, 8, 4, 8.00, NULL, NULL),
(293, 36, 8, 5, 8.00, NULL, NULL),
(294, 36, 8, 6, 7.00, NULL, NULL),
(295, 36, 9, 1, 9.00, NULL, NULL),
(296, 36, 9, 2, 8.00, NULL, NULL),
(297, 36, 9, 3, 5.00, NULL, NULL),
(298, 36, 10, 1, 9.00, NULL, NULL),
(299, 36, 10, 2, 6.00, NULL, NULL),
(300, 36, 10, 3, 5.00, NULL, NULL),
(301, 34, 8, 1, 8.00, NULL, NULL),
(302, 34, 8, 2, 6.00, NULL, NULL),
(303, 34, 8, 3, 9.00, NULL, NULL),
(304, 34, 8, 4, 8.00, NULL, NULL),
(305, 34, 8, 5, 7.00, NULL, NULL),
(306, 34, 8, 6, 7.00, NULL, NULL),
(307, 34, 9, 1, 8.00, NULL, NULL),
(308, 34, 9, 2, 8.00, NULL, NULL),
(309, 34, 9, 3, 7.00, NULL, NULL),
(310, 34, 10, 1, 6.00, NULL, NULL),
(311, 34, 10, 2, 9.00, NULL, NULL),
(312, 34, 10, 3, 9.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_style_preference`
--

CREATE TABLE `hairstyle_style_preference` (
  `hairstyle_id` bigint UNSIGNED NOT NULL,
  `style_preference_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hairstyle_style_preference`
--

INSERT INTO `hairstyle_style_preference` (`hairstyle_id`, `style_preference_id`) VALUES
(34, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(34, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(34, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_tipe_rambut`
--

CREATE TABLE `hairstyle_tipe_rambut` (
  `hairstyle_id` bigint UNSIGNED NOT NULL,
  `tipe_rambut_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hairstyle_tipe_rambut`
--

INSERT INTO `hairstyle_tipe_rambut` (`hairstyle_id`, `tipe_rambut_id`) VALUES
(34, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(34, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(34, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `loyalties`
--

CREATE TABLE `loyalties` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `loyalties`
--

INSERT INTO `loyalties` (`id`, `user_id`, `points`, `updated_at`, `created_at`) VALUES
(11, 55, 2, '2025-12-10 23:12:10', '2025-08-07 01:00:43'),
(12, 51, 1, '2025-08-25 20:19:17', '2025-08-25 20:19:17'),
(13, 50, 1, '2025-08-25 23:18:20', '2025-08-25 23:18:20'),
(14, 49, 1, '2025-10-04 07:22:09', '2025-10-04 07:22:09'),
(15, 53, 2, '2025-10-04 17:36:43', '2025-10-04 17:35:29'),
(16, 79, 3, '2025-10-23 03:26:41', '2025-10-22 20:04:22'),
(17, 45, 0, '2025-10-26 03:11:35', '2025-10-26 03:11:35'),
(19, 86, 2, '2025-11-09 06:05:50', '2025-11-07 00:47:18'),
(20, 109, 1, '2025-12-19 14:20:28', '2025-12-19 14:20:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_06_25_082041_create_permission_tables', 1),
(6, '2025_06_28_032618_create_bookings_table', 1),
(7, '2025_06_28_032722_create_dashboards_table', 1),
(8, '2025_06_28_033154_create_loyalties_table', 1),
(9, '2025_06_28_033206_create_services_table', 1),
(10, '2025_06_28_033234_create_hairstyles_table', 1),
(11, '2025_06_28_033306_create_recommendations_table', 1),
(12, '2025_06_28_060903_create_transactions_table', 1),
(13, '2025_07_16_030956_add_columns_to_services_table', 1),
(14, '2025_07_16_033549_add_columns_to_hairstyles_table', 1),
(15, '2025_07_16_064917_add_timestamps_to_bookings_table', 1),
(16, '2025_07_22_033016_add_payment_fields_to_transactions_table', 1),
(17, '2025_07_25_043000_fix_bookings_table_structure', 1),
(18, '2025_07_25_043100_add_database_indexes', 1),
(19, '2025_07_25_055720_add_loyalty_columns_to_loyalties_table', 1),
(20, '2025_07_25_055837_add_dashboard_statistics_columns', 1),
(21, '2025_07_25_060441_add_duration_is_active_to_services_table', 1),
(22, '2025_07_25_060530_add_is_active_to_hairstyles_table', 1),
(23, '2025_07_25_060840_add_service_user_to_transactions_table', 1),
(24, '2025_07_25_065942_fix_dashboard_metric_value_column', 1),
(25, '2025_07_25_071500_add_user_management_columns', 1),
(26, '2025_07_29_060324_add_midtrans_columns_to_bookings_table', 2),
(27, '2025_08_08_022026_create_criteria_table', 3),
(28, '2025_08_08_022053_create_pairwise_comparisons_table', 3),
(29, '2025_08_08_022102_create_hairstyle_scores_table', 3),
(30, '2025_08_08_025917_add_style_preference_to_hairstyles_table', 4),
(31, '2025_10_01_030004_create_feedback_table', 5),
(32, '2025_10_01_030040_create_products_table', 5),
(33, '2025_10_08_020138_add_is_loyalty_redeem_to_bookings_table', 6),
(34, '2025_10_08_025134_remove_is_loyalty_redeem_from_bookings_table', 7),
(36, '2025_10_26_071711_add_payment_status_to_bookings_table_if_missing', 8),
(37, '2025_10_26_155000_add_multilingual_fields_to_products_table', 8),
(38, '2025_10_26_080456_add_multilingual_fields_to_services_table', 9),
(39, '2025_11_06_134622_add_multilingual_descriptions_to_hairstyles_table', 10),
(40, '2025_12_02_061214_add_expired_status_to_bookings_table', 11),
(41, '2025_12_04_035235_add_session_booking_columns_to_tables', 12),
(42, '2025_12_04_042816_add_shift_to_bookings_table', 13),
(43, '2025_12_08_123229_add_sub_criterion_id_to_hairstyle_scores_table', 14),
(44, '2025_12_21_add_guest_support_to_bookings_table', 15),
(45, '2025_12_21_161451_add_guest_fields_to_bookings_table', 16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 45),
(4, 'App\\Models\\User', 45),
(5, 'App\\Models\\User', 45),
(6, 'App\\Models\\User', 45),
(7, 'App\\Models\\User', 45),
(8, 'App\\Models\\User', 45),
(9, 'App\\Models\\User', 45),
(10, 'App\\Models\\User', 45),
(11, 'App\\Models\\User', 45),
(12, 'App\\Models\\User', 45),
(13, 'App\\Models\\User', 45),
(14, 'App\\Models\\User', 45),
(15, 'App\\Models\\User', 45),
(16, 'App\\Models\\User', 45),
(17, 'App\\Models\\User', 45),
(18, 'App\\Models\\User', 45),
(19, 'App\\Models\\User', 45),
(20, 'App\\Models\\User', 45),
(21, 'App\\Models\\User', 45),
(22, 'App\\Models\\User', 45),
(23, 'App\\Models\\User', 45),
(24, 'App\\Models\\User', 45),
(25, 'App\\Models\\User', 45),
(26, 'App\\Models\\User', 45),
(27, 'App\\Models\\User', 45),
(28, 'App\\Models\\User', 45),
(29, 'App\\Models\\User', 45),
(30, 'App\\Models\\User', 45),
(31, 'App\\Models\\User', 45),
(32, 'App\\Models\\User', 45),
(33, 'App\\Models\\User', 45),
(34, 'App\\Models\\User', 45),
(35, 'App\\Models\\User', 45),
(36, 'App\\Models\\User', 45),
(37, 'App\\Models\\User', 45),
(38, 'App\\Models\\User', 45),
(39, 'App\\Models\\User', 45),
(40, 'App\\Models\\User', 45),
(41, 'App\\Models\\User', 45),
(42, 'App\\Models\\User', 45),
(43, 'App\\Models\\User', 45),
(44, 'App\\Models\\User', 45),
(45, 'App\\Models\\User', 45),
(46, 'App\\Models\\User', 45),
(47, 'App\\Models\\User', 45),
(48, 'App\\Models\\User', 45),
(49, 'App\\Models\\User', 45),
(50, 'App\\Models\\User', 45),
(51, 'App\\Models\\User', 45),
(52, 'App\\Models\\User', 45),
(53, 'App\\Models\\User', 45),
(54, 'App\\Models\\User', 45),
(55, 'App\\Models\\User', 45),
(56, 'App\\Models\\User', 45),
(57, 'App\\Models\\User', 45),
(58, 'App\\Models\\User', 45),
(59, 'App\\Models\\User', 45),
(60, 'App\\Models\\User', 45),
(61, 'App\\Models\\User', 45),
(62, 'App\\Models\\User', 45),
(63, 'App\\Models\\User', 45),
(64, 'App\\Models\\User', 45),
(65, 'App\\Models\\User', 45),
(66, 'App\\Models\\User', 45),
(67, 'App\\Models\\User', 45),
(68, 'App\\Models\\User', 45),
(69, 'App\\Models\\User', 45),
(70, 'App\\Models\\User', 45),
(2, 'App\\Models\\User', 46),
(2, 'App\\Models\\User', 47);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(30, 'App\\Models\\User', 45),
(31, 'App\\Models\\User', 46),
(31, 'App\\Models\\User', 47),
(32, 'App\\Models\\User', 48),
(32, 'App\\Models\\User', 49),
(32, 'App\\Models\\User', 50),
(32, 'App\\Models\\User', 51),
(32, 'App\\Models\\User', 53),
(32, 'App\\Models\\User', 55),
(32, 'App\\Models\\User', 56),
(32, 'App\\Models\\User', 57),
(32, 'App\\Models\\User', 71),
(32, 'App\\Models\\User', 72),
(32, 'App\\Models\\User', 74),
(32, 'App\\Models\\User', 76),
(32, 'App\\Models\\User', 77),
(32, 'App\\Models\\User', 79),
(32, 'App\\Models\\User', 80),
(32, 'App\\Models\\User', 83),
(32, 'App\\Models\\User', 84),
(32, 'App\\Models\\User', 86),
(31, 'App\\Models\\User', 88),
(32, 'App\\Models\\User', 88),
(30, 'App\\Models\\User', 89),
(31, 'App\\Models\\User', 89),
(30, 'App\\Models\\User', 90),
(31, 'App\\Models\\User', 90),
(32, 'App\\Models\\User', 91),
(32, 'App\\Models\\User', 92),
(32, 'App\\Models\\User', 93),
(32, 'App\\Models\\User', 94),
(32, 'App\\Models\\User', 95),
(30, 'App\\Models\\User', 96),
(32, 'App\\Models\\User', 97),
(32, 'App\\Models\\User', 98),
(32, 'App\\Models\\User', 99),
(32, 'App\\Models\\User', 100),
(32, 'App\\Models\\User', 101),
(32, 'App\\Models\\User', 102),
(32, 'App\\Models\\User', 103),
(32, 'App\\Models\\User', 104),
(32, 'App\\Models\\User', 105),
(32, 'App\\Models\\User', 106),
(32, 'App\\Models\\User', 107),
(32, 'App\\Models\\User', 108),
(32, 'App\\Models\\User', 109);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pairwise_comparisons`
--

CREATE TABLE `pairwise_comparisons` (
  `id` bigint UNSIGNED NOT NULL,
  `criterion_id_1` bigint UNSIGNED NOT NULL,
  `criterion_id_2` bigint UNSIGNED NOT NULL,
  `value` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pairwise_comparisons`
--

INSERT INTO `pairwise_comparisons` (`id`, `criterion_id_1`, `criterion_id_2`, `value`, `created_at`, `updated_at`) VALUES
(10, 8, 9, 1.67, '2025-09-25 04:36:22', '2025-09-25 04:36:22'),
(11, 8, 10, 2.50, '2025-09-25 04:36:22', '2025-09-25 04:36:22'),
(12, 9, 10, 1.50, '2025-09-25 04:36:22', '2025-09-25 04:36:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('faizal01.appkey@gmail.com', '$2y$12$Y94ByCNX3N2ub4.Pn1wANOK4BFJ51VkuDYbDdjdccZvEi1ogIw8NG', '2025-12-05 00:58:40'),
('wahyubrahmantha05@gmail.com', '$2y$12$dD4o9OmBay6xl2W1TkgUCeX6UAVQepyCDsGpczYqoIlRKeQ3zS/8G', '2025-12-05 22:31:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'web', '2025-07-25 00:19:12', '2025-07-25 00:19:12'),
(2, 'pegawai', 'web', '2025-07-25 00:19:13', '2025-07-25 00:19:13'),
(3, 'view users', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(4, 'create users', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(5, 'edit users', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(6, 'delete users', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(7, 'manage user roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(8, 'manage user permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(9, 'reset user passwords', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(10, 'verify user emails', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(11, 'activate user accounts', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(12, 'deactivate user accounts', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(13, 'export users', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(14, 'import users', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(15, 'view user profiles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(16, 'edit user profiles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(17, 'view user activity logs', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(18, 'view roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(19, 'create roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(20, 'edit roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(21, 'delete roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(22, 'assign roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(23, 'revoke roles', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(24, 'view permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(25, 'create permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(26, 'edit permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(27, 'delete permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(28, 'assign permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(29, 'revoke permissions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(30, 'view bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(31, 'create bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(32, 'edit bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(33, 'delete bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(34, 'manage all bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(35, 'confirm bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(36, 'cancel bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(37, 'complete bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(38, 'view booking history', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(39, 'export bookings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(40, 'view services', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(41, 'create services', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(42, 'edit services', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(43, 'delete services', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(44, 'activate services', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(45, 'deactivate services', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(46, 'manage service pricing', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(47, 'view service analytics', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(48, 'view transactions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(49, 'create transactions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(50, 'edit transactions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(51, 'delete transactions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(52, 'process payments', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(53, 'refund payments', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(54, 'view payment history', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(55, 'export transactions', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(56, 'manage payment methods', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(57, 'view dashboard', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(58, 'view analytics', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(59, 'view reports', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(60, 'export reports', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(61, 'view admin dashboard', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(62, 'view staff dashboard', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(63, 'view customer dashboard', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(64, 'manage system settings', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(65, 'backup system', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(66, 'view system logs', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(67, 'system maintenance', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(68, 'clear cache', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(69, 'manage notifications', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18'),
(70, 'manage email templates', 'web', '2025-07-25 00:34:18', '2025-07-25 00:34:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_id` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `name_id`, `name_en`, `description`, `description_id`, `description_en`, `price`, `category`, `image`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Hair Shampoo for Men', 'Shampo Rambut untuk Pria', 'Hair Shampoo for Men', 'Shampoo khusus pria yang diformulasikan untuk mengatasi ketombe dan menjaga kebersihan rambut secara menyeluruh. Menyegarkan dan memberikan kelembutan pada rambut tanpa membuatnya kering.', 'Shampoo khusus pria yang diformulasikan untuk mengatasi ketombe dan menjaga kebersihan rambut secara menyeluruh. Menyegarkan dan memberikan kelembutan pada rambut tanpa membuatnya kering.', 'A men\'s shampoo formulated to combat dandruff and maintain thorough hair hygiene. It refreshes and softens hair without drying it out.', 120000.00, 'Perawatan Rambut', 'products/OmJujD7IZAkrd114MQS8etPD6sFNQ1yDI5iXQ0pL.png', 50, 1, NULL, '2025-11-06 05:43:29'),
(4, 'Beard Oil - Classic Scent', 'Minyak Jenggot - Aroma Klasik', 'Beard Oil - Classic Scent', 'A classic-scented beard oil that helps soften and condition your beard. It helps reduce damage and dryness, while also providing a natural shine.', 'Minyak jenggot dengan aroma klasik yang membantu melembutkan dan merawat jenggot Anda. Membantu mengurangi kerusakan dan kekeringan pada jenggot serta memberikan kilau alami.', 'A classic-scented beard oil that helps soften and condition your beard. It helps reduce damage and dryness, while also providing a natural shine.', 150000.00, 'Perawatan Jenggot', 'products/2l6BYQiSfAQbiss80srtjpqJF6VNDzMp3vNlmCcI.png', 30, 1, NULL, '2025-11-06 05:42:59'),
(5, 'Pomade Strong Hold', 'Pomade Penguat Kuat', 'Pomade Strong Hold', 'A strong-hold pomade, perfect for styling like pompadours or slick-backs. It provides a glossy finish that lasts all day.', 'Pomade dengan daya tahan kuat, cocok untuk styling rambut seperti pompadour atau slick-back. Memberikan hasil akhir yang glossy dan tahan sepanjang hari.', 'A strong-hold pomade, perfect for styling like pompadours or slick-backs. It provides a glossy finish that lasts all day.', 100000.00, 'Perawatan Rambut', 'products/98SRanW4tj6sOFluwqdkqqU8ShwIGsaULEOaqMVi.png', 40, 1, NULL, '2025-11-06 05:41:30'),
(6, 'Aftershave Lotion - Cooling Effect', 'Aftershave Lotion - Cooling Effect', 'Aftershave Lotion - Cooling Effect', 'Aftershave lotion with a cooling effect that soothes the skin after shaving. Helps reduce irritation and softens the skin.', 'Lotion aftershave dengan efek pendingin yang menenangkan kulit setelah bercukur. Membantu mengurangi iritasi dan memberikan kelembutan pada kulit.', 'Aftershave lotion with a cooling effect that soothes the skin after shaving. Helps reduce irritation and softens the skin.', 80000.00, 'Perawatan Kulit', 'products/joYBiXBVICB5KNpLyAS9laChawDEyszQu33BMY9W.png', 60, 1, NULL, '2025-11-06 05:41:01'),
(7, 'Beard Balm - Conditioning', 'Beard Balm - Pelembap', 'Beard Balm - Conditioning', 'A beard balm that moisturizes and conditions your beard while maintaining its shape. Contains natural ingredients that nourish your beard and the skin underneath.', 'Balm jenggot yang melembapkan dan merawat jenggot sekaligus menjaga bentuknya tetap rapi. Mengandung bahan alami yang menutrisi jenggot dan kulit di bawahnya.', 'A beard balm that moisturizes and conditions your beard while maintaining its shape. Contains natural ingredients that nourish your beard and the skin underneath.', 130000.00, 'Perawatan Jenggot', 'products/3wcqd81WoPLg28Qd7qApoO5UZgYbFWJ4WEOznQxE.png', 25, 1, NULL, '2025-11-06 05:39:53'),
(8, 'Hair Clipper - Pro Series', 'Pisau Cukur - Baja Tahan Karat', 'Hair Clipper - Pro Series', 'A hair clipper with a powerful, high-precision motor. Equipped with several blade sizes for various haircuts.', 'Mesin pemotong rambut dengan motor yang kuat dan presisi tinggi. Dilengkapi dengan beberapa ukuran pisau untuk berbagai jenis potongan rambut.', 'A hair clipper with a powerful, high-precision motor. Equipped with several blade sizes for various haircuts.', 500000.00, 'Peralatan Barbershop', 'products/LalC7J84fr1xcWlPT4igjpPUH4EdTxyLecu4yURU.png', 15, 1, NULL, '2025-11-06 05:38:31'),
(9, 'Shaving Razor - Stainless Steel', 'Pisau Cukur - Baja Tahan Karat', 'Shaving Razor - Stainless Steel', 'A stainless steel manual razor that delivers a precise and sharp shave. Ideal for professional use in barbershops.', 'Pisau cukur manual berbahan stainless steel yang memberikan hasil cukur presisi dan tajam. Ideal untuk penggunaan profesional di barbershop.', 'A stainless steel manual razor that delivers a precise and sharp shave. Ideal for professional use in barbershops.', 200000.00, 'Peralatan Barbershop', 'products/VhY69VUtNNPTfQ7Kc52My7YZc7owJGaPuXd2YKBI.png', 20, 1, NULL, '2025-11-06 05:29:57'),
(10, 'Beard Comb - Wood', 'Sisir Jenggot - Kayu', 'Beard Comb - Wood', 'A wooden beard comb that provides comfort when grooming your beard without damaging the hair fibers.', 'Sisir jenggot berbahan kayu yang memberikan kenyamanan saat merapikan jenggot tanpa merusak serat rambut.', 'A wooden beard comb that provides comfort when grooming your beard without damaging the hair fibers.', 50000.00, 'Aksesori Barbershop', 'products/fstxCip4i4XIiEF2EwGvPC8PESLQstI9FkJLRZcQ.png', 75, 1, NULL, '2025-11-06 05:29:08'),
(11, 'Barber Apron - Premium', 'Celemek Tukang Cukur - Premium', 'Barber Apron - Premium', 'Premium quality apron for barbershop, made of durable material that is comfortable to wear, with large pockets for storing razors and other accessories.', 'Apron berkualitas premium untuk barbershop, terbuat dari bahan tahan lama yang nyaman dipakai, dengan kantong besar untuk menyimpan alat cukur dan aksesori lainnya.', 'Premium quality apron for barbershop, made of durable material that is comfortable to wear, with large pockets for storing razors and other accessories.', 250000.00, 'Aksesori Barbershop', 'products/QYPk19ORt5w6XU0HPHirGnZCdoJSZNURzUrLYFyP.png', 20, 1, NULL, '2025-11-06 05:28:00'),
(12, 'Pembersih Wajah untuk Pria', 'Pembersih Wajah untuk Pria', 'Facial Cleanser for Men', 'Pembersih wajah khusus pria yang menghilangkan kotoran dan minyak berlebih tanpa mengeringkan kulit. Menjaga keseimbangan pH dan memberi kesegaran sepanjang hari.', 'Pembersih wajah khusus pria yang menghilangkan kotoran dan minyak berlebih tanpa mengeringkan kulit. Menjaga keseimbangan pH dan memberi kesegaran sepanjang hari.', 'A facial cleanser specifically for men that removes dirt and excess oil without drying out the skin. It maintains pH balance and leaves you feeling fresh all day long.', 95000.00, 'Perawatan Kulit', 'products/y6ffWUaLmXpyMVGud1jq6WD5kKxMj3lSeUK3g8mG.png', 45, 1, NULL, '2025-11-06 06:09:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(30, 'admin', 'web', '2025-07-25 00:19:06', '2025-07-25 00:19:06'),
(31, 'pegawai', 'web', '2025-07-25 00:19:06', '2025-07-25 00:19:06'),
(32, 'pelanggan', 'web', '2025-07-25 00:19:06', '2025-07-25 00:19:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 30),
(3, 30),
(4, 30),
(5, 30),
(6, 30),
(7, 30),
(8, 30),
(9, 30),
(10, 30),
(11, 30),
(12, 30),
(13, 30),
(14, 30),
(15, 30),
(16, 30),
(17, 30),
(18, 30),
(19, 30),
(20, 30),
(21, 30),
(22, 30),
(23, 30),
(24, 30),
(25, 30),
(26, 30),
(27, 30),
(28, 30),
(29, 30),
(30, 30),
(31, 30),
(32, 30),
(33, 30),
(34, 30),
(35, 30),
(36, 30),
(37, 30),
(38, 30),
(39, 30),
(40, 30),
(41, 30),
(42, 30),
(43, 30),
(44, 30),
(45, 30),
(46, 30),
(47, 30),
(48, 30),
(49, 30),
(50, 30),
(51, 30),
(52, 30),
(53, 30),
(54, 30),
(55, 30),
(56, 30),
(57, 30),
(58, 30),
(59, 30),
(60, 30),
(61, 30),
(62, 30),
(63, 30),
(64, 30),
(65, 30),
(66, 30),
(67, 30),
(68, 30),
(69, 30),
(70, 30),
(3, 31),
(5, 31),
(10, 31),
(15, 31),
(30, 31),
(31, 31),
(32, 31),
(35, 31),
(36, 31),
(37, 31),
(38, 31),
(40, 31),
(47, 31),
(48, 31),
(49, 31),
(52, 31),
(54, 31),
(57, 31),
(58, 31),
(62, 31),
(66, 31),
(15, 32),
(16, 32),
(30, 32),
(31, 32),
(32, 32),
(36, 32),
(40, 32),
(48, 32),
(57, 32),
(63, 32);

-- --------------------------------------------------------

--
-- Struktur dari tabel `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_id` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `duration` int NOT NULL DEFAULT '30' COMMENT 'Duration in minutes',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `services`
--

INSERT INTO `services` (`id`, `name`, `name_id`, `name_en`, `description`, `description_id`, `description_en`, `price`, `duration`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Regular Haircut', 'Potong Rambut Regular', 'Regular Haircut', 'Standard haircut with a classic style', 'Potong rambut standar dengan gaya klasik', 'Standard haircut with a classic style', 25000.00, 30, 1, '2025-07-25 00:19:18', '2025-11-06 05:18:56'),
(2, 'Premium Haircut', 'Potong Rambut Premium', 'Premium Haircut', 'Haircut with premium styling consultation and finishing', 'Potong rambut dengan konsultasi styling dan finishing premium', 'Haircut with premium styling consultation and finishing', 50000.00, 45, 1, '2025-07-25 00:19:18', '2025-11-06 05:18:10'),
(7, 'Complete Package', 'Paket Lengkap', 'Complete Package', 'Complete package: haircut, shave, wash, and styling', 'Paket lengkap: potong rambut, cukur, cuci, dan styling', 'Complete package: haircut, shave, wash, and styling', 85000.00, 90, 1, '2025-07-25 00:19:18', '2025-11-06 05:24:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `style_preference`
--

CREATE TABLE `style_preference` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `style_preference`
--

INSERT INTO `style_preference` (`id`, `nama`) VALUES
(1, 'Klasik'),
(2, 'Modern'),
(3, 'Kasual');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tipe_rambut`
--

CREATE TABLE `tipe_rambut` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tipe_rambut`
--

INSERT INTO `tipe_rambut` (`id`, `nama`) VALUES
(1, 'Lurus'),
(2, 'Bergelombang'),
(3, 'Keriting');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_amount` decimal(10,2) DEFAULT NULL,
  `transaction_time` timestamp NULL DEFAULT NULL,
  `bank` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `transaction_status`, `payment_type`, `gross_amount`, `transaction_time`, `bank`, `va_number`, `created_at`, `updated_at`, `name`, `email`) VALUES
(1, '6', 'cancel', 'cash', 50000.00, '2025-12-03 20:50:42', NULL, NULL, '2025-12-03 20:50:42', '2025-12-03 22:10:26', 'Buffy Roy', 'lisa@example.com'),
(2, '5', 'cancel', 'bank', 50000.00, '2025-12-03 22:10:07', NULL, NULL, '2025-12-03 22:10:07', '2025-12-03 22:10:07', 'Dalton Foley', 'lisa@example.com'),
(3, '4', 'cancel', 'bank', 85000.00, '2025-12-03 22:10:10', NULL, NULL, '2025-12-03 22:10:10', '2025-12-03 22:10:10', 'Cassady Sims', 'lisa@example.com'),
(4, '3', 'cancel', 'cash', 25000.00, '2025-12-03 22:10:14', NULL, NULL, '2025-12-03 22:10:14', '2025-12-03 22:10:14', 'Zachary Pollard', 'lisa@example.com'),
(5, '2', 'cancel', 'cash', 25000.00, '2025-12-03 22:10:17', NULL, NULL, '2025-12-03 22:10:17', '2025-12-03 22:10:17', 'Cameran Franklin', 'lisa@example.com'),
(6, '1', 'cancel', 'bank', 50000.00, '2025-12-03 22:10:22', NULL, NULL, '2025-12-03 22:10:22', '2025-12-03 22:10:22', 'Marcia Lara', 'lisa@example.com'),
(7, '7', 'settlement', 'bank_transfer', 25000.00, '2025-12-11 01:35:52', 'bca', '35797161554343384944968', '2025-12-10 18:35:46', '2025-12-10 18:36:35', 'Finn Middleton', 'lisa@example.com'),
(8, '8', 'settlement', 'bank_transfer', 25000.00, '2025-12-11 06:11:41', 'bca', '35797968517245930047251', '2025-12-10 23:11:30', '2025-12-10 23:12:10', 'Catherine Horne', 'lisa@example.com'),
(9, '9', 'settlement', 'cash', 85000.00, '2025-12-19 06:12:26', NULL, NULL, '2025-12-19 06:12:26', '2025-12-19 14:20:28', 'Agung Wahyu', 'wahyubrahmantha05@gmail.com'),
(10, '11', 'cancel', 'cash', 50000.00, '2025-12-21 07:37:05', NULL, NULL, '2025-12-21 07:37:05', '2025-12-21 07:37:05', 'Bianca Pitts', 'admin@woxbarbershop.com'),
(11, '12', 'settlement', 'cash', 25000.00, '2025-12-21 07:37:55', NULL, NULL, '2025-12-21 07:37:55', '2025-12-21 08:01:04', 'Quamar Tran', 'radaj@mailinator.com'),
(12, '19', 'settlement', 'cash', 50000.00, '2025-12-21 10:15:34', NULL, NULL, '2025-12-21 10:15:34', '2025-12-24 14:38:34', 'Shelly Wynn', 'lybuqogobu@mailinator.com'),
(13, '20', 'pending', 'bank', 25000.00, '2025-12-21 10:17:57', NULL, NULL, '2025-12-21 10:17:57', '2025-12-21 10:17:57', 'Cameran Hawkins', 'qojoz@mailinator.com'),
(14, '23', 'settlement', 'cash', 85000.00, '2025-12-24 14:49:35', NULL, NULL, '2025-12-24 14:49:35', '2025-12-24 14:49:35', 'Anastasia Drake', 'zifo@mailinator.com'),
(15, '24', 'pending', NULL, 85000.00, '2025-12-24 14:50:45', NULL, NULL, '2025-12-24 14:50:45', '2025-12-24 14:50:45', 'Tate Stein', 'zaritufo@mailinator.com'),
(16, '25', 'settlement', 'cash', 25000.00, '2025-12-24 14:53:22', NULL, NULL, '2025-12-24 14:53:22', '2025-12-24 14:53:22', 'Cullen Ruiz', 'gyrucuw@mailinator.com'),
(17, '26', 'settlement', 'cash', 25000.00, '2025-12-24 14:55:05', NULL, NULL, '2025-12-24 14:55:05', '2025-12-24 14:55:05', 'Kai Schmidt', 'wujesul@mailinator.com'),
(18, '27', 'settlement', 'cash', 25000.00, '2025-12-24 14:58:17', NULL, NULL, '2025-12-24 14:58:17', '2025-12-24 14:58:17', 'Yuri Porter', NULL),
(19, '28', 'pending', NULL, 50000.00, '2025-12-24 15:01:26', NULL, NULL, '2025-12-24 15:01:26', '2025-12-24 15:01:26', 'Xavier Gonzalez', 'sarah@example.com'),
(20, '29', 'pending', NULL, 85000.00, '2025-12-24 15:07:15', NULL, NULL, '2025-12-24 15:07:15', '2025-12-24 15:07:15', 'Judith Curtis', 'radaj@mailinator.com'),
(21, '30', 'pending', NULL, 50000.00, '2025-12-24 15:11:26', NULL, NULL, '2025-12-24 15:11:26', '2025-12-24 15:11:26', 'Simone Humphrey', 'aisyahnadia5@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `no_telepon`, `email_verified_at`, `password`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(45, 'Super Admin', 'admin@woxbarbershop.com', '081234567890', '2025-07-25 00:19:36', '$2y$12$cESMekqUEtM9A9wXvleli.RjgbArHEu3hFLwO2ez8LX43dbMsCwoe', 1, '2026-01-14 03:18:12', NULL, '2025-07-25 00:19:36', '2026-01-14 03:18:12'),
(46, 'Barber Ahmad', 'ahmad@woxbarbershop.com', '081234567891', '2025-07-25 00:19:36', '$2y$12$cniTOFtL6o.bBpETghYmTekFTYuvOnQrJOwA8miSePOKHRDmB6fRe', 1, '2025-12-09 23:43:35', NULL, '2025-07-25 00:19:36', '2025-12-09 23:43:35'),
(47, 'Barber Budi', 'budi@woxbarbershop.com', '081234567892', '2025-07-25 00:19:37', '$2y$12$Nve5vdVIzWRXxHOlOEbYRuNj.57iZAllDKuijvEcF64/mKmpykssC', 1, NULL, NULL, '2025-07-25 00:19:37', '2025-07-25 00:19:37'),
(48, 'John Doe', 'john@example.com', '089876543210', '2025-07-25 00:19:39', '$2y$12$vfTnRqxQ9NqvPteFVqePlepm2fQn6BURrjpWttdcCNYLQBrDKO0X2', 1, '2025-09-08 08:03:32', NULL, '2025-07-25 00:19:39', '2025-09-08 08:03:32'),
(49, 'Jane Smith', 'jane@example.com', '089876543211', '2025-07-25 00:19:39', '$2y$12$zzWsm99NrpF2CucW..CO6eql2YBLRMgX.FYRiMKC473YqBoX076vG', 1, '2025-10-04 07:17:16', NULL, '2025-07-25 00:19:39', '2025-10-04 07:17:16'),
(50, 'Michael Johnson', 'michael@example.com', '089876543212', '2025-07-25 00:19:39', '$2y$12$xbvZZSop3xqTxOoFWBy/pejPJE5nt1sESZdwwcrfo0vYcCFKkhwPC', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(51, 'Sarah Wilson', 'sarah@example.com', '089876543213', '2025-07-25 00:19:39', '$2y$12$QNKmTBLxw8c12QdLIUngR.SKsxnfEfTJOYA5EqNMpEKpNbjUgcAdW', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(53, 'Emily Davis', 'emily@example.com', '089876543215', '2025-07-25 00:19:39', '$2y$12$IbRKsN3Zt3iAVrArvDzowOR/2c3dK7R7s6UGbMbRt7kPw6RtNCq.6', 1, '2025-10-04 17:24:47', NULL, '2025-07-25 00:19:39', '2025-10-04 17:24:47'),
(55, 'Lisa', 'lisa@example.com', '089876543217', '2025-07-25 00:19:39', '$2y$12$N2y0lNbY6.1SXO3zPCDDlu/rh4JmMDOR7gXWly9a7cDhuEUlP3nHy', 1, '2026-01-14 03:21:48', '3hKdktShuPxrymupmgt5j2oCFHBk23oNKR686ZqRwyLYGa5qjXj1XQOZTbfy', '2025-07-25 00:19:39', '2026-01-14 03:21:48'),
(56, 'Robert Martinez', 'robert@example.com', '089876543218', '2025-07-25 00:19:39', '$2y$12$m4AN0yhjvmtMZpRghXVAUeffv8EByE1wbAcOijqye7Knx5RGMij8e', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(57, 'Jennifer Rodriguez', 'jennifer@example.com', '089876543219', '2025-07-25 00:19:39', '$2y$12$9tr3qq7GwRIwQOmAwfXEl.dmli0KlGNdgiNyOb.Q3uDRKIgJj0efu', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(71, 'Tanisha Mccarty', 'jesub@mailinator.com', '12', '2025-08-12 23:31:47', '$2y$12$6r9Gs32ZdiUNFxv.PoNob.Hkw5Y0KjB5GpDTAqrncQRuCnjCd44cW', 1, NULL, NULL, '2025-08-12 23:31:08', '2025-08-12 23:31:47'),
(72, 'Melyssa Gaines', 'fologuf@mailinator.com', '37', '2025-08-12 23:38:34', '$2y$12$86DPDKHWNtzJc1AKFecihOK.8EbqjkdqcMGVJAXQX2KwfiSEJqsfW', 1, NULL, NULL, '2025-08-12 23:32:57', '2025-08-12 23:38:34'),
(74, 'Hop Ball', 'wujesul@mailinator.com', '43', '2025-08-13 00:37:05', '$2y$12$w6ruT7lAOYdJMhCRNIsadO9/dsyvC/.URv0vzC0yCTukeUdJSJdkG', 1, NULL, NULL, '2025-08-13 00:33:55', '2025-08-13 00:37:05'),
(77, 'Pande Pertama', 'pande@gmail.com', '081234567555', '2025-10-06 21:59:48', '$2y$12$wzJ1ld5kRXHI6SZCa0E8uOmgpJUcNp1ll2hFcf9oWoTM7w3CKjV5m', 1, NULL, NULL, '2025-10-06 21:59:23', '2025-10-06 21:59:48'),
(78, 'Test Customer Loyalty', 'testloyalty@test.com', '08123456789', '2025-10-07 18:34:29', '$2y$12$1Vhr5DdOY7hmPmk402QT2./nqhWGONY7USEwv1u/qgl7ihZytbyXm', 1, NULL, NULL, '2025-10-07 18:34:29', '2025-10-07 18:34:29'),
(79, 'Ciara', 'byzeqaxe@mailinator.com', '6272', '2025-10-23 02:34:57', '$2y$12$C.dBRvfCT8JnNTXFHQCoWukcX8csx/wKgLg0nWMjdXs.YljfbapUy', 1, NULL, NULL, '2025-10-22 18:28:58', '2025-10-23 00:06:24'),
(80, 'Aisya Nadia', 'aisyahnadia5@gmail.com', '081234567899', '2025-10-23 02:05:14', '$2y$12$Fp6esf6QYrixP2SDAFQinuiyjlHK7fAgxNS8iYmbkYn6cvRsZs9G6', 1, '2025-10-26 21:39:41', NULL, '2025-10-23 02:04:36', '2025-10-26 21:39:41'),
(83, 'Quamar Tran', 'radaj@mailinator.com', '17853135062', NULL, '$2y$12$yXfSrp3kuTU/C91t1O0KLesuaf1eQF5jWHSWLf60UxvGuF0AbxKPS', 1, NULL, NULL, '2025-10-29 23:13:59', '2025-10-29 23:13:59'),
(84, 'Kendall Chambers', 'zifo@mailinator.com', '15331841465', '2025-10-30 07:15:09', '$2y$12$Cb1lh/aBEGShoxBcb4q1a.JqZYNUENk4bYdwgWSjMndJURZT1SzOW', 1, NULL, NULL, '2025-10-29 23:14:19', '2025-10-29 23:14:19'),
(86, 'Dara', 'dara@gmail.com', '081239887665', '2025-11-05 03:03:47', '$2y$12$wW20Mx55Bo34ZrqqS97NGeXoBu0E3X6YEDs/o7hdfRIDn5Meh7cZC', 1, '2025-11-09 18:05:21', NULL, '2025-11-05 03:03:47', '2025-11-09 18:16:56'),
(88, 'Joelle Ashley', 'wapomypoqe@mailinator.com', '18016382229', NULL, '$2y$12$0YQQS3at/dS4kkcVu8NTKulxJZXo4Yp1ydyFNjdhqRQIonfl0Qo92', 1, NULL, NULL, '2025-11-06 00:06:50', '2025-11-06 00:06:50'),
(89, 'Odysseus Rodriquez', 'fubohovy@mailinator.com', '19648423834', NULL, '$2y$12$Vy8Jkct7x5jHJk1/h3cafu1QliLO0vTk80/ZXtgqDtQZ28Wzky98W', 1, NULL, NULL, '2025-11-06 00:11:28', '2025-11-06 00:11:28'),
(90, 'Alice Gray', 'puda@mailinator.com', '14241869005', NULL, '$2y$12$baLUxboxOUlr3rHWuHmILuAfgdjtTe0NsbVpYUR531410bIqRlera', 1, NULL, NULL, '2025-11-06 00:16:39', '2025-11-06 00:16:39'),
(91, 'Nash Perkins', 'gyrucuw@mailinator.com', '13932315088', '2025-11-07 03:38:17', '$2y$12$Z89bXiXE0elAxMJcj8w9uuC.D3vY6mhnPjKrdxVcwWgG50DfqEfQu', 1, NULL, NULL, '2025-11-07 03:38:17', '2025-11-07 03:38:17'),
(92, 'Brett Douglas', 'kebo@mailinator.com', '19621661022', '2025-11-07 03:40:08', '$2y$12$sik898VHPgP.7SM39DGeJOulMrdvEsbMHLgNWdvR.J6wxCOogyEYm', 1, NULL, NULL, '2025-11-07 03:40:08', '2025-11-07 03:40:08'),
(93, 'Teagan Lucas', 'nalefafa@mailinator.com', '18525135232', '2025-11-07 03:40:39', '$2y$12$UxaulQgtyqNpUrniw9wurenlI9Kpe0EdmGuDGVv6RhAB8UZbwm3Uy', 1, NULL, NULL, '2025-11-07 03:40:39', '2025-11-07 03:40:39'),
(94, 'Hedley Michael', 'kuhoxiri@mailinator.com', '14232568767', '2025-11-07 03:42:44', '$2y$12$vsx2PSxHyljm6Lhc4IvTwOCvt7W7HVvroNVo/RbT3uwZH2rDqxG1C', 1, NULL, NULL, '2025-11-07 03:42:44', '2025-11-07 03:42:44'),
(95, 'Bianca Pitts', 'gyledazom@mailinator.com', '14746632856', '2025-11-07 03:43:40', '$2y$12$z1vPSD.I.4417j0Kcn8pM.GrsIDaCt/QzFk3KrWYCjLMvXAUz247O', 1, NULL, NULL, '2025-11-07 03:43:40', '2025-11-07 03:43:40'),
(96, 'Roanna Padilla', 'coxytyp@mailinator.com', '14477149553', NULL, '$2y$12$X7yt2B/uOnk4wRg1dXoQe.tVzBqiXF7q77YWOgp7IIeOpMgNebsC2', 1, NULL, NULL, '2025-11-19 01:00:36', '2025-11-19 01:00:36'),
(97, 'Kimberly Stuart', 'mazoj@mailinator.com', '13395444355', NULL, '$2y$12$8QXZ4dxD4amEQC98cA6Gj.HQLuXa79hnqWEwkvsxkz0MfWM6qMh8C', 1, NULL, NULL, '2025-11-19 01:00:47', '2025-11-19 01:00:47'),
(98, 'Madison Cain', 'zaritufo@mailinator.com', '18177779878', NULL, '$2y$12$lbaRDeAuY0kMkj9OaVWgneVmYtc8qsBZj1AHotON.oCyFQk7KBWJ6', 1, NULL, NULL, '2025-11-19 01:01:18', '2025-11-19 01:01:18'),
(99, 'Samantha Ortega', 'zeqy@mailinator.com', '11699592285', '2025-11-19 01:02:55', '$2y$12$dJ5sdzG1uyL5Rch7p5WVZu8ASH8FXKO99S6ggjS/0k3wllHJ0cyOm', 1, NULL, NULL, '2025-11-19 01:01:54', '2025-11-19 01:02:55'),
(100, 'Meisa', 'meisa@gmail.com', '089876543216', NULL, '$2y$12$5mqkHwdFMZqa1vrcH.hhEOPsf95zegFY/tpXyvg6kw3w2gFlgTczK', 1, NULL, NULL, '2025-12-03 22:18:26', '2025-12-03 22:18:26'),
(101, 'Dominic Meyers', 'totavej@mailinator.com', '15481575553', NULL, '$2y$12$EFAw2/J2a8vFa9YA65RI5OdkSfebS3fgGRRzsswbLNS08.SzgqOk2', 1, NULL, NULL, '2025-12-03 22:19:49', '2025-12-03 22:19:49'),
(102, 'Randall Donovan', 'deleho@mailinator.com', '19892296228', NULL, '$2y$12$H2FMJqBklOleQNe6/im3H.PHaNI8qTgfkDUEZvSUkjWxoo./O0JW.', 1, NULL, NULL, '2025-12-03 22:21:23', '2025-12-03 22:21:23'),
(103, 'Faizal', 'faizal01.appkey@gmail.com', '082223889667', '2025-12-05 08:48:13', '$2y$12$nZb5ykzMv4gH.yDgFtH8CeoF3NqQFEODKmDvO6s5HyZ.JeN26o1hW', 1, NULL, NULL, '2025-12-05 00:45:00', '2025-12-05 00:45:00'),
(105, 'jodi', 'jodi.appkey@gmail.com', '087654321234', NULL, '$2y$12$zRskX5Y/blR6jbeIpG.54eV.hVpfF4wAgF57tYOBu/Se98EK6kB6K', 1, '2025-12-06 00:49:59', NULL, '2025-12-06 00:49:06', '2025-12-06 00:49:59'),
(106, 'beecool', 'gunakanlahakunini@gmail.com', '08113932168', NULL, '$2y$12$atGM5deOeETdwwos8PWgdeKSdXlOUJtmDfDmae3SRtQ61qefYG3Ae', 1, '2025-12-06 01:00:29', NULL, '2025-12-06 00:58:44', '2025-12-06 01:00:29'),
(108, 'Bayu', 'bayutngks@gmail.com', '082334556778', NULL, '$2y$12$EUxDvN7PZMH/rJ/s6VJHZ.p0wFNcC7TYKk1qZvRnutesPaO1zCPaS', 1, NULL, NULL, '2025-12-07 03:23:15', '2025-12-07 03:23:15'),
(109, 'Agung Wahyu', 'wahyubrahmantha05@gmail.com', '081239261344', '2025-12-07 03:25:47', '$2y$12$NAh0pwzmgUOscZxTTLBpc.cDF7CKjIef8KXG0i89m58xBnxNJaAtm', 1, '2025-12-19 06:11:28', NULL, '2025-12-07 03:25:30', '2025-12-19 06:11:28');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `bentuk_kepala`
--
ALTER TABLE `bentuk_kepala`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_service_id_foreign` (`service_id`),
  ADD KEY `bookings_hairstyle_id_foreign` (`hairstyle_id`),
  ADD KEY `bookings_date_time_status_index` (`date_time`,`status`),
  ADD KEY `bookings_queue_number_index` (`queue_number`),
  ADD KEY `bookings_user_id_index` (`user_id`),
  ADD KEY `bookings_guest_name_guest_phone_index` (`guest_name`,`guest_phone`),
  ADD KEY `bookings_guest_email_index` (`guest_email`);

--
-- Indeks untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_user_id_foreign` (`user_id`),
  ADD KEY `feedback_booking_id_foreign` (`booking_id`);

--
-- Indeks untuk tabel `hairstyles`
--
ALTER TABLE `hairstyles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hairstyles_name_index` (`name`);

--
-- Indeks untuk tabel `hairstyle_bentuk_kepala`
--
ALTER TABLE `hairstyle_bentuk_kepala`
  ADD PRIMARY KEY (`hairstyle_id`,`bentuk_kepala_id`),
  ADD KEY `bentuk_kepala_id` (`bentuk_kepala_id`);

--
-- Indeks untuk tabel `hairstyle_scores`
--
ALTER TABLE `hairstyle_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hairstyle_scores_hairstyle_id_foreign` (`hairstyle_id`),
  ADD KEY `hairstyle_scores_criterion_id_foreign` (`criterion_id`);

--
-- Indeks untuk tabel `hairstyle_style_preference`
--
ALTER TABLE `hairstyle_style_preference`
  ADD PRIMARY KEY (`hairstyle_id`,`style_preference_id`),
  ADD KEY `style_preference_id` (`style_preference_id`);

--
-- Indeks untuk tabel `hairstyle_tipe_rambut`
--
ALTER TABLE `hairstyle_tipe_rambut`
  ADD PRIMARY KEY (`hairstyle_id`,`tipe_rambut_id`),
  ADD KEY `tipe_rambut_id` (`tipe_rambut_id`);

--
-- Indeks untuk tabel `loyalties`
--
ALTER TABLE `loyalties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalties_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pairwise_comparisons_criterion_id_1_foreign` (`criterion_id_1`),
  ADD KEY `pairwise_comparisons_criterion_id_2_foreign` (`criterion_id_2`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_name_index` (`name`),
  ADD KEY `services_price_index` (`price`),
  ADD KEY `services_name_id_index` (`name_id`),
  ADD KEY `services_name_en_index` (`name_en`);

--
-- Indeks untuk tabel `style_preference`
--
ALTER TABLE `style_preference`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tipe_rambut`
--
ALTER TABLE `tipe_rambut`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_no_telepon_unique` (`no_telepon`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_no_telepon_index` (`no_telepon`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bentuk_kepala`
--
ALTER TABLE `bentuk_kepala`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `hairstyles`
--
ALTER TABLE `hairstyles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `hairstyle_scores`
--
ALTER TABLE `hairstyle_scores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT untuk tabel `loyalties`
--
ALTER TABLE `loyalties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `style_preference`
--
ALTER TABLE `style_preference`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tipe_rambut`
--
ALTER TABLE `tipe_rambut`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_hairstyle_id_foreign` FOREIGN KEY (`hairstyle_id`) REFERENCES `hairstyles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hairstyle_bentuk_kepala`
--
ALTER TABLE `hairstyle_bentuk_kepala`
  ADD CONSTRAINT `hairstyle_bentuk_kepala_ibfk_1` FOREIGN KEY (`hairstyle_id`) REFERENCES `hairstyles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hairstyle_bentuk_kepala_ibfk_2` FOREIGN KEY (`bentuk_kepala_id`) REFERENCES `bentuk_kepala` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hairstyle_scores`
--
ALTER TABLE `hairstyle_scores`
  ADD CONSTRAINT `hairstyle_scores_criterion_id_foreign` FOREIGN KEY (`criterion_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hairstyle_scores_hairstyle_id_foreign` FOREIGN KEY (`hairstyle_id`) REFERENCES `hairstyles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hairstyle_style_preference`
--
ALTER TABLE `hairstyle_style_preference`
  ADD CONSTRAINT `hairstyle_style_preference_ibfk_1` FOREIGN KEY (`hairstyle_id`) REFERENCES `hairstyles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hairstyle_style_preference_ibfk_2` FOREIGN KEY (`style_preference_id`) REFERENCES `style_preference` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hairstyle_tipe_rambut`
--
ALTER TABLE `hairstyle_tipe_rambut`
  ADD CONSTRAINT `hairstyle_tipe_rambut_ibfk_1` FOREIGN KEY (`hairstyle_id`) REFERENCES `hairstyles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hairstyle_tipe_rambut_ibfk_2` FOREIGN KEY (`tipe_rambut_id`) REFERENCES `tipe_rambut` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `loyalties`
--
ALTER TABLE `loyalties`
  ADD CONSTRAINT `loyalties_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  ADD CONSTRAINT `pairwise_comparisons_criterion_id_1_foreign` FOREIGN KEY (`criterion_id_1`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pairwise_comparisons_criterion_id_2_foreign` FOREIGN KEY (`criterion_id_2`) REFERENCES `criteria` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
