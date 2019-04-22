-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 22, 2019 at 03:13 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `Id` int(11) NOT NULL,
  `id_valas` int(11) NOT NULL,
  `rate_valas` varchar(150) NOT NULL,
  `jumlah` varchar(150) NOT NULL,
  `hasil` varchar(150) NOT NULL,
  `trx` int(11) NOT NULL,
  `date_created` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`Id`, `id_valas`, `rate_valas`, `jumlah`, `hasil`, `trx`, `date_created`, `status`) VALUES
(14, 1, '14500', '1500000', '103', 1, 1555861577, 1),
(15, 2, '10500', '1000000', '95', 1, 1555862050, 1),
(16, 5, '16500', '500000', '30', 1, 1555862203, 1),
(17, 1, '14400', '1000000', '69', 1, 1555862483, 1),
(18, 2, '10500', '1000000', '95', 1, 1555862602, 1),
(19, 2, '10500', '5000000', '476', 1, 1555862622, 1),
(20, 1, '14700', '15000000', '1020', 1, 1555862704, 1),
(21, 5, '16000', '100000', '6', 1, 1555862731, 1),
(22, 2, '10300', '5000000', '485', 1, 1555862753, 1),
(23, 5, '17000', '200000', '12', 1, 1555862779, 1),
(24, 5, '16000', '32000', '2', 1, 1555862795, 1),
(26, 1, '14500', '100', '1450000', 2, 1555892826, 1),
(27, 5, '16500', '1000', '16500000', 2, 1555892922, 1),
(28, 1, '14300', '1000000', '70', 1, 1555892990, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Id`, `name`, `email`, `image`, `password`, `role_id`, `is_active`, `date_created`) VALUES
(6, 'Yonathan Rizky Nathanael', 'yonathan@yonathanrizky.com', 'icon2.png', '$2y$10$cQ0UQ0d5KcqeRM5qBxhE7.jwb7vKB0orwy.TYPD9J2tPf71t7DA5G', 2, 1, 1555036944),
(7, 'Yonathan Rizky', 'jrizky.jonathan@gmail.com', 'icon1.png', '$2y$10$rcwWNfM8HzgbIa9qWQnBjuxLRc9yZX4Pis7ixUtH0wIS5GzEofMou', 1, 1, 1555122623),
(8, 'M Rojul Dwi Rizky Akbar', 'rojul@rojul.com', 'default.jpg', '$2y$10$7hXTdUL69BhNQBsbEMZq1eXfPmFX5qX39NLs/7SYrPmZgHjqh1sBC', 2, 1, 1555771922);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `Id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`Id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 1, 3),
(7, 1, 10),
(8, 2, 10),
(9, 1, 11),
(10, 2, 11);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `Id` int(11) NOT NULL,
  `menu` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`Id`, `menu`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Menu'),
(10, 'Transaksi'),
(11, 'Rate');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `Id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`Id`, `role`) VALUES
(1, 'Administrator'),
(2, 'Member');

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
  `is_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`Id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 1, 'Dashboard', 'admin', 'fas fa-fw fa-tachometer-alt', 1),
(2, 2, 'My Profile', 'user', 'fas fa-fw fa-user', 1),
(3, 2, 'Edit Profile', 'user/edit', 'fas fa-fw fa-user-edit', 1),
(4, 3, 'Menu Management', 'menu', 'fas fa-fw fa-folder', 1),
(5, 3, 'SubMenu Management', 'menu/submenu', 'fas fa-fw fa-folder-open', 1),
(6, 1, 'Role', 'admin/role', 'fas fa-fw fa-user-tie', 1),
(12, 1, 'Valas', 'admin/valas', 'fas fa-money-check-alt', 1),
(13, 10, 'Transaksi Penjualan', 'transaksi/penjualan', 'fas fa-money-bill-alt', 1),
(14, 10, 'Transaksi Pembelian', 'transaksi/pembelian', 'fas fa-money-check-alt', 1),
(15, 1, 'All User', 'admin/user', 'fas fa-fw fa-user-tie', 1),
(16, 1, 'Stock', 'admin/stock', 'fas fa-fw fa-wallet', 1),
(17, 11, 'Rate', 'rate', 'fas fa-fw-youtube', 1);

-- --------------------------------------------------------

--
-- Table structure for table `valas`
--

CREATE TABLE `valas` (
  `Id` int(11) NOT NULL,
  `valas` varchar(50) NOT NULL,
  `stock` varchar(150) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `valas`
--

INSERT INTO `valas` (`Id`, `valas`, `stock`, `status`) VALUES
(1, 'USD', '238', 1),
(2, 'SGD', '3849', 1),
(5, 'EUR', '1000', 1);

--
-- Indexes for dumped tables
--

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
  ADD UNIQUE KEY `Id` (`Id`);

--
-- Indexes for table `valas`
--
ALTER TABLE `valas`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `valas`
--
ALTER TABLE `valas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
