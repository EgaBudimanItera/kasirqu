<?php
// ============================================================
// kasirqu/crud/config/auth.php
// Penjaga halaman versi materi ajar (KasirQu).
//
// Dipakai oleh halaman laporan saja, supaya materi P9-P16 yang lain
// tetap bisa dibuka tanpa login. Di proyek siswa (kasirsmk), penjaga
// ini dipasang di semua halaman bisnis.
//
// Cara pakai:
//   require_once __DIR__ . '/config/auth.php';
//   wajib_role('admin');
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('KASIRQU_SESSION_KEY')) {
    define('KASIRQU_SESSION_KEY', 'kasirqu');
}

if (!defined('KASIRQU_FLASH_KEY')) {
    define('KASIRQU_FLASH_KEY', 'kasirqu_flash');
}

if (!function_exists('pengguna_login')) {
    function pengguna_login(): array
    {
        return $_SESSION[KASIRQU_SESSION_KEY] ?? [];
    }
}

if (!function_exists('cek_role')) {
    function cek_role(string $role): bool
    {
        return (pengguna_login()['role'] ?? '') === $role;
    }
}

if (!function_exists('wajib_role')) {
    function wajib_role(string $role, string $pesan = 'Halaman ini hanya untuk admin.'): void
    {
        if (!cek_role($role)) {
            $_SESSION[KASIRQU_FLASH_KEY] = ['type' => 'warning', 'text' => $pesan];
            header('Location: index.php');
            exit;
        }
    }
}

if (pengguna_login() === []) {
    $_SESSION[KASIRQU_FLASH_KEY] = [
        'type' => 'warning',
        'text' => 'Silakan login dulu untuk membuka halaman ini.',
    ];
    header('Location: login.php');
    exit;
}
