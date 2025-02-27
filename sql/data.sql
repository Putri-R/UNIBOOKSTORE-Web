CREATE TABLE `penerbit` (
  `id` VARCHAR(255) NOT NULL,
  `nama_penerbit` VARCHAR(500) NOT NULL,
  `alamat` VARCHAR(500) NOT NULL,
  `kota` VARCHAR(255) NOT NULL,
  `telepon` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Buat tabel buku setelah penerbit
CREATE TABLE `buku` (
  `id` VARCHAR(255) NOT NULL,
  `kategori` VARCHAR(255) NOT NULL,
  `nama_buku` VARCHAR(500) NOT NULL,
  `harga` INT(11) NOT NULL,
  `stok` INT(11) NOT NULL,
  `penerbit` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penerbit` (`penerbit`),
  CONSTRAINT `fk_buku_penerbit` FOREIGN KEY (`penerbit`) REFERENCES `penerbit`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Masukkan data ke tabel penerbit
INSERT INTO `penerbit` (`id`, `nama_penerbit`, `alamat`, `kota`, `telepon`) VALUES
('SP01', 'Penerbit Informatika', 'Jl. Buah Batu No.121', 'Bandung', 2147483647),
('SP02', 'Andi Offset', 'Jl. Suryalaya IX No.3', 'Bandung', 2147483647),
('SP03', 'Danendra', 'Jl.Moch Toha 445', 'Bandung', 225201215);

-- Masukkan data ke tabel buku
INSERT INTO `buku` (`id`, `kategori`, `nama_buku`, `harga`, `stok`, `penerbit`) VALUES
('B1001', 'Bisnis', 'Bisnis Online', 75000, 9, 'SP01'),
('B1002', 'Bisnis', 'Etika Bisnis dan Tanggung Jawab Sosial', 67500, 20, 'SP01'),
('K1001', 'Keilmuan', 'Analisis & Perancangan Sistem Informasi', 50000, 60, 'SP01'),
('K1002', 'Keilmuan', 'Artificial Intelligence', 45000, 60, 'SP01'),
('K2003', 'Keilmuan', 'Autocad 3 Dimensi', 40000, 25, 'SP01'),
('K3004', 'Keilmuan', 'Cloud Computing Technology', 85000, 15, 'SP01'),
('N1001', 'Novel', 'Cahaya Di Penjuru Hati', 68000, 10, 'SP02'),
('N1002', 'Novel', 'Aku Ingin Cerita', 48000, 12, 'SP03');