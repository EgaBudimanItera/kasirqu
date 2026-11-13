<?php
$judul = 'Latihan Daftar Produk';
$halaman = 'produk';
require __DIR__ . '/../config/database.php';

$cari = trim($_GET['cari'] ?? '');
$errorTabel = '';
$daftarProduk = [];
$jumlahProduk = 0;
$totalStok = 0;
$stokMenipis = 0;

// var_dump($cari); // untuk debugging, hapus setelah yakin $cari sudah benar
// // = C# === / !== / !=
// //jika $cari tidak kosong, lakukan pencarian dengan LIKE
// if ($cari !== ''){
//   //pencarian dengan LIKE
//   //siapkan query SELECT dengan WHERE kode_produk LIKE ? OR nama_produk LIKE ?
//   //gunakan mysqli_prepare(), bind_param(), execute(), lalu ambil hasilnya
//   //masukkan setiap baris ke $daftarProduk
//   $stmt = mysqli_prepare($koneksi, "SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal FROM produk WHERE kode_produk LIKE ? OR nama_produk LIKE ?");
//   if ($stmt) {
//     $kataKunci = '%' . $cari . '%';
//     mysqli_stmt_bind_param($stmt, 'ss', $kataKunci, $kataKunci);
//     mysqli_stmt_execute($stmt);     
//   }
//   $hasil = mysqli_stmt_get_result($stmt);
//   while ($baris = mysqli_fetch_assoc($hasil)) {
//     $daftarProduk[] = $baris;
//   }
//   mysqli_stmt_close($stmt); 
// }
// //jika $cari kosong, ambil semua data produk dari database
// else{
//   //tampilkan semua data produk
//   $hasil = mysqli_query($koneksi, "SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal FROM produk");
//   while ($baris = mysqli_fetch_assoc($hasil)) {
//     $daftarProduk[] = $baris;     
//   }
//   $
// }
// // BAGIAN 1 - AMBIL DATA
// // TODO: tulis query SELECT ke tabel produk.

// // Jika $cari terisi, gunakan WHERE kode_produk LIKE ? OR nama_produk LIKE ?.
// // Siapkan dengan mysqli_prepare(), bind_param(), execute(), lalu ambil hasilnya.
// // Masukkan setiap baris ke $daftarProduk.
// // Contoh lengkap: ../crud/index.php

// // BAGIAN 2 - HITUNG STATISTIK
// // TODO: hitung $jumlahProduk, $totalStok, dan $stokMenipis dari $daftarProduk.

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

require __DIR__ . '/partials/header.php';
?>

<p class="tag">Mode Latihan &middot; READ</p>
<h1>Daftar Produk Toko Maju Jaya</h1>
<p class="lead">
  Semua logika database dikosongkan. Lengkapi bagian bertanda <code>TODO</code>
  dengan melihat versi contoh dan panduannya.
</p>

<?php if ($errorTabel !== ''): ?>
  <div class="alert error">
    <strong>Tabel <code>produk</code> belum bisa dibaca.</strong>
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
  <a class="btn btn-secondary" href="../crud/index.php">Lihat Versi Contoh</a>
  <a class="btn btn-secondary" href="../crud/presentasi_p11.php">Slide P11: Update &amp; Delete</a>
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
            Belum ada data. Lengkapi BAGIAN 1 agar tabel terisi.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($daftarProduk as $produk): ?>
          <tr>
            <td><code><?= htmlspecialchars($produk['kode_produk']) ?></code></td>
            <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
            <td class="num">Rp <?= number_format((float) $produk['harga_beli'], 0, ',', '.') ?></td>
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

<?php require __DIR__ . '/partials/footer.php'; ?>
