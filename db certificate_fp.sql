-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 04:42 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `certificate_fp`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenislomba`
--

DROP TABLE IF EXISTS `jenislomba`;
CREATE TABLE `jenislomba` (
  `id_jenislomba` int NOT NULL,
  `jenis_lomba` varchar(50) DEFAULT NULL
) ;

--
-- Dumping data for table `jenislomba`
--

INSERT INTO `jenislomba` (`id_jenislomba`, `jenis_lomba`) VALUES
(1, 'Lomba BTQ'),
(2, 'Lomba Pidato'),
(3, 'Lomba Kaligrafi'),
(5, 'Lomba CCI');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

DROP TABLE IF EXISTS `kegiatan`;
CREATE TABLE `kegiatan` (
  `id_kegiatan` int NOT NULL,
  `nama_penyelenggara` varchar(255) DEFAULT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `tema` varchar(50) DEFAULT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `tempat_via` varchar(50) DEFAULT NULL,
  `penomoran_sertifikat` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id_kegiatan`, `nama_penyelenggara`, `nama_kegiatan`, `tema`, `tanggal_kegiatan`, `tempat_via`, `penomoran_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 'DKM Jamie Su\'ada', 'Lomba Muharram', 'Mewujudkan remaja islami yang aktif dan berkreasi ', '2024-12-29', 'Masjid Jamie Su\'ada Ciasem', '152/B/PENGURUS-DKM/VII/1445H', '2024-12-28 23:19:28', NULL),
(3, 'DKM Jamie Su\'ada', 'Lomba Muharram', 'Mewujudkan remaja islami yang aktif dan berkreasi ', '2024-12-29', 'Masjid Jamie Su\'ada Ciasem', '152/B/PENGURUS-DKM/VII/1445H', '2024-12-29 05:29:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peringkat`
--

DROP TABLE IF EXISTS `peringkat`;
CREATE TABLE `peringkat` (
  `id_peringkat` int NOT NULL,
  `peringkat` varchar(50) DEFAULT NULL
);

--
-- Dumping data for table `peringkat`
--

INSERT INTO `peringkat` (`id_peringkat`, `peringkat`) VALUES
(1, 'Juara 2'),
(2, 'Juara 3'),
(3, 'Juara 1');

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

