-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 30 Sep 2025 pada 14.21
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
  `date_time` datetime NOT NULL,
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
(1, 49, 'Jane Smith', 4, NULL, '2025-07-18 09:00:00', 1, 'Potong rambut model terbaru yang kekinian', '', '2025-07-16 02:00:00', '2025-07-25 00:19:47', 35000.00, 'completed'),
(2, 57, 'Jennifer Rodriguez', 1, NULL, '2025-07-18 17:00:00', 2, 'Potong rambut model terbaru yang kekinian', '', '2025-07-16 08:00:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(3, 48, 'John Doe', 1, NULL, '2025-07-18 13:30:00', 3, 'Potong rambut model terbaru yang kekinian', '', '2025-07-15 14:30:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(4, 48, 'John Doe', 5, NULL, '2025-07-18 12:30:00', 4, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-16 16:30:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(5, 49, 'Jane Smith', 7, NULL, '2025-07-18 13:30:00', 5, 'Minta dipotong pendek di bagian samping', '', '2025-07-16 11:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(6, 55, 'Lisa Garcia', 6, NULL, '2025-07-18 13:00:00', 6, 'Gaya rambut untuk acara formal', '', '2025-07-17 08:00:00', '2025-07-25 00:19:47', 75000.00, 'completed'),
(7, 53, 'Emily Davis', 2, NULL, '2025-07-18 13:00:00', 7, 'Minta dipotong pendek di bagian samping', '', '2025-07-18 00:00:00', '2025-07-25 00:19:47', 50000.00, 'completed'),
(8, 48, 'John Doe', 5, NULL, '2025-07-18 17:00:00', 8, 'Potong rambut seperti biasa', '', '2025-07-17 06:00:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(9, 57, 'Jennifer Rodriguez', 8, NULL, '2025-07-19 14:00:00', 1, '', '', '2025-07-17 02:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(10, 51, 'Sarah Wilson', 7, NULL, '2025-07-19 13:00:00', 2, '', '', '2025-07-17 20:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(11, 50, 'Michael Johnson', 8, NULL, '2025-07-19 16:00:00', 3, 'Minta dipotong pendek di bagian samping', '', '2025-07-17 23:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(12, 53, 'Emily Davis', 8, NULL, '2025-07-19 16:00:00', 4, '', '', '2025-07-16 08:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(13, 49, 'Jane Smith', 2, NULL, '2025-07-19 15:00:00', 5, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-17 19:00:00', '2025-07-25 00:19:47', 50000.00, 'cancelled'),
(14, 51, 'Sarah Wilson', 2, NULL, '2025-07-19 15:30:00', 6, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-19 03:30:00', '2025-07-25 00:19:47', 50000.00, 'completed'),
(15, 55, 'Lisa Garcia', 7, NULL, '2025-07-19 13:30:00', 7, '', '', '2025-07-18 22:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(16, 48, 'John Doe', 8, NULL, '2025-07-21 17:30:00', 1, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-20 02:30:00', '2025-07-25 00:19:47', 30000.00, 'cancelled'),
(17, 56, 'Robert Martinez', 8, NULL, '2025-07-21 11:30:00', 2, '', '', '2025-07-20 02:30:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(18, 48, 'John Doe', 5, NULL, '2025-07-21 09:30:00', 3, 'Potong rambut seperti biasa', '', '2025-07-18 17:30:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(19, 48, 'John Doe', 8, NULL, '2025-07-21 13:00:00', 4, '', '', '2025-07-21 00:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(20, 57, 'Jennifer Rodriguez', 1, NULL, '2025-07-21 16:00:00', 5, '', '', '2025-07-19 20:00:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(21, 55, 'Lisa Garcia', 5, NULL, '2025-07-21 10:00:00', 6, 'Potong rambut model fade yang rapi', '', '2025-07-19 21:00:00', '2025-07-25 00:19:47', 20000.00, 'completed'),
(22, 56, 'Robert Martinez', 1, NULL, '2025-07-21 17:00:00', 7, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-19 01:00:00', '2025-07-25 00:19:47', 25000.00, 'completed'),
(23, 49, 'Jane Smith', 8, NULL, '2025-07-22 16:00:00', 1, 'Gaya rambut untuk acara formal', '', '2025-07-19 20:00:00', '2025-07-25 00:19:47', 30000.00, 'cancelled'),
(24, 57, 'Jennifer Rodriguez', 6, NULL, '2025-07-22 09:30:00', 2, '', '', '2025-07-20 05:30:00', '2025-07-25 00:19:47', 75000.00, 'cancelled'),
(25, 53, 'Emily Davis', 8, NULL, '2025-07-22 12:00:00', 3, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-21 01:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(26, 49, 'Jane Smith', 1, NULL, '2025-07-22 12:30:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-19 10:30:00', '2025-07-25 00:19:47', 25000.00, 'cancelled'),
(27, 57, 'Jennifer Rodriguez', 8, NULL, '2025-07-22 11:00:00', 5, 'Gaya rambut untuk acara formal', '', '2025-07-20 16:00:00', '2025-07-25 00:19:47', 30000.00, 'completed'),
(28, 53, 'Emily Davis', 7, NULL, '2025-07-22 09:00:00', 6, '', '', '2025-07-19 20:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(29, 50, 'Michael Johnson', 3, NULL, '2025-07-23 09:30:00', 1, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-20 05:30:00', '2025-07-25 00:19:47', 15000.00, 'completed'),
(30, 56, 'Robert Martinez', 7, NULL, '2025-07-23 11:00:00', 2, 'Gaya rambut untuk acara formal', '', '2025-07-20 13:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(31, 49, 'Jane Smith', 2, NULL, '2025-07-23 16:00:00', 3, '', '', '2025-07-20 10:00:00', '2025-07-25 00:19:47', 50000.00, 'completed'),
(32, 48, 'John Doe', 6, NULL, '2025-07-23 13:30:00', 4, 'Minta dipotong pendek di bagian samping', '', '2025-07-20 11:30:00', '2025-07-25 00:19:47', 75000.00, 'cancelled'),
(33, 52, 'David Brown', 5, NULL, '2025-07-23 17:00:00', 5, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-21 18:00:00', '2025-07-25 00:19:47', 20000.00, 'cancelled'),
(34, 48, 'John Doe', 7, NULL, '2025-07-23 17:00:00', 6, '', '', '2025-07-23 00:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(35, 51, 'Sarah Wilson', 4, NULL, '2025-07-23 17:00:00', 7, 'Potong rambut model terbaru yang kekinian', '', '2025-07-21 14:00:00', '2025-07-25 00:19:47', 35000.00, 'completed'),
(36, 56, 'Robert Martinez', 6, NULL, '2025-07-23 16:30:00', 8, 'Potong rambut seperti biasa', '', '2025-07-22 03:30:00', '2025-07-25 00:19:47', 75000.00, 'cancelled'),
(37, 49, 'Jane Smith', 3, NULL, '2025-07-24 11:00:00', 1, '', '', '2025-07-22 18:00:00', '2025-07-25 00:19:47', 15000.00, 'completed'),
(38, 52, 'David Brown', 7, NULL, '2025-07-24 09:30:00', 2, '', '', '2025-07-22 09:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(39, 53, 'Emily Davis', 7, NULL, '2025-07-24 14:30:00', 3, 'Potong rambut seperti biasa', '', '2025-07-21 20:30:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(40, 51, 'Sarah Wilson', 7, NULL, '2025-07-24 16:00:00', 4, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-24 05:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(41, 57, 'Jennifer Rodriguez', 7, NULL, '2025-07-24 12:00:00', 5, 'Gaya rambut untuk acara formal', '', '2025-07-23 07:00:00', '2025-07-25 00:19:47', 85000.00, 'completed'),
(42, 53, 'Emily Davis', 4, NULL, '2025-07-25 12:30:00', 1, 'Gaya rambut untuk acara formal', '', '2025-07-24 01:30:00', '2025-07-25 00:19:47', 35000.00, 'cancelled'),
(43, 56, 'Robert Martinez', 1, NULL, '2025-07-25 16:30:00', 2, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-25 04:30:00', '2025-07-25 00:19:47', 25000.00, 'cancelled'),
(44, 50, 'Michael Johnson', 6, NULL, '2025-07-25 14:00:00', 3, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-24 12:00:00', '2025-08-25 23:18:20', 75000.00, 'completed'),
(45, 54, 'Chris Miller', 7, NULL, '2025-07-25 13:30:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-22 11:30:00', '2025-07-25 05:00:18', 85000.00, 'completed'),
(46, 55, 'Lisa Garcia', 3, NULL, '2025-07-25 14:30:00', 5, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-24 04:30:00', '2025-07-30 01:02:39', 15000.00, 'completed'),
(47, 51, 'Sarah Wilson', 7, NULL, '2025-07-25 09:00:00', 6, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-24 18:00:00', '2025-07-25 00:19:47', 85000.00, 'confirmed'),
(48, 56, 'Robert Martinez', 8, NULL, '2025-07-25 12:00:00', 7, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-24 02:00:00', '2025-07-25 00:19:47', 30000.00, 'confirmed'),
(49, 53, 'Emily Davis', 1, NULL, '2025-07-26 11:00:00', 1, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-24 16:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(50, 56, 'Robert Martinez', 5, NULL, '2025-07-26 13:30:00', 2, '', '', '2025-07-25 19:30:00', '2025-07-25 00:19:47', 20000.00, 'pending'),
(51, 48, 'John Doe', 2, NULL, '2025-07-26 16:00:00', 3, 'Potong rambut model terbaru yang kekinian', '', '2025-07-23 21:00:00', '2025-07-25 00:19:47', 50000.00, 'pending'),
(52, 50, 'Michael Johnson', 3, NULL, '2025-07-28 15:00:00', 1, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-27 23:00:00', '2025-07-25 00:19:47', 15000.00, 'pending'),
(53, 50, 'Michael Johnson', 7, NULL, '2025-07-28 14:30:00', 2, 'Minta dipotong pendek di bagian samping', '', '2025-07-26 18:30:00', '2025-07-25 00:19:47', 85000.00, 'pending'),
(54, 50, 'Michael Johnson', 5, NULL, '2025-07-28 16:30:00', 3, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-25 18:30:00', '2025-07-25 00:19:47', 20000.00, 'pending'),
(55, 50, 'Michael Johnson', 1, NULL, '2025-07-28 17:00:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-25 23:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(56, 55, 'Lisa Garcia', 7, NULL, '2025-07-28 14:00:00', 5, '', '', '2025-07-27 20:00:00', '2025-07-30 01:02:42', 85000.00, 'completed'),
(57, 53, 'Emily Davis', 6, NULL, '2025-07-28 16:00:00', 6, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-27 17:00:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(58, 54, 'Chris Miller', 6, NULL, '2025-07-28 13:30:00', 7, '', '', '2025-07-27 20:30:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(59, 49, 'Jane Smith', 7, NULL, '2025-07-28 16:00:00', 8, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-26 06:00:00', '2025-07-25 00:19:47', 85000.00, 'pending'),
(60, 55, 'Lisa Garcia', 4, NULL, '2025-07-29 12:00:00', 1, '', '', '2025-07-28 00:00:00', '2025-07-30 01:02:44', 35000.00, 'completed'),
(61, 55, 'Lisa Garcia', 2, NULL, '2025-07-29 14:00:00', 2, 'Potong rambut model fade yang rapi', '', '2025-07-28 08:00:00', '2025-07-30 01:02:52', 50000.00, 'completed'),
(62, 48, 'John Doe', 8, NULL, '2025-07-29 14:30:00', 3, '', '', '2025-07-28 13:30:00', '2025-07-25 00:19:47', 30000.00, 'pending'),
(63, 50, 'Michael Johnson', 2, NULL, '2025-07-29 12:00:00', 4, 'Potong rambut untuk anak, jangan terlalu pendek', '', '2025-07-26 17:00:00', '2025-07-25 00:19:47', 50000.00, 'pending'),
(64, 48, 'John Doe', 1, NULL, '2025-07-29 09:30:00', 5, 'Minta konsultasi untuk gaya rambut yang cocok', '', '2025-07-28 05:30:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(65, 57, 'Jennifer Rodriguez', 1, NULL, '2025-07-30 10:00:00', 1, 'Gaya rambut untuk acara formal', '', '2025-07-29 04:00:00', '2025-07-25 00:19:47', 25000.00, 'confirmed'),
(66, 50, 'Michael Johnson', 6, NULL, '2025-07-30 15:30:00', 2, '', '', '2025-07-28 21:30:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(67, 52, 'David Brown', 6, NULL, '2025-07-30 14:00:00', 3, '', '', '2025-07-28 00:00:00', '2025-07-25 00:19:47', 75000.00, 'pending'),
(68, 55, 'Lisa Garcia', 1, NULL, '2025-07-30 12:00:00', 4, '', '', '2025-07-30 02:00:00', '2025-07-30 01:02:54', 25000.00, 'completed'),
(69, 49, 'Jane Smith', 3, NULL, '2025-07-31 15:30:00', 1, 'Minta dipotong sesuai bentuk wajah', '', '2025-07-30 19:30:00', '2025-07-25 00:19:47', 15000.00, 'pending'),
(70, 52, 'David Brown', 1, NULL, '2025-07-31 13:00:00', 2, 'Ingin gaya rambut yang mudah diatur', '', '2025-07-30 05:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(71, 52, 'David Brown', 7, NULL, '2025-07-31 15:30:00', 3, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-30 15:30:00', '2025-07-25 00:19:47', 85000.00, 'pending'),
(72, 54, 'Chris Miller', 8, NULL, '2025-07-31 10:30:00', 4, 'Gaya rambut kasual untuk sehari-hari', '', '2025-07-28 21:30:00', '2025-07-25 00:19:47', 30000.00, 'confirmed'),
(73, 55, 'Lisa Garcia', 2, NULL, '2025-08-01 14:30:00', 1, 'Potong rambut seperti biasa', '', '2025-07-29 23:30:00', '2025-07-25 04:55:01', 50000.00, 'cancelled'),
(74, 50, 'Michael Johnson', 6, NULL, '2025-08-01 14:30:00', 2, 'Minta dipotong pendek di bagian samping', '', '2025-07-30 11:30:00', '2025-07-25 04:57:42', 75000.00, 'completed'),
(75, 48, 'John Doe', 1, NULL, '2025-08-01 10:00:00', 3, '', '', '2025-07-29 21:00:00', '2025-07-25 00:19:47', 25000.00, 'pending'),
(76, 56, 'Robert Martinez', 4, NULL, '2025-08-01 15:00:00', 4, 'Minta dipotong pendek di bagian samping', '', '2025-07-30 19:00:00', '2025-07-25 04:49:27', 35000.00, 'cancelled'),
(77, 51, 'Sarah Wilson', 7, NULL, '2025-08-01 12:00:00', 5, 'Ingin gaya rambut yang mudah diatur', '', '2025-08-01 00:00:00', '2025-08-25 20:19:17', 85000.00, 'completed'),
(78, 54, 'agungwahyu', 2, NULL, '2025-08-20 16:08:00', 1, 'dawdwdwada', '', '2025-07-26 00:04:05', '2025-07-30 22:57:18', 50000.00, 'completed'),
(79, 55, 'Salvador Smith', 1, NULL, '2025-07-29 16:17:00', 6, 'Hello', '', '2025-07-27 19:01:16', '2025-07-27 23:22:11', 25000.00, 'cancelled'),
(80, 55, 'Macey Hopper', 3, NULL, '2025-07-29 20:34:00', 7, 'Ut ea explicabo Off', '', '2025-07-27 23:28:12', '2025-07-27 23:29:36', 15000.00, 'cancelled'),
(81, 55, 'Tanya Bridges', 3, NULL, '2025-07-28 18:26:00', 9, 'Et error ea irure fa', '', '2025-07-27 23:35:23', '2025-07-27 23:43:17', 15000.00, 'cancelled'),
(82, 55, 'Blaze Neal', 8, NULL, '2025-07-28 18:56:00', 10, 'Unde ut porro repreh', '', '2025-07-27 23:43:09', '2025-07-29 00:25:17', 30000.00, 'cancelled'),
(83, 55, 'Phelan Hahn', 6, NULL, '2025-07-30 20:59:00', 5, 'Illo sint dolorum l', '', '2025-07-29 00:25:06', '2025-07-29 00:33:55', 75000.00, 'cancelled'),
(84, 55, 'Montana Acosta', 2, NULL, '2025-07-31 17:27:00', 5, 'Illo nostrud hic tem', '', '2025-07-29 00:35:30', '2025-07-30 01:04:52', 50000.00, 'completed'),
(85, 55, 'wide', 2, NULL, '2025-07-31 18:38:00', 6, 'fuck', '', '2025-07-29 01:08:47', '2025-07-30 01:04:50', 50000.00, 'completed'),
(86, 55, 'Hayes Rivera', 5, NULL, '2025-07-30 17:10:00', 6, 'Et sit qui ad ea el', '', '2025-07-30 00:55:44', '2025-07-30 01:04:56', 20000.00, 'completed'),
(87, 55, 'Lewis Newman', 5, NULL, '2025-07-31 17:37:00', 7, 'Adipisci voluptatem', '', '2025-07-30 22:16:51', '2025-07-30 22:44:25', 20000.00, 'completed'),
(88, 51, 'Gage Larsen', 1, NULL, '2025-07-31 20:40:00', 8, 'Eius iste sapiente n', '', '2025-07-30 22:51:20', '2025-07-30 22:51:20', 25000.00, 'completed'),
(89, 48, 'Agung', 1, NULL, '2025-08-04 14:00:00', 1, 'test', '', '2025-08-03 22:00:29', '2025-08-04 17:18:21', 25000.00, 'completed'),
(90, 48, 'Agung', 2, NULL, '2025-08-04 16:12:00', 2, 'dawdwdad', '', '2025-08-03 22:12:11', '2025-08-04 17:15:13', 50000.00, 'completed'),
(91, 55, 'Carson Frye', 4, NULL, '2025-08-07 20:19:00', 1, 'Enim error debitis i', '', '2025-08-06 20:56:31', '2025-08-07 00:38:32', 35000.00, 'completed'),
(92, 55, 'Tamara Martin', 3, NULL, '2025-08-07 17:08:00', 2, 'Ullam aut reiciendis', '', '2025-08-07 00:49:12', '2025-08-07 01:00:43', 15000.00, 'completed'),
(93, 55, 'Hedley Schneider', 1, NULL, '2025-08-09 20:26:00', 1, 'Aut vel consequatur', '', '2025-08-09 00:00:38', '2025-08-09 00:16:29', 25000.00, 'completed'),
(94, 55, 'Sade', 8, NULL, '2025-09-02 11:13:00', 1, 'Et et illo facilis d', '', '2025-09-01 20:26:59', '2025-09-17 07:16:38', 30000.00, 'completed'),
(95, 55, 'Nasim Odonnell', 1, NULL, '2025-09-18 13:54:00', 1, 'Ad fugit ea iste re', 'cash', '2025-09-17 05:44:18', '2025-09-17 06:25:14', 25000.00, 'completed'),
(96, 55, 'Cole Oneill', 5, NULL, '2025-09-18 17:55:00', 2, 'Do incidunt sed off', 'cash', '2025-09-17 06:30:34', '2025-09-17 06:46:04', 20000.00, 'completed');

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
(8, 'Bentuk Kepala', 0.50026636036223, '2025-09-25 01:31:39', '2025-09-29 21:38:59'),
(9, 'Tipe Rambut', 0.29976029562608, '2025-09-25 01:32:01', '2025-09-29 21:38:59'),
(10, 'Preferensi Gaya', 0.19997334401169, '2025-09-25 01:32:07', '2025-09-29 21:38:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dashboards`
--

CREATE TABLE `dashboards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `metric_name` varchar(255) NOT NULL,
  `metric_value` longtext NOT NULL,
  `metric_type` varchar(255) NOT NULL DEFAULT 'count',
  `date` date NOT NULL,
  `period` varchar(255) NOT NULL DEFAULT 'daily',
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dashboards`
--

INSERT INTO `dashboards` (`id`, `metric_name`, `metric_value`, `metric_type`, `date`, `period`, `additional_data`, `created_at`, `updated_at`) VALUES
(1, 'total_bookings', '0', 'count', '2025-06-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(2, 'completed_bookings', '0', 'count', '2025-06-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(3, 'daily_revenue', '0', 'currency', '2025-06-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(4, 'new_customers', '0', 'count', '2025-06-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(5, 'cancellation_rate', '0', 'percentage', '2025-06-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(6, 'popular_services', '[]', 'json', '2025-06-25', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(7, 'total_bookings', '0', 'count', '2025-06-26', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(8, 'completed_bookings', '0', 'count', '2025-06-26', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(9, 'daily_revenue', '0', 'currency', '2025-06-26', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(10, 'new_customers', '0', 'count', '2025-06-26', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(11, 'cancellation_rate', '0', 'percentage', '2025-06-26', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(12, 'popular_services', '[]', 'json', '2025-06-26', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(13, 'total_bookings', '0', 'count', '2025-06-27', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(14, 'completed_bookings', '0', 'count', '2025-06-27', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(15, 'daily_revenue', '0', 'currency', '2025-06-27', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(16, 'new_customers', '0', 'count', '2025-06-27', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(17, 'cancellation_rate', '0', 'percentage', '2025-06-27', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(18, 'popular_services', '[]', 'json', '2025-06-27', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(19, 'total_bookings', '0', 'count', '2025-06-28', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(20, 'completed_bookings', '0', 'count', '2025-06-28', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(21, 'daily_revenue', '0', 'currency', '2025-06-28', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(22, 'new_customers', '0', 'count', '2025-06-28', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(23, 'cancellation_rate', '0', 'percentage', '2025-06-28', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(24, 'popular_services', '[]', 'json', '2025-06-28', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(25, 'total_bookings', '0', 'count', '2025-06-29', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(26, 'completed_bookings', '0', 'count', '2025-06-29', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(27, 'daily_revenue', '0', 'currency', '2025-06-29', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(28, 'new_customers', '0', 'count', '2025-06-29', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(29, 'cancellation_rate', '0', 'percentage', '2025-06-29', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(30, 'popular_services', '[]', 'json', '2025-06-29', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(31, 'total_bookings', '0', 'count', '2025-06-30', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(32, 'completed_bookings', '0', 'count', '2025-06-30', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(33, 'daily_revenue', '0', 'currency', '2025-06-30', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(34, 'new_customers', '0', 'count', '2025-06-30', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(35, 'cancellation_rate', '0', 'percentage', '2025-06-30', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(36, 'popular_services', '[]', 'json', '2025-06-30', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(37, 'total_bookings', '0', 'count', '2025-07-01', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(38, 'completed_bookings', '0', 'count', '2025-07-01', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(39, 'daily_revenue', '0', 'currency', '2025-07-01', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(40, 'new_customers', '0', 'count', '2025-07-01', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(41, 'cancellation_rate', '0', 'percentage', '2025-07-01', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(42, 'popular_services', '[]', 'json', '2025-07-01', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(43, 'total_bookings', '0', 'count', '2025-07-02', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(44, 'completed_bookings', '0', 'count', '2025-07-02', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(45, 'daily_revenue', '0', 'currency', '2025-07-02', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(46, 'new_customers', '0', 'count', '2025-07-02', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(47, 'cancellation_rate', '0', 'percentage', '2025-07-02', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(48, 'popular_services', '[]', 'json', '2025-07-02', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(49, 'total_bookings', '0', 'count', '2025-07-03', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(50, 'completed_bookings', '0', 'count', '2025-07-03', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(51, 'daily_revenue', '0', 'currency', '2025-07-03', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(52, 'new_customers', '0', 'count', '2025-07-03', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(53, 'cancellation_rate', '0', 'percentage', '2025-07-03', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(54, 'popular_services', '[]', 'json', '2025-07-03', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(55, 'total_bookings', '0', 'count', '2025-07-04', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(56, 'completed_bookings', '0', 'count', '2025-07-04', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(57, 'daily_revenue', '0', 'currency', '2025-07-04', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(58, 'new_customers', '0', 'count', '2025-07-04', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(59, 'cancellation_rate', '0', 'percentage', '2025-07-04', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(60, 'popular_services', '[]', 'json', '2025-07-04', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(61, 'total_bookings', '0', 'count', '2025-07-05', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(62, 'completed_bookings', '0', 'count', '2025-07-05', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(63, 'daily_revenue', '0', 'currency', '2025-07-05', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(64, 'new_customers', '0', 'count', '2025-07-05', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(65, 'cancellation_rate', '0', 'percentage', '2025-07-05', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(66, 'popular_services', '[]', 'json', '2025-07-05', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(67, 'total_bookings', '0', 'count', '2025-07-06', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(68, 'completed_bookings', '0', 'count', '2025-07-06', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(69, 'daily_revenue', '0', 'currency', '2025-07-06', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(70, 'new_customers', '0', 'count', '2025-07-06', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(71, 'cancellation_rate', '0', 'percentage', '2025-07-06', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(72, 'popular_services', '[]', 'json', '2025-07-06', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(73, 'total_bookings', '0', 'count', '2025-07-07', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(74, 'completed_bookings', '0', 'count', '2025-07-07', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(75, 'daily_revenue', '0', 'currency', '2025-07-07', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(76, 'new_customers', '0', 'count', '2025-07-07', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(77, 'cancellation_rate', '0', 'percentage', '2025-07-07', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(78, 'popular_services', '[]', 'json', '2025-07-07', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(79, 'total_bookings', '0', 'count', '2025-07-08', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(80, 'completed_bookings', '0', 'count', '2025-07-08', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(81, 'daily_revenue', '0', 'currency', '2025-07-08', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(82, 'new_customers', '0', 'count', '2025-07-08', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(83, 'cancellation_rate', '0', 'percentage', '2025-07-08', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(84, 'popular_services', '[]', 'json', '2025-07-08', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(85, 'total_bookings', '0', 'count', '2025-07-09', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(86, 'completed_bookings', '0', 'count', '2025-07-09', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(87, 'daily_revenue', '0', 'currency', '2025-07-09', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(88, 'new_customers', '0', 'count', '2025-07-09', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(89, 'cancellation_rate', '0', 'percentage', '2025-07-09', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(90, 'popular_services', '[]', 'json', '2025-07-09', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(91, 'total_bookings', '0', 'count', '2025-07-10', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(92, 'completed_bookings', '0', 'count', '2025-07-10', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(93, 'daily_revenue', '0', 'currency', '2025-07-10', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(94, 'new_customers', '0', 'count', '2025-07-10', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(95, 'cancellation_rate', '0', 'percentage', '2025-07-10', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(96, 'popular_services', '[]', 'json', '2025-07-10', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(97, 'total_bookings', '0', 'count', '2025-07-11', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(98, 'completed_bookings', '0', 'count', '2025-07-11', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(99, 'daily_revenue', '0', 'currency', '2025-07-11', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(100, 'new_customers', '0', 'count', '2025-07-11', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(101, 'cancellation_rate', '0', 'percentage', '2025-07-11', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(102, 'popular_services', '[]', 'json', '2025-07-11', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(103, 'total_bookings', '0', 'count', '2025-07-12', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(104, 'completed_bookings', '0', 'count', '2025-07-12', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(105, 'daily_revenue', '0', 'currency', '2025-07-12', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(106, 'new_customers', '0', 'count', '2025-07-12', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(107, 'cancellation_rate', '0', 'percentage', '2025-07-12', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(108, 'popular_services', '[]', 'json', '2025-07-12', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(109, 'total_bookings', '0', 'count', '2025-07-13', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(110, 'completed_bookings', '0', 'count', '2025-07-13', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(111, 'daily_revenue', '0', 'currency', '2025-07-13', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(112, 'new_customers', '0', 'count', '2025-07-13', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(113, 'cancellation_rate', '0', 'percentage', '2025-07-13', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(114, 'popular_services', '[]', 'json', '2025-07-13', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(115, 'total_bookings', '0', 'count', '2025-07-14', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(116, 'completed_bookings', '0', 'count', '2025-07-14', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(117, 'daily_revenue', '0', 'currency', '2025-07-14', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(118, 'new_customers', '0', 'count', '2025-07-14', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(119, 'cancellation_rate', '0', 'percentage', '2025-07-14', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(120, 'popular_services', '[]', 'json', '2025-07-14', 'daily', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(121, 'total_bookings', '1', 'count', '2025-07-15', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(122, 'completed_bookings', '1', 'count', '2025-07-15', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(123, 'daily_revenue', '25000', 'currency', '2025-07-15', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(124, 'new_customers', '0', 'count', '2025-07-15', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(125, 'cancellation_rate', '0', 'percentage', '2025-07-15', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(126, 'popular_services', '{\"Potong Rambut Regular\":1}', 'json', '2025-07-15', 'daily', '{\"Potong Rambut Regular\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(127, 'total_bookings', '4', 'count', '2025-07-16', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(128, 'completed_bookings', '4', 'count', '2025-07-16', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(129, 'daily_revenue', '175000', 'currency', '2025-07-16', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(130, 'new_customers', '0', 'count', '2025-07-16', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(131, 'cancellation_rate', '0', 'percentage', '2025-07-16', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(132, 'popular_services', '{\"Complete Package\":1,\"Hair Wash & Styling\":1,\"Kids Haircut\":1}', 'json', '2025-07-16', 'daily', '{\"Complete Package\":1,\"Hair Wash & Styling\":1,\"Kids Haircut\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(133, 'total_bookings', '4', 'count', '2025-07-17', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(134, 'completed_bookings', '4', 'count', '2025-07-17', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(135, 'daily_revenue', '145000', 'currency', '2025-07-17', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(136, 'new_customers', '0', 'count', '2025-07-17', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(137, 'cancellation_rate', '0', 'percentage', '2025-07-17', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(138, 'popular_services', '{\"Beard Trimming\":2,\"Hair Treatment\":1,\"Kids Haircut\":1}', 'json', '2025-07-17', 'daily', '{\"Beard Trimming\":2,\"Hair Treatment\":1,\"Kids Haircut\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(139, 'total_bookings', '4', 'count', '2025-07-18', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(140, 'completed_bookings', '3', 'count', '2025-07-18', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(141, 'daily_revenue', '165000', 'currency', '2025-07-18', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(142, 'new_customers', '0', 'count', '2025-07-18', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(143, 'cancellation_rate', '25', 'percentage', '2025-07-18', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(144, 'popular_services', '{\"Potong Rambut Premium\":2,\"Complete Package\":1,\"Kids Haircut\":1}', 'json', '2025-07-18', 'daily', '{\"Potong Rambut Premium\":2,\"Complete Package\":1,\"Kids Haircut\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(145, 'total_bookings', '5', 'count', '2025-07-19', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(146, 'completed_bookings', '4', 'count', '2025-07-19', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(147, 'daily_revenue', '180000', 'currency', '2025-07-19', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(148, 'new_customers', '0', 'count', '2025-07-19', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(149, 'cancellation_rate', '20', 'percentage', '2025-07-19', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(150, 'popular_services', '{\"Potong Rambut Regular\":2,\"Beard Trimming\":1,\"Complete Package\":1}', 'json', '2025-07-19', 'daily', '{\"Potong Rambut Regular\":2,\"Beard Trimming\":1,\"Complete Package\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(151, 'total_bookings', '11', 'count', '2025-07-20', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(152, 'completed_bookings', '7', 'count', '2025-07-20', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(153, 'daily_revenue', '310000', 'currency', '2025-07-20', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(154, 'new_customers', '0', 'count', '2025-07-20', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(155, 'cancellation_rate', '36.36', 'percentage', '2025-07-20', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(156, 'popular_services', '{\"Kids Haircut\":3,\"Complete Package\":2,\"Hair Treatment\":2}', 'json', '2025-07-20', 'daily', '{\"Kids Haircut\":3,\"Complete Package\":2,\"Hair Treatment\":2}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(157, 'total_bookings', '4', 'count', '2025-07-21', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(158, 'completed_bookings', '4', 'count', '2025-07-21', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(159, 'daily_revenue', '125000', 'currency', '2025-07-21', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(160, 'new_customers', '0', 'count', '2025-07-21', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(161, 'cancellation_rate', '0', 'percentage', '2025-07-21', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(162, 'popular_services', '{\"Kids Haircut\":3,\"Hair Wash & Styling\":1}', 'json', '2025-07-21', 'daily', '{\"Kids Haircut\":3,\"Hair Wash & Styling\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(163, 'total_bookings', '5', 'count', '2025-07-22', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(164, 'completed_bookings', '2', 'count', '2025-07-22', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(165, 'daily_revenue', '255000', 'currency', '2025-07-22', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(166, 'new_customers', '0', 'count', '2025-07-22', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(167, 'cancellation_rate', '40', 'percentage', '2025-07-22', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(168, 'popular_services', '{\"Complete Package\":3,\"Beard Trimming\":1,\"Hair Treatment\":1}', 'json', '2025-07-22', 'daily', '{\"Complete Package\":3,\"Beard Trimming\":1,\"Hair Treatment\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(169, 'total_bookings', '3', 'count', '2025-07-23', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(170, 'completed_bookings', '3', 'count', '2025-07-23', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(171, 'daily_revenue', '185000', 'currency', '2025-07-23', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(172, 'new_customers', '0', 'count', '2025-07-23', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(173, 'cancellation_rate', '0', 'percentage', '2025-07-23', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(174, 'popular_services', '{\"Complete Package\":2,\"Shaving\\/Cukur\":1}', 'json', '2025-07-23', 'daily', '{\"Complete Package\":2,\"Shaving\\/Cukur\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(175, 'total_bookings', '6', 'count', '2025-07-24', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(176, 'completed_bookings', '1', 'count', '2025-07-24', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(177, 'daily_revenue', '190000', 'currency', '2025-07-24', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(178, 'new_customers', '0', 'count', '2025-07-24', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(179, 'cancellation_rate', '16.67', 'percentage', '2025-07-24', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(180, 'popular_services', '{\"Complete Package\":1,\"Hair Treatment\":1,\"Hair Wash & Styling\":1}', 'json', '2025-07-24', 'daily', '{\"Complete Package\":1,\"Hair Treatment\":1,\"Hair Wash & Styling\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(181, 'total_bookings', '3', 'count', '2025-07-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(182, 'completed_bookings', '0', 'count', '2025-07-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(183, 'daily_revenue', '0', 'currency', '2025-07-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(184, 'new_customers', '10', 'count', '2025-07-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(185, 'cancellation_rate', '33.33', 'percentage', '2025-07-25', 'daily', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(186, 'popular_services', '{\"Potong Rambut Regular\":2,\"Complete Package\":1}', 'json', '2025-07-25', 'daily', '{\"Potong Rambut Regular\":2,\"Complete Package\":1}', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(187, 'weekly_bookings', '0', 'count', '2025-04-28', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(188, 'weekly_revenue', '0', 'currency', '2025-04-28', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(189, 'customer_retention_rate', '0', 'percentage', '2025-04-28', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(190, 'weekly_bookings', '0', 'count', '2025-05-05', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(191, 'weekly_revenue', '0', 'currency', '2025-05-05', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(192, 'customer_retention_rate', '0', 'percentage', '2025-05-05', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(193, 'weekly_bookings', '0', 'count', '2025-05-12', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(194, 'weekly_revenue', '0', 'currency', '2025-05-12', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(195, 'customer_retention_rate', '0', 'percentage', '2025-05-12', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(196, 'weekly_bookings', '0', 'count', '2025-05-19', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(197, 'weekly_revenue', '0', 'currency', '2025-05-19', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(198, 'customer_retention_rate', '0', 'percentage', '2025-05-19', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(199, 'weekly_bookings', '0', 'count', '2025-05-26', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(200, 'weekly_revenue', '0', 'currency', '2025-05-26', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(201, 'customer_retention_rate', '0', 'percentage', '2025-05-26', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(202, 'weekly_bookings', '0', 'count', '2025-06-02', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(203, 'weekly_revenue', '0', 'currency', '2025-06-02', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(204, 'customer_retention_rate', '0', 'percentage', '2025-06-02', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(205, 'weekly_bookings', '0', 'count', '2025-06-09', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(206, 'weekly_revenue', '0', 'currency', '2025-06-09', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(207, 'customer_retention_rate', '0', 'percentage', '2025-06-09', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(208, 'weekly_bookings', '0', 'count', '2025-06-16', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(209, 'weekly_revenue', '0', 'currency', '2025-06-16', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(210, 'customer_retention_rate', '0', 'percentage', '2025-06-16', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(211, 'weekly_bookings', '0', 'count', '2025-06-23', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(212, 'weekly_revenue', '0', 'currency', '2025-06-23', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(213, 'customer_retention_rate', '0', 'percentage', '2025-06-23', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(214, 'weekly_bookings', '0', 'count', '2025-06-30', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(215, 'weekly_revenue', '0', 'currency', '2025-06-30', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(216, 'customer_retention_rate', '0', 'percentage', '2025-06-30', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(217, 'weekly_bookings', '0', 'count', '2025-07-07', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(218, 'weekly_revenue', '0', 'currency', '2025-07-07', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(219, 'customer_retention_rate', '0', 'percentage', '2025-07-07', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(220, 'weekly_bookings', '29', 'count', '2025-07-14', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(221, 'weekly_revenue', '1000000', 'currency', '2025-07-14', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(222, 'customer_retention_rate', '100', 'percentage', '2025-07-14', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(223, 'weekly_bookings', '27', 'count', '2025-07-21', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(224, 'weekly_revenue', '755000', 'currency', '2025-07-21', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(225, 'customer_retention_rate', '80', 'percentage', '2025-07-21', 'weekly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(226, 'monthly_bookings', '0', 'count', '2025-01-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(227, 'monthly_revenue', '0', 'currency', '2025-01-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(228, 'avg_order_value', '0', 'currency', '2025-01-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(229, 'service_performance', '[]', 'json', '2025-01-01', 'monthly', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(230, 'monthly_bookings', '0', 'count', '2025-02-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(231, 'monthly_revenue', '0', 'currency', '2025-02-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(232, 'avg_order_value', '0', 'currency', '2025-02-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(233, 'service_performance', '[]', 'json', '2025-02-01', 'monthly', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(234, 'monthly_bookings', '0', 'count', '2025-03-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(235, 'monthly_revenue', '0', 'currency', '2025-03-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(236, 'avg_order_value', '0', 'currency', '2025-03-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(237, 'service_performance', '[]', 'json', '2025-03-01', 'monthly', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(238, 'monthly_bookings', '0', 'count', '2025-04-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(239, 'monthly_revenue', '0', 'currency', '2025-04-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(240, 'avg_order_value', '0', 'currency', '2025-04-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(241, 'service_performance', '[]', 'json', '2025-04-01', 'monthly', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(242, 'monthly_bookings', '0', 'count', '2025-05-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(243, 'monthly_revenue', '0', 'currency', '2025-05-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(244, 'avg_order_value', '0', 'currency', '2025-05-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(245, 'service_performance', '[]', 'json', '2025-05-01', 'monthly', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(246, 'monthly_bookings', '0', 'count', '2025-06-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(247, 'monthly_revenue', '0', 'currency', '2025-06-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(248, 'avg_order_value', '0', 'currency', '2025-06-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(249, 'service_performance', '[]', 'json', '2025-06-01', 'monthly', '[]', '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(250, 'monthly_bookings', '76', 'count', '2025-07-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(251, 'monthly_revenue', '1785000', 'currency', '2025-07-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(252, 'avg_order_value', '48243', 'currency', '2025-07-01', 'monthly', NULL, '2025-07-25 00:20:05', '2025-07-25 00:20:05'),
(253, 'service_performance', '[{\"name\":\"Complete Package\",\"booking_count\":16,\"total_revenue\":\"1360000.00\"},{\"name\":\"Hair Treatment\",\"booking_count\":10,\"total_revenue\":\"750000.00\"},{\"name\":\"Potong Rambut Premium\",\"booking_count\":8,\"total_revenue\":\"400000.00\"},{\"name\":\"Kids Haircut\",\"booking_count\":12,\"total_revenue\":\"360000.00\"},{\"name\":\"Potong Rambut Regular\",\"booking_count\":13,\"total_revenue\":\"325000.00\"},{\"name\":\"Hair Wash & Styling\",\"booking_count\":5,\"total_revenue\":\"175000.00\"},{\"name\":\"Beard Trimming\",\"booking_count\":7,\"total_revenue\":\"140000.00\"},{\"name\":\"Shaving\\/Cukur\",\"booking_count\":5,\"total_revenue\":\"75000.00\"}]', 'json', '2025-07-01', 'monthly', '[{\"name\":\"Complete Package\",\"booking_count\":16,\"total_revenue\":\"1360000.00\"},{\"name\":\"Hair Treatment\",\"booking_count\":10,\"total_revenue\":\"750000.00\"},{\"name\":\"Potong Rambut Premium\",\"booking_count\":8,\"total_revenue\":\"400000.00\"},{\"name\":\"Kids Haircut\",\"booking_count\":12,\"total_revenue\":\"360000.00\"},{\"name\":\"Potong Rambut Regular\",\"booking_count\":13,\"total_revenue\":\"325000.00\"},{\"name\":\"Hair Wash & Styling\",\"booking_count\":5,\"total_revenue\":\"175000.00\"},{\"name\":\"Beard Trimming\",\"booking_count\":7,\"total_revenue\":\"140000.00\"},{\"name\":\"Shaving\\/Cukur\",\"booking_count\":5,\"total_revenue\":\"75000.00\"}]', '2025-07-25 00:20:05', '2025-07-25 00:20:05');

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
(58, 37, 8, 8.50, '2025-09-29 20:30:09', '2025-09-29 20:30:09'),
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
(11, 55, 3, '2025-09-17 07:16:38', '2025-08-07 01:00:43'),
(12, 51, 1, '2025-08-25 20:19:17', '2025-08-25 20:19:17'),
(13, 50, 1, '2025-08-25 23:18:20', '2025-08-25 23:18:20');

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
(30, '2025_08_08_025917_add_style_preference_to_hairstyles_table', 4);

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
(32, 'App\\Models\\User', 74);

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
  `transaction_time` datetime DEFAULT NULL,
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
(2, '89', 'settlement', 'bank_transfer', 25000.00, '2025-08-04 13:09:57', 'bca', '35797291880706315963357', '2025-08-03 22:02:53', '2025-08-03 22:10:11', NULL, NULL),
(3, '90', 'settlement', 'bank_transfer', 50000.00, '2025-08-04 13:12:18', 'bca', '35797302236278579244493', '2025-08-03 22:12:16', '2025-08-03 22:14:43', NULL, NULL),
(4, '95', 'settlement', 'cash', 25000.00, '2025-09-17 14:25:14', NULL, NULL, '2025-09-17 06:25:14', '2025-09-17 06:25:14', 'Nasim Odonnell', 'lisa@example.com'),
(5, '96', 'settlement', 'cash', 20000.00, '2025-09-17 14:30:40', NULL, NULL, '2025-09-17 06:30:40', '2025-09-17 06:46:04', 'Cole Oneill', 'lisa@example.com');

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
  `profile_photo` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `no_telepon`, `email_verified_at`, `password`, `is_active`, `last_login_at`, `profile_photo`, `remember_token`, `created_at`, `updated_at`) VALUES
(45, 'Super Admin', 'admin@woxbarbershop.com', '081234567890', '2025-07-25 00:19:36', '$2y$12$cESMekqUEtM9A9wXvleli.RjgbArHEu3hFLwO2ez8LX43dbMsCwoe', 1, '2025-09-29 19:22:15', 'profile_photos/BwFhsUXZyEPoWWUn9x9L1dMFJGF2Wn12kkMw1J7W.jpg', NULL, '2025-07-25 00:19:36', '2025-09-29 19:22:15'),
(46, 'Barber Ahmad', 'ahmad@woxbarbershop.com', '081234567891', '2025-07-25 00:19:36', '$2y$12$cniTOFtL6o.bBpETghYmTekFTYuvOnQrJOwA8miSePOKHRDmB6fRe', 1, '2025-09-16 23:43:40', NULL, NULL, '2025-07-25 00:19:36', '2025-09-16 23:43:40'),
(47, 'Barber Budi', 'budi@woxbarbershop.com', '081234567892', '2025-07-25 00:19:37', '$2y$12$Nve5vdVIzWRXxHOlOEbYRuNj.57iZAllDKuijvEcF64/mKmpykssC', 1, NULL, NULL, NULL, '2025-07-25 00:19:37', '2025-07-25 00:19:37'),
(48, 'John Doe', 'john@example.com', '089876543210', '2025-07-25 00:19:39', '$2y$12$vfTnRqxQ9NqvPteFVqePlepm2fQn6BURrjpWttdcCNYLQBrDKO0X2', 1, '2025-09-08 08:03:32', NULL, NULL, '2025-07-25 00:19:39', '2025-09-08 08:03:32'),
(49, 'Jane Smith', 'jane@example.com', '089876543211', '2025-07-25 00:19:39', '$2y$12$zzWsm99NrpF2CucW..CO6eql2YBLRMgX.FYRiMKC473YqBoX076vG', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(50, 'Michael Johnson', 'michael@example.com', '089876543212', '2025-07-25 00:19:39', '$2y$12$xbvZZSop3xqTxOoFWBy/pejPJE5nt1sESZdwwcrfo0vYcCFKkhwPC', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(51, 'Sarah Wilson', 'sarah@example.com', '089876543213', '2025-07-25 00:19:39', '$2y$12$QNKmTBLxw8c12QdLIUngR.SKsxnfEfTJOYA5EqNMpEKpNbjUgcAdW', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(52, 'David Brown', 'david@example.com', '089876543214', '2025-07-25 00:19:39', '$2y$12$O2L/jT592hvZi01hg46Lue.ZP0ayp/6VpGtMFvAi1x5.36RKrywgu', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(53, 'Emily Davis', 'emily@example.com', '089876543215', '2025-07-25 00:19:39', '$2y$12$IbRKsN3Zt3iAVrArvDzowOR/2c3dK7R7s6UGbMbRt7kPw6RtNCq.6', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(54, 'Chris Miller', 'chris@example.com', '089876543216', '2025-07-25 00:19:39', '$2y$12$8ay1PTxI0MxlsKfVHA9D2O1Y3.zNjHykI6l6xtO2r5cO/OQ.GQBcK', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(55, 'Lisa Garcia', 'lisa@example.com', '089876543217', '2025-07-25 00:19:39', '$2y$12$N2y0lNbY6.1SXO3zPCDDlu/rh4JmMDOR7gXWly9a7cDhuEUlP3nHy', 1, '2025-09-29 20:42:16', 'profile_photos/pMdCnee3GvjrwfnYdrd5j8vDFoCUCQ2JACGZS0oa.jpg', '6Y800EUgBmC5EiQsxzFZC0K13GA1PaQn8tGog1uPrfigRkSOQhpJ9ydfGwa5', '2025-07-25 00:19:39', '2025-09-29 20:42:16'),
(56, 'Robert Martinez', 'robert@example.com', '089876543218', '2025-07-25 00:19:39', '$2y$12$m4AN0yhjvmtMZpRghXVAUeffv8EByE1wbAcOijqye7Knx5RGMij8e', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(57, 'Jennifer Rodriguez', 'jennifer@example.com', '089876543219', '2025-07-25 00:19:39', '$2y$12$9tr3qq7GwRIwQOmAwfXEl.dmli0KlGNdgiNyOb.Q3uDRKIgJj0efu', 1, NULL, NULL, NULL, '2025-07-25 00:19:39', '2025-07-25 00:19:39'),
(60, 'aagungwahyu', 'aagungwwahyu05@gmail.com', '081239339998', '2025-08-13 08:23:03', '$2y$12$UX3sYEfsBfcbj7XzO4K3eOokagJPYcXn83OPz4jVilhZyewsEQa2O', 1, NULL, NULL, NULL, '2025-08-12 19:27:07', '2025-09-10 04:02:31'),
(71, 'Tanisha Mccarty', 'jesub@mailinator.com', '12', '2025-08-12 23:31:47', '$2y$12$6r9Gs32ZdiUNFxv.PoNob.Hkw5Y0KjB5GpDTAqrncQRuCnjCd44cW', 1, NULL, NULL, NULL, '2025-08-12 23:31:08', '2025-08-12 23:31:47'),
(72, 'Melyssa Gaines', 'fologuf@mailinator.com', '37', '2025-08-12 23:38:34', '$2y$12$86DPDKHWNtzJc1AKFecihOK.8EbqjkdqcMGVJAXQX2KwfiSEJqsfW', 1, NULL, NULL, NULL, '2025-08-12 23:32:57', '2025-08-12 23:38:34'),
(73, 'Anak Agung Rai', 'rai@gmail.com', '081223876889', '2025-08-13 00:30:28', '$2y$12$lIZrDjzq0mTK85u2mOJLqO586Z5tgzae504uZMkLHkeDc4rvdbJvq', 1, NULL, NULL, NULL, '2025-08-13 00:26:14', '2025-08-13 00:30:28'),
(74, 'Hop Ball', 'wujesul@mailinator.com', '43', '2025-08-13 00:37:05', '$2y$12$w6ruT7lAOYdJMhCRNIsadO9/dsyvC/.URv0vzC0yCTukeUdJSJdkG', 1, NULL, NULL, NULL, '2025-08-13 00:33:55', '2025-08-13 00:37:05'),
(75, 'Dora White', 'motelaxeq@mailinator.com', '17649939373', '2025-09-10 03:51:27', '$2y$12$Nlse7OrNeYZTLETKsKmqZ.7zk4J6YbjMcmG15RIqczTAiFO5Pe9Ma', 1, NULL, NULL, NULL, '2025-09-10 03:50:42', '2025-09-10 03:51:27');

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
-- Indeks untuk tabel `dashboards`
--
ALTER TABLE `dashboards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dashboards_metric_name_date_period_unique` (`metric_name`,`date`,`period`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT untuk tabel `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `dashboards`
--
ALTER TABLE `dashboards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

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
