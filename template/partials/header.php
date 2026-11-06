<?php
$judul = $judul ?? 'KasirQu';
$halaman = $halaman ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($judul) ?> - KasirQu</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <a class="brand" href="index.php">KasirQu <small>/ Toko Maju Jaya</small></a>
      <nav class="menu">
        <a href="index.php" class="<?= $halaman === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="produk.php" class="<?= $halaman === 'produk' ? 'active' : '' ?>">Produk</a>
        <a href="transaksi.php" class="<?= $halaman === 'transaksi' ? 'active' : '' ?>">Transaksi</a>
        <a href="form.php" class="<?= $halaman === 'form' ? 'active' : '' ?>">Form</a>
        <a href="panduan_partial.php" class="<?= $halaman === 'panduan' ? 'active' : '' ?>">Panduan</a>
      </nav>
    </div>
  </header>
  <main class="wrap">
