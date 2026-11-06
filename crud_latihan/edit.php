<?php
$judul = 'Latihan Edit Produk';
$halaman = 'produk';
require __DIR__ . '/../config/database.php';

$kode = strtoupper(trim($_GET['kode'] ?? $_POST['kode_produk'] ?? ''));
$error = [];
$produk = null;

if ($kode === '') {
    $error[] = 'Kode produk tidak dikirim. Pilih produk dari halaman daftar.';
} else {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal
         FROM produk WHERE kode_produk = ?'
    );

    if (!$stmt) {
        $error[] = 'Tabel produk belum siap: ' . mysqli_error($koneksi);
    } else {
        mysqli_stmt_bind_param($stmt, 's', $kode);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $produk = mysqli_fetch_assoc($hasil);
        mysqli_stmt_close($stmt);

        if (!$produk) {
            $error[] = 'Produk dengan kode ' . $kode . ' tidak ditemukan.';
        }
    }
}

$nama = $produk['nama_produk'] ?? '';
$hargaBeliInput = $produk ? (string) $produk['harga_beli'] : '';
$hargaJualInput = $produk ? (string) $produk['harga_jual'] : '';
$stokInput = $produk ? (string) $produk['stok_awal'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produk) {
    // BAGIAN 1 - AMBIL INPUT
    // TODO: isi ulang $nama, $hargaBeliInput, $hargaJualInput, $stokInput
    // dari $_POST memakai trim() dan ?? ''.

    // BAGIAN 2 - VALIDASI
    // TODO: tambahkan pesan ke $error[] jika nama kosong, harga bukan angka,
    // atau stok bukan bilangan bulat >= 0.

    // BAGIAN 3 - SIMPAN PERUBAHAN
    // TODO: jika count($error) === 0, jalankan UPDATE produk SET ...
    // WHERE kode_produk = ? memakai prepared statement.

    // BAGIAN 4 - REDIRECT
    // TODO: jika berhasil, arahkan ke index.php?status=edit lalu exit.
}

require __DIR__ . '/partials/header.php';
?>

<p class="tag">Mode Latihan &middot; UPDATE</p>
<h1>Edit Produk</h1>
<p class="lead">
  Data lama sudah dibaca dan ditampilkan otomatis. Lengkapi bagian
  <code>TODO</code> untuk validasi dan menyimpan perubahan.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Perubahan belum bisa disimpan:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($produk): ?>
  <div class="card">
    <h2>Form Edit Produk</h2>
    <form method="post" action="edit.php">
      <input type="hidden" name="kode_produk" value="<?= htmlspecialchars($kode) ?>">

      <div class="form-grid">
        <div class="field">
          <label for="kode_produk_tampil">Kode Produk</label>
          <input type="text" id="kode_produk_tampil" value="<?= htmlspecialchars($kode) ?>" readonly>
          <small>Kode produk tidak bisa diubah.</small>
        </div>

        <div class="field">
          <label for="nama_produk">Nama Produk</label>
          <input type="text" id="nama_produk" name="nama_produk" maxlength="50"
                 value="<?= htmlspecialchars($nama) ?>" required>
        </div>

        <div class="field">
          <label for="harga_beli">Harga Beli (Rp)</label>
          <input type="number" id="harga_beli" name="harga_beli" min="0" step="0.01"
                 value="<?= htmlspecialchars($hargaBeliInput) ?>" required>
        </div>

        <div class="field">
          <label for="harga_jual">Harga Jual (Rp)</label>
          <input type="number" id="harga_jual" name="harga_jual" min="0" step="0.01"
                 value="<?= htmlspecialchars($hargaJualInput) ?>" required>
        </div>

        <div class="field">
          <label for="stok_awal">Stok Awal</label>
          <input type="number" id="stok_awal" name="stok_awal" min="0" step="1"
                 value="<?= htmlspecialchars($stokInput) ?>" required>
        </div>
      </div>

      <div class="actions-top">
        <button class="btn" type="submit">Simpan Perubahan</button>
        <a class="btn btn-secondary" href="index.php">Batal</a>
      </div>
    </form>
  </div>
<?php else: ?>
  <div class="actions-top">
    <a class="btn" href="index.php">Kembali ke Daftar Produk</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
