<?php
require_once __DIR__ . '/services/config.php';

// Jalankan query SELECT; hentikan dengan pesan jelas jika gagal
function jalankan_query($conn, $sql)
{
    $hasil = mysqli_query($conn, $sql);
    if (!$hasil) {
        die('Query gagal: ' . htmlspecialchars(mysqli_error($conn)));
    }
    return $hasil;
}

// Amankan teks sebelum dicetak ke HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// Ubah 2026-09-01 menjadi 01-09-2026
function tanggal($tgl)
{
    return $tgl ? date('d-m-Y', strtotime($tgl)) : '-';
}

// Ringkasan jumlah data dari masing-masing tabel
$ringkasan = [];
foreach (['anggota' => 'Anggota', 'buku' => 'Buku', 'peminjaman' => 'Peminjaman'] as $tabel => $label) {
    $baris = mysqli_fetch_assoc(jalankan_query($conn, "SELECT COUNT(*) AS total FROM $tabel"));
    $ringkasan[$label] = $baris['total'];
}

// Query SELECT untuk tiap tabel
$q_anggota = jalankan_query($conn, "SELECT * FROM anggota ORDER BY id_anggota");
$q_buku    = jalankan_query($conn, "SELECT * FROM buku ORDER BY id_buku");

// JOIN: menggabungkan 3 tabel (peminjaman + anggota + buku)
$q_pinjam = jalankan_query($conn,
    "SELECT p.id_peminjaman, a.nama, b.judul, p.tgl_pinjam, p.tgl_kembali, p.status
     FROM peminjaman p
     JOIN anggota a ON p.id_anggota = a.id_anggota
     JOIN buku b    ON p.id_buku = b.id_buku
     ORDER BY p.id_peminjaman");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Kampus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="brand">Perpustakaan Kampus</span>
    <div class="nav-links">
        <a href="#anggota">Anggota</a>
        <a href="#buku">Buku</a>
        <a href="#peminjaman">Peminjaman</a>
    </div>
</nav>

<header class="hero">
    <h1>Data Perpustakaan Kampus</h1>
    <p>Data di bawah ini diambil langsung dari database MySQL menggunakan PHP.</p>
</header>

<main class="container">

    <section class="stats">
        <?php foreach ($ringkasan as $label => $total): ?>
            <div class="card stat">
                <span class="stat-number"><?= e($total) ?></span>
                <span class="stat-label">Total <?= e($label) ?></span>
            </div>
        <?php endforeach; ?>
    </section>

    <section id="anggota" class="card">
        <h2>Daftar Anggota</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>ID</th><th>Nama</th><th>Jurusan</th><th>Email</th><th>Tanggal Daftar</th></tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($q_anggota)): ?>
                    <tr>
                        <td><?= e($row['id_anggota']) ?></td>
                        <td><?= e($row['nama']) ?></td>
                        <td><?= e($row['jurusan']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e(tanggal($row['tgl_daftar'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="buku" class="card">
        <h2>Daftar Buku</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Tahun Terbit</th><th>Stok</th></tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($q_buku)): ?>
                    <tr>
                        <td><?= e($row['id_buku']) ?></td>
                        <td><?= e($row['judul']) ?></td>
                        <td><?= e($row['penulis']) ?></td>
                        <td><?= e($row['tahun_terbit']) ?></td>
                        <td><?= e($row['stok']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="peminjaman" class="card">
        <h2>Riwayat Peminjaman</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>ID</th><th>Peminjam</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($q_pinjam)): ?>
                    <tr>
                        <td><?= e($row['id_peminjaman']) ?></td>
                        <td><?= e($row['nama']) ?></td>
                        <td><?= e($row['judul']) ?></td>
                        <td><?= e(tanggal($row['tgl_pinjam'])) ?></td>
                        <td><?= e(tanggal($row['tgl_kembali'])) ?></td>
                        <td>
                            <span class="badge <?= $row['status'] === 'Dipinjam' ? 'badge-pinjam' : 'badge-kembali' ?>">
                                <?= e($row['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

</main>

<footer class="footer">
    <p>Tugas Divisi Programming ISCOM 2026 &mdash; Web Sederhana dengan MySQL, PHP &amp; GitHub</p>
</footer>

</body>
</html>
<?php mysqli_close($conn); ?>
