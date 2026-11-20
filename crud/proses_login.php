<?php
// ============================================================
// kasirqu/crud/proses_login.php
// Cek username + password dengan prepared statement, lalu simpan session.
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/../config/database.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

function gagal_login(string $pesan): void
{
    $_SESSION['kasirqu_flash'] = ['type' => 'danger', 'text' => $pesan];
    header('Location: login.php');
    exit;
}

if ($username === '' || $password === '') {
    gagal_login('Username dan password wajib diisi.');
}

$stmt = mysqli_prepare(
    $koneksi,
    'SELECT id_pengguna, username, password, nama_lengkap, role, id_kasir, status
     FROM pengguna
     WHERE username = ?
     LIMIT 1'
);

if (!$stmt) {
    gagal_login(
        'Tabel pengguna belum ada. Impor kasirqu/crud/setup_pengguna.sql dulu. '
        . 'Pesan MySQL: ' . mysqli_error($koneksi)
    );
}

mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$baris = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$baris) {
    gagal_login('Username atau password salah.');
}

if (($baris['status'] ?? 'aktif') !== 'aktif') {
    gagal_login('Akun ini nonaktif.');
}

if (!password_verify($password, $baris['password'])) {
    gagal_login('Username atau password salah.');
}

session_regenerate_id(true);

$_SESSION['kasirqu'] = [
    'id_pengguna' => (int) $baris['id_pengguna'],
    'username'    => $baris['username'],
    'nama'        => $baris['nama_lengkap'],
    'role'        => $baris['role'],
    'id_kasir'    => $baris['id_kasir'] !== null ? (int) $baris['id_kasir'] : null,
];

$_SESSION['kasirqu_flash'] = [
    'type' => 'success',
    'text' => 'Selamat datang, ' . $baris['nama_lengkap'] . '. Role kamu: ' . $baris['role'] . '.',
];

header('Location: index.php');
exit;