DROP TABLE IF EXISTS `peserta`;
CREATE TABLE `peserta` (
  `id_peserta` int NOT NULL,
  `id_kegiatan` int NOT NULL,
  `id_peringkat` int NOT NULL,
  `id_jenislomba` int NOT NULL,
  `nama_peserta` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` text,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id_peserta`, `id_kegiatan`, `id_peringkat`, `id_jenislomba`, `nama_peserta`, `no_telepon`, `email`, `alamat`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Bintu Humaira Zaenal ', '081911418891', 'bintuhumairazainal@gmail.com', 'Subang', 'Mahasiswi', '2024-12-28 23:19:50', NULL),
(2, 1, 2, 2, 'Alya Lucyana Azhari', '085697322030', 'alyalucyanaazhari@gmail.com', 'Kali Jeruk', 'Mahasiswi', '2024-12-28 23:19:50', NULL),
(3, 1, 2, 3, 'Nisrina Fauziah Azzayina', '082123096522', 'nisrinafauziahazzayina@gmail.com', 'Kosambi', 'Mahasiswi', '2024-12-28 23:19:50', NULL),
(8, 3, 1, 5, 'Widia Kamelia', '081732948329', 'kameliawidia@gmail.com', 'Purwokerto', 'Mahasiswi', '2024-12-29 05:29:53', '2024-12-29 05:30:07'),
(9, 3, 3, 1, 'Belta Matuwo', '089364928305', 'matuwobelta@gmail.com', 'Mandala', 'Siswi', '2024-12-29 05:29:53', NULL),
(10, 3, 2, 2, 'Gafi Tandala', '083018285943', 'tandalagafi@gmail.com', 'Cikarang', 'Mahasiswa', '2024-12-29 05:29:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

DROP TABLE IF EXISTS `sertifikat`;
CREATE TABLE `sertifikat` (
  `id_sertifikat` int NOT NULL,
  `id_peserta` int NOT NULL,
  `id_kegiatan` int NOT NULL,
  `id_template` int NOT NULL,
  `hasil_sertifikat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`id_sertifikat`, `id_peserta`, `id_kegiatan`, `id_template`, `hasil_sertifikat`, `created_at`) VALUES
(1, 1, 1, 1, 'Sertifikat_Lomba Muharram_Bintu Humaira Zaenal .pdf', '2024-12-29 00:48:49'),
(3, 8, 3, 3, 'Sertifikat_Lomba Muharram_Widia Kamelia.pdf', '2024-12-29 05:30:36');

-- --------------------------------------------------------

--
-- Table structure for table `template_sertifikat`
--

DROP TABLE IF EXISTS `template_sertifikat`;
CREATE TABLE `template_sertifikat` (
  `id_template` int NOT NULL,
  `id_kegiatan` int NOT NULL,
  `logo_organisasi` varchar(255) NOT NULL,
  `logo_sponsor` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `bahasa` char(10) NOT NULL,
  `ukuran` char(50) NOT NULL,
  `fontCertificate` char(50) NOT NULL,
  `fontNama` char(50) NOT NULL,
  `NamaTtd1` char(255) NOT NULL,
  `jabatan1` char(255) NOT NULL,
  `scanTtd1` varchar(255) NOT NULL,
  `namaTtd2` char(255) DEFAULT NULL,
  `jabatan2` char(255) DEFAULT NULL,
  `scanTtd2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

--
-- Dumping data for table `template_sertifikat`
--

INSERT INTO `template_sertifikat` (`id_template`, `id_kegiatan`, `logo_organisasi`, `logo_sponsor`, `background`, `bahasa`, `ukuran`, `fontCertificate`, `fontNama`, `NamaTtd1`, `jabatan1`, `scanTtd1`, `namaTtd2`, `jabatan2`, `scanTtd2`, `created_at`, `updated_at`) VALUES
(1, 1, 'dkmsuada.jpeg', 'irmadaofficial.jpeg', '1.png', 'id', 'landscape_center', 'minatur', 'times_new_roman', 'H. Firman Ayatullah', 'Ketua DKM Su\'ada', 'ttd1.png', 'Fahmi Hilmawan', 'Ketua Pelaksana', 'ttd2.png', '2024-12-28 23:19:28', NULL),
(3, 3, 'dkmsuada.jpeg', 'irmadaofficial.jpeg', '2.png', 'id', 'landscape_center', 'minatur', 'times_new_roman', 'H. Firman Ayatullah', 'Ketua DKM Su\'ada', 'ttd1.png', 'Fahmi Hilmawan', 'Ketua Pelaksana', 'ttd2.png', '2024-12-29 05:29:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_user` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `email`, `username`, `password`) VALUES
(1, 'DKM Su\'ada', 'dkmsuadaciasem@gmail.com', 'dkmsuada', '98f4b8969093f73ce31a5fd6c2fc6006d738b1f2eb01fa2d7863f306980f1f7b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenislomba`
--
ALTER TABLE `jenislomba`
  ADD PRIMARY KEY (`id_jenislomba`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`);

--
-- Indexes for table `peringkat`
--
ALTER TABLE `peringkat`
  ADD PRIMARY KEY (`id_peringkat`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_peserta`),
  ADD KEY `peserta_ibfk_1` (`id_kegiatan`),
  ADD KEY `id_peringkat` (`id_peringkat`),
  ADD KEY `id_jenislomba` (`id_jenislomba`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`id_sertifikat`),
  ADD KEY `id_peserta` (`id_peserta`),
  ADD KEY `id_kegiatan` (`id_kegiatan`),
  ADD KEY `id_template` (`id_template`);

--
-- Indexes for table `template_sertifikat`
--
ALTER TABLE `template_sertifikat`
  ADD PRIMARY KEY (`id_template`),
  ADD KEY `id_kegiatan` (`id_kegiatan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenislomba`
--
ALTER TABLE `jenislomba`
  MODIFY `id_jenislomba` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peringkat`
--
ALTER TABLE `peringkat`
  MODIFY `id_peringkat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peserta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `id_sertifikat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `template_sertifikat`
--
ALTER TABLE `template_sertifikat`
  MODIFY `id_template` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peserta`
--
ALTER TABLE `peserta`
  ADD CONSTRAINT `peserta_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `peserta_ibfk_2` FOREIGN KEY (`id_peringkat`) REFERENCES `peringkat` (`id_peringkat`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `peserta_ibfk_3` FOREIGN KEY (`id_jenislomba`) REFERENCES `jenislomba` (`id_jenislomba`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `sertifikat_ibfk_1` FOREIGN KEY (`id_peserta`) REFERENCES `peserta` (`id_peserta`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `sertifikat_ibfk_2` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `sertifikat_ibfk_3` FOREIGN KEY (`id_template`) REFERENCES `template_sertifikat` (`id_template`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `template_sertifikat`
--
ALTER TABLE `template_sertifikat`
  ADD CONSTRAINT `template_sertifikat_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
