-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2019 at 09:30 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maj`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Id` int(11) NOT NULL,
  `kd_cst` varchar(10) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `tempat_lahir` varchar(150) DEFAULT NULL,
  `tgl_lahir` varchar(50) DEFAULT NULL,
  `alamat` text,
  `no_ktp` varchar(20) NOT NULL,
  `no_npwp` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `pekerjaan` varchar(150) DEFAULT NULL,
  `kewarganegaraan` varchar(150) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `date_created` varchar(150) NOT NULL,
  `date_changed` varchar(50) NOT NULL,
  `user_id_created` int(11) NOT NULL,
  `user_id_changed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Id`, `kd_cst`, `nama`, `tempat_lahir`, `tgl_lahir`, `alamat`, `no_ktp`, `no_npwp`, `email`, `telp`, `pekerjaan`, `kewarganegaraan`, `status`, `date_created`, `date_changed`, `user_id_created`, `user_id_changed`) VALUES
(1, 'CST000001', 'Yonathan Rizky Nathanael', '', '1996-08-05', 'Tangerang', '3603120508960011', '', NULL, '', '', 'Indonesia', 1, '2019-11-16', '2019-11-16', 0, 0),
(2, 'CST000002', 'Rojul Dwi', '', '', '', '123', '', NULL, '', '', 'Indonesia', 1, '2019-11-17', '', 0, 0),
(3, 'CST000003', 'Jonathan', '', '', '', '12831283912831', '', NULL, '', '', '', 1, '2019-11-27', '2019-11-27', 0, 7),
(4, 'CST000004', 'ambar', '', '', '', '123123', '', NULL, '12391802938012890312', '', '', 1, '2019-11-27', '2019-11-27', 7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `laba`
--

CREATE TABLE `laba` (
  `Id_laba` int(11) NOT NULL,
  `id_valas` varchar(150) NOT NULL,
  `total` text NOT NULL,
  `tgl_laporan` varchar(150) NOT NULL,
  `date_created` varchar(150) NOT NULL,
  `user_id_created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `laba`
--

INSERT INTO `laba` (`Id_laba`, `id_valas`, `total`, `tgl_laporan`, `date_created`, `user_id_created`) VALUES
(5, '1', '2166667', '2019-11-24', '2019-11-24', NULL),
(6, '2', '0', '2019-11-24', '2019-11-24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `Id` int(11) NOT NULL,
  `id_valas` varchar(150) NOT NULL,
  `nr` varchar(150) NOT NULL,
  `stock_awal` varchar(150) NOT NULL,
  `kd_trx` varchar(150) NOT NULL,
  `trx` int(2) NOT NULL,
  `rate` varchar(150) NOT NULL,
  `user_id_created` int(11) NOT NULL DEFAULT '0',
  `jumlah` varchar(150) NOT NULL,
  `total` varchar(150) NOT NULL,
  `stock_akhir` varchar(150) NOT NULL,
  `date_created` varchar(150) NOT NULL,
  `time_created` varchar(150) NOT NULL,
  `status` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`Id`, `id_valas`, `nr`, `stock_awal`, `kd_trx`, `trx`, `rate`, `user_id_created`, `jumlah`, `total`, `stock_akhir`, `date_created`, `time_created`, `status`) VALUES
(228, '1', '', '0', '', 0, '0', 0, '', '', '0', '2019-11-24', '12:12:16', '1'),
(229, '2', '', '0', '', 0, '0', 0, '', '', '0', '2019-11-24', '12:22:11', '1'),
(231, '1', '15000', '0', 'AS-2019-11-24-0001', 0, '', 0, '1000', '15000000', '1000', '2019-11-24', '19:07:02', '1'),
(232, '1', '15600', '1000', 'AS-2019-11-24-0002', 5, '16000', 0, '1500', '39000000', '2500', '2019-11-24', '19:07:59', '1'),
(233, '1', '15833.333333333', '2500', '2019-11-24-0003', 1, '17000', 0, '500', '47500000', '3000', '2019-11-24', '20:32:06', '1'),
(234, '1', '15833.333333333', '3000', '2019-11-24-0004', 2, '18000', 0, '1000', '31666666.666666', '2000', '2019-11-24', '20:40:37', '1'),
(235, '1', '15756.084157638', '2000', '2020-01-01-0001', 1, '14500', 0, '123', '33450166.666666', '2123', '2020-01-01', '03:11:45', '1');

-- --------------------------------------------------------

--
-- Table structure for table `temp_stock`
--

CREATE TABLE `temp_stock` (
  `Id` int(11) NOT NULL,
  `id_valas` varchar(150) NOT NULL,
  `nr` varchar(150) NOT NULL,
  `stock_awal` varchar(150) NOT NULL,
  `trx` varchar(150) NOT NULL,
  `rate` varchar(150) NOT NULL,
  `jumlah` varchar(150) NOT NULL,
  `total` varchar(150) NOT NULL,
  `stock_akhir` varchar(150) NOT NULL,
  `kd_trx` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_transaksi`
--

CREATE TABLE `temp_transaksi` (
  `Id` int(11) NOT NULL,
  `id_valas` varchar(150) NOT NULL,
  `rate_valas` varchar(150) NOT NULL,
  `jumlah` varchar(150) NOT NULL,
  `total` varchar(150) NOT NULL,
  `kd_trx` varchar(150) NOT NULL,
  `trx` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `Id` int(11) NOT NULL,
  `kd_trx` varchar(150) NOT NULL,
  `customer` varchar(150) NOT NULL,
  `trx` varchar(5) NOT NULL,
  `id_valas` varchar(150) NOT NULL,
  `rate_valas` varchar(150) NOT NULL,
  `jumlah` varchar(150) NOT NULL,
  `total` varchar(150) NOT NULL,
  `user_id_created` int(11) NOT NULL DEFAULT '0',
  `date_created` varchar(150) NOT NULL,
  `time_created` varchar(150) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`Id`, `kd_trx`, `customer`, `trx`, `id_valas`, `rate_valas`, `jumlah`, `total`, `user_id_created`, `date_created`, `time_created`, `status`) VALUES
(202, 'AS-2019-11-24-0002', '', '5', '1', '16000', '1500', '24000000', 0, '2019-11-24', '19:07:59', 1),
(203, '2019-11-24-0003', 'CST000001', '1', '1', '17000', '500', '8500000', 0, '2019-11-24', '20:32:06', 1),
(204, '2019-11-24-0004', 'CST000002', '2', '1', '18000', '1000', '18000000', 0, '2019-11-24', '20:40:37', 1),
(205, '2020-01-01-0001', 'CST000003', '1', '1', '14500', '123', '1783500', 0, '2020-01-01', '03:11:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `date_created` int(11) NOT NULL,
  `date_update` int(11) DEFAULT NULL,
  `user_id_created` int(11) DEFAULT NULL,
  `user_id_update` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Id`, `nama`, `email`, `image`, `password`, `role_id`, `is_active`, `date_created`, `date_update`, `user_id_created`, `user_id_update`) VALUES
(7, 'Yonathan Rizky Nathanael', 'jrizky.jonathan@gmail.com', 'P_20171019_104324_1.jpg', '$2y$10$mNxuahweilflZfgZiIK0gu0V7R2NenmeJU1wV8EoCeZrK9hmPyt22', 1, 1, 1555122623, 1574868188, NULL, 7),
(8, 'M Rojul Dwi Rizky Akbar', 'rojul@rojul.com', 'iherb2.JPG', '$2y$10$7hXTdUL69BhNQBsbEMZq1eXfPmFX5qX39NLs/7SYrPmZgHjqh1sBC', 2, 0, 1555771922, 1574867964, NULL, 7),
(10, 'Nur Kholis', 'kholis@maj.com', 'default.jpg', '$2y$10$utNSNhzDOyDIksoWEVk9UOlHB9iSAf2gQWcDGWqK2NtKHi5xrPp5a', 2, 0, 1557583528, 1574262624, NULL, NULL),
(11, 'Yusuf', 'ucup@gmail.com', 'default.jpg', '$2y$10$jDzWgFM6cU1a4HF7qBliietW4MOuPTIRxbvovIWZBrQ5FIdU4afzC', 5, 0, 1561696589, 0, NULL, NULL),
(12, 'Yonathan Rizky Nathanael', 'yonathan@yonathanrizky.com', 'default.jpg', '$2y$10$.R8M9C5jqwMkf22Zr2pTp.Qys6vSfyYAy1qy025Mc/9SONPzrzdPW', 2, 0, 1562768693, 0, NULL, NULL),
(13, 'User UAT', 'info@yonathanrizky.com', 'default.jpg', '$2y$10$Hum6hHE/rnGOSk7P4/jAjeYMHQyfD2bGOSidEaBq/fCxK8DKyKQry', 2, 1, 1573988652, 1574868067, 1, 0),
(14, 'UAT1', 'uptprokeswankecmoyohulu@gmail.com', 'default.jpg', '$2y$10$UloT5fAm7liI7yJn8ohBTOaCHBvjpqeRD2EBFVm0LWGb81XONqTN2', 3, 1, 1573989024, 1574001788, NULL, NULL),
(15, 'Yonathan Rizky Nathanael', 'jon@gmail.com', 'default.jpg', '$2y$10$D5THJ/hdxNgcEK8TIBIv1OECnjznDN31os1/wO/zBJ.YuQcqN36gK', 1, 1, 1573989654, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `Id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `date_created` date DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  `user_id_created` int(11) DEFAULT NULL,
  `user_id_updated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`Id`, `role_id`, `menu_id`, `date_created`, `date_updated`, `user_id_created`, `user_id_updated`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL),
(2, 1, 2, NULL, NULL, NULL, NULL),
(3, 2, 2, NULL, NULL, NULL, NULL),
(4, 1, 3, NULL, NULL, NULL, NULL),
(7, 1, 10, NULL, NULL, NULL, NULL),
(8, 2, 10, NULL, NULL, NULL, NULL),
(9, 1, 11, NULL, NULL, NULL, NULL),
(10, 2, 11, NULL, NULL, NULL, NULL),
(11, 1, 12, NULL, NULL, NULL, NULL),
(12, 2, 12, NULL, NULL, NULL, NULL),
(13, 2, 13, NULL, NULL, NULL, NULL),
(14, 1, 13, NULL, NULL, NULL, NULL),
(16, 1, 14, NULL, NULL, NULL, NULL),
(17, 5, 2, NULL, NULL, NULL, NULL),
(18, 5, 10, NULL, NULL, NULL, NULL),
(19, 5, 11, NULL, NULL, NULL, NULL),
(20, 5, 12, NULL, NULL, NULL, NULL),
(21, 5, 14, NULL, NULL, NULL, NULL),
(22, 5, 13, NULL, NULL, NULL, NULL),
(23, 1, 16, NULL, NULL, NULL, NULL),
(24, 2, 16, NULL, NULL, NULL, NULL),
(25, 1, 17, NULL, NULL, NULL, NULL),
(26, 5, 17, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `Id` int(11) NOT NULL,
  `menu` varchar(128) NOT NULL,
  `date_created` date DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  `user_id_created` int(11) DEFAULT NULL,
  `user_id_updated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`Id`, `menu`, `date_created`, `date_updated`, `user_id_created`, `user_id_updated`) VALUES
(1, 'Admin', NULL, NULL, NULL, NULL),
(2, 'User', NULL, NULL, NULL, NULL),
(3, 'Menu', NULL, NULL, NULL, NULL),
(10, 'Transaksi', NULL, NULL, NULL, NULL),
(11, 'Rate', NULL, NULL, NULL, NULL),
(12, 'Laporan', NULL, NULL, NULL, NULL),
(13, 'Customer', NULL, NULL, NULL, NULL),
(14, 'Void', NULL, NULL, NULL, NULL),
(16, 'Dashboard', NULL, NULL, NULL, NULL),
(17, 'Master', NULL, NULL, NULL, NULL),
(19, 'Menu Edit', NULL, NULL, NULL, NULL),
(20, 'test123456', NULL, '2019-11-27', NULL, 7),
(21, 'Menu Edit Ubah', NULL, NULL, NULL, NULL),
(22, 'coba', NULL, NULL, NULL, NULL),
(23, 'cba', '2019-11-27', NULL, 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `Id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL,
  `date_created` date DEFAULT NULL,
  `date_updated` date DEFAULT NULL,
  `user_id_created` int(11) DEFAULT NULL,
  `user_id_updated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`Id`, `role`, `date_created`, `date_updated`, `user_id_created`, `user_id_updated`) VALUES
(1, 'Administrator', NULL, NULL, NULL, NULL),
(2, 'Staff', NULL, NULL, NULL, NULL),
(5, 'Direktur', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `Id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `user_id_created` int(11) NOT NULL DEFAULT '0',
  `user_id_updated` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `date_update` date NOT NULL,
  `is_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`Id`, `menu_id`, `title`, `url`, `icon`, `user_id_created`, `user_id_updated`, `date_created`, `date_update`, `is_active`) VALUES
(2, 2, 'My Profile', 'user', 'fas fa-fw fa-user', 0, 0, '0000-00-00', '0000-00-00', 1),
(3, 2, 'Edit Profile', 'user/edit', 'fas fa-fw fa-user-edit', 0, 0, '0000-00-00', '0000-00-00', 1),
(4, 3, 'Menu Management', 'menu', 'fas fa-fw fa-folder', 0, 0, '0000-00-00', '0000-00-00', 1),
(5, 3, 'SubMenu Management', 'menu/submenu', 'fas fa-fw fa-folder-open', 0, 0, '0000-00-00', '0000-00-00', 1),
(6, 1, 'Hak Akses Menu', 'admin/role', 'fas fa-fw fa-user-tie', 0, 0, '0000-00-00', '0000-00-00', 1),
(12, 17, 'Valas', 'master/valas', 'fas fa-money-check-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(13, 10, 'Transaksi Penjualan', 'transaksi/penjualan', 'fas fa-money-bill-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(14, 10, 'Transaksi Pembelian', 'transaksi/pembelian', 'fas fa-money-check-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(15, 1, 'All User', 'admin/user', 'fas fa-fw fa-user-tie', 0, 0, '0000-00-00', '0000-00-00', 1),
(16, 17, 'Stock', 'master/stock', 'fas fa-fw fa-wallet', 0, 0, '0000-00-00', '0000-00-00', 1),
(17, 11, 'Rate', 'rate', 'fas fa-donate', 0, 0, '0000-00-00', '0000-00-00', 1),
(18, 12, 'Laba Rugi', 'laporan/labarugi', 'fas fa-file-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(19, 13, 'Customer', 'customer', 'fas fa-users', 0, 0, '0000-00-00', '0000-00-00', 1),
(20, 12, 'Laporan Penjualan', 'laporan/penjualan', 'fas fa-file-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(21, 12, 'Laporan Pembelian', 'laporan/pembelian', 'fas fa-file-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(22, 14, 'Void Transaksi Penjualan', 'void/penjualan', 'fas fa-money-check-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(23, 14, 'Void Transaksi Pembelian', 'void/pembelian', 'fas fa-money-check-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(24, 2, 'Ganti Password', 'user/changepassword', 'fas fa-fw fa-key', 0, 0, '0000-00-00', '0000-00-00', 1),
(28, 16, 'Dashboard', 'dashboard', 'fas fa-fw fa-tachometer-alt', 0, 0, '0000-00-00', '0000-00-00', 1),
(29, 22, 'test123', 'test', '', 7, 7, '2019-11-27', '2019-11-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `valas`
--

CREATE TABLE `valas` (
  `Id` int(11) NOT NULL,
  `kd_valas` varchar(150) NOT NULL,
  `valas` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `date_created` varchar(150) NOT NULL,
  `time_created` varchar(150) NOT NULL,
  `user_id_created` int(11) NOT NULL DEFAULT '0',
  `user_id_update` int(11) NOT NULL DEFAULT '0',
  `date_update` varchar(150) NOT NULL,
  `time_update` varchar(150) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `valas`
--

INSERT INTO `valas` (`Id`, `kd_valas`, `valas`, `description`, `date_created`, `time_created`, `user_id_created`, `user_id_update`, `date_update`, `time_update`, `status`) VALUES
(1, 'VLS001', 'USD', 'Dollar Amerika', '2019-11-24', '12:12:16', 0, 0, '2019-11-24', '12:30:33', 1),
(2, 'VLS002', 'SGD', 'Dollar Singapura', '2019-11-24', '12:22:11', 0, 0, '2019-11-24', '12:27:34', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `laba`
--
ALTER TABLE `laba`
  ADD PRIMARY KEY (`Id_laba`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `temp_stock`
--
ALTER TABLE `temp_stock`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `temp_transaksi`
--
ALTER TABLE `temp_transaksi`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `valas`
--
ALTER TABLE `valas`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `laba`
--
ALTER TABLE `laba`
  MODIFY `Id_laba` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;
--
-- AUTO_INCREMENT for table `temp_stock`
--
ALTER TABLE `temp_stock`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `temp_transaksi`
--
ALTER TABLE `temp_transaksi`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `valas`
--
ALTER TABLE `valas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
