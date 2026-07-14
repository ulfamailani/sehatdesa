-- =====================================================================
-- SehatDesa — Struktur Basis Data
-- Impor file ini melalui phpMyAdmin (tab "Import") atau jalankan lewat
-- konsol MySQL: mysql -u root -p < database.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `sehatdesa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sehatdesa`;

-- ---------------------------------------------------------------------
-- Tabel: users
-- Menyimpan akun warga, kader posyandu, dan admin
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(120) NOT NULL,
  `role` ENUM('warga','kader','admin') NOT NULL DEFAULT 'warga',
  `desa` VARCHAR(120) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: api_settings
-- Menyimpan API key layanan AI (hanya bisa diubah oleh admin)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider` VARCHAR(50) NOT NULL DEFAULT 'anthropic',
  `api_key` VARCHAR(255) NOT NULL,
  `model` VARCHAR(100) NOT NULL DEFAULT 'claude-sonnet-5',
  `updated_by` INT UNSIGNED DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: posyandu_schedule
-- Jadwal kegiatan posyandu tiap dusun/desa
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posyandu_schedule` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama_posyandu` VARCHAR(150) NOT NULL,
  `alamat` VARCHAR(255) NOT NULL,
  `tanggal` DATE NOT NULL,
  `jam` TIME NOT NULL,
  `kegiatan` TEXT NOT NULL,
  `sasaran` ENUM('balita','ibu_hamil','lansia','umum') NOT NULL DEFAULT 'umum',
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: gizi_articles
-- Artikel edukasi gizi keluarga
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gizi_articles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `judul` VARCHAR(200) NOT NULL,
  `kategori` ENUM('balita','ibu_hamil','lansia','umum') NOT NULL DEFAULT 'umum',
  `ringkasan` VARCHAR(300) NOT NULL,
  `konten` TEXT NOT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: penyakit_info
-- Basis pengetahuan penyakit umum di lingkungan masyarakat
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `penyakit_info` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama_penyakit` VARCHAR(150) NOT NULL,
  `gejala` TEXT NOT NULL,
  `pencegahan` TEXT NOT NULL,
  `penanganan` TEXT NOT NULL,
  `tingkat_bahaya` ENUM('ringan','sedang','waspada') NOT NULL DEFAULT 'ringan',
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: chat_history
-- Riwayat percakapan dengan Asisten AI Kesehatan
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chat_history` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `pesan` TEXT NOT NULL,
  `jawaban` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Data awal (contoh) — silakan sesuaikan / hapus
-- ---------------------------------------------------------------------

-- Catatan: akun admin pertama TIDAK dibuat lewat SQL ini, karena hash
-- kata sandi harus dibuat oleh fungsi password_hash() milik PHP agar
-- valid. Setelah database ini diimpor, jalankan sekali file
-- create_admin.php (lihat README.md) untuk membuat akun admin, lalu
-- hapus file tersebut dari server.

INSERT INTO `api_settings` (`provider`,`api_key`,`model`) VALUES
('anthropic', 'GANTI_DENGAN_API_KEY_ANDA', 'claude-sonnet-5')
ON DUPLICATE KEY UPDATE provider=provider;

INSERT INTO `posyandu_schedule` (`nama_posyandu`,`alamat`,`tanggal`,`jam`,`kegiatan`,`sasaran`) VALUES
('Posyandu Melati I','Balai Dusun I, Desa Makmur', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '08:00:00','Penimbangan balita, imunisasi, dan pemberian vitamin A','balita'),
('Posyandu Lansia Sejahtera','Balai Dusun II, Desa Makmur', DATE_ADD(CURDATE(), INTERVAL 10 DAY), '09:00:00','Pemeriksaan tekanan darah dan senam lansia','lansia');

INSERT INTO `gizi_articles` (`judul`,`kategori`,`ringkasan`,`konten`) VALUES
('Pentingnya 1000 Hari Pertama Kehidupan','ibu_hamil','Periode emas tumbuh kembang anak dimulai sejak dalam kandungan hingga usia 2 tahun.','Seribu hari pertama kehidupan, dihitung sejak masa kehamilan hingga anak berusia dua tahun, merupakan periode paling menentukan bagi tumbuh kembang anak. Kecukupan gizi ibu hamil dan menyusui, serta pemberian ASI eksklusif enam bulan, sangat memengaruhi kualitas fisik dan kecerdasan anak di masa depan.'),
('Cegah Stunting dengan Menu Seimbang','balita','Menu bergizi seimbang harian membantu mencegah stunting pada balita.','Stunting dapat dicegah dengan memastikan balita mendapat menu yang mengandung karbohidrat, protein hewani, sayur, dan buah setiap hari. Pemberian makanan pendamping ASI yang tepat sejak usia 6 bulan menjadi kunci utama pencegahan.');

INSERT INTO `penyakit_info` (`nama_penyakit`,`gejala`,`pencegahan`,`penanganan`,`tingkat_bahaya`) VALUES
('Diare','Buang air besar cair lebih dari tiga kali sehari, disertai kram perut.','Cuci tangan pakai sabun, konsumsi air bersih dan matang, jaga kebersihan makanan.','Berikan oralit untuk mencegah dehidrasi, teruskan makan, segera ke fasilitas kesehatan jika disertai darah atau demam tinggi.','sedang'),
('Demam Berdarah Dengue (DBD)','Demam tinggi mendadak, nyeri otot dan sendi, bintik merah pada kulit.','Lakukan 3M Plus: menguras, menutup, mendaur ulang tempat penampungan air, gunakan kelambu dan obat nyamuk.','Perbanyak cairan, kompres hangat, segera periksa ke fasilitas kesehatan bila demam tidak turun dalam 2 hari.','waspada');
