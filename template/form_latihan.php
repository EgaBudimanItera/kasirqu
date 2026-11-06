<?php
$judul = 'Latihan Form';
$halaman = 'form';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">P04 &middot; Latihan</p>
<h1>Latihan: Form Pemesanan KasirQu</h1>
<p class="lead">
  Saatnya membuat form sendiri. Gunakan komponen yang sudah dipelajari, lalu
  tampilkan hasilnya. Tidak perlu database; fokus pada form dan output PHP.
</p>

<div class="card">
  <h2>Brief</h2>
  <p>
    Toko Maju Jaya ingin menerima pesanan dari pembeli lewat halaman web.
    Buat satu halaman form pemesanan yang berisi data berikut:
  </p>
  <table>
    <thead>
      <tr>
        <th>Data</th>
        <th>Komponen</th>
        <th>Aturan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Nama pembeli</td>
        <td><code>input type="text"</code></td>
        <td>Wajib diisi, maksimal 50 karakter.</td>
      </tr>
      <tr>
        <td>Nomor WhatsApp</td>
        <td><code>input type="text"</code> atau <code>tel</code></td>
        <td>Wajib diisi, hanya angka.</td>
      </tr>
      <tr>
        <td>Produk</td>
        <td><code>select</code></td>
        <td>Pilih salah satu dari 4 produk.</td>
      </tr>
      <tr>
        <td>Jumlah</td>
        <td><code>input type="number"</code></td>
        <td>Minimal 1.</td>
      </tr>
      <tr>
        <td>Metode bayar</td>
        <td>radio</td>
        <td>Tunai, Transfer, atau QRIS.</td>
      </tr>
      <tr>
        <td>Topping</td>
        <td>checkbox</td>
        <td>Boleh pilih lebih dari satu (opsional).</td>
      </tr>
      <tr>
        <td>Alamat / catatan</td>
        <td><code>textarea</code></td>
        <td>Opsional, maksimal 200 karakter.</td>
      </tr>
      <tr>
        <td>Konfirmasi</td>
        <td>checkbox</td>
        <td>Wajib dicentang sebelum kirim.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Langkah Kerja</h2>
  <ol class="steps">
    <li>Salin <code>form_output.php</code> menjadi <code>form_pesanan_saya.php</code>.</li>
    <li>Ubah judul dan teks pembuka.</li>
    <li>Tambahkan field nomor WhatsApp, topping, dan checkbox konfirmasi.</li>
    <li>Di bagian PHP, ambil data dengan <code>$_POST</code> dan <code>trim()</code>.</li>
    <li>Validasi nama, nomor WhatsApp, produk, jumlah, dan konfirmasi.</li>
    <li>Tampilkan hasil dalam tabel dengan <code>htmlspecialchars</code>.</li>
    <li>Uji dengan data benar dan data salah.</li>
  </ol>
</div>

<div class="card">
  <h2>Kerangka Kode</h2>
  <pre>&lt;?php
$nama = trim($_POST['nama'] ?? '');
$wa = trim($_POST['wa'] ?? '');
$produk = $_POST['produk'] ?? '';
$jumlah = (int) ($_POST['jumlah'] ?? 0);
$metode = $_POST['metode'] ?? '';
$topping = $_POST['topping'] ?? [];
$catatan = trim($_POST['catatan'] ?? '');
$setuju = isset($_POST['setuju']);

$error = [];

if ($nama === '') {
    $error[] = 'Nama wajib diisi.';
}
// TODO: validasi field lain

if (count($error) === 0) {
    // TODO: tampilkan hasil
}
?&gt;</pre>
</div>

<div class="card">
  <h2>Checklist Sebelum Selesai</h2>
  <ul class="steps">
    <li>Semua input punya <code>label</code> dan <code>name</code>.</li>
    <li>Form memakai <code>method="post"</code>.</li>
    <li>Data kosong ditolak dengan pesan yang jelas.</li>
    <li>Jumlah tidak bisa 0 atau negatif.</li>
    <li>Checkbox konfirmasi wajib dicentang.</li>
    <li>Semua output memakai <code>htmlspecialchars</code>.</li>
    <li>Form bisa di-reset dan diisi ulang tanpa error.</li>
  </ul>
</div>

<div class="task">
  <h3>// TANTANGAN TAMBAHAN</h3>
  <ol>
    <li>Hitung total harga dari jumlah x harga produk.</li>
    <li>Beri potongan Rp 5.000 jika total di atas Rp 100.000.</li>
    <li>Tampilkan topping sebagai daftar, bukan teks panjang.</li>
    <li>Simpan hasil terakhir di session agar tidak hilang saat halaman di-refresh.</li>
    <li>Tambahkan upload foto dan validasi tipe file memakai <code>getimagesize()</code>.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="form_output.php">Lihat Contoh Output</a>
  <a class="btn btn-secondary" href="form_upload.php">Coba Upload Foto</a>
  <a class="btn btn-secondary" href="form.php">Kembali ke Ringkasan Form</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
