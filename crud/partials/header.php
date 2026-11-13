<?php
$judul = $judul ?? 'CRUD Produk';
$halaman = $halaman ?? '';
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
        <a href="presentasi.php" class="<?= $halaman === 'slide' ? 'active' : '' ?>">Slide P16</a>
        <a href="koneksi.php" class="<?= $halaman === 'koneksi' ? 'active' : '' ?>">Koneksi</a>
        <a href="panduan.php" class="<?= $halaman === 'panduan' ? 'active' : '' ?>">Panduan</a>
        <a href="../crud_latihan/index.php">Latihan</a>
        <a href="../index.php">&larr; Menu Latihan</a>
      </nav>
    </div>
  </header>
  <main class="wrap">
