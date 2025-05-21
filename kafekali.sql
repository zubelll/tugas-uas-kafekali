-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2025 at 11:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kafekali`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `id_checkout` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `tanggal_checkout` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `jumlah`, `subtotal`) VALUES
(25, 24, 27, 1, 35000),
(26, 25, 26, 1, 17000),
(27, 25, 32, 1, 21000),
(42, 41, 32, 1, 21000),
(45, 44, 32, 1, 21000),
(46, 44, 26, 1, 17000),
(47, 44, 27, 3, 105000),
(48, 45, 32, 1, 21000),
(49, 45, 35, 1, 21000),
(50, 46, 32, 1, 21000),
(51, 47, 32, 1, 21000),
(52, 47, 27, 1, 35000),
(53, 48, 35, 1, 21000),
(54, 49, 32, 1, 21000),
(55, 50, 35, 1, 21000),
(56, 51, 26, 1, 17000),
(57, 51, 27, 1, 35000),
(58, 52, 35, 1, 21000),
(59, 52, 26, 1, 17000),
(60, 53, 32, 1, 21000),
(61, 53, 35, 1, 21000),
(62, 54, 35, 1, 21000),
(63, 54, 29, 1, 6000),
(64, 55, 35, 1, 21000),
(65, 55, 28, 1, 16000),
(66, 56, 35, 2, 42000),
(67, 56, 25, 1, 5000),
(68, 57, 35, 1, 21000),
(69, 58, 35, 2, 42000),
(70, 58, 32, 1, 21000),
(71, 59, 35, 1, 21000),
(72, 60, 35, 3, 63000),
(73, 60, 32, 2, 42000),
(74, 61, 35, 1, 21000),
(75, 62, 35, 1, 21000),
(76, 62, 26, 1, 17000);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_user` int NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `harga` varchar(20) DEFAULT NULL,
  `kategori` enum('food','drink') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `image`, `name`, `harga`, `kategori`) VALUES
(25, 'es teh.jpg', 'es teh', 'Rp 5.000', 'drink'),
(26, 'matcha.jpeg', 'matcha', 'Rp 17.000', 'drink'),
(27, 'gurame.jpg', 'gurame', 'Rp 35.000', 'food'),
(28, 'avogato.jpg', 'avogato', 'Rp 16.000', 'drink'),
(29, 'esjeruk.jpg', 'es jeruk', 'Rp 6.000', 'drink'),
(30, 'redvelvet.avif', 'red velvet', 'Rp 13.000', 'drink'),
(31, 'taro late.jpg', 'taro late', 'Rp 16.000', 'drink'),
(32, 'nasisayapbakar.jpg', 'nasi sayap bakar', 'Rp 21.000', 'food'),
(33, 'bananachees.jpg', 'banana chess', 'Rp 12.000', 'food'),
(34, 'Nasgorspesial.jpg', 'nasi goreng sepesial', 'Rp 17.000', 'food'),
(35, 'nasiayampenyetjpg.jpg', 'nasi ayam penyet ', 'Rp 21.000', 'food');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_user` int NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `metode_pembayaran` enum('cash','e-wallet','transfer_bank') NOT NULL,
  `status_pembayaran` enum('pending','dibayar','gagal') DEFAULT 'pending',
  `tanggal_pembayaran` datetime DEFAULT CURRENT_TIMESTAMP,
  `alamat` varchar(200) NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `bukti_transfer` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_user`, `id_pesanan`, `metode_pembayaran`, `status_pembayaran`, `tanggal_pembayaran`, `alamat`, `keterangan`, `bukti_transfer`) VALUES
