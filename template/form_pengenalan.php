<?php
$judul = 'Pengenalan Form';
$halaman = 'form';
require __DIR__ . '/partials/header.php';

$sudahKirim = isset($_POST['kirim']);
$nama = trim($_POST['nama'] ?? '');
?>

<p class="tag">P01 &middot; Pengenalan Form</p>
<h1>Form: Jembatan dari Pengguna ke Program</h1>
<p class="lead">
  Form adalah tempat pengguna mengisi data. Tanpa form, aplikasi hanya bisa
  menampilkan informasi; dengan form, aplikasi bisa menerima perintah dan data
  baru dari pengguna.
</p>

<div class="card">
  <h2>1. Apa itu form?</h2>
  <p>
    Form adalah bagian halaman web yang berisi kotak isian dan tombol. Saat
    tombol diklik, browser mengirim isi form ke halaman PHP tujuan.
  </p>
  <ol class="steps">
    <li>Pengguna mengisi data.</li>
    <li>Browser mengumpulkan data dari setiap kolom yang punya <code>name</code>.</li>
    <li>Data dikirim ke halaman yang ditulis di <code>action</code>.</li>
    <li>PHP membaca data lewat <code>$_POST</code> atau <code>$_GET</code>.</li>
  </ol>
</div>

<div class="card">
  <h2>2. Anatomi tag form</h2>
  <p>Ini bentuk paling sederhana dari sebuah form:</p>
  <pre>&lt;form method="post" action="form_pengenalan.php"&gt;
  &lt;label for="nama"&gt;Nama Pembeli&lt;/label&gt;
  &lt;input type="text" id="nama" name="nama"&gt;
  &lt;button type="submit" name="kirim" value="1"&gt;Kirim&lt;/button&gt;
&lt;/form&gt;</pre>

  <table>
    <thead>
      <tr>
        <th>Bagian</th>
        <th>Fungsi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>&lt;form&gt;</code></td>
        <td>Pembungkus semua kolom dan tombol form.</td>
      </tr>
      <tr>
        <td><code>action</code></td>
        <td>Halaman PHP yang akan menerima data.</td>
      </tr>
      <tr>
        <td><code>method</code></td>
        <td>Cara mengirim data: <code>get</code> atau <code>post</code>.</td>
      </tr>
      <tr>
        <td><code>&lt;label for&gt;</code></td>
        <td>Label yang terhubung ke input lewat <code>id</code>.</td>
      </tr>
      <tr>
        <td><code>&lt;input id&gt;</code></td>
        <td>Penghubung label dan input di halaman.</td>
      </tr>
      <tr>
        <td><code>&lt;input name&gt;</code></td>
        <td>Kunci data yang dibaca PHP, misalnya <code>$_POST['nama']</code>.</td>
      </tr>
      <tr>
        <td><code>&lt;button type="submit"&gt;</code></td>
        <td>Tombol yang memulai proses kirim data.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>3. id vs name: jangan tertukar</h2>
  <table>
    <thead>
      <tr>
        <th>Atribut</th>
        <th>Dipakai oleh</th>
        <th>Contoh</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>id</code></td>
        <td>HTML dan label</td>
        <td><code>&lt;label for="nama"&gt;</code> terhubung ke <code>id="nama"</code>.</td>
      </tr>
      <tr>
        <td><code>name</code></td>
        <td>PHP</td>
        <td><code>name="nama"</code> menjadi <code>$_POST['nama']</code>.</td>
      </tr>
    </tbody>
  </table>
  <p class="hint">Kalau <code>name</code> dihapus, data tidak ikut terkirim ke PHP.</p>
</div>

<div class="card">
  <h2>4. Coba form mini</h2>
  <form method="post" action="form_pengenalan.php">
    <div class="form-grid">
      <div class="field">
        <label for="nama">Nama Pembeli</label>
        <input type="text" id="nama" name="nama" placeholder="Contoh: Rina" required>
        <span class="hint">Coba isi dengan &lt;b&gt;Rina&lt;/b&gt; untuk melihat hasil escape.</span>
      </div>
    </div>
    <button class="btn" type="submit" name="kirim" value="1">Kirim Form</button>
  </form>
</div>

<?php if ($sudahKirim): ?>
  <?php if ($nama === ''): ?>
    <div class="alert error">
      Nama masih kosong. Isi dulu kolom nama, lalu kirim lagi.
    </div>
  <?php else: ?>
    <div class="result">
      <h2>Hasil yang Dibaca PHP</h2>
      <p>Nama: <strong><?= htmlspecialchars($nama) ?></strong></p>
      <p>Kunci data: <code>$_POST['nama']</code></p>
    </div>
  <?php endif; ?>
<?php endif; ?>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Ganti <code>method="post"</code> menjadi <code>method="get"</code>, lalu kirim form. Apa yang berubah di URL?</li>
    <li>Hapus atribut <code>name="nama"</code>, lalu kirim form. Mengapa data tidak terbaca PHP?</li>
    <li>Ganti <code>action</code> ke <code>form_output.php</code>, lalu kirim form. Halaman mana yang terbuka?</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="form_komponen.php">Lanjut: Komponen Form</a>
  <a class="btn" href="form.php">Kembali ke Ringkasan Form</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
