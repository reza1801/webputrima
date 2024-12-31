-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Waktu pembuatan: 31 Des 2024 pada 04.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dataputrima`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `arsip`
--

CREATE TABLE `arsip` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `rt_rw` varchar(10) DEFAULT NULL,
  `pinjaman` decimal(15,2) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT curdate(),
  `angsuran1` decimal(10,2) DEFAULT NULL,
  `angsuran2` decimal(10,2) DEFAULT NULL,
  `angsuran3` decimal(10,2) DEFAULT NULL,
  `angsuran4` decimal(10,2) DEFAULT NULL,
  `angsuran5` decimal(10,2) DEFAULT NULL,
  `angsuran6` decimal(10,2) DEFAULT NULL,
  `angsuran7` decimal(10,2) DEFAULT NULL,
  `angsuran8` decimal(10,2) DEFAULT NULL,
  `angsuran9` decimal(10,2) DEFAULT NULL,
  `angsuran10` decimal(10,2) DEFAULT NULL,
  `angsuran1_date` datetime DEFAULT NULL,
  `angsuran2_date` datetime DEFAULT NULL,
  `angsuran3_date` datetime DEFAULT NULL,
  `angsuran4_date` datetime DEFAULT NULL,
  `angsuran5_date` datetime DEFAULT NULL,
  `angsuran6_date` datetime DEFAULT NULL,
  `angsuran7_date` datetime DEFAULT NULL,
  `angsuran8_date` datetime DEFAULT NULL,
  `angsuran9_date` datetime DEFAULT NULL,
  `angsuran10_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `arsip`
--

INSERT INTO `arsip` (`id`, `nik`, `nama`, `alamat`, `rt_rw`, `pinjaman`, `tanggal`, `angsuran1`, `angsuran2`, `angsuran3`, `angsuran4`, `angsuran5`, `angsuran6`, `angsuran7`, `angsuran8`, `angsuran9`, `angsuran10`, `angsuran1_date`, `angsuran2_date`, `angsuran3_date`, `angsuran4_date`, `angsuran5_date`, `angsuran6_date`, `angsuran7_date`, `angsuran8_date`, `angsuran9_date`, `angsuran10_date`) VALUES
(21, '010101', 'Riyanto', 'sangkanayu', '12/04', 500000.00, '2024-12-31', 300000.00, 60000.00, 240000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2024-12-31 03:34:16', '2024-12-31 03:34:29', '2024-12-31 03:35:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id`, `username`, `password`) VALUES
(1, 'admin1', '1234');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nasabah`
--

CREATE TABLE `nasabah` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `rt_rw` varchar(10) NOT NULL,
  `pinjaman` decimal(15,2) NOT NULL,
  `saldo` decimal(15,2) DEFAULT NULL,
  `angsuran1` decimal(10,2) DEFAULT NULL,
  `angsuran1_date` datetime DEFAULT NULL,
  `angsuran2` decimal(10,2) DEFAULT NULL,
  `angsuran2_date` datetime DEFAULT NULL,
  `angsuran3` decimal(10,2) DEFAULT NULL,
  `angsuran3_date` datetime DEFAULT NULL,
  `angsuran4` decimal(10,2) DEFAULT NULL,
  `angsuran4_date` datetime DEFAULT NULL,
  `angsuran5` decimal(10,2) DEFAULT NULL,
  `angsuran5_date` datetime DEFAULT NULL,
  `angsuran6` decimal(10,2) DEFAULT NULL,
  `angsuran6_date` datetime DEFAULT NULL,
  `angsuran7` decimal(10,2) DEFAULT NULL,
  `angsuran7_date` datetime DEFAULT NULL,
  `angsuran8` decimal(10,2) DEFAULT NULL,
  `angsuran8_date` datetime DEFAULT NULL,
  `angsuran9` decimal(10,2) DEFAULT NULL,
  `angsuran9_date` datetime DEFAULT NULL,
  `angsuran10` decimal(10,2) DEFAULT NULL,
  `angsuran10_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `nasabah`
--

INSERT INTO `nasabah` (`id`, `nik`, `nama`, `alamat`, `rt_rw`, `pinjaman`, `saldo`, `angsuran1`, `angsuran1_date`, `angsuran2`, `angsuran2_date`, `angsuran3`, `angsuran3_date`, `angsuran4`, `angsuran4_date`, `angsuran5`, `angsuran5_date`, `angsuran6`, `angsuran6_date`, `angsuran7`, `angsuran7_date`, `angsuran8`, `angsuran8_date`, `angsuran9`, `angsuran9_date`, `angsuran10`, `angsuran10_date`) VALUES
(25, '010102', 'Riyanto', 'sangkanayu', '12/04', 1000000.00, NULL, 120000.00, '2024-12-31 03:42:53', 120000.00, '2024-12-31 03:42:58', 120000.00, '2024-12-31 03:43:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, '010101', 'Riyanto', 'serang', '12/04', 1000000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `arsip`
--
ALTER TABLE `arsip`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `arsip`
--
ALTER TABLE `arsip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