(8, 8, 44, 'e-wallet', 'dibayar', '2025-05-21 09:51:27', '', '', 'logo jek pus.jpg'),
(9, 9, 25, 'e-wallet', 'dibayar', '2025-05-21 10:22:52', '', 'asdasda', '7606699_3692229.jpg'),
(10, 9, 48, 'e-wallet', 'dibayar', '2025-05-21 14:11:03', '', 'weqweq', '12982910_5124556.jpg'),
(11, 9, 49, 'cash', 'dibayar', '2025-05-21 15:00:33', '', 'sad', ''),
(12, 25, 46, 'cash', 'dibayar', '2025-05-21 15:16:43', '', 'sda', ''),
(13, 25, 46, 'cash', 'dibayar', '2025-05-21 15:17:12', '', 'sdad', ''),
(15, 25, 52, 'e-wallet', 'dibayar', '2025-05-21 15:44:14', '', 'da', ''),
(16, 25, 52, 'e-wallet', 'dibayar', '2025-05-21 15:44:36', '', 'dsad', ''),
(17, 25, 52, 'cash', 'dibayar', '2025-05-21 15:51:51', '', 'das', ''),
(18, 25, 54, 'cash', 'dibayar', '2025-05-21 15:53:49', '', 'dad', ''),
(19, 25, 55, 'cash', 'dibayar', '2025-05-21 15:56:47', '', 'dsad', ''),
(20, 25, 56, 'cash', 'dibayar', '2025-05-21 15:57:19', '', 'dsa', ''),
(21, 25, 57, 'e-wallet', 'dibayar', '2025-05-21 15:58:31', '', 'erwer', ''),
(22, 25, 57, 'transfer_bank', 'pending', '2025-05-21 16:15:14', '', 'ssax', ''),
(23, 25, 57, 'e-wallet', 'pending', '2025-05-21 16:19:32', '', 'pedas', ''),
(24, 25, 60, 'e-wallet', 'dibayar', '2025-05-21 16:34:27', 'dasda', 'sda', 'OJEK KAMPUS.png'),
(25, 25, 60, 'transfer_bank', 'dibayar', '2025-05-21 16:35:44', 'xc', 'asu', 'anime-girl-tattoo-demon-horn-8k-wallpaper-uhdpaper.com-224@3@a.jpg'),
(26, 31, 53, 'e-wallet', 'dibayar', '2025-05-21 16:38:08', 'arab', 'dsad', 'Screenshot 2025-05-17 210606.png');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal_pesan` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` int NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `tanggal_pesan`, `total`, `status`) VALUES
(24, 9, '2025-05-19 12:55:40', 35000, 'pending'),
(25, 9, '2025-05-19 15:02:49', 38000, 'pending'),
(41, 25, '2025-05-19 23:22:20', 21000, 'sukses'),
(44, 8, '2025-05-20 16:23:56', 143000, 'pending'),
(45, 25, '2025-05-20 21:54:41', 42000, 'pending'),
(46, 25, '2025-05-21 09:29:12', 21000, 'pending'),
(47, 8, '2025-05-21 09:51:31', 56000, 'sukses'),
(48, 9, '2025-05-21 10:22:57', 21000, 'sukses'),
(49, 9, '2025-05-21 14:11:19', 21000, 'pending'),
(50, 9, '2025-05-21 15:00:36', 21000, 'pending'),
(51, 30, '2025-05-21 15:16:00', 52000, 'sukses'),
(52, 25, '2025-05-21 15:17:21', 38000, 'pending'),
(53, 31, '2025-05-21 15:46:24', 21000, 'sukses'),
(54, 25, '2025-05-21 15:51:55', 27000, 'pending'),
(55, 25, '2025-05-21 15:53:53', 37000, 'pending'),
(56, 25, '2025-05-21 15:56:56', 47000, 'pending'),
(57, 25, '2025-05-21 15:57:30', 21000, 'pending'),
(58, 25, '2025-05-21 16:22:25', 63000, 'pending'),
(59, 25, '2025-05-21 16:27:58', 21000, 'pending'),
(60, 25, '2025-05-21 16:28:40', 105000, 'pending'),
(61, 25, '2025-05-21 16:35:57', 21000, 'sukses'),
(62, 31, '2025-05-21 16:38:13', 38000, 'sukses');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `jumlah_orang` int NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id`, `first_name`, `last_name`, `email`, `phone`, `tanggal`, `waktu`, `jumlah_orang`, `dibuat_pada`) VALUES
(1, 'cris', '3', 'a@gmail.com', '56436452', '2025-06-22', '02:00:23', 5, '2025-05-04 21:08:39'),
(2, 'cris', '3', 'yunidiarkrisna@gmail.com', '081272902632', '2025-05-07', '04:13:00', 3, '2025-05-04 21:12:20'),
(3, 'cris', '3', 'yunidiarkrisna@gmail.com', '081272902632', '2025-05-10', '08:19:00', 4, '2025-05-04 21:15:18'),
(4, 'cris', 'ju', 'yunidiarkrisna@gmail.com', '081272902632', '2025-05-16', '04:45:00', 5, '2025-05-05 21:42:48'),
(5, 'cris', 'dfsd', 'yunidiarkrisna@gmail.com', '081272902632', '2025-05-15', '09:57:00', 3, '2025-05-05 23:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','pelanggan') NOT NULL DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `level`) VALUES
(8, 'admin', 'admin@gmailcom', 'admin123', 'admin'),
(9, 'cris', 'cris@gmail.com', '123', 'pelanggan'),
(25, 'asep', 'asep@gmail', '1', 'pelanggan'),
(27, 'kriss', 'krisna@gmail.com', '123', 'pelanggan'),
(30, 'bayu', 'bayu@gmail.com', '`', 'pelanggan'),
(31, 'bayuu', 'bayuu@gmail.com', '1', 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`id_checkout`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `detail_pesanan_ibfk_1` (`id_pesanan`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `fk_keranjang_user` (`id_user`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`) USING BTREE;

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pesanan_pembayaran` (`id_pesanan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_user_pesanan` (`id_user`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `id_checkout` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkout`
--
ALTER TABLE `checkout`
  ADD CONSTRAINT `checkout_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pesanan_pembayaran` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_user_pesanan` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
