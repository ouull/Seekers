-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Bulan Mei 2025 pada 07.10
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lost_and_found`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `claims`
--

CREATE TABLE `claims` (
  `claim_id` int(11) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `id_card_image` varchar(255) DEFAULT NULL,
  `train_ticket_image` varchar(255) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `proof_of_ownership` varchar(255) DEFAULT NULL,
  `lost_item_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metode_pengambilan` varchar(50) DEFAULT NULL,
  `stasiun_ambil` varchar(100) DEFAULT NULL,
  `stasiun_kirim` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `claims`
--

INSERT INTO `claims` (`claim_id`, `passenger_name`, `phone_number`, `id_card_image`, `train_ticket_image`, `item_description`, `proof_of_ownership`, `lost_item_id`, `created_at`, `updated_at`, `metode_pengambilan`, `stasiun_ambil`, `stasiun_kirim`) VALUES
(42, 'Tejo', '+6281256728669', 'claim/1746864537_1746671437_idcard.jpg', 'claim/1746864537_1746671437_ticket-proof.jpg', 'dadsd', 'claim/1746864537_1746671437_ownership.jpg', 14, '2025-05-10 08:08:57', '2025-05-10 08:08:57', NULL, NULL, NULL),
(49, 'dasdsad', '+6281256728669', 'claim/1746887722_1746671437_idcard.jpg', 'claim/1746887722_1746671437_ticket-proof.jpg', 'dadsadas', 'claim/1746887722_1746671437_ownership.jpg', 11, '2025-05-10 14:35:22', '2025-05-10 14:35:22', 'dikirim', NULL, 'Tegalluar Summarecon'),
(50, 'naufal', '+6281256728669', 'claim/1746887974_1746671437_idcard.jpg', 'claim/1746887974_1746671437_ticket-proof.jpg', 'adadasda', 'claim/1746887974_1746671437_ownership.jpg', 7, '2025-05-10 14:39:34', '2025-05-10 14:39:34', 'ambil', 'Halim Station', NULL),
(53, 'aasda', '082298335762', 'claim/1747020003_aaa.jpg', 'claim/1747020003_aaa.jpg', 'asdasd', 'claim/1747020003_aaa.jpg', 9, '2025-05-12 03:20:03', '2025-05-12 03:20:03', 'dikirim', NULL, 'Padalarang Station'),
(55, 'koko', '082122681865', 'claim/1747024761_aaa.jpg', 'claim/1747024761_aaa.jpg', 'asadsdasda', 'claim/1747024761_aaa.jpg', 10, '2025-05-12 04:39:21', '2025-05-12 04:39:21', 'dikirim', NULL, 'Tegalluar Summarecon'),
(56, 'ijal', '(+62) 857 8252 4513', 'claim/1747455481_BPJS.jpg', 'claim/1747455481_BPJS.jpg', 'assaas', 'claim/1747455481_BPJS.jpg', 6, '2025-05-17 04:18:01', '2025-05-17 04:18:01', 'ambil', 'Tegalluar Summarecon', NULL),
(57, 'apisaselole', '82122681865', 'claim/1747455565_a.jpg', 'claim/1747455565_b.jpg', 'asasa', 'claim/1747455565_c.jpg', 9, '2025-05-17 04:19:25', '2025-05-17 04:19:25', 'ambil', 'Tegalluar Summarecon', NULL),
(58, 'iksan', '08938947892', 'claim/1748407795_Bayi Gajah.jpg', 'claim/1748407795_Bayi Gajah.jpg', 'sudah ditemukan', 'claim/1748407795_with yoan.png', 22, '2025-05-28 04:49:55', '2025-05-28 04:49:55', 'ambil', 'Karawang Station', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lost_items`
--

