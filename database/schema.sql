-- ============================================================
-- Database : db_perpustakaan
-- Tema     : Perpustakaan Kampus
-- Relasi   : anggota 1:N peminjaman, buku 1:N peminjaman
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_perpustakaan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_perpustakaan;

-- Hapus tabel lama (tabel anak dulu, baru tabel induk)
DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS anggota;

-- ------------------------------------------------------------
-- Tabel 1: anggota
-- ------------------------------------------------------------
CREATE TABLE anggota (
  id_anggota   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama         VARCHAR(100) NOT NULL,
  jurusan      VARCHAR(100) NOT NULL,
  email        VARCHAR(100) NOT NULL,
  tgl_daftar   DATE NOT NULL,
  PRIMARY KEY (id_anggota),
  UNIQUE KEY uq_anggota_email (email)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel 2: buku
-- ------------------------------------------------------------
CREATE TABLE buku (
  id_buku       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  judul         VARCHAR(150) NOT NULL,
  penulis       VARCHAR(100) NOT NULL,
  tahun_terbit  SMALLINT UNSIGNED NOT NULL,
  stok          INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id_buku)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel 3: peminjaman (tabel penghubung, berisi 2 Foreign Key)
-- ------------------------------------------------------------
CREATE TABLE peminjaman (
  id_peminjaman  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_anggota     INT UNSIGNED NOT NULL,
  id_buku        INT UNSIGNED NOT NULL,
  tgl_pinjam     DATE NOT NULL,
  tgl_kembali    DATE NULL,
  status         ENUM('Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Dipinjam',
  PRIMARY KEY (id_peminjaman),
  CONSTRAINT fk_peminjaman_anggota FOREIGN KEY (id_anggota)
    REFERENCES anggota (id_anggota) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_peminjaman_buku FOREIGN KEY (id_buku)
    REFERENCES buku (id_buku) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Data awal
-- ------------------------------------------------------------
INSERT INTO anggota (nama, jurusan, email, tgl_daftar) VALUES
('Andi Pratama',   'Informatika',       'andi.pratama@mail.com',   '2026-08-18'),
('Siti Nurhaliza', 'Sistem Informasi',  'siti.nurhaliza@mail.com', '2026-08-19'),
('Budi Santoso',   'Informatika',       'budi.santoso@mail.com',   '2026-08-20'),
('Dewi Lestari',   'Sains Data',        'dewi.lestari@mail.com',   '2026-08-22'),
('Rizky Ramadhan', 'Teknik Industri',   'rizky.ramadhan@mail.com', '2026-08-25'),
('Putri Maharani', 'Sistem Informasi',  'putri.maharani@mail.com', '2026-08-27');

INSERT INTO buku (judul, penulis, tahun_terbit, stok) VALUES
('Clean Code',               'Robert C. Martin',            2008, 4),
('The Pragmatic Programmer', 'Andrew Hunt & David Thomas',  1999, 3),
('Laskar Pelangi',           'Andrea Hirata',               2005, 5),
('Bumi Manusia',             'Pramoedya Ananta Toer',       1980, 2),
('Filosofi Teras',           'Henry Manampiring',           2018, 6),
('Atomic Habits',            'James Clear',                 2018, 5);

INSERT INTO peminjaman (id_anggota, id_buku, tgl_pinjam, tgl_kembali, status) VALUES
(1, 1, '2026-09-01', '2026-09-08', 'Dikembalikan'),
(1, 2, '2026-09-10', NULL,         'Dipinjam'),
(2, 3, '2026-09-03', '2026-09-10', 'Dikembalikan'),
(3, 1, '2026-09-12', NULL,         'Dipinjam'),
(4, 5, '2026-09-15', '2026-09-22', 'Dikembalikan'),
(5, 4, '2026-09-20', NULL,         'Dipinjam'),
(6, 6, '2026-09-25', NULL,         'Dipinjam');
