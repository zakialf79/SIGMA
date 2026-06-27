CREATE TABLE `akumulasi_pakai` (
  `id` int(11) NOT NULL,
  `bulan_tahun` varchar(20) NOT NULL,
  `total_mentah` float DEFAULT 0,
  `total_minyak` float DEFAULT 0,
  `total_gas` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `akumulasi_pakai` (`id`, `bulan_tahun`, `total_mentah`, `total_minyak`, `total_gas`) VALUES
(1, 'June 2026', 0, 0, 0);

CREATE TABLE `buku_kas` (
  `id` bigint(20) NOT NULL,
  `tgl_sort` date NOT NULL,
  `tgl_visual` varchar(10) NOT NULL,
  `keterangan` text NOT NULL,
  `debet` int(11) DEFAULT 0,
  `kredit` int(11) DEFAULT 0,
  `is_arsip` tinyint(1) DEFAULT 0,
  `nama_bulan_arsip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `gudang_stok` (
  `nama_bahan` varchar(50) NOT NULL,
  `qty` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `gudang_stok` (`nama_bahan`, `qty`) VALUES
('Gas', 1),
('Kulit Mentah', 0),
('Minyak', 0),
('Plastik 8x13', 0),
('Plastik 9x16', 0),
('Ziplock 20x29', 0);

CREATE TABLE `histori_gudang` (
  `id` int(11) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_teks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `histori_gudang` (`id`, `waktu`, `log_teks`) VALUES
(1, '2026-06-13 10:27:48', 'Koreksi Manual: Gas disesuaikan ke 1'),
(2, '2026-06-27 02:57:31', 'Sistem: Populate test data'),
(3, '2026-06-27 02:57:39', 'Sistem: Tutup buku dan pengarsipan untuk periode Juni 2026');

CREATE TABLE `mitra` (
  `id` int(11) NOT NULL,
  `nama_mitra` varchar(100) NOT NULL,
  `tipe_mitra` enum('kon','lsg') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mitra` (`id`, `nama_mitra`, `tipe_mitra`) VALUES
(1, 'Lontong Ampang', 'kon'),
(2, 'Sate Simp Tanah Sirah', 'kon'),
(3, 'Hotel Truntum', 'lsg'),
(4, 'RM Padang Busi', 'lsg');

ALTER TABLE `akumulasi_pakai` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bulan_tahun` (`bulan_tahun`);
ALTER TABLE `buku_kas` ADD PRIMARY KEY (`id`);
ALTER TABLE `gudang_stok` ADD PRIMARY KEY (`nama_bahan`);
ALTER TABLE `histori_gudang` ADD PRIMARY KEY (`id`);
ALTER TABLE `mitra` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nama_mitra` (`nama_mitra`);

ALTER TABLE `akumulasi_pakai` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `buku_kas` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `histori_gudang` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `mitra` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