CREATE TABLE `lost_items` (
  `id` int(11) NOT NULL,
  `no_regist` varchar(50) DEFAULT NULL,
  `categories` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `nomor_kereta` varchar(50) DEFAULT NULL,
  `gerbong` varchar(10) DEFAULT NULL,
  `kursi` varchar(10) DEFAULT NULL,
  `nama_pelapor` varchar(255) NOT NULL,
  `reporter` varchar(100) DEFAULT NULL,
  `chronology` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('unclaimed','claimed') DEFAULT 'unclaimed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `perilis` varchar(100) DEFAULT NULL,
  `tanggal_rilis` date DEFAULT NULL,
  `foto_rilis` varchar(255) DEFAULT NULL,
  `metode_pengambilan` varchar(50) DEFAULT NULL,
  `stasiun_ambil` varchar(100) DEFAULT NULL,
  `stasiun_kirim` varchar(100) DEFAULT NULL,
  `status_rilis` enum('rilis','belum rilis') DEFAULT 'belum rilis'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lost_items`
--

INSERT INTO `lost_items` (`id`, `no_regist`, `categories`, `date`, `nama_barang`, `location`, `nomor_kereta`, `gerbong`, `kursi`, `nama_pelapor`, `reporter`, `chronology`, `image`, `status`, `created_at`, `perilis`, `tanggal_rilis`, `foto_rilis`, `metode_pengambilan`, `stasiun_ambil`, `stasiun_kirim`, `status_rilis`) VALUES
(6, 'T-1236', 'Bag', '2025-02-14', 'Tas Kanken', 'Tegalluar Summarecon', 'G 1222', '3', '13 B', 'Joko', 'PSAP', 'Barang tertinggal di kursi penumpang', 'uploads/f23524-550_1 (1).jpeg', 'claimed', '2025-04-14 03:40:16', 'kepin', '2025-05-17', 'rilis_uploads/1747455499_BUKTIPERMANEN_SISWA_0050378373 (1).jpeg', NULL, NULL, NULL, 'rilis'),
(7, 'T-2455', 'Automotive', '2025-02-15', 'Arai Helmet', 'Tegalluar Summarecon', '', '', '', 'Kevin', 'PSAP', 'Barang tertinggal di toilet Waiting Hall Lantai 3', 'uploads/570ffae31e52591647d9944fd170fc8d.png', 'claimed', '2025-04-15 03:40:16', 'lea', '2025-05-09', 'rilis_uploads/1746888047_1746678027_release.jpg', NULL, NULL, NULL, 'rilis'),
(9, 'P-1123', 'Bag', '2025-03-15', 'Hermes Birkin', 'Padalarang Station', '', '', '', 'Fatur', 'Security', 'Barang ditemukan di toilet wanita ', 'uploads/Birkin-25-Gold-Epsom-.jpg', 'claimed', '2025-04-13 03:40:16', 'kepin', '2025-05-17', 'rilis_uploads/1747455581_asd.JPG', NULL, NULL, NULL, 'rilis'),
(10, 'H-1256', 'Kids Stuff', '2025-03-23', 'Boneka Labubu', 'Halim Station', 'G 1024', '5', '14 B', 'Nanda', 'PSOT', 'Barang ditemukan di kursi penumpang', 'uploads/id-11134207-7r98q-lz6skh0zjkq50a.jpg', 'unclaimed', '2025-04-20 03:40:16', 'asdsa', '2025-05-16', 'rilis_uploads/1746891477_aaa.jpg', NULL, NULL, NULL, 'belum rilis'),
(11, 'P-1130', 'FnB', '2025-03-29', 'Bakpia Tugu', 'Padalarang Station', '', '', '', 'Fatur', 'Security', 'Barang di temukan di waiting hall', 'uploads/Bakpia.jpg', 'claimed', '2025-04-18 03:40:16', 'lea', '2025-05-09', 'rilis_uploads/1746888166_1746678027_release.jpg', NULL, NULL, NULL, 'rilis'),
(13, 'T-1234', 'Electronic', '2025-03-30', 'AirPods Max', 'Tegalluar Summarecon', '', '', '', 'Kevin', 'Security', 'Barang ditemukan di toilet waiting hall lantai 3', 'uploads/0788-APPMWW53ID-A-.jpg', 'unclaimed', '2025-04-03 03:40:16', 'lea', '2025-05-09', 'rilis_uploads/1746886796_1746678027_release.jpg', 'dikirim', NULL, 'Tegalluar Summarecon', 'belum rilis'),
(14, 'H-1133', 'Accessories', '2025-03-28', 'Rolex Watch', 'Halim Station', '', '', '', 'Nanda', 'Security', 'Barang ditemukan di toilet pria ', 'uploads/RolexDate.png', 'claimed', '2025-04-04 03:40:16', 'lea', '2025-05-09', 'rilis_uploads/1746866053_1746678027_release.jpg', 'ambil', 'Halim Station', NULL, 'rilis'),
(22, '0121', 'Bag', '2025-05-30', 'hp', 'Halim Station', 'g8099', '2', '12 A', 'joni', 'PSAP', 'hilang ditelan', 'uploads/animepoik.png', 'claimed', '2025-05-28 04:48:45', 'sena', '2025-05-31', 'rilis_uploads/1748407836_Logo-WIKA.png', NULL, NULL, NULL, 'rilis');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lost_report`
--

CREATE TABLE `lost_report` (
  `nama_pelapor` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `ciri_ciri` text NOT NULL,
  `lokasi_kehilangan` varchar(255) DEFAULT NULL,
  `kronologi` text NOT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Belum ditemukan','Sudah ditemukan','Expired') DEFAULT 'Belum ditemukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lost_report`
--

INSERT INTO `lost_report` (`nama_pelapor`, `kontak`, `id`, `nama_barang`, `ciri_ciri`, `lokasi_kehilangan`, `kronologi`, `tanggal`, `created_at`, `status`) VALUES
('rizal', '08123123123', 1, 'Laptop', 'Hitam, layar 14 inci, ada stiker', 'Stasiun Tegalluar', 'Ketinggalan di kafe saat bekerja', '2025-04-16', '2025-03-30 07:42:10', 'Expired'),
('naufal', '08123123123', 2, 'Dompet', 'Kulit coklat, berisi KTP & ATM', 'stasiun padalarang\r\n', 'Jatuh saat naik motor di jalan raya', NULL, '2025-03-30 03:42:10', 'Expired'),
('halwa', '0891231312', 3, 'Handphone', 'iPhone 12, casing merah, retak kecil di pojok', 'stasiun padalarang', 'Hilang saat naik transportasi umum', NULL, '2025-04-05 03:42:10', 'Expired'),
('diaz', '0812374424', 4, 'Kunci motor', 'Gantungan Doraemon, ada 3 kunci', 'Stasiun Halim', 'Terjatuh di parkiran mall', NULL, '2025-04-05 03:42:10', 'Sudah ditemukan'),
('ijal', '0821312412', 11, 'bpjs', 'kartu', 'Halim Station', 'qweasdzxc', '2025-04-18', '2025-04-18 06:02:45', 'Expired'),
('alif', '0898231231', 12, 'laptop geming', 'ROG ', 'Karawang Station', 'tertinggal di tas hitam di kursi waiting hall', '2025-04-18', '2025-04-18 06:05:02', 'Sudah ditemukan'),
('dadss', 'dasd', 13, 'dasds', 'dadsad', 'Halim Station', 'dasdasd', '2025-05-10', '2025-05-01 13:07:48', 'Expired');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `departement` varchar(100) DEFAULT NULL,
  `biro` varchar(100) DEFAULT NULL,
  `placement` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) NOT NULL DEFAULT 'profil/noprofil.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `position`, `departement`, `biro`, `placement`, `profile_pic`) VALUES
(1, 'lala', '', 'lalapo@gmail.com', '$2y$10$wER/srM6fzeIeGaOI/eNgOLW55QW5yIjta/LI6Drcvz76paiKeY0S', NULL, NULL, NULL, NULL, ''),
(2, 'Muhammad Naufal Zhalifunnas', 'zhalfnass', 'mnz110504@gmail.com', '$2y$10$MpyREhVrFcn1rnTJKL8TdO033P1gQLxg.Ru8GQOKHJ92UTQUyUbwK', 'Magang', 'HPIO', 'IT Ticketing', 'Tegalluar', 'profil/0788-APPMWW53ID-A-.jpg'),
(3, 'Naufal Zhalifunnas', '', 'naufal.naufal712@gmail.com', '$2y$10$BXKc4FCNxnhM.0g6Cj2xTOZgSIjXhFALGQw6bCB.oAsXvT9YAtvnK', NULL, NULL, NULL, NULL, ''),
(4, 'yuyu', '', 'yunikom@gmail.com', '$2y$10$.eVIHIXD7L8eOTb5waTth.IGzGgTU38vnt.hoQFjvae7C7PaKM70i', NULL, NULL, NULL, NULL, ''),
(5, 'Tejo', '', 'tejo@gmail.com', '$2y$10$Ot8sD01pMNkoxcM/556pzOc363SL10XsRPScyN51Up02ywJPhlyCy', NULL, NULL, NULL, NULL, ''),
(6, 'naufal', '', 'nz@gmail.com', '$2y$10$qu0eRTaeEUW95Lg5nuzNCup/jLbCsIkv924gOYAW5fNjs/a4qZUNG', NULL, NULL, NULL, NULL, 'profil/noprofil.png'),
(7, 'Rizal Mutaqien', 'ijal', 'ijal@gmail.com', '$2y$10$ywKNvFjmYG09G3YeD1JfXeDAtiuNnRg9qoRIne7uWDXjsTObkb0Gq', 'Engineer', 'HPIO', 'IT Data Center & Operation Network', 'Stasiun Tegalluar Summarecon', 'profil/FOrmal.jpg'),
(8, 'Muhammad Naufal Zhalifunnas', 'zhalfnass_', 'naufal.zhalifunnas04@gmail.com', '$2y$10$9bQhgCRMCwGR59p6Fi/M9eyMux7Nk0cWLr5hELgRXBGX.qFNoXrFa', 'Engineer', 'HPIO', 'IT Ticketing', 'Tegalluar', 'profil/noprofil.png'),
(9, 'test', '', 'test@gmail', '$2y$10$SgINOmKqh5TbhmonuKqVj.hgxpvTybM1keYcX6SHqObCh7YaHJ.MK', '', '', '', '', 'profil/Bayi Gajah.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `lost_item_id` (`lost_item_id`);

--
-- Indeks untuk tabel `lost_items`
--
ALTER TABLE `lost_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lost_report`
--
ALTER TABLE `lost_report`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `claims`
--
ALTER TABLE `claims`
  MODIFY `claim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `lost_items`
--
ALTER TABLE `lost_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `lost_report`
--
ALTER TABLE `lost_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`lost_item_id`) REFERENCES `lost_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
