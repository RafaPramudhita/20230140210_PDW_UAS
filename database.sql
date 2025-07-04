CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','asisten') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Mata Praktikum
CREATE TABLE IF NOT EXISTS praktikum (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT
);

-- Tabel Modul
CREATE TABLE IF NOT EXISTS modul (
  id INT AUTO_INCREMENT PRIMARY KEY,
  praktikum_id INT,
  judul VARCHAR(100) NOT NULL,
  file_materi VARCHAR(255),
  pertemuan_ke INT,
  FOREIGN KEY (praktikum_id) REFERENCES praktikum(id)
);

-- Tabel Pendaftaran Praktikum
CREATE TABLE IF NOT EXISTS pendaftaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  praktikum_id INT,
  tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (praktikum_id) REFERENCES praktikum(id)
);

-- Tabel Laporan/Tugas
CREATE TABLE IF NOT EXISTS laporan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  modul_id INT,
  user_id INT,
  file_laporan VARCHAR(255),
  nilai INT,
  feedback TEXT,
  status ENUM('Dikirim', 'Dinilai') DEFAULT 'Dikirim',
  tanggal_kumpul DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (modul_id) REFERENCES modul(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Update tabel users (jika belum ada kolom role)
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('mahasiswa', 'asisten') DEFAULT 'mahasiswa';