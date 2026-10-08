<?php
// Konfigurasi & koneksi database MySQL.
// Sesuaikan nilai di bawah ini dengan pengaturan MySQL di komputermu.
// (Default XAMPP: user "root" dan password kosong)

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_perpustakaan';

// Matikan exception bawaan agar error bisa kita tangani sendiri
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error()
        . '<br>Pastikan MySQL sudah menyala dan database/schema.sql sudah di-import.');
}

mysqli_set_charset($conn, 'utf8mb4');
