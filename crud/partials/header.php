<?php
// ============================================================
// kasirqu/crud/partials/header.php
// Navbar bersama KasirQu + pembuka container.
//
// Catatan P17: menu Laporan hanya muncul untuk admin, dan tombol
// Logout memakai form POST (bukan link GET).
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$judul   = $judul ?? 'CRUD Produk';
$halaman = $halaman ?? '';

$navUser = $_SESSION['kasirqu'] ?? [];
$navNama = $navUser['nama'] ?? '';
$navRole = $navUser['role'] ?? '';

$flash = $_SESSION['kasirqu_flash'] ?? null;
unset($_SESSION['kasirqu_flash']);

$menuLaporan  = ['laporan_penjualan'];
$laporanAktif = in_array($halaman, $menuLaporan, true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($judul) ?> - KasirQu</title>
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/transaksi.css">
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <a class="brand" href="index.php">KasirQu <small>/ CRUD &amp; Transaksi</small></a>
      <nav class="menu">
        <a href="index.php" class="<?= $halaman === 'produk' ? 'active' : '' ?>">Produk</a>
        <a href="tambah.php" class="<?= $halaman === 'tambah' ? 'active' : '' ?>">Tambah</a>
        <a href="transaksi.php" class="<?= $halaman === 'transaksi' ? 'active' : '' ?>">Transaksi</a>
        <?php if ($navRole === 'admin'): ?>
          <a href="laporan_penjualan.php" class="<?= $laporanAktif ? 'active' : '' ?>">Laporan</a>
        <?php endif; ?>
        <a href="presentasi.php" class="<?= $halaman === 'slide' ? 'active' : '' ?>">Slide P16</a>
        <a href="koneksi.php" class="<?= $halaman === 'koneksi' ? 'active' : '' ?>">Koneksi</a>
        <a href="panduan.php" class="<?= $halaman === 'panduan' ? 'active' : '' ?>">Panduan</a>
        <a href="../crud_latihan/index.php">Latihan</a>
        <a href="../index.php">&larr; Menu Latihan</a>
      </nav>

      <div class="topbar-user">
        <?php if ($navNama !== ''): ?>
          <span class="user-name"><?= htmlspecialchars($navNama) ?></span>
          <span class="pill <?= $navRole === 'admin' ? 'ok' : '' ?>">
            <?= $navRole === 'admin' ? 'Admin' : 'Kasir' ?>
          </span>
          <form method="post" action="logout.php" style="display: inline;">
            <button class="btn btn-sm btn-danger" type="submit">Logout</button>
          </form>
        <?php else: ?>
          <a class="btn btn-sm" href="login.php">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <main class="wrap">

  <?php if ($flash): ?>
    <div class="alert <?= ($flash['type'] ?? '') === 'danger' ? 'error' : 'ok' ?>">
      <?= htmlspecialchars($flash['text'] ?? '') ?>
    </div>
  <?php endif; ?>
