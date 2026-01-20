-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2026 at 10:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cleantrans`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id_jenis` int(11) NOT NULL,
  `nama_sampah` varchar(100) NOT NULL,
  `harga_per_kg` decimal(10,2) NOT NULL,
  `satuan` enum('kg','pcs','liter') DEFAULT 'kg',
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_sampah`
--

INSERT INTO `jenis_sampah` (`id_jenis`, `nama_sampah`, `harga_per_kg`, `satuan`, `deskripsi`, `created_at`) VALUES
(1, 'Plastik PET', 3000.00, 'kg', 'Botol plastik bekas minuman', '2026-01-17 21:08:23'),
(2, 'Kardus', 2000.00, 'kg', 'Kardus bekas kemasan', '2026-01-17 21:08:23'),
(3, 'Kertas HVS', 2500.00, 'kg', 'Kertas bekas kantor/sekolah', '2026-01-17 21:08:23'),
(4, 'Kaleng Aluminium', 5000.00, 'kg', 'Kaleng bekas minuman', '2026-01-17 21:08:23'),
(5, 'Botol Kaca', 1500.00, 'kg', 'Botol kaca bekas', '2026-01-17 21:08:23'),
(6, 'Besi/Logam', 4000.00, 'kg', 'Besi bekas dan logam lainnya', '2026-01-17 21:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--

CREATE TABLE `log_activity` (
  `id_log` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nasabah`
--

CREATE TABLE `nasabah` (
  `id_nasabah` varchar(10) NOT NULL,
  `nama_nasabah` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `saldo` decimal(15,2) DEFAULT 0.00,
  `tgl_daftar` date NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nasabah`
--

INSERT INTO `nasabah` (`id_nasabah`, `nama_nasabah`, `alamat`, `no_telp`, `email`, `saldo`, `tgl_daftar`, `status`) VALUES
('NSB0001', 'Budi Santoso', 'Jl. Merdeka No. 123, Bandung', '081234567890', 'budi@email.com', 50000.00, '2024-01-15', 'aktif'),
('NSB0002', 'Siti Rahma', 'Jl. Sudirman No. 45, Bandung', '082345678901', 'siti@email.com', 75000.00, '2024-01-20', 'aktif'),
('NSB0003', 'Ahmad Fauzi', 'Jl. Asia Afrika No. 78, Bandung', '083456789012', 'ahmad@email.com', 30000.00, '2024-02-01', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` varchar(15) NOT NULL,
  `id_nasabah` varchar(10) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `harga_per_kg` decimal(10,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_nasabah`, `id_jenis`, `tanggal_transaksi`, `berat`, `harga_per_kg`, `total`, `keterangan`, `created_by`) VALUES
('TRX202401150001', 'NSB0001', 1, '2024-01-15 10:30:00', 5.00, 3000.00, 15000.00, NULL, 'admin123'),
('TRX202401150002', 'NSB0001', 2, '2024-01-15 11:00:00', 10.00, 2000.00, 20000.00, NULL, 'admin123'),
('TRX202401200001', 'NSB0002', 3, '2024-01-20 09:15:00', 8.00, 2500.00, 20000.00, NULL, 'admin123'),
('TRX202401200002', 'NSB0002', 4, '2024-01-20 14:30:00', 3.00, 5000.00, 15000.00, NULL, 'admin123'),
('TRX202402010001', 'NSB0003', 5, '2024-02-01 13:45:00', 6.00, 1500.00, 9000.00, NULL, 'admin123'),
('TRX202402010002', 'NSB0003', 6, '2024-02-01 15:20:00', 4.00, 4000.00, 16000.00, NULL, 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `level` enum('admin','nasabah') NOT NULL DEFAULT 'nasabah',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama`, `level`, `created_at`, `last_login`) VALUES
(1, 'admin123', '0192023a7bbd73250516f069df18b500', 'Administrator CleanTrans', 'admin', '2026-01-17 21:08:23', NULL),
(2, 'budi123', 'f5d9e3c3b3e3d3c3b3e3d3c3b3e3d3c3', 'Budi Santoso', 'nasabah', '2026-01-17 21:08:23', NULL),
(3, 'siti123', 'f5d9e3c3b3e3d3c3b3e3d3c3b3e3d3c3', 'Siti Rahma', 'nasabah', '2026-01-17 21:08:23', NULL),
(4, 'ahmad123', 'f5d9e3c3b3e3d3c3b3e3d3c3b3e3d3c3', 'Ahmad Fauzi', 'nasabah', '2026-01-17 21:08:23', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_dashboard_stats`
-- (See below for the actual view)
--
CREATE TABLE `v_dashboard_stats` (
`total_nasabah` bigint(21)
,`total_transaksi` bigint(21)
,`total_pendapatan` decimal(37,2)
,`total_sampah_kg` decimal(32,2)
,`total_jenis_sampah` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_laporan_transaksi`
-- (See below for the actual view)
--
CREATE TABLE `v_laporan_transaksi` (
`id_transaksi` varchar(15)
,`tanggal_transaksi` datetime
,`id_nasabah` varchar(10)
,`nama_nasabah` varchar(100)
,`nama_sampah` varchar(100)
,`berat` decimal(10,2)
,`satuan` enum('kg','pcs','liter')
,`harga_per_kg` decimal(10,2)
,`total` decimal(15,2)
,`keterangan` text
);

-- --------------------------------------------------------

--
-- Structure for view `v_dashboard_stats`
--
DROP TABLE IF EXISTS `v_dashboard_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_dashboard_stats`  AS SELECT (select count(0) from `nasabah` where `nasabah`.`status` = 'aktif') AS `total_nasabah`, (select count(0) from `transaksi`) AS `total_transaksi`, (select sum(`transaksi`.`total`) from `transaksi`) AS `total_pendapatan`, (select sum(`transaksi`.`berat`) from `transaksi`) AS `total_sampah_kg`, (select count(0) from `jenis_sampah`) AS `total_jenis_sampah` ;

-- --------------------------------------------------------

--
-- Structure for view `v_laporan_transaksi`
--
DROP TABLE IF EXISTS `v_laporan_transaksi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan_transaksi`  AS SELECT `t`.`id_transaksi` AS `id_transaksi`, `t`.`tanggal_transaksi` AS `tanggal_transaksi`, `n`.`id_nasabah` AS `id_nasabah`, `n`.`nama_nasabah` AS `nama_nasabah`, `j`.`nama_sampah` AS `nama_sampah`, `t`.`berat` AS `berat`, `j`.`satuan` AS `satuan`, `t`.`harga_per_kg` AS `harga_per_kg`, `t`.`total` AS `total`, `t`.`keterangan` AS `keterangan` FROM ((`transaksi` `t` join `nasabah` `n` on(`t`.`id_nasabah` = `n`.`id_nasabah`)) join `jenis_sampah` `j` on(`t`.`id_jenis` = `j`.`id_jenis`)) ORDER BY `t`.`tanggal_transaksi` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD PRIMARY KEY (`id_jenis`),
  ADD KEY `idx_nama` (`nama_sampah`);

--
-- Indexes for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id_nasabah`),
  ADD KEY `idx_nama` (`nama_nasabah`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_jenis` (`id_jenis`),
  ADD KEY `idx_nasabah` (`id_nasabah`),
  ADD KEY `idx_tanggal` (`tanggal_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_level` (`level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_sampah` (`id_jenis`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
