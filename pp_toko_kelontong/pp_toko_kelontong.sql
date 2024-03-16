-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Des 2023 pada 22.43
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pp_toko_kelontong`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_bar` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `fk_id_kat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(15) NOT NULL,
  `harga` int(11) NOT NULL,
  `exp_date` date NOT NULL,
  `kode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_bar`, `nama`, `fk_id_kat`, `jumlah`, `satuan`, `harga`, `exp_date`, `kode`) VALUES
(13, 'Susu Ultramilk Coklat 200 ML', 2, 40, 'dus', 60000, '2025-11-21', 'M013'),
(14, 'Qtela Singkong Balado 185 gr', 5, 50, 'bungkus', 10000, '2025-12-18', 'S014'),
(15, 'Minyak Goreng Sunco 5 L', 3, 50, 'botol', 100000, '2026-06-12', 'MGDBM015'),
(16, 'Ciptadent Fresh Mint 225 gr', 4, 45, 'kotak', 19000, '2024-07-18', 'PMDK016'),
(23, 'Gulaku Gula Tebu 1 kg', 10, 25, 'bungkus', 40000, '2025-07-17', 'BMD023'),
(29, 'Lifebuoy Body Wash Lemon Fresh 825 ML', 4, 45, 'bungkus', 35000, '2025-05-07', 'PMDK029');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_barkel` int(11) NOT NULL,
  `fk_id_transaksi` int(11) NOT NULL,
  `fk_id_bar` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_barkel`, `fk_id_transaksi`, `fk_id_bar`, `jumlah`, `total_harga`) VALUES
(51, 56, 14, 5, 50000),
(52, 56, 13, 2, 120000),
(53, 56, 23, 1, 41000),
(54, 59, 14, 3, 30000),
(55, 59, 16, 2, 38000),
(56, 59, 15, 5, 500000),
(58, 60, 14, 7, 70000),
(59, 61, 13, 8, 480000),
(60, 61, 15, 5, 500000),
(61, 61, 23, 4, 160000),
(62, 62, 15, 15, 1500000),
(63, 62, 16, 18, 342000),
(64, 62, 13, 15, 900000),
(65, 62, 23, 3, 120000),
(66, 63, 13, 5, 300000),
(67, 64, 15, 10, 1000000),
(68, 65, 13, 3, 180000),
(69, 65, 16, 5, 95000),
(70, 65, 14, 5, 50000),
(71, 66, 14, 5, 50000),
(73, 71, 23, 5, 200000),
(77, 71, 14, 1, 10000),
(79, 73, 14, 5, 50000),
(80, 73, 16, 3, 57000),
(81, 75, 16, 2, 38000),
(82, 75, 23, 2, 80000),
(83, 75, 29, 5, 175000);

--
-- Trigger `barang_keluar`
--
DELIMITER $$
CREATE TRIGGER `BarangKeluar_AD` AFTER DELETE ON `barang_keluar` FOR EACH ROW BEGIN
	UPDATE barang SET jumlah = jumlah + old.jumlah WHERE barang.id_bar = old.fk_id_bar;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BarangKeluar_BI` BEFORE INSERT ON `barang_keluar` FOR EACH ROW BEGIN
    DECLARE stok_saat_ini INT;
    SELECT jumlah INTO stok_saat_ini FROM barang WHERE barang.id_bar = NEW.fk_id_bar;
    IF NEW.jumlah > stok_saat_ini THEN
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok Barang Tidak Cukup.';
    ELSE
	UPDATE barang AS b SET b.jumlah = b.jumlah - NEW.jumlah WHERE b.id_bar = NEW.fk_id_bar;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BarangKeluar_BU` BEFORE UPDATE ON `barang_keluar` FOR EACH ROW BEGIN
	DECLARE stok_awal INT;
        SET stok_awal = (SELECT jumlah FROM barang WHERE id_bar = old.fk_id_bar) + old.jumlah;
        IF NEW.jumlah > stok_awal THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok Barang Tidak Cukup.';
        ELSE
            UPDATE barang SET jumlah = stok_awal - NEW.jumlah WHERE id_bar = old.fk_id_bar;
        END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_barmas` int(11) NOT NULL,
  `fk_id_transaksi` int(11) NOT NULL,
  `fk_id_bar` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `fk_id_dist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_barmas`, `fk_id_transaksi`, `fk_id_bar`, `jumlah`, `total_harga`, `fk_id_dist`) VALUES
(34, 55, 23, 15, 440000, 19),
(35, 55, 15, 25, 2750000, 21),
(37, 55, 13, 10, 660000, 21),
(38, 57, 23, 10, 500000, 20),
(39, 57, 16, 20, 300000, 20),
(40, 58, 14, 25, 200000, 21),
(41, 58, 15, 10, 1100000, 17),
(42, 67, 13, 13, 715000, 19),
(44, 70, 14, 6, 66000, 18),
(45, 72, 16, 5, 85000, 19),
(46, 74, 29, 25, 800000, 19),
(47, 74, 23, 15, 562500, 21);

