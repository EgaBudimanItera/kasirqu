<?php
$judul = 'Latihan Tambah Produk';
$halaman = 'tambah';
require __DIR__ . '/../config/database.php';

$error = [];
$kode = '';
$nama = '';
$hargaBeliInput = '';
$hargaJualInput = '';
$stokInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // BAGIAN 1 - AMBIL INPUT
    // TODO: isi $kode, $nama, $hargaBeliInput, $hargaJualInput, $stokInput
    // dari $_POST. Gunakan trim(), strtoupper() untuk kode, dan ?? ''.

    // BAGIAN 2 - VALIDASI TEKS
    // TODO: tambahkan pesan ke $error[] jika kode/nama kosong atau tidak valid.

    // BAGIAN 3 - VALIDASI ANGKA
    // TODO: pastikan harga berupa angka >= 0 dan stok bilangan bulat >= 0.

    // BAGIAN 4 - CEK KODE DUPLIKAT
    // TODO: SELECT kode_produk FROM produk WHERE kode_produk = ?
    // lalu cek mysqli_stmt_num_rows().

    // BAGIAN 5 - SIMPAN DATA
    // TODO: jika count($error) === 0, jalankan INSERT INTO produk
    // memakai prepared statement dan mysqli_stmt_bind_param().

    // BAGIAN 6 - REDIRECT
    // TODO: jika berhasil, arahkan ke index.php?status=tambah lalu exit.
}

require __DIR__ . '/partials/header.php';
?>

<p class="tag">Mode Latihan &middot; CREATE</p>
<h1>Tambah Produk Baru</h1>
<p class="lead">
  Form dan tombol sudah siap. Lengkapi bagian bertanda <code>TODO</code> di
  dalam file ini agar data bisa disimpan ke tabel <code>produk</code>.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Data belum bisa disimpan:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card">
  <h2>Form Produk</h2>
  <form method="post" action="tambah.php">
    <div class="form-grid">
      <div class="field">
        <label for="kode_produk">Kode Produk</label>
        <input type="text" id="kode_produk" name="kode_produk" maxlength="10"
               value="<?= htmlspecialchars($kode) ?>" placeholder="Contoh: BRG106" required>
        <small>2-10 karakter, huruf/angka/tanda minus. Kode tidak boleh sama.</small>
      </div>

      <div class="field">
        <label for="nama_produk">Nama Produk</label>
        <input type="text" id="nama_produk" name="nama_produk" maxlength="50"
               value="<?= htmlspecialchars($nama) ?>" placeholder="Contoh: Kopi Sachet" required>
      </div>

      <div class="field">
        <label for="harga_beli">Harga Beli (Rp)</label>
        <input type="number" id="harga_beli" name="harga_beli" min="0" step="0.01"
               value="<?= htmlspecialchars($hargaBeliInput) ?>" placeholder="2500" required>
      </div>

      <div class="field">
        <label for="harga_jual">Harga Jual (Rp)</label>
        <input type="number" id="harga_jual" name="harga_jual" min="0" step="0.01"
               value="<?= htmlspecialchars($hargaJualInput) ?>" placeholder="3500" required>
      </div>

      <div class="field">
        <label for="stok_awal">Stok Awal</label>
        <input type="number" id="stok_awal" name="stok_awal" min="0" step="1"
               value="<?= htmlspecialchars($stokInput) ?>" placeholder="20" required>
      </div>
    </div>

    <div class="actions-top">
      <button class="btn" type="submit">Simpan Produk</button>
      <a class="btn btn-secondary" href="index.php">Batal</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
