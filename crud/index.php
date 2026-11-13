<?php
$judul = 'Daftar Produk';
$halaman = 'produk';
require __DIR__ . '/../config/database.php';
require __DIR__ . '/partials/header.php';

$cari = trim($_GET['cari'] ?? '');
$errorTabel = '';

if ($cari !== '') {
    $sql = "SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal
            FROM produk
            WHERE kode_produk LIKE ? OR nama_produk LIKE ?
            ORDER BY kode_produk";
    $stmt = mysqli_prepare($koneksi, $sql);

    if (!$stmt) {
        $errorTabel = mysqli_error($koneksi);
        $hasil = false;
    } else {
      //mie ayam jumbo
      //ayam% = ayam goreng, ayam bakar, ayam geprek
      //%ayam= mie ayam, soto ayam, ayam bakar

        $kataKunci = '%' . $cari . '%';
        mysqli_stmt_bind_param($stmt, 'ss', $kataKunci, $kataKunci);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
} else {
    $hasil = mysqli_query(
        $koneksi,
        "SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal
         FROM produk
         ORDER BY kode_produk"
    );

    if (!$hasil) {
        $errorTabel = mysqli_error($koneksi);
    }
}

$daftarProduk = [];// Array kosong untuk menampung data produk dari database ke tabel HTML. Jika tidak ada data, tetap menampilkan tabel kosong.
if ($hasil) {
    while ($baris = mysqli_fetch_assoc($hasil)) {
        $daftarProduk[] = $baris;
    }
}

$jumlahProduk = count($daftarProduk);
$totalStok = 0;
$stokMenipis = 0;
foreach ($daftarProduk as $baris) {
    $totalStok += (int) $baris['stok_awal'];
    if ((int) $baris['stok_awal'] <= 5) {
        $stokMenipis++;
    }
}

$pesanStatus = [
    'tambah' => ['ok', 'Produk baru berhasil ditambahkan ke database.'],
    'edit'   => ['ok', 'Perubahan produk berhasil disimpan.'],
    'hapus'  => ['ok', 'Produk berhasil dihapus dari database.'],
];
$status = $_GET['status'] ?? '';
?>

<p class="tag">Pertemuan 14 &middot; Koneksi Database</p>
<h1>Daftar Produk Toko Maju Jaya</h1>
<p class="lead">
  Data di bawah ini dibaca langsung dari tabel <code>produk</code> di database
  <code>db_penjualan</code>. Tambah, edit, dan hapus data lewat tombol yang tersedia.
</p>

<?php if ($errorTabel !== ''): ?>
  <div class="alert error">
    <strong>Tabel <code>produk</code> belum bisa dibaca.</strong>
    Impor <code>kasirqu/crud/setup_produk.sql</code> lewat phpMyAdmin, lalu muat ulang halaman ini.
    <br><small>Pesan MySQL: <?= htmlspecialchars($errorTabel) ?></small>
  </div>
<?php endif; ?>

<?php if (isset($pesanStatus[$status])): ?>
  <div class="alert <?= $pesanStatus[$status][0] ?>">
    <?= htmlspecialchars($pesanStatus[$status][1]) ?>
  </div>
<?php endif; ?>

<div class="grid">
  <div class="stat">
    <span class="stat-label">Jumlah Produk</span>
    <strong class="stat-value"><?= $jumlahProduk ?></strong>
    <span class="stat-note">baris di tabel produk</span>
  </div>
  <div class="stat">
    <span class="stat-label">Total Stok Awal</span>
    <strong class="stat-value"><?= number_format($totalStok, 0, ',', '.') ?></strong>
    <span class="stat-note">dari semua produk</span>
  </div>
  <div class="stat">
    <span class="stat-label">Stok Menipis</span>
    <strong class="stat-value"><?= $stokMenipis ?></strong>
    <span class="stat-note">stok 5 atau kurang</span>
  </div>
</div>

<div class="actions-top">
  <a class="btn" href="tambah.php">+ Tambah Produk</a>
  <a class="btn btn-secondary" href="transaksi.php">Transaksi</a>
  <a class="btn btn-secondary" href="koneksi.php">Cek Koneksi</a>
  <a class="btn btn-secondary" href="panduan.php">Panduan</a>
</div>

<div class="card">
  <h2>Data Produk</h2>

  <form class="search-row" method="get" action="index.php">
    <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>"
           placeholder="Cari kode atau nama produk...">
    <button class="btn" type="submit">Cari</button>
    <?php if ($cari !== ''): ?>
      <a class="btn btn-secondary" href="index.php">Reset</a>
    <?php endif; ?>
  </form>

  <?php if ($cari !== ''): ?>
    <p class="muted">Hasil pencarian untuk: <strong><?= htmlspecialchars($cari) ?></strong></p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama Produk</th>
        <th class="num">Harga Beli</th>
        <th class="num">Harga Jual</th>
        <th class="num">Stok Awal</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($jumlahProduk === 0): ?>
        <tr>
          <td class="empty" colspan="7">
            <?= $cari !== '' ? 'Tidak ada produk yang cocok dengan pencarian.' : 'Belum ada produk. Klik "Tambah Produk" untuk mengisi data.' ?>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($daftarProduk as $produk): ?>
          <tr>
            <td><code><?= htmlspecialchars($produk['kode_produk']) ?></code></td>
            <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
            <td class="num">Rp <?= number_format((float) $produk['harga_beli'], 0, ',', '.') ?></td>
            <!-- <td><?=$produk['harga_beli']?></td> -->
            <td class="num">Rp <?= number_format((float) $produk['harga_jual'], 0, ',', '.') ?></td>
            <td class="num"><?= (int) $produk['stok_awal'] ?></td>
            <td>
              <?php if ((int) $produk['stok_awal'] <= 5): ?>
                <span class="pill no">Menipis</span>
              <?php else: ?>
                <span class="pill ok">Aman</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="table-actions">
                <a class="btn btn-sm btn-secondary" href="edit.php?kode=<?= urlencode($produk['kode_produk']) ?>">Edit</a>
                <a class="btn btn-sm btn-danger" href="hapus.php?kode=<?= urlencode($produk['kode_produk']) ?>">Hapus</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Tambah satu produk baru, lalu cari produk itu lewat kotak pencarian.</li>
    <li>Edit harga jual produk tersebut, lalu pastikan tabel berubah.</li>
    <li>Hapus produk baru itu, lalu pastikan jumlah produk kembali seperti semula.</li>
    <li>Buka <code>config/database.php</code> dan cari tahu di mana nama database ditulis.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
