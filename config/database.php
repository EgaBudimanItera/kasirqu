<?php
// ============================================================
// kasirqu/config/database.php
// Satu tempat untuk mengatur koneksi ke database MySQL.
//
// Dipakai oleh semua halaman di folder kasirqu/crud/.
// Driver: mysqli (prosedural), supaya sederhana untuk pemula.
//
// Sebelum memakai file ini:
//   1. Nyalakan MySQL (Laragon / XAMPP).
//   2. Pastikan database db_penjualan sudah ada.
//      Impor pertemuan-07/bangun_database_penjualan.sql
//      atau kasirqu/crud/setup_produk.sql.
//   Catatan: sebagian materi lama menyebut penjualan_lengkap.
//   Untuk lab ini, nama database yang dipakai adalah db_penjualan.
// ============================================================

mysqli_report(MYSQLI_REPORT_OFF);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'db_penjualan';

$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$koneksi) {
    $pesan = htmlspecialchars(mysqli_connect_error(), ENT_QUOTES, 'UTF-8');
    die(
        '<h2>Koneksi database gagal</h2>' .
        '<p>Pesan MySQL: <strong>' . $pesan . '</strong></p>' .
        '<p>Periksa tiga hal ini:</p>' .
        '<ol>' .
        '<li>MySQL sudah menyala (Laragon: Start All, XAMPP: Start MySQL).</li>' .
        '<li>Database <code>db_penjualan</code> sudah diimpor.</li>' .
        '<li>User <code>root</code> dan password di <code>config/database.php</code> sudah benar.</li>' .
        '</ol>'
    );
}

mysqli_set_charset($koneksi, 'utf8mb4');
