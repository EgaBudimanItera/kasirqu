
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
//disini
$sudahKirim = $_SERVER['REQUEST_METHOD'] === 'POST';
$error = [];
// PENTING: Semua teks dari pengguna wajib di-escape dengan htmlspecialchars() sebelum ditampilkan di HTML.
$nama = trim($_POST['nama1'] ?? '');
$produk = $_POST['produk'] ?? '';
$jumlahInput = trim((string) ($_POST['jumlah'] ?? ''));
$metode = $_POST['metode'] ?? '';
$catatan = trim($_POST['catatan'] ?? '');
// Penting: Semua teks dari pengguna wajib di-escape dengan htmlspecialchars() sebelum ditampilkan di HTML.
var_dump($_POST);//debugging

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
<div class="card">
  <h2>Form Pemesanan</h2>
  <form method="post" action="form_lt.php">
    <div class="form-grid">
      <div class="field">
        <label for="nama">Nama Pembeli</label>
        <input type="text" id="nama" name="nama1" value="<?= htmlspecialchars($nama) ?>"
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

<?php require __DIR__ . '/partials/footer.php'; ?>
