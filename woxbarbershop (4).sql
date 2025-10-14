-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 14 Okt 2025 pada 14.21
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `woxbarbershop`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bentuk_kepala`
--

CREATE TABLE `bentuk_kepala` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `hairstyle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `queue_number` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `payment_method` varchar(225) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `name`, `service_id`, `hairstyle_id`, `date_time`, `queue_number`, `description`, `payment_method`, `created_at`, `updated_at`, `total_price`, `status`) VALUES
(1, 49, 'Jane Smith', 4, NULL, '2025-10-13 04:04:58', 1, 'Potong rambut model terbaru yang kekinian', '', '2025-07-16 02:00:00', '2025-07-25 00:19:47', 35000.00, 'pending'),
(2, 57, 'Jennifer Rodriguez', 1, NULL, '2025-07-18 09:00:00', 2, 'Potong rambut model terbaru yang kekinian', '', '2025-07-16 08:00:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(3, 48, 'John Doe', 1, NULL, '2025-07-18 05:30:00', 3, 'Potong rambut model terbaru yang kekinian', '', '2025-07-15 14:30:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(4, 48, 'John Doe', 5, NULL, '2025-07-18 04:30:00', 4, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-16 16:30:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(5, 49, 'Jane Smith', 7, NULL, '2025-07-18 05:30:00', 5, 'Minta dipotong pendek di bagian samping', '', '2025-07-16 11:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(6, 55, 'Lisa Garcia', 6, NULL, '2025-07-18 05:00:00', 6, 'Gaya rambut untuk acara formal', '', '2025-07-17 08:00:00', '2025-07-25 00:19:47', 75000.00, 'completed'),
(7, 53, 'Emily Davis', 2, NULL, '2025-07-18 05:00:00', 7, 'Minta dipotong pendek di bagian samping', '', '2025-07-18 00:00:00', '2025-07-25 00:19:47', 50000.00, 'completed'),
(8, 48, 'John Doe', 5, NULL, '2025-07-18 09:00:00', 8, 'Potong rambut seperti biasa', '', '2025-07-17 06:00:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(9, 57, 'Jennifer Rodriguez', 8, NULL, '2025-07-19 06:00:00', 1, '', '', '2025-07-17 02:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(10, 51, 'Sarah Wilson', 7, NULL, '2025-07-19 05:00:00', 2, '', '', '2025-07-17 20:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(11, 50, 'Michael Johnson', 8, NULL, '2025-07-19 08:00:00', 3, 'Minta dipotong pendek di bagian samping', '', '2025-07-17 23:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(12, 53, 'Emily Davis', 8, NULL, '2025-07-19 08:00:00', 4, '', '', '2025-07-16 08:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(13, 49, 'Jane Smith', 2, NULL, '2025-07-19 07:00:00', 5, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-17 19:00:00', '2025-07-25 00:19:47', 50000.00, 'cancelled'),
(14, 51, 'Sarah Wilson', 2, NULL, '2025-07-19 07:30:00', 6, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-19 03:30:00', '2025-07-25 00:19:47', 50000.00, 'completed'),
(15, 55, 'Lisa Garcia', 7, NULL, '2025-07-19 05:30:00', 7, '', '', '2025-07-18 22:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(16, 48, 'John Doe', 8, NULL, '2025-07-21 09:30:00', 1, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-20 02:30:00', '2025-07-25 00:19:47', 30000.00, 'cancelled'),
(17, 56, 'Robert Martinez', 8, NULL, '2025-07-21 03:30:00', 2, '', '', '2025-07-20 02:30:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(18, 48, 'John Doe', 5, NULL, '2025-07-21 01:30:00', 3, 'Potong rambut seperti biasa', '', '2025-07-18 17:30:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(19, 48, 'John Doe', 8, NULL, '2025-07-21 05:00:00', 4, '', '', '2025-07-21 00:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(20, 57, 'Jennifer Rodriguez', 1, NULL, '2025-07-21 08:00:00', 5, '', '', '2025-07-19 20:00:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(21, 55, 'Lisa Garcia', 5, NULL, '2025-07-21 02:00:00', 6, 'Potong rambut model fade yang rapi', '', '2025-07-19 21:00:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(22, 56, 'Robert Martinez', 1, NULL, '2025-07-21 09:00:00', 7, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-19 01:00:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(23, 49, 'Jane Smith', 8, NULL, '2025-07-22 08:00:00', 1, 'Gaya rambut untuk acara formal', '', '2025-07-19 20:00:00', '2025-07-25 00:19:47', 30000.00, 'cancelled'),
(24, 57, 'Jennifer Rodriguez', 6, NULL, '2025-07-22 01:30:00', 2, '', '', '2025-07-20 05:30:00', '2025-07-25 00:19:47', 75000.00, 'cancelled'),
(25, 53, 'Emily Davis', 8, NULL, '2025-07-22 04:00:00', 3, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-21 01:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(26, 49, 'Jane Smith', 1, NULL, '2025-07-22 04:30:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-19 10:30:00', '2025-07-25 00:19:47', 25000.00, 'cancelled'),
(27, 57, 'Jennifer Rodriguez', 8, NULL, '2025-07-22 03:00:00', 5, 'Gaya rambut untuk acara formal', '', '2025-07-20 16:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(28, 53, 'Emily Davis', 7, NULL, '2025-07-22 01:00:00', 6, '', '', '2025-07-19 20:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(29, 50, 'Michael Johnson', 3, NULL, '2025-07-23 01:30:00', 1, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-20 05:30:00', '2025-07-25 00:19:47', 15000.00, 'completed'),
(30, 56, 'Robert Martinez', 7, NULL, '2025-07-23 03:00:00', 2, 'Gaya rambut untuk acara formal', '', '2025-07-20 13:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(31, 49, 'Jane Smith', 2, NULL, '2025-07-23 08:00:00', 3, '', '', '2025-07-20 10:00:00', '2025-07-25 00:19:47', 50000.00, 'completed'),
(32, 48, 'John Doe', 6, NULL, '2025-07-23 05:30:00', 4, 'Minta dipotong pendek di bagian samping', '', '2025-07-20 11:30:00', '2025-07-25 00:19:47', 75000.00, 'cancelled'),
(33, 52, 'David Brown', 5, NULL, '2025-07-23 09:00:00', 5, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-21 18:00:00', '2025-07-25 00:19:47', 20000.00, 'cancelled'),
(34, 48, 'John Doe', 7, NULL, '2025-07-23 09:00:00', 6, '', '', '2025-07-23 00:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(35, 51, 'Sarah Wilson', 4, NULL, '2025-07-23 09:00:00', 7, 'Potong rambut model terbaru yang kekinian', '', '2025-07-21 14:00:00', '2025-07-25 00:19:47', 35000.00, 'completed'),
(36, 56, 'Robert Martinez', 6, NULL, '2025-07-23 08:30:00', 8, 'Potong rambut seperti biasa', '', '2025-07-22 03:30:00', '2025-07-25 00:19:47', 75000.00, 'cancelled'),
(37, 49, 'Jane Smith', 3, NULL, '2025-07-24 03:00:00', 1, '', '', '2025-07-22 18:00:00', '2025-07-25 00:19:47', 15000.00, 'completed'),
(38, 52, 'David Brown', 7, NULL, '2025-07-24 01:30:00', 2, '', '', '2025-07-22 09:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(39, 53, 'Emily Davis', 7, NULL, '2025-07-24 06:30:00', 3, 'Potong rambut seperti biasa', '', '2025-07-21 20:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(40, 51, 'Sarah Wilson', 7, NULL, '2025-07-24 08:00:00', 4, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-24 05:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(41, 57, 'Jennifer Rodriguez', 7, NULL, '2025-07-24 04:00:00', 5, 'Gaya rambut untuk acara formal', '', '2025-07-23 07:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(42, 53, 'Emily Davis', 4, NULL, '2025-07-25 04:30:00', 1, 'Gaya rambut untuk acara formal', '', '2025-07-24 01:30:00', '2025-07-25 00:19:47', 35000.00, 'cancelled'),
(43, 56, 'Robert Martinez', 1, NULL, '2025-07-25 08:30:00', 2, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-25 04:30:00', '2025-07-25 00:19:47', 25000.00, 'cancelled'),
(44, 50, 'Michael Johnson', 6, NULL, '2025-07-25 06:00:00', 3, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-24 12:00:00', '2025-08-25 23:18:20', 75000.00, 'completed'),
(45, 54, 'Chris Miller', 7, NULL, '2025-07-25 05:30:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-22 11:30:00', '2025-07-25 05:00:18', 85000.00, 'completed'),
(46, 55, 'Lisa Garcia', 3, NULL, '2025-07-25 06:30:00', 5, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-24 04:30:00', '2025-07-30 01:02:39', 15000.00, 'completed'),
(47, 51, 'Sarah Wilson', 7, NULL, '2025-07-25 01:00:00', 6, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-24 18:00:00', '2025-07-25 00:19:47', 85000.00, 'confirmed'),
(48, 56, 'Robert Martinez', 8, NULL, '2025-07-25 04:00:00', 7, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-24 02:00:00', '2025-07-25 00:19:47', 30000.00, 'confirmed'),
(49, 53, 'Emily Davis', 1, NULL, '2025-07-26 03:00:00', 1, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-24 16:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(50, 56, 'Robert Martinez', 5, NULL, '2025-07-26 05:30:00', 2, '', '', '2025-07-25 19:30:00', '2025-07-25 00:19:47', 20000.00, 'pending'),
(51, 48, 'John Doe', 2, NULL, '2025-07-26 08:00:00', 3, 'Potong rambut model terbaru yang kekinian', '', '2025-07-23 21:00:00', '2025-07-25 00:19:47', 50000.00, 'pending'),
(52, 50, 'Michael Johnson', 3, NULL, '2025-07-28 07:00:00', 1, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-27 23:00:00', '2025-07-25 00:19:47', 15000.00, 'pending'),
(53, 50, 'Michael Johnson', 7, NULL, '2025-07-28 06:30:00', 2, 'Minta dipotong pendek di bagian samping', '', '2025-07-26 18:30:00', '2025-07-25 00:19:47', 85000.00, 'pending'),
(54, 50, 'Michael Johnson', 5, NULL, '2025-07-28 08:30:00', 3, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-25 18:30:00', '2025-07-25 00:19:47', 20000.00, 'pending'),
(55, 50, 'Michael Johnson', 1, NULL, '2025-07-28 09:00:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-25 23:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(56, 55, 'Lisa Garcia', 7, NULL, '2025-07-28 06:00:00', 5, '', '', '2025-07-27 20:00:00', '2025-07-30 01:02:42', 85000.00, 'completed'),
(57, 53, 'Emily Davis', 6, NULL, '2025-07-28 08:00:00', 6, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-27 17:00:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(58, 54, 'Chris Miller', 6, NULL, '2025-07-28 05:30:00', 7, '', '', '2025-07-27 20:30:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(59, 49, 'Jane Smith', 7, NULL, '2025-07-28 08:00:00', 8, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-26 06:00:00', '2025-07-25 00:19:47', 85000.00, 'pending'),
(60, 55, 'Lisa Garcia', 4, NULL, '2025-07-29 04:00:00', 1, '', '', '2025-07-28 00:00:00', '2025-07-30 01:02:44', 35000.00, 'completed'),
(61, 55, 'Lisa Garcia', 2, NULL, '2025-07-29 06:00:00', 2, 'Potong rambut model fade yang rapi', '', '2025-07-28 08:00:00', '2025-07-30 01:02:52', 50000.00, 'completed'),
(62, 48, 'John Doe', 8, NULL, '2025-07-29 06:30:00', 3, '', '', '2025-07-28 13:30:00', '2025-07-25 00:19:47', 30000.00, 'pending'),
(63, 50, 'Michael Johnson', 2, NULL, '2025-07-29 04:00:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-26 17:00:00', '2025-07-25 00:19:47', 50000.00, 'pending'),
(64, 48, 'John Doe', 1, NULL, '2025-07-29 01:30:00', 5, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-28 05:30:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(65, 57, 'Jennifer Rodriguez', 1, NULL, '2025-07-30 02:00:00', 1, 'Gaya rambut untuk acara formal', '', '2025-07-29 04:00:00', '2025-07-25 00:19:47', 25000.00, 'confirmed'),
(66, 50, 'Michael Johnson', 6, NULL, '2025-07-30 07:30:00', 2, '', '', '2025-07-28 21:30:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(67, 52, 'David Brown', 6, NULL, '2025-07-30 06:00:00', 3, '', '', '2025-07-28 00:00:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(68, 55, 'Lisa Garcia', 1, NULL, '2025-07-30 04:00:00', 4, '', '', '2025-07-30 02:00:00', '2025-07-30 01:02:54', 25000.00, 'completed'),
(69, 49, 'Jane Smith', 3, NULL, '2025-07-31 07:30:00', 1, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-30 19:30:00', '2025-07-25 00:19:47', 15000.00, 'pending'),
(70, 52, 'David Brown', 1, NULL, '2025-07-31 05:00:00', 2, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-30 05:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(71, 52, 'David Brown', 7, NULL, '2025-07-31 07:30:00', 3, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-30 15:30:00', '2025-07-25 00:19:47', 85000.00, 'pending'),
(72, 54, 'Chris Miller', 8, NULL, '2025-07-31 02:30:00', 4, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-28 21:30:00', '2025-07-25 00:19:47', 30000.00, 'confirmed'),
(73, 55, 'Lisa Garcia', 2, NULL, '2025-08-01 06:30:00', 1, 'Potong rambut seperti biasa', '', '2025-07-29 23:30:00', '2025-07-25 04:55:01', 50000.00, 'cancelled'),
(74, 50, 'Michael Johnson', 6, NULL, '2025-08-01 06:30:00', 2, 'Minta dipotong pendek di bagian samping', '', '2025-07-30 11:30:00', '2025-07-25 04:57:42', 75000.00, 'completed'),
(75, 48, 'John Doe', 1, NULL, '2025-08-01 02:00:00', 3, '', '', '2025-07-29 21:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(76, 56, 'Robert Martinez', 4, NULL, '2025-08-01 07:00:00', 4, 'Minta dipotong pendek di bagian samping', '', '2025-07-30 19:00:00', '2025-07-25 04:49:27', 35000.00, 'cancelled'),
(77, 51, 'Sarah Wilson', 7, NULL, '2025-08-01 04:00:00', 5, 'Ingin gaya rambut yang mudah diatur', '', '2025-08-01 00:00:00', '2025-08-25 20:19:17', 85000.00, 'completed'),
(78, 54, 'agungwahyu', 2, NULL, '2025-08-20 08:08:00', 1, 'dawdwdwada', '', '2025-07-26 00:04:05', '2025-07-30 22:57:18', 50000.00, 'completed'),
(79, 55, 'Salvador Smith', 1, NULL, '2025-07-29 08:17:00', 6, 'Hello', '', '2025-07-27 19:01:16', '2025-07-27 23:22:11', 25000.00, 'cancelled'),
(80, 55, 'Macey Hopper', 3, NULL, '2025-07-29 12:34:00', 7, 'Ut ea explicabo Off', '', '2025-07-27 23:28:12', '2025-07-27 23:29:36', 15000.00, 'cancelled'),
(81, 55, 'Tanya Bridges', 3, NULL, '2025-07-28 10:26:00', 9, 'Et error ea irure fa', '', '2025-07-27 23:35:23', '2025-07-27 23:43:17', 15000.00, 'cancelled'),
(82, 55, 'Blaze Neal', 8, NULL, '2025-07-28 10:56:00', 10, 'Unde ut porro repreh', '', '2025-07-27 23:43:09', '2025-07-29 00:25:17', 30000.00, 'cancelled'),
(83, 55, 'Phelan Hahn', 6, NULL, '2025-07-30 12:59:00', 5, 'Illo sint dolorum l', '', '2025-07-29 00:25:06', '2025-07-29 00:33:55', 75000.00, 'cancelled'),
(84, 55, 'Montana Acosta', 2, NULL, '2025-07-31 09:27:00', 5, 'Illo nostrud hic tem', '', '2025-07-29 00:35:30', '2025-07-30 01:04:52', 50000.00, 'completed'),
(85, 55, 'wide', 2, NULL, '2025-07-31 10:38:00', 6, 'fuck', '', '2025-07-29 01:08:47', '2025-07-30 01:04:50', 50000.00, 'completed'),
(86, 55, 'Hayes Rivera', 5, NULL, '2025-07-30 09:10:00', 6, 'Et sit qui ad ea el', '', '2025-07-30 00:55:44', '2025-07-30 01:04:56', 20000.00, 'completed'),
(87, 55, 'Lewis Newman', 5, NULL, '2025-07-31 09:37:00', 7, 'Adipisci voluptatem', '', '2025-07-30 22:16:51', '2025-07-30 22:44:25', 20000.00, 'completed'),
(88, 51, 'Gage Larsen', 1, NULL, '2025-07-31 12:40:00', 8, 'Eius iste sapiente n', '', '2025-07-30 22:51:20', '2025-07-30 22:51:20', 25000.00, 'completed'),
(89, 48, 'Agung', 1, NULL, '2025-08-04 06:00:00', 1, 'test', '', '2025-08-03 22:00:29', '2025-08-04 17:18:21', 25000.00, 'completed'),
(90, 48, 'Agung', 2, NULL, '2025-08-04 08:12:00', 2, 'dawdwdad', '', '2025-08-03 22:12:11', '2025-08-04 17:15:13', 50000.00, 'completed'),
(91, 55, 'Carson Frye', 4, NULL, '2025-08-07 12:19:00', 1, 'Enim error debitis i', '', '2025-08-06 20:56:31', '2025-08-07 00:38:32', 35000.00, 'completed'),
(92, 55, 'Tamara Martin', 3, NULL, '2025-08-07 09:08:00', 2, 'Ullam aut reiciendis', '', '2025-08-07 00:49:12', '2025-08-07 01:00:43', 15000.00, 'completed'),
(93, 55, 'Hedley Schneider', 1, NULL, '2025-08-09 12:26:00', 1, 'Aut vel consequatur', '', '2025-08-09 00:00:38', '2025-08-09 00:16:29', 25000.00, 'completed'),
(94, 55, 'Sade', 8, NULL, '2025-09-02 03:13:00', 1, 'Et et illo facilis d', '', '2025-09-01 20:26:59', '2025-09-17 07:16:38', 30000.00, 'completed'),
(95, 55, 'Nasim Odonnell', 1, NULL, '2025-09-18 05:54:00', 1, 'Ad fugit ea iste re', 'cash', '2025-09-17 05:44:18', '2025-09-17 06:25:14', 25000.00, 'completed'),
(96, 55, 'Cole Oneill', 5, NULL, '2025-09-18 09:55:00', 2, 'Do incidunt sed off', 'cash', '2025-09-17 06:30:34', '2025-09-17 06:46:04', 20000.00, 'completed'),
(97, 55, 'Callie Mccormick', 7, 43, '2025-09-30 08:57:00', 1, 'Doloremque repudiand', 'bank', '2025-09-30 06:31:54', '2025-09-30 07:08:10', 85000.00, 'completed'),
(98, 55, 'Alfreda Robbins', 2, 44, '2025-10-02 02:31:00', 1, 'Aute unde deserunt q', 'cash', '2025-09-30 20:25:31', '2025-09-30 20:25:31', 50000.00, 'completed'),
(99, 49, 'Ocean Odom', 8, 36, '2025-10-06 12:24:00', 1, 'In fugiat est sed', 'bank', '2025-10-04 07:18:52', '2025-10-04 17:37:08', 30000.00, 'completed'),
(100, 49, 'Arsenio Goodwin', 7, 42, '2025-10-08 10:36:00', 1, 'Incididunt pariatur', 'cash', '2025-10-04 07:20:43', '2025-10-04 07:22:09', 85000.00, 'completed'),
(101, 53, 'Freya Holt', 5, 34, '2025-10-06 07:34:00', 2, 'Dolores molestiae ea', 'cash', '2025-10-04 17:32:56', '2025-10-04 17:36:43', 20000.00, 'completed'),
(102, 77, 'Francis Weaver', 1, 34, '2025-10-07 06:07:48', 1, 'Dolore nobis nostrud', 'cash', '2025-10-06 22:05:54', '2025-10-06 22:07:48', 25000.00, 'in_progress'),
(103, 55, 'Lisa', 7, 41, '2025-10-07 06:11:41', 2, 'Dolor sed velit qui', 'cash', '2025-10-06 22:08:54', '2025-10-06 22:11:41', 85000.00, 'completed'),
(104, 55, 'Adrienne Jennings', 8, 45, '2025-10-08 03:42:19', 2, 'Magni tempor minus o', 'cash', '2025-10-07 19:41:36', '2025-10-07 19:42:19', 30000.00, 'completed'),
(105, 55, 'William Wooten', 2, 37, '2025-10-08 03:45:03', 3, 'In ipsum maiores do', 'cash', '2025-10-07 19:42:45', '2025-10-07 19:45:03', 50000.00, 'completed'),
(106, 55, 'Jack Cohen', 3, 39, '2025-10-08 03:45:07', 4, 'Necessitatibus reici', 'cash', '2025-10-07 19:43:08', '2025-10-07 19:45:07', 15000.00, 'completed'),
(107, 55, 'Xander Lee', 7, 46, '2025-10-08 03:45:11', 5, 'Ab sapiente sit mag', 'cash', '2025-10-07 19:43:34', '2025-10-07 19:45:11', 85000.00, 'completed'),
(108, 55, 'Callie Valenzuela', 2, 38, '2025-10-13 04:29:07', 6, 'Tempora dolorum obca', 'cash', '2025-10-07 19:44:04', '2025-10-07 19:46:19', 50000.00, 'completed'),
(109, 55, 'Laurel Mejia', 7, 40, '2025-10-13 04:31:25', 7, 'Iusto harum est maio', 'cash', '2025-10-12 20:30:46', '2025-10-12 20:30:46', 85000.00, 'completed');

-- --------------------------------------------------------

--
-- Struktur dari tabel `criteria`
--

CREATE TABLE `criteria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `weight` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `criteria`
--

INSERT INTO `criteria` (`id`, `name`, `weight`, `created_at`, `updated_at`) VALUES
(8, 'Bentuk Kepala', 0.50026636036223, '2025-09-25 01:31:39', '2025-10-12 20:30:33'),
(9, 'Tipe Rambut', 0.29976029562608, '2025-09-25 01:32:01', '2025-10-12 20:30:33'),
(10, 'Preferensi Gaya', 0.19997334401169, '2025-09-25 01:32:07', '2025-10-12 20:30:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `booking_id`, `rating`, `comment`, `is_public`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 55, 98, 5, 'nice banget', 1, 1, '2025-09-30 20:29:22', '2025-10-04 04:59:58'),
(5, 49, 100, 5, 'puas banget', 1, 1, '2025-10-04 07:32:10', '2025-10-04 07:32:10'),
(6, 74, 98, 5, 'Pengalaman cukur terbaik yang pernah saya dapatkan. Barber sangat profesional dan hasilnya sempurna!', 1, 1, '2025-09-09 17:29:33', '2025-09-06 17:29:33'),
(7, 49, 1, 5, 'Produk perawatan jenggot mereka sangat bagus. Jenggot saya jadi lebih sehat dan mudah diatur.', 1, 1, '2025-09-27 17:29:33', '2025-09-16 17:29:33'),
(8, 48, 3, 5, 'Atmosfernya sangat nyaman dan stafnya ramah. Potongan rambutnya selalu sesuai permintaan.', 1, 1, '2025-09-21 17:29:33', '2025-09-11 17:29:33'),
(9, 52, 38, 4, 'Layanan yang memuaskan dengan harga yang terjangkau. Tempat yang bersih dan nyaman.', 1, 1, '2025-09-10 17:29:33', '2025-09-18 17:29:33'),
(10, 49, 1, 5, 'Rekomendasi gaya rambut yang diberikan sangat cocok dengan bentuk wajah saya. Terima kasih!', 1, 1, '2025-09-20 17:29:33', '2025-09-04 17:29:33'),
(11, 74, 27, 4, 'Pelayanannya cepat dan hasil potongan rambutnya rapi. Akan kembali lagi pasti.', 1, 1, '2025-09-08 17:29:33', '2025-09-04 17:29:33'),
(12, 74, 96, 5, 'Wox Barbershop adalah barbershop terbaik di Gianyar. Highly recommended!', 1, 1, '2025-09-15 17:29:33', '2025-09-18 17:29:33'),
(13, 52, 38, 4, 'Staff yang ramah dan profesional. Potongan sesuai dengan yang diminta.', 1, 1, '2025-09-24 17:29:33', '2025-09-22 17:29:33'),
(14, 55, 108, 5, 'dwdw', 1, 1, '2025-10-08 22:42:50', '2025-10-08 22:42:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyles`
--

CREATE TABLE `hairstyles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hairstyles`
--

INSERT INTO `hairstyles` (`id`, `name`, `description`, `image`, `created_at`, `updated_at`) VALUES
(34, 'French Crop', 'Potongan rambut pendek dengan poni rata ke depan, cocok untuk wajah tegas.', 'french_crop.jpg', '2025-09-25 02:33:50', '2025-09-25 02:33:50'),
(36, 'Side Part', 'Rambut dibelah ke samping dengan garis jelas.', 'french_crop.jpg', '2025-09-25 06:06:27', '2025-09-29 20:21:04'),
(37, 'Pompadour', 'Rambut bagian atas panjang & bervolume, disisir ke belakang.', NULL, '2025-09-29 20:00:48', '2025-09-29 20:18:21'),
(38, 'Quiff', 'Bagian depan diangkat ke atas lalu diarahkan ke belakang/samping.', NULL, '2025-09-29 20:02:29', '2025-09-29 20:18:32'),
(39, 'Buzzcut', 'Potongan sangat pendek dengan clipper, seragam di seluruh kepala.', NULL, '2025-09-29 20:04:16', '2025-09-29 20:18:45'),
(40, 'Tapper Fade', 'rambut bagian samping & belakang dikecilkan perlahan (taper) lalu dibuat gradasi halus (fade).', NULL, '2025-09-29 20:05:13', '2025-09-29 20:22:06'),
(41, 'Crew Cut', 'Rambut atas lebih panjang (2–4 cm), samping lebih pendek.', NULL, '2025-09-29 20:06:34', '2025-09-29 20:19:05'),
(42, 'Undercut', 'Atas panjang, samping & belakang sangat pendek tanpa gradasi.', NULL, '2025-09-29 20:07:14', '2025-09-29 20:21:15'),
(43, 'Fringe', 'Poni jatuh ke dahi, bisa pendek atau panjang.', NULL, '2025-09-29 20:09:20', '2025-09-29 20:19:32'),
(44, 'Caesar Cut', 'Rambut atas pendek rata dengan poni pendek lurus ke depan.', NULL, '2025-09-29 20:10:22', '2025-09-29 20:20:52'),
(45, 'Side Swept Fringe', 'Poni panjang/pendek disapu ke samping.', NULL, '2025-09-29 20:12:03', '2025-09-29 20:20:18'),
(46, 'Long Fringe', 'Poni panjang jatuh sampai mata, bisa lurus, miring, atau messy.', NULL, '2025-09-29 20:14:50', '2025-09-29 20:19:43'),
(47, 'Textured Crop', 'Potongan pendek dengan tekstur acak di atas, poni pendek ke depan.', NULL, '2025-09-29 20:15:54', '2025-09-29 20:20:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_bentuk_kepala`
--

CREATE TABLE `hairstyle_bentuk_kepala` (
  `hairstyle_id` bigint(20) UNSIGNED NOT NULL,
  `bentuk_kepala_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hairstyle_bentuk_kepala`
--

INSERT INTO `hairstyle_bentuk_kepala` (`hairstyle_id`, `bentuk_kepala_id`) VALUES
(34, 1),
(34, 2),
(34, 3),
(34, 4),
(36, 3),
(36, 4),
(37, 1),
(37, 2),
(37, 6),
(38, 1),
(38, 6),
(39, 1),
(39, 2),
(39, 5),
(40, 2),
(40, 5),
(41, 5),
(42, 5),
(42, 6),
(43, 3),
(44, 3),
(45, 6),
(46, 4),
(47, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_scores`
--

CREATE TABLE `hairstyle_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hairstyle_id` bigint(20) UNSIGNED NOT NULL,
  `criterion_id` bigint(20) UNSIGNED NOT NULL,
  `score` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hairstyle_scores`
--

INSERT INTO `hairstyle_scores` (`id`, `hairstyle_id`, `criterion_id`, `score`, `created_at`, `updated_at`) VALUES
(52, 34, 8, 8.00, '2025-09-25 04:29:35', '2025-09-25 04:29:35'),
(53, 34, 9, 8.20, '2025-09-25 04:29:35', '2025-09-29 20:28:37'),
(54, 34, 10, 8.70, '2025-09-25 04:29:35', '2025-09-29 20:28:45'),
(55, 36, 8, 8.30, NULL, '2025-09-29 20:29:13'),
(56, 36, 9, 8.20, NULL, '2025-09-29 20:29:21'),
(57, 36, 10, 8.70, NULL, '2025-09-29 20:29:31'),
(58, 37, 8, 8.70, '2025-09-29 20:30:09', '2025-10-06 22:16:56'),
(59, 37, 9, 8.50, '2025-09-29 20:30:22', '2025-09-29 20:30:22'),
(60, 37, 10, 9.00, '2025-09-29 20:30:39', '2025-09-29 20:30:39'),
(61, 38, 8, 8.00, '2025-09-29 20:30:58', '2025-09-29 20:30:58'),
(62, 38, 9, 8.50, '2025-09-29 20:31:19', '2025-09-29 20:31:19'),
(63, 38, 10, 9.00, '2025-09-29 20:31:30', '2025-09-29 20:31:30'),
(64, 39, 8, 7.50, '2025-09-29 20:31:49', '2025-09-29 20:31:49'),
(65, 39, 9, 9.00, '2025-09-29 20:32:05', '2025-09-29 20:32:05'),
(66, 39, 10, 8.00, '2025-09-29 20:32:21', '2025-09-29 20:32:21'),
(67, 40, 8, 8.50, '2025-09-29 20:32:36', '2025-09-29 20:32:36'),
(68, 40, 9, 9.00, '2025-09-29 20:32:46', '2025-09-29 20:32:46'),
(69, 40, 10, 9.00, '2025-09-29 20:33:00', '2025-09-29 20:33:00'),
(70, 41, 8, 8.00, '2025-09-29 20:33:22', '2025-09-29 20:33:32'),
(71, 41, 9, 8.50, '2025-09-29 20:33:40', '2025-09-29 20:33:40'),
(72, 41, 10, 8.50, '2025-09-29 20:33:48', '2025-09-29 20:33:48'),
(73, 42, 8, 8.00, '2025-09-29 20:34:02', '2025-09-29 20:34:02'),
(74, 42, 9, 8.00, '2025-09-29 20:34:10', '2025-09-29 20:34:10'),
(75, 42, 10, 9.00, '2025-09-29 20:34:22', '2025-09-29 20:34:22'),
(76, 43, 8, 8.00, '2025-09-29 20:35:12', '2025-09-29 20:35:12'),
(77, 43, 9, 8.50, '2025-09-29 20:35:21', '2025-09-29 20:35:21'),
(78, 43, 10, 8.50, '2025-09-29 20:35:30', '2025-09-29 20:35:30'),
(79, 44, 8, 8.00, '2025-09-29 20:37:38', '2025-09-29 20:37:38'),
(80, 44, 9, 8.00, '2025-09-29 20:37:51', '2025-09-29 20:37:51'),
(81, 44, 10, 8.00, '2025-09-29 20:38:03', '2025-09-29 20:38:03'),
(82, 45, 8, 8.00, '2025-09-29 20:38:26', '2025-09-29 20:38:26'),
(83, 45, 9, 8.50, '2025-09-29 20:38:35', '2025-09-29 20:38:35'),
(84, 45, 10, 8.50, '2025-09-29 20:38:44', '2025-09-29 20:38:44'),
(85, 46, 8, 7.50, '2025-09-29 20:40:13', '2025-09-29 20:40:13'),
(86, 46, 9, 8.20, '2025-09-29 20:40:24', '2025-09-29 20:40:24'),
(87, 46, 10, 8.50, '2025-09-29 20:40:38', '2025-09-29 20:40:38'),
(88, 47, 8, 8.00, '2025-09-29 20:40:53', '2025-09-29 20:40:53'),
(89, 47, 9, 8.20, '2025-09-29 20:41:04', '2025-09-29 20:41:04'),
(90, 47, 10, 8.70, '2025-09-29 20:41:19', '2025-09-29 20:41:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_style_preference`
--

CREATE TABLE `hairstyle_style_preference` (
  `hairstyle_id` bigint(20) UNSIGNED NOT NULL,
  `style_preference_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hairstyle_style_preference`
--

INSERT INTO `hairstyle_style_preference` (`hairstyle_id`, `style_preference_id`) VALUES
(34, 1),
(34, 2),
(36, 1),
(36, 2),
(36, 3),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(39, 3),
(40, 1),
(40, 2),
(40, 3),
(41, 1),
(41, 2),
(41, 3),
(42, 1),
(42, 2),
(42, 3),
(43, 1),
(43, 2),
(43, 3),
(44, 1),
(44, 2),
(44, 3),
(45, 1),
(45, 2),
(45, 3),
(46, 1),
(46, 2),
(46, 3),
(47, 1),
(47, 2),
(47, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hairstyle_tipe_rambut`
--

CREATE TABLE `hairstyle_tipe_rambut` (
  `hairstyle_id` bigint(20) UNSIGNED NOT NULL,
  `tipe_rambut_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hairstyle_tipe_rambut`
--

INSERT INTO `hairstyle_tipe_rambut` (`hairstyle_id`, `tipe_rambut_id`) VALUES
(34, 1),
(34, 2),
(36, 1),
(36, 2),
(36, 3),
(37, 1),
(37, 2),
(37, 3),
(38, 1),
(38, 2),
(38, 3),
(39, 1),
(39, 2),
(39, 3),
(40, 1),
(40, 2),
(40, 3),
(41, 1),
(41, 2),
(41, 3),
(42, 1),
(42, 2),
(42, 3),
(43, 1),
(43, 2),
(43, 3),
(44, 1),
(44, 2),
(44, 3),
(45, 1),
(45, 2),
(45, 3),
(46, 1),
(46, 2),
(46, 3),
(47, 1),
(47, 2),
(47, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `loyalties`
--

CREATE TABLE `loyalties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `loyalties`
--

INSERT INTO `loyalties` (`id`, `user_id`, `points`, `updated_at`, `created_at`) VALUES
(11, 55, 10, '2025-10-07 19:46:45', '2025-08-07 01:00:43'),
(12, 51, 1, '2025-08-25 20:19:17', '2025-08-25 20:19:17'),
(13, 50, 1, '2025-08-25 23:18:20', '2025-08-25 23:18:20'),
(14, 49, 1, '2025-10-04 07:22:09', '2025-10-04 07:22:09'),
(15, 53, 2, '2025-10-04 17:36:43', '2025-10-04 17:35:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
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
(34, '2025_10_08_025134_remove_is_loyalty_redeem_from_bookings_table', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 45),
(2, 'App\\Models\\User', 46),
(2, 'App\\Models\\User', 47),
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
(70, 'App\\Models\\User', 45);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(30, 'App\\Models\\User', 45),
(30, 'App\\Models\\User', 60),
(31, 'App\\Models\\User', 46),
(31, 'App\\Models\\User', 47),
(31, 'App\\Models\\User', 75),
(32, 'App\\Models\\User', 48),
(32, 'App\\Models\\User', 49),
(32, 'App\\Models\\User', 50),
(32, 'App\\Models\\User', 51),
(32, 'App\\Models\\User', 52),
(32, 'App\\Models\\User', 53),
(32, 'App\\Models\\User', 54),
(32, 'App\\Models\\User', 55),
(32, 'App\\Models\\User', 56),
(32, 'App\\Models\\User', 57),
(32, 'App\\Models\\User', 71),
(32, 'App\\Models\\User', 72),
(32, 'App\\Models\\User', 73),
(32, 'App\\Models\\User', 74),
(32, 'App\\Models\\User', 76),
(32, 'App\\Models\\User', 77);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pairwise_comparisons`
--

CREATE TABLE `pairwise_comparisons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `criterion_id_1` bigint(20) UNSIGNED NOT NULL,
  `criterion_id_2` bigint(20) UNSIGNED NOT NULL,
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
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('wahyubrahmantha05@gmail.com', '$2y$12$qKhVh0k0S6v7Fob6rv2iiur7MSThrsfhHeK5wORlFqHQYXZ2vexBG', '2025-10-06 02:31:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Hair Shampoo for Men', 'Shampoo khusus pria yang diformulasikan untuk mengatasi ketombe dan menjaga kebersihan rambut secara menyeluruh. Menyegarkan dan memberikan kelembutan pada rambut tanpa membuatnya kering.', 120000.00, 'Perawatan Rambut', 'products/OmJujD7IZAkrd114MQS8etPD6sFNQ1yDI5iXQ0pL.png', 50, 1, NULL, '2025-10-04 08:05:59'),
(4, 'Beard Oil - Classic Scent', 'Minyak jenggot dengan aroma klasik yang membantu melembutkan dan merawat jenggot Anda. Membantu mengurangi kerusakan dan kekeringan pada jenggot serta memberikan kilau alami.', 150000.00, 'Perawatan Jenggot', 'products/2l6BYQiSfAQbiss80srtjpqJF6VNDzMp3vNlmCcI.png', 30, 1, NULL, '2025-10-04 08:03:53'),
(5, 'Pomade Strong Hold', 'Pomade dengan daya tahan kuat, cocok untuk styling rambut seperti pompadour atau slick-back. Memberikan hasil akhir yang glossy dan tahan sepanjang hari.', 100000.00, 'Perawatan Rambut', 'products/98SRanW4tj6sOFluwqdkqqU8ShwIGsaULEOaqMVi.png', 40, 1, NULL, '2025-10-04 07:58:57'),
(6, 'Aftershave Lotion - Cooling Effect', 'Lotion aftershave dengan efek pendingin yang menenangkan kulit setelah bercukur. Membantu mengurangi iritasi dan memberikan kelembutan pada kulit.', 80000.00, 'Perawatan Kulit', 'products/joYBiXBVICB5KNpLyAS9laChawDEyszQu33BMY9W.png', 60, 1, NULL, '2025-10-04 07:59:20'),
(7, 'Beard Balm - Conditioning', 'Balm jenggot yang melembapkan dan merawat jenggot sekaligus menjaga bentuknya tetap rapi. Mengandung bahan alami yang menutrisi jenggot dan kulit di bawahnya.', 130000.00, 'Perawatan Jenggot', 'products/3wcqd81WoPLg28Qd7qApoO5UZgYbFWJ4WEOznQxE.png', 25, 1, NULL, '2025-10-04 08:01:15'),
(8, 'Hair Clipper - Pro Series', 'Mesin pemotong rambut dengan motor yang kuat dan presisi tinggi. Dilengkapi dengan beberapa ukuran pisau untuk berbagai jenis potongan rambut.', 500000.00, 'Peralatan Barbershop', 'products/LalC7J84fr1xcWlPT4igjpPUH4EdTxyLecu4yURU.png', 15, 1, NULL, '2025-10-04 08:05:07'),
(9, 'Shaving Razor - Stainless Steel', 'Pisau cukur manual berbahan stainless steel yang memberikan hasil cukur presisi dan tajam. Ideal untuk penggunaan profesional di barbershop.', 200000.00, 'Peralatan Barbershop', 'products/VhY69VUtNNPTfQ7Kc52My7YZc7owJGaPuXd2YKBI.png', 20, 1, NULL, '2025-10-04 08:06:35'),
(10, 'Beard Comb - Wood', 'Sisir jenggot berbahan kayu yang memberikan kenyamanan saat merapikan jenggot tanpa merusak serat rambut.', 50000.00, 'Aksesori Barbershop', 'products/fstxCip4i4XIiEF2EwGvPC8PESLQstI9FkJLRZcQ.png', 75, 1, NULL, '2025-10-04 08:03:37'),
(11, 'Barber Apron - Premium', 'Apron berkualitas premium untuk barbershop, terbuat dari bahan tahan lama yang nyaman dipakai, dengan kantong besar untuk menyimpan alat cukur dan aksesori lainnya.', 250000.00, 'Aksesori Barbershop', 'products/QYPk19ORt5w6XU0HPHirGnZCdoJSZNURzUrLYFyP.png', 20, 1, NULL, '2025-10-04 08:00:28'),
(12, 'Facial Cleanser for Men', 'Pembersih wajah khusus pria yang menghilangkan kotoran dan minyak berlebih tanpa mengeringkan kulit. Menjaga keseimbangan pH dan memberi kesegaran sepanjang hari.', 95000.00, 'Perawatan Kulit', 'products/y6ffWUaLmXpyMVGud1jq6WD5kKxMj3lSeUK3g8mG.png', 45, 1, NULL, '2025-10-04 08:04:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `recommendations`
--

CREATE TABLE `recommendations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
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
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 30),
(3, 30),
(3, 31),
(4, 30),
(5, 30),
(5, 31),
(6, 30),
(7, 30),
(8, 30),
(9, 30),
(10, 30),
(10, 31),
(11, 30),
(12, 30),
(13, 30),
(14, 30),
(15, 30),
(15, 31),
(15, 32),
(16, 30),
(16, 32),
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
(30, 31),
(30, 32),
(31, 30),
(31, 31),
(31, 32),
(32, 30),
(32, 31),
(32, 32),
(33, 30),
(34, 30),
(35, 30),
(35, 31),
(36, 30),
(36, 31),
(36, 32),
(37, 30),
(37, 31),
(38, 30),
(38, 31),
(39, 30),
(40, 30),
(40, 31),
(40, 32),
(41, 30),
(42, 30),
(43, 30),
(44, 30),
(45, 30),
(46, 30),
(47, 30),
(47, 31),
(48, 30),
(48, 31),
(48, 32),
(49, 30),
(49, 31),
(50, 30),
(51, 30),
(52, 30),
(52, 31),
(53, 30),
(54, 30),
(54, 31),
(55, 30),
(56, 30),
(57, 30),
(57, 31),
(57, 32),
(58, 30),
(58, 31),
(59, 30),
(60, 30),
(61, 30),
(62, 30),
(62, 31),
(63, 30),
(63, 32),
(64, 30),
(65, 30),
(66, 30),
(66, 31),
(67, 30),
(68, 30),
(69, 30),
(70, 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `duration` int(11) NOT NULL DEFAULT 30 COMMENT 'Duration in minutes',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `duration`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Potong Rambut Regular', 'Potong rambut standar dengan gaya klasik', 25000.00, 30, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(2, 'Potong Rambut Premium', 'Potong rambut dengan konsultasi styling dan finishing premium', 50000.00, 45, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(3, 'Shaving/Cukur', 'Cukur bersih dengan pisau cukur profesional', 15000.00, 20, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(4, 'Hair Wash & Styling', 'Cuci rambut dengan shampo khusus dan styling sesuai keinginan', 35000.00, 40, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(5, 'Beard Trimming', 'Rapikan jenggot dan kumis dengan gaya modern', 20000.00, 25, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(6, 'Hair Treatment', 'Perawatan rambut dengan vitamin dan nutrisi khusus', 75000.00, 60, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(7, 'Complete Package', 'Paket lengkap: potong rambut, cukur, cuci, dan styling', 85000.00, 90, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18'),
(8, 'Kids Haircut', 'Potong rambut khusus anak-anak dengan kesabaran extra', 30000.00, 35, 1, '2025-07-25 00:19:18', '2025-07-25 00:19:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `style_preference`
--

CREATE TABLE `style_preference` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `transaction_status` varchar(50) NOT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `gross_amount` decimal(10,2) DEFAULT NULL,
  `transaction_time` timestamp NULL DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `va_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `transaction_status`, `payment_type`, `gross_amount`, `transaction_time`, `bank`, `va_number`, `created_at`, `updated_at`, `name`, `email`) VALUES
(2, '89', 'settlement', 'bank_transfer', 25000.00, '2025-08-04 05:09:57', 'bca', '35797291880706315963357', '2025-08-03 22:02:53', '2025-08-03 22:10:11', NULL, NULL),
(3, '90', 'settlement', 'bank_transfer', 50000.00, '2025-08-04 05:12:18', 'bca', '35797302236278579244493', '2025-08-03 22:12:16', '2025-08-03 22:14:43', NULL, NULL),
(4, '95', 'settlement', 'cash', 25000.00, '2025-09-17 06:25:14', NULL, NULL, '2025-09-17 06:25:14', '2025-09-17 06:25:14', 'Nasim Odonnell', 'lisa@example.com'),
(5, '96', 'settlement', 'cash', 20000.00, '2025-09-17 06:30:40', NULL, NULL, '2025-09-17 06:30:40', '2025-09-17 06:46:04', 'Cole Oneill', 'lisa@example.com'),
(6, '98', 'settlement', 'cash', 50000.00, '2025-09-30 20:26:08', NULL, NULL, '2025-09-30 20:26:08', '2025-09-30 20:33:29', 'Alfreda Robbins', 'lisa@example.com'),
(7, '99', 'settlement', NULL, 30000.00, '2025-10-04 07:18:58', NULL, NULL, '2025-10-04 07:18:58', '2025-10-04 17:37:08', 'Ocean Odom', 'jane@example.com'),
(8, '100', 'settlement', 'cash', 85000.00, '2025-10-04 07:20:47', NULL, NULL, '2025-10-04 07:20:47', '2025-10-04 17:37:05', 'Arsenio Goodwin', 'jane@example.com'),
(9, '101', 'settlement', 'cash', 20000.00, '2025-10-04 17:36:03', NULL, NULL, '2025-10-04 17:36:03', '2025-10-04 17:36:49', 'Freya Holt', 'emily@example.com'),
(10, '103', 'settlement', 'cash', 85000.00, '2025-10-06 22:11:24', NULL, NULL, '2025-10-06 22:11:24', '2025-10-06 22:11:53', 'Lisa', 'lisa@example.com'),
(11, '104', 'settlement', 'cash', 30000.00, '2025-10-07 19:42:08', NULL, NULL, '2025-10-07 19:42:08', '2025-10-07 19:42:19', 'Adrienne Jennings', 'lisa@example.com'),
(12, '105', 'settlement', 'cash', 50000.00, '2025-10-07 19:44:14', NULL, NULL, '2025-10-07 19:44:14', '2025-10-07 19:45:03', 'William Wooten', 'lisa@example.com'),
(13, '106', 'settlement', 'cash', 15000.00, '2025-10-07 19:44:27', NULL, NULL, '2025-10-07 19:44:27', '2025-10-07 19:45:07', 'Jack Cohen', 'lisa@example.com'),
(14, '107', 'settlement', 'cash', 85000.00, '2025-10-07 19:44:42', NULL, NULL, '2025-10-07 19:44:42', '2025-10-07 19:45:11', 'Xander Lee', 'lisa@example.com'),
(15, '108', 'settlement', 'cash', 50000.00, '2025-10-07 19:46:02', NULL, NULL, '2025-10-07 19:46:02', '2025-10-13 04:31:35', 'Callie Valenzuela', 'lisa@example.com'),
(16, '109', 'settlement', 'cash', 85000.00, '2025-10-12 20:30:54', NULL, NULL, '2025-10-12 20:30:54', '2025-10-13 04:31:41', 'Laurel Mejia', 'lisa@example.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_telepon` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `no_telepon`, `email_verified_at`, `password`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(45, 'Super Admin', 'admin@woxbarbershop.com', '081234567890', '2025-07-25 00:19:36', '$2y$12$cESMekqUEtM9A9wXvleli.RjgbArHEu3hFLwO2ez8LX43dbMsCwoe', 1, '2025-10-13 22:23:36', NULL, '2025-07-25 00:19:36', '2025-10-13 22:23:36'),
(46, 'Barber Ahmad', 'ahmad@woxbarbershop.com', '081234567891', '2025-07-25 00:19:36', '$2y$12$cniTOFtL6o.bBpETghYmTekFTYuvOnQrJOwA8miSePOKHRDmB6fRe', 1, '2025-09-16 23:43:40', NULL, '2025-07-25 00:19:36', '2025-09-16 23:43:40'),
(47, 'Barber Budi', 'budi@woxbarbershop.com', '081234567892', '2025-07-25 00:19:37', '$2y$12$Nve5vdVIzWRXxHOlOEbYRuNj.57iZAllDKuijvEcF64/mKmpykssC', 1, NULL, NULL, '2025-07-25 00:19:37', '2025-07-25 00:19:37'),
(48, 'John Doe', 'john@example.com', '089876543210', '2025-07-25 00:19:39', '$2y$12$vfTnRqxQ9NqvPteFVqePlepm2fQn6BURrjpWttdcCNYLQBrDKO0X2', 1, '2025-09-08 08:03:32', NULL, '2025-07-25 00:19:39', '2025-09-08 08:03:32'),
(49, 'Jane Smith', 'jane@example.com', '089876543211', '2025-07-25 00:19:39', '$2y$12$zzWsm99NrpF2CucW..CO6eql2YBLRMgX.FYRiMKC473YqBoX076vG', 1, '2025-10-04 07:17:16', NULL, '2025-07-25 00:19:39', '2025-10-04 07:17:16'),
(50, 'Michael Johnson', 'michael@example.com', '089876543212', '2025-07-25 00:19:39', '$2y$12$xbvZZSop3xqTxOoFWBy/pejPJE5nt1sESZdwwcrfo0vYcCFKkhwPC', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(51, 'Sarah Wilson', 'sarah@example.com', '089876543213', '2025-07-25 00:19:39', '$2y$12$QNKmTBLxw8c12QdLIUngR.SKsxnfEfTJOYA5EqNMpEKpNbjUgcAdW', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(52, 'David Brown', 'david@example.com', '089876543214', '2025-07-25 00:19:39', '$2y$12$O2L/jT592hvZi01hg46Lue.ZP0ayp/6VpGtMFvAi1x5.36RKrywgu', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(53, 'Emily Davis', 'emily@example.com', '089876543215', '2025-07-25 00:19:39', '$2y$12$IbRKsN3Zt3iAVrArvDzowOR/2c3dK7R7s6UGbMbRt7kPw6RtNCq.6', 1, '2025-10-04 17:24:47', NULL, '2025-07-25 00:19:39', '2025-10-04 17:24:47'),
(54, 'Chris Miller', 'chris@example.com', '089876543216', '2025-07-25 00:19:39', '$2y$12$8ay1PTxI0MxlsKfVHA9D2O1Y3.zNjHykI6l6xtO2r5cO/OQ.GQBcK', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(55, 'Lisa Garcia', 'lisa@example.com', '089876543217', '2025-07-25 00:19:39', '$2y$12$N2y0lNbY6.1SXO3zPCDDlu/rh4JmMDOR7gXWly9a7cDhuEUlP3nHy', 1, '2025-10-12 19:22:38', 'foie40L8gKjVtKVbxlE71twuHFqHedJK2D3mswY7dsf5bK2VMIKoXua0bVJy', '2025-07-25 00:19:39', '2025-10-12 19:22:38'),
(56, 'Robert Martinez', 'robert@example.com', '089876543218', '2025-07-25 00:19:39', '$2y$12$m4AN0yhjvmtMZpRghXVAUeffv8EByE1wbAcOijqye7Knx5RGMij8e', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(57, 'Jennifer Rodriguez', 'jennifer@example.com', '089876543219', '2025-07-25 00:19:39', '$2y$12$9tr3qq7GwRIwQOmAwfXEl.dmli0KlGNdgiNyOb.Q3uDRKIgJj0efu', 1, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(60, 'aagungwahyu', 'aagungwwahyu05@gmail.com', '081239339998', '2025-08-13 08:23:03', '$2y$12$UX3sYEfsBfcbj7XzO4K3eOokagJPYcXn83OPz4jVilhZyewsEQa2O', 1, NULL, NULL, '2025-08-12 19:27:07', '2025-09-10 04:02:31'),
(71, 'Tanisha Mccarty', 'jesub@mailinator.com', '12', '2025-08-12 23:31:47', '$2y$12$6r9Gs32ZdiUNFxv.PoNob.Hkw5Y0KjB5GpDTAqrncQRuCnjCd44cW', 1, NULL, NULL, '2025-08-12 23:31:08', '2025-08-12 23:31:47'),
(72, 'Melyssa Gaines', 'fologuf@mailinator.com', '37', '2025-08-12 23:38:34', '$2y$12$86DPDKHWNtzJc1AKFecihOK.8EbqjkdqcMGVJAXQX2KwfiSEJqsfW', 1, NULL, NULL, '2025-08-12 23:32:57', '2025-08-12 23:38:34'),
(73, 'Anak Agung Rai', 'rai@gmail.com', '081223876889', '2025-08-13 00:30:28', '$2y$12$lIZrDjzq0mTK85u2mOJLqO586Z5tgzae504uZMkLHkeDc4rvdbJvq', 1, NULL, NULL, '2025-08-13 00:26:14', '2025-08-13 00:30:28'),
(74, 'Hop Ball', 'wujesul@mailinator.com', '43', '2025-08-13 00:37:05', '$2y$12$w6ruT7lAOYdJMhCRNIsadO9/dsyvC/.URv0vzC0yCTukeUdJSJdkG', 1, NULL, NULL, '2025-08-13 00:33:55', '2025-08-13 00:37:05'),
(75, 'Dora White', 'motelaxeq@mailinator.com', '17649939373', '2025-09-10 03:51:27', '$2y$12$Nlse7OrNeYZTLETKsKmqZ.7zk4J6YbjMcmG15RIqczTAiFO5Pe9Ma', 1, NULL, NULL, '2025-09-10 03:50:42', '2025-09-10 03:51:27'),
(76, 'I Dewa Gede Agung Wahyu Brahmantha', 'wahyubrahmantha05@gmail.com', '081239261344', '2025-10-06 02:31:17', '$2y$12$gKOgfqr17YIqWc0e/axwJObkEADmx6XC7Vr8TfuvCcra7URQklye6', 1, '2025-10-06 02:54:37', NULL, '2025-10-06 02:30:38', '2025-10-06 02:54:37'),
(77, 'Pande Pertama', 'pande@gmail.com', '081234567555', '2025-10-06 21:59:48', '$2y$12$wzJ1ld5kRXHI6SZCa0E8uOmgpJUcNp1ll2hFcf9oWoTM7w3CKjV5m', 1, NULL, NULL, '2025-10-06 21:59:23', '2025-10-06 21:59:48'),
(78, 'Test Customer Loyalty', 'testloyalty@test.com', '08123456789', '2025-10-07 18:34:29', '$2y$12$1Vhr5DdOY7hmPmk402QT2./nqhWGONY7USEwv1u/qgl7ihZytbyXm', 1, NULL, NULL, '2025-10-07 18:34:29', '2025-10-07 18:34:29');

--
-- Indexes for dumped tables
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
  ADD KEY `bookings_user_id_index` (`user_id`);

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
-- Indeks untuk tabel `recommendations`
--
ALTER TABLE `recommendations`
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
  ADD KEY `services_price_index` (`price`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT untuk tabel `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `hairstyles`
--
ALTER TABLE `hairstyles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `hairstyle_scores`
--
ALTER TABLE `hairstyle_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT untuk tabel `loyalties`
--
ALTER TABLE `loyalties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `style_preference`
--
ALTER TABLE `style_preference`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tipe_rambut`
--
ALTER TABLE `tipe_rambut`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

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
