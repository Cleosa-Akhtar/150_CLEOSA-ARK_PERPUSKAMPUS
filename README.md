# Perpustakaan Kampus

Website sederhana yang menampilkan data **anggota**, **buku**, dan **peminjaman** perpustakaan kampus secara dinamis dari database MySQL menggunakan PHP.

Tugas Divisi Programming ISCOM 2026 — *Web Sederhana dengan Database MySQL, PHP & GitHub*.

## Struktur Project

```
perpustakaan-kampus/
├── services/
│   └── config.php      # konfigurasi & koneksi database
├── database/
│   └── schema.sql      # struktur database, tabel, dan data awal
├── index.php           # halaman utama (SELECT + JOIN + while/fetch_assoc)
├── style.css           # tampilan halaman
└── README.md
```

## Cara Menjalankan

1. Install **XAMPP** (atau Laragon), lalu jalankan **Apache** dan **MySQL**.
2. Salin folder `perpustakaan-kampus` ke `C:\xampp\htdocs\`.
3. Buka `http://localhost/phpmyadmin` → tab **Import** → pilih file `database/schema.sql` → **Go**.
   (Atau lewat terminal: `mysql -u root -p < database/schema.sql`)
4. Cek `services/config.php`. Sesuaikan `$db_user` dan `$db_pass` jika MySQL-mu memakai password.
5. Buka `http://localhost/perpustakaan-kampus/` di browser.

## Entitas, Atribut, Relasi, dan Kardinalitas

### Entitas dan Atribut

| Entitas | Atribut | Primary Key | Foreign Key |
|---|---|---|---|
| `anggota` | id_anggota, nama, jurusan, email, tgl_daftar | id_anggota | - |
| `buku` | id_buku, judul, penulis, tahun_terbit, stok | id_buku | - |
| `peminjaman` | id_peminjaman, id_anggota, id_buku, tgl_pinjam, tgl_kembali, status | id_peminjaman | id_anggota → anggota, id_buku → buku |

### Relasi dan Kardinalitas

| Relasi | Kardinalitas | Penjelasan |
|---|---|---|
| `anggota` — `peminjaman` | **One-to-Many (1:N)** | Satu anggota dapat melakukan banyak peminjaman, tetapi satu peminjaman hanya dilakukan oleh satu anggota. |
| `buku` — `peminjaman` | **One-to-Many (1:N)** | Satu buku dapat dipinjam berkali-kali, tetapi satu catatan peminjaman hanya untuk satu buku. |

Secara tidak langsung, `anggota` dan `buku` berelasi **Many-to-Many (M:N)** yang dipecah menjadi dua relasi 1:N melalui tabel `peminjaman`.

### ERD Sederhana

```
┌───────────┐ 1        N ┌──────────────┐ N        1 ┌──────────┐
│  anggota  │────────────│  peminjaman  │────────────│   buku   │
│ PK id_ang │            │ PK id_pinjam │            │ PK id_buk│
└───────────┘            │ FK id_anggota│            └──────────┘
                         │ FK id_buku   │
                         └──────────────┘
```

## Fitur Sesuai Ketentuan

- Database `db_perpustakaan`: 3 tabel, masing-masing ≥ 3 kolom, memiliki PK, relasi FK, dan ≥ 5 data.
- PHP terhubung ke MySQL (`mysqli_connect`), memakai `SELECT`, `JOIN`, `while`, dan `mysqli_fetch_assoc()`.
- Seluruh data ditampilkan dinamis dari database, tidak ditulis manual di HTML.
- Visual: satu warna brand (teal), card/tabel dengan padding-border-shadow, Flexbox, dan responsif (desktop & mobile).

## Upload ke GitHub

```bash
cd perpustakaan-kampus
git init
git add .
git commit -m "Initial commit: web perpustakaan kampus (PHP + MySQL)"
git branch -M main
git remote add origin https://github.com/USERNAME/perpustakaan-kampus.git
git push -u origin main
```