--
-- Trigger `barang_masuk`
--
DELIMITER $$
CREATE TRIGGER `BarangMasuk_AI` AFTER INSERT ON `barang_masuk` FOR EACH ROW BEGIN
    UPDATE barang AS b 
    SET b.jumlah = b.jumlah + NEW.jumlah WHERE b.id_bar = NEW.fk_id_bar;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BarangMasuk_BD` AFTER DELETE ON `barang_masuk` FOR EACH ROW BEGIN
    DECLARE stok_setelah_delete INT;
    SET stok_setelah_delete = (SELECT jumlah - old.jumlah FROM barang WHERE id_bar = old.fk_id_bar);
    IF stok_setelah_delete >= 0 THEN
        UPDATE barang SET jumlah = stok_setelah_delete WHERE id_bar = old.fk_id_bar;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `BarangMasuk_BU` BEFORE UPDATE ON `barang_masuk` FOR EACH ROW BEGIN
    DECLARE new_stock INT;    
    SET new_stock = (SELECT jumlah - OLD.jumlah + NEW.jumlah FROM barang WHERE id_bar = OLD.fk_id_bar);
    IF new_stock >= 0 THEN
        UPDATE barang SET jumlah = new_stock WHERE id_bar = OLD.fk_id_bar;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Update rejected: Insufficient stock.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `data_barang`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `data_barang` (
`id_bar` int(11)
,`nama` varchar(50)
,`kategori` varchar(30)
,`jumlah` int(11)
,`satuan` varchar(15)
,`harga` int(11)
,`exp_date` date
,`kode` varchar(15)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `detail_barangkeluar`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `detail_barangkeluar` (
`id_barkel` int(11)
,`fk_id_bar` int(11)
,`fk_id_transaksi` int(11)
,`jumlah` int(11)
,`total_harga` int(11)
,`nama` varchar(50)
,`harga` int(11)
,`kategori` varchar(30)
,`tanggal_transaksi` timestamp
,`kode_transaksi` varchar(25)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `detail_barangmasuk`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `detail_barangmasuk` (
`id_barmas` int(11)
,`fk_id_transaksi` int(11)
,`fk_id_bar` int(11)
,`jumlah` int(11)
,`total_harga` int(11)
,`fk_id_dist` int(11)
,`nama` varchar(50)
,`distributor` varchar(50)
,`kategori` varchar(30)
,`id_transaksi` int(11)
,`tanggal_transaksi` timestamp
,`kode_transaksi` varchar(25)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `distributor`
--

CREATE TABLE `distributor` (
  `id_dist` int(11) NOT NULL,
  `distributor` varchar(50) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `distributor`
--

INSERT INTO `distributor` (`id_dist`, `distributor`, `no_telp`, `email`, `alamat`) VALUES
(16, 'PT ABC Distribusi', '081234567890', 'abc.distributor@example.com', 'Jl. Raya Distributor No. 123'),
(17, 'CV Distribusi Jaya', '087654321098', 'distribusi.jaya@example.com', 'Jl. Distribusi Baru No. 45'),
(18, 'Mega Distributor Sentosa', '081112233445', 'mega.sentosa@example.com', 'Jl. Mega Sentosa Indah No. 78'),
(19, 'Distributor Bersama Makmur', '089876543210', 'bersama.makmur@example.com', 'Jl. Distributor Makmur No. 56'),
(20, 'Harmoni Distributor Nusantara', '081998877665', 'harmoni.nusantara@example.com', 'Jl. Harmoni Sejahtera No. 34'),
(21, 'PT Sentosa Distribusi', '087811223345', 'admin@sentosadistribusi.co.id', 'Jl. Harmoni No. 75');

--
-- Trigger `distributor`
--
DELIMITER $$
CREATE TRIGGER `Distributor_BI` BEFORE INSERT ON `distributor` FOR EACH ROW BEGIN
	IF EXISTS (SELECT * FROM distributor WHERE distributor = NEW.distributor OR no_telp = new.no_telp OR email = new.email OR alamat = new.alamat) THEN
	    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Data yang Dimasukkan Keliru. Periksa Kembali.';
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kat` int(11) NOT NULL,
  `kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kat`, `kategori`) VALUES
