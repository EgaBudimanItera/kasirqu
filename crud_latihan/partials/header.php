<?php
$judul = $judul ?? 'Latihan CRUD Produk';
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
      <a class="brand" href="index.php">KasirQu <small>/ Latihan CRUD</small></a>
      <nav class="menu">
        <a href="index.php" class="<?= $halaman === 'produk' ? 'active' : '' ?>">Produk</a>
        <a href="tambah.php" class="<?= $halaman === 'tambah' ? 'active' : '' ?>">Tambah</a>
        <a href="laporan_penjualan.php" class="<?= $halaman === 'laporan_penjualan' ? 'active' : '' ?>">Laporan</a>
        <a href="../crud/index.php">Contoh</a>
        <a href="../index.php">&larr; Menu Latihan</a>
      </nav>
    </div>
  </header>
  <main class="wrap">
