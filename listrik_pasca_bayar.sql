-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jul 2024 pada 12.12
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `listrik_pasca_bayar`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pelanggan_daya_900` ()  BEGIN
    SELECT p.id_pelanggan, p.nama_pelanggan, t.daya 
    FROM pelanggan p
    JOIN tarif t ON p.id_tarif = t.id_tarif
    WHERE t.daya = 900;
END$$

--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `total_penggunaan_perbulan` (`id_pelanggan` INT, `bulan` VARCHAR(20), `tahun` VARCHAR(4)) RETURNS INT(11) BEGIN
    DECLARE total INT;
    SELECT SUM(meter_ahir - meter_awal) INTO total
    FROM penggunaan
    WHERE id_pelanggan = id_pelanggan AND bulan = bulan AND tahun = tahun;
    RETURN total;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `level`
--

CREATE TABLE `level` (
  `id_level` int(11) NOT NULL,
  `nama_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `level`
--

INSERT INTO `level` (`id_level`, `nama_level`) VALUES
(1, 'Administrator'),
(2, 'Pelanggan'),
(3, 'Administrator'),
(4, 'Pelanggan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_tagihan`
--

CREATE TABLE `log_tagihan` (
  `id_log` int(11) NOT NULL,
  `id_tagihan` int(11) DEFAULT NULL,
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nomor_kwh` varchar(50) DEFAULT NULL,
  `nama_pelanggan` varchar(50) DEFAULT NULL,
  `alamat` text,
  `id_tarif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `password`, `nomor_kwh`, `nama_pelanggan`, `alamat`, `id_tarif`) VALUES
(1, 'pelanggan1', 'pelanggan1pass', '1234567890', 'Pelanggan Satu', 'Alamat 1', 1),
(2, 'pelanggan2', 'pelanggan2pass', '0987654321', 'Pelanggan Dua', 'Alamat 2', 2),
(3, 'pelanggan3', '123456789', '138981487', 'Pelanggan 3', 'asca', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_tagihan` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `bulan_bayar` varchar(20) DEFAULT NULL,
  `biaya_admin` float DEFAULT NULL,
  `total_bayar` float DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penggunaan`
--

CREATE TABLE `penggunaan` (
  `id_penggunaan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `meter_awal` int(11) DEFAULT NULL,
  `meter_ahir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penggunaan`
--

INSERT INTO `penggunaan` (`id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `meter_awal`, `meter_ahir`) VALUES
(1, 1, 'Januari', '2024', 100, 200);

--
-- Trigger `penggunaan`
--
DELIMITER $$
CREATE TRIGGER `after_insert_penggunaan` AFTER INSERT ON `penggunaan` FOR EACH ROW BEGIN
    DECLARE jumlah_meter INT;
    SET jumlah_meter = NEW.meter_ahir - NEW.meter_awal;
    INSERT INTO tagihan (id_penggunaan, id_pelanggan, bulan, tahun, jumlah_meter, status) 
    VALUES (NEW.id_penggunaan, NEW.id_pelanggan, NEW.bulan, NEW.tahun, jumlah_meter, 'Belum Lunas');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

CREATE TABLE `tagihan` (
  `id_tagihan` int(11) NOT NULL,
  `id_penggunaan` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `jumlah_meter` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tagihan`
--

INSERT INTO `tagihan` (`id_tagihan`, `id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `jumlah_meter`, `status`) VALUES
(1, 3, 3, '2', '2024', 50, 'Belum Lunas'),
(2, 4, 0, '5', '2024', 100, 'Belum Lunas'),
(3, 5, 3, 'Mei', '2024', 20, 'Belum Lunas'),
(4, 6, 7, 'Oktober', '2024', 100, 'Belum Lunas'),
(5, 7, 2, 'Juli', '2024', 50, 'Belum Lunas'),
(6, 8, 2, 'Juli', '2024', 100, 'Belum Lunas'),
(7, 9, 2, 'Januari', '2024', 25, 'Belum Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarif`
--

CREATE TABLE `tarif` (
  `id_tarif` int(11) NOT NULL,
  `daya` int(11) DEFAULT NULL,
  `tarifperkwh` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tarif`
--

INSERT INTO `tarif` (`id_tarif`, `daya`, `tarifperkwh`) VALUES
(1, 900, 1360),
(2, 450, 500),
(3, 500, 999);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nama_admin` varchar(50) DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_admin`, `id_level`) VALUES
(1, 'admin', 'adminpass', 'Admin', 1),
(2, 'user1', 'user1pass', 'User1', 2),
(3, 'admin2', 'admin2pass', 'Admin 2', 1),
(6, 'user2', 'user2pass', 'User 2', 2);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_penggunaan_listrik`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_penggunaan_listrik` (
`id_pelanggan` int(11)
,`nama_pelanggan` varchar(50)
,`bulan` varchar(20)
,`tahun` varchar(4)
,`meter_awal` int(11)
,`meter_ahir` int(11)
,`total_meter` bigint(12)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_tagihan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_tagihan` (
`id_pelanggan` int(11)
,`nama_pelanggan` varchar(50)
,`bulan` varchar(20)
,`tahun` varchar(4)
,`meter_awal` int(11)
,`meter_ahir` int(11)
,`jumlah_meter` bigint(12)
,`tarifperkwh` float
,`total_tagihan` double
);

-- --------------------------------------------------------

--
-- Struktur untuk view `view_penggunaan_listrik`
--
DROP TABLE IF EXISTS `view_penggunaan_listrik`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_penggunaan_listrik`  AS  select `p`.`id_pelanggan` AS `id_pelanggan`,`p`.`nama_pelanggan` AS `nama_pelanggan`,`pg`.`bulan` AS `bulan`,`pg`.`tahun` AS `tahun`,`pg`.`meter_awal` AS `meter_awal`,`pg`.`meter_ahir` AS `meter_ahir`,(`pg`.`meter_ahir` - `pg`.`meter_awal`) AS `total_meter` from (`pelanggan` `p` join `penggunaan` `pg` on((`p`.`id_pelanggan` = `pg`.`id_pelanggan`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_tagihan`
--
DROP TABLE IF EXISTS `view_tagihan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_tagihan`  AS  select `p`.`id_pelanggan` AS `id_pelanggan`,`pel`.`nama_pelanggan` AS `nama_pelanggan`,`p`.`bulan` AS `bulan`,`p`.`tahun` AS `tahun`,`p`.`meter_awal` AS `meter_awal`,`p`.`meter_ahir` AS `meter_ahir`,(`p`.`meter_ahir` - `p`.`meter_awal`) AS `jumlah_meter`,`t`.`tarifperkwh` AS `tarifperkwh`,((`p`.`meter_ahir` - `p`.`meter_awal`) * `t`.`tarifperkwh`) AS `total_tagihan` from ((`penggunaan` `p` join `pelanggan` `pel` on((`p`.`id_pelanggan` = `pel`.`id_pelanggan`))) join `tarif` `t` on((`pel`.`id_tarif` = `t`.`id_tarif`))) ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indeks untuk tabel `log_tagihan`
--
ALTER TABLE `log_tagihan`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indeks untuk tabel `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD PRIMARY KEY (`id_penggunaan`);

--
-- Indeks untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id_tagihan`);

--
-- Indeks untuk tabel `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_level` (`id_level`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `log_tagihan`
--
ALTER TABLE `log_tagihan`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penggunaan`
--
ALTER TABLE `penggunaan`
  MODIFY `id_penggunaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id_tagihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tarif`
--
ALTER TABLE `tarif`
  MODIFY `id_tarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