(10, 'Bahan Makanan Dasar'),
(19, 'Detergen'),
(1, 'Makanan Kaleng dan Kemasan'),
(2, 'Minuman'),
(3, 'Minyak Goreng dan Bumbu Masak'),
(4, 'Peralatan Mandi dan Kebersihan'),
(5, 'Snack');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `liat_transaksi_bk`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `liat_transaksi_bk` (
`id_transaksi` int(11)
,`tanggal_transaksi` timestamp
,`kode_transaksi` varchar(25)
,`status` varchar(5)
,`id_barkel` int(11)
,`fk_id_transaksi` int(11)
,`jumlah` int(11)
,`total_harga` int(11)
,`nama` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `liat_transaksi_bm`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `liat_transaksi_bm` (
`id_transaksi` int(11)
,`tanggal_transaksi` timestamp
,`kode_transaksi` varchar(25)
,`status` varchar(5)
,`id_barmas` int(11)
,`fk_id_transaksi` int(11)
,`jumlah` int(11)
,`total_harga` int(11)
,`nama` varchar(50)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `username` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`username`, `password`, `role`) VALUES
('Admin', 'admin12345', 'Admin'),
('Admin 2', 'xyz@#$%', 'Admin'),
('Kasir', 'kasir12345', 'Kasir'),
('Kasir 2', 'abcde123', 'Kasir');

--
-- Trigger `login`
--
DELIMITER $$
CREATE TRIGGER `BD_AdminUtama` BEFORE DELETE ON `login` FOR EACH ROW BEGIN
    IF OLD.username = 'Admin' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tidak dapat menghapus user Admin';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode_transaksi` varchar(25) NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `kode_transaksi`, `status`) VALUES
(55, '2023-12-07 06:22:47', 'BM20231205064404', 'in'),
(56, '2023-12-07 05:10:33', 'BK20231207061033', 'out'),
(57, '2023-12-06 05:44:41', 'BM20231206063117', 'in'),
(58, '2023-12-07 05:38:57', 'BM20231207063857', 'in'),
(59, '2023-12-05 05:55:17', 'BK20231207065517', 'out'),
(60, '2023-12-07 05:56:49', 'BK20231207065649', 'out'),
(61, '2023-12-07 06:03:28', 'BK20231207070328', 'out'),
(62, '2023-12-07 06:06:38', 'BK20231207070638', 'out'),
(63, '2023-12-01 06:09:07', 'BK20231207070907', 'out'),
(64, '2023-12-02 06:10:46', 'BK20231207071046', 'out'),
(65, '2023-12-07 06:20:54', 'BK20231207072054', 'out'),
(66, '2023-12-07 06:23:26', 'BK20231207072326', 'out'),
(67, '2023-12-07 06:23:45', 'BM20231207072345', 'in'),
(70, '2023-12-08 07:42:40', 'BM20231208084240', 'in'),
(71, '2023-12-08 07:46:36', 'BK20231208084636', 'out'),
(72, '2023-12-15 14:33:45', 'BM20231215153345', 'in'),
(73, '2023-12-15 14:37:57', 'BK20231215153757', 'out'),
(74, '2023-12-16 11:28:35', 'BM20231216122835', 'in'),
(75, '2023-12-16 11:31:07', 'BK20231216123107', 'out');

-- --------------------------------------------------------

--
-- Struktur untuk view `data_barang`
--
DROP TABLE IF EXISTS `data_barang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `data_barang`  AS SELECT `b`.`id_bar` AS `id_bar`, `b`.`nama` AS `nama`, `k`.`kategori` AS `kategori`, `b`.`jumlah` AS `jumlah`, `b`.`satuan` AS `satuan`, `b`.`harga` AS `harga`, `b`.`exp_date` AS `exp_date`, `b`.`kode` AS `kode` FROM (`barang` `b` join `kategori` `k` on(`b`.`fk_id_kat` = `k`.`id_kat`))  ;

-- --------------------------------------------------------

--
-- Struktur untuk view `detail_barangkeluar`
--
DROP TABLE IF EXISTS `detail_barangkeluar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_barangkeluar`  AS SELECT `bk`.`id_barkel` AS `id_barkel`, `bk`.`fk_id_bar` AS `fk_id_bar`, `bk`.`fk_id_transaksi` AS `fk_id_transaksi`, `bk`.`jumlah` AS `jumlah`, `bk`.`total_harga` AS `total_harga`, `b`.`nama` AS `nama`, `b`.`harga` AS `harga`, `k`.`kategori` AS `kategori`, `t`.`tanggal_transaksi` AS `tanggal_transaksi`, `t`.`kode_transaksi` AS `kode_transaksi` FROM (((`barang_keluar` `bk` join `barang` `b` on(`bk`.`fk_id_bar` = `b`.`id_bar`)) join `transaksi` `t` on(`bk`.`fk_id_transaksi` = `t`.`id_transaksi`)) join `kategori` `k` on(`b`.`fk_id_kat` = `k`.`id_kat`))  ;

-- --------------------------------------------------------

--
-- Struktur untuk view `detail_barangmasuk`
--
DROP TABLE IF EXISTS `detail_barangmasuk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_barangmasuk`  AS SELECT `bm`.`id_barmas` AS `id_barmas`, `bm`.`fk_id_transaksi` AS `fk_id_transaksi`, `bm`.`fk_id_bar` AS `fk_id_bar`, `bm`.`jumlah` AS `jumlah`, `bm`.`total_harga` AS `total_harga`, `bm`.`fk_id_dist` AS `fk_id_dist`, `b`.`nama` AS `nama`, `d`.`distributor` AS `distributor`, `k`.`kategori` AS `kategori`, `t`.`id_transaksi` AS `id_transaksi`, `t`.`tanggal_transaksi` AS `tanggal_transaksi`, `t`.`kode_transaksi` AS `kode_transaksi` FROM ((((`barang_masuk` `bm` join `barang` `b` on(`bm`.`fk_id_bar` = `b`.`id_bar`)) join `distributor` `d` on(`bm`.`fk_id_dist` = `d`.`id_dist`)) join `transaksi` `t` on(`bm`.`fk_id_transaksi` = `t`.`id_transaksi`)) join `kategori` `k` on(`b`.`fk_id_kat` = `k`.`id_kat`))  ;

-- --------------------------------------------------------

--
-- Struktur untuk view `liat_transaksi_bk`
--
DROP TABLE IF EXISTS `liat_transaksi_bk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `liat_transaksi_bk`  AS SELECT `t`.`id_transaksi` AS `id_transaksi`, `t`.`tanggal_transaksi` AS `tanggal_transaksi`, `t`.`kode_transaksi` AS `kode_transaksi`, `t`.`status` AS `status`, `bk`.`id_barkel` AS `id_barkel`, `bk`.`fk_id_transaksi` AS `fk_id_transaksi`, `bk`.`jumlah` AS `jumlah`, `bk`.`total_harga` AS `total_harga`, `b`.`nama` AS `nama` FROM ((`transaksi` `t` join `barang_keluar` `bk` on(`t`.`id_transaksi` = `bk`.`fk_id_transaksi`)) join `barang` `b` on(`b`.`id_bar` = `bk`.`fk_id_bar`))  ;

-- --------------------------------------------------------

--
-- Struktur untuk view `liat_transaksi_bm`
--
DROP TABLE IF EXISTS `liat_transaksi_bm`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `liat_transaksi_bm`  AS SELECT `t`.`id_transaksi` AS `id_transaksi`, `t`.`tanggal_transaksi` AS `tanggal_transaksi`, `t`.`kode_transaksi` AS `kode_transaksi`, `t`.`status` AS `status`, `bm`.`id_barmas` AS `id_barmas`, `bm`.`fk_id_transaksi` AS `fk_id_transaksi`, `bm`.`jumlah` AS `jumlah`, `bm`.`total_harga` AS `total_harga`, `b`.`nama` AS `nama` FROM ((`transaksi` `t` join `barang_masuk` `bm` on(`t`.`id_transaksi` = `bm`.`fk_id_transaksi`)) join `barang` `b` on(`b`.`id_bar` = `bm`.`fk_id_bar`))  ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_bar`),
  ADD UNIQUE KEY `nama_unik` (`nama`),
  ADD KEY `fk_idkat` (`fk_id_kat`);

--
-- Indeks untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_barkel`),
  ADD KEY `fk_idbar_bk` (`fk_id_bar`),
  ADD KEY `dk_bk_idtrans` (`fk_id_transaksi`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_barmas`),
  ADD KEY `fk_idbar` (`fk_id_bar`),
  ADD KEY `fk_iddist` (`fk_id_dist`),
  ADD KEY `fk_bm_idtrans` (`fk_id_transaksi`);

--
-- Indeks untuk tabel `distributor`
--
ALTER TABLE `distributor`
  ADD PRIMARY KEY (`id_dist`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kat`),
  ADD UNIQUE KEY `kategori_unik` (`kategori`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_bar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_barkel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_barmas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `distributor`
--
ALTER TABLE `distributor`
  MODIFY `id_dist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_idkat` FOREIGN KEY (`fk_id_kat`) REFERENCES `kategori` (`id_kat`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `dk_bk_idtrans` FOREIGN KEY (`fk_id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idbar_bk` FOREIGN KEY (`fk_id_bar`) REFERENCES `barang` (`id_bar`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `fk_bm_idtrans` FOREIGN KEY (`fk_id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idbar` FOREIGN KEY (`fk_id_bar`) REFERENCES `barang` (`id_bar`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_iddist` FOREIGN KEY (`fk_id_dist`) REFERENCES `distributor` (`id_dist`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
