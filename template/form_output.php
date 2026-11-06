<?php
$judul = 'Output Form';
$halaman = 'form';
require __DIR__ . '/partials/header.php';

$produkPilihan = ['Beras 5 kg', 'Minyak Goreng 1 L', 'Gula Pasir 1 kg', 'Telur 1 kg'];
$metodePilihan = ['Tunai', 'Transfer', 'QRIS'];
$hargaProduk = [
    'Beras 5 kg' => 62000,
    'Minyak Goreng 1 L' => 15000,
    'Gula Pasir 1 kg' => 18000,
    'Telur 1 kg' => 28000,
];

$sudahKirim = $_SERVER['REQUEST_METHOD'] === 'POST';
$error = [];

$nama = trim($_POST['nama'] ?? '');
$produk = $_POST['produk'] ?? '';
$jumlahInput = trim((string) ($_POST['jumlah'] ?? ''));
$metode = $_POST['metode'] ?? '';
$catatan = trim($_POST['catatan'] ?? '');

if ($sudahKirim) {
    if ($nama === '') {
        $error[] = 'Nama pembeli wajib diisi.';
    }

    if (!in_array($produk, $produkPilihan, true)) {
        $error[] = 'Pilih produk dari daftar.';
    }

    $jumlahValid = filter_var($jumlahInput, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);
    if ($jumlahInput === '' || $jumlahValid === false) {
        $error[] = 'Jumlah harus bilangan bulat minimal 1.';
    }

    if (!in_array($metode, $metodePilihan, true)) {
        $error[] = 'Pilih metode pembayaran.';
    }
}

$tampilHasil = $sudahKirim && count($error) === 0;
$jumlah = $tampilHasil ? (int) $jumlahValid : 0;
$harga = $tampilHasil ? $hargaProduk[$produk] : 0;
$total = $harga * $jumlah;
?>

<p class="tag">P03 &middot; Output Form</p>
<h1>Membaca dan Menampilkan Output Form</h1>
<p class="lead">
  Setelah data dikirim, PHP membacanya dari <code>$_POST</code>, memvalidasi,
  lalu menampilkan hasil. Semua teks dari pengguna wajib di-escape dengan
  <code>htmlspecialchars</code>.
</p>

<div class="card">
  <h2>Form Pemesanan</h2>
  <form method="post" action="form_output.php">
    <div class="form-grid">
      <div class="field">
        <label for="nama">Nama Pembeli</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>"
               placeholder="Contoh: Rina" required>
      </div>

      <div class="field">
        <label for="produk">Produk</label>
        <select id="produk" name="produk" required>
          <option value="">-- Pilih produk --</option>
          <?php foreach ($produkPilihan as $pilihan): ?>
            <option value="<?= htmlspecialchars($pilihan) ?>" <?= $produk === $pilihan ? 'selected' : '' ?>>
              <?= htmlspecialchars($pilihan) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label for="jumlah">Jumlah</label>
        <input type="number" id="jumlah" name="jumlah" min="1" step="1"
               value="<?= htmlspecialchars($jumlahInput) ?>" placeholder="1" required>
      </div>

      <div class="field">
        <label for="metode">Metode Bayar</label>
        <select id="metode" name="metode" required>
          <option value="">-- Pilih metode --</option>
          <?php foreach ($metodePilihan as $pilihan): ?>
            <option value="<?= htmlspecialchars($pilihan) ?>" <?= $metode === $pilihan ? 'selected' : '' ?>>
              <?= htmlspecialchars($pilihan) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="field">
      <label for="catatan">Catatan</label>
      <textarea id="catatan" name="catatan" placeholder="Contoh: antar ke kantin"><?= htmlspecialchars($catatan) ?></textarea>
    </div>

    <p style="margin-top:16px;">
      <button class="btn" type="submit" name="kirim" value="1">Kirim Pesanan</button>
    </p>
  </form>
</div>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Data belum valid:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($tampilHasil): ?>
  <div class="result">
    <h2>Pesanan Diterima</h2>
    <table>
      <tbody>
        <tr>
          <th>Nama Pembeli</th>
          <td><?= htmlspecialchars($nama) ?></td>
        </tr>
        <tr>
          <th>Produk</th>
          <td><?= htmlspecialchars($produk) ?></td>
        </tr>
        <tr>
          <th>Harga Satuan</th>
          <td>Rp <?= number_format($harga, 0, ',', '.') ?></td>
        </tr>
        <tr>
          <th>Jumlah</th>
          <td><?= $jumlah ?></td>
        </tr>
        <tr>
          <th>Total</th>
          <td><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
        </tr>
        <tr>
          <th>Metode Bayar</th>
          <td><?= htmlspecialchars($metode) ?></td>
        </tr>
        <tr>
          <th>Catatan</th>
          <td><?= $catatan !== '' ? htmlspecialchars($catatan) : '(tidak ada)' ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Isi $_POST yang Diterima PHP</h2>
    <table>
      <thead>
        <tr>
          <th>Key</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_POST as $key => $value): ?>
          <?php
            $nilaiTampil = is_array($value)
                ? implode(', ', array_filter($value, 'is_scalar'))
                : (string) $value;
          ?>
          <tr>
            <td><code><?= htmlspecialchars((string) $key) ?></code></td>
            <td><?= htmlspecialchars($nilaiTampil) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<div class="card">
  <h2>Kode Inti Output</h2>
  <pre>$nama = trim($_POST['nama'] ?? '');

if ($nama === '') {
    $error[] = 'Nama wajib diisi.';
}

echo htmlspecialchars($nama);</pre>
  <p>
    <code>trim()</code> membuang spasi di awal/akhir. <code>?? ''</code> memberi
    nilai cadangan agar tidak error saat data belum ada. <code>htmlspecialchars</code>
    membuat teks seperti <code>&lt;b&gt;Rina&lt;/b&gt;</code> tampil sebagai teks biasa,
    bukan HTML.
  </p>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Kirim form dengan nama <code>&lt;b&gt;Rina&lt;/b&gt;</code>. Pastikan yang tampil adalah teks biasa.</li>
    <li>Kosongkan nama, lalu kirim. Baca pesan validasi yang muncul.</li>
    <li>Isi jumlah dengan <code>0</code> atau <code>-2</code>. Mengapa ditolak?</li>
    <li>Bandingkan halaman ini dengan <a href="form_get.php">Form GET</a> dan <a href="form_post.php">Form POST</a>.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="form_latihan.php">Lanjut: Latihan Form</a>
  <a class="btn" href="form_komponen.php">Kembali: Komponen</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
