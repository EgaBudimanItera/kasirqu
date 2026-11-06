<?php
$judul = 'Panduan Partial Header/Footer';
$halaman = 'panduan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Pertemuan 12 &middot; Panduan Partial</p>
<h1>Partial Header dan Footer</h1>
<p class="lead">
  Halaman ini menjelaskan layout bersama KasirQu: apa isi
  <code>partials/header.php</code>, apa isi <code>partials/footer.php</code>,
  cara memanggilnya, dan di folder mana semuanya harus diletakkan.
</p>

<div class="alert ok">
  Halaman ini memakai partial template yang asli, jadi topbar dan footer yang
  kamu lihat sekarang adalah contoh hasil pemanggilan partial.
</div>

<div class="card">
  <h2>1. Apa itu partial?</h2>
  <p>
    Partial adalah potongan halaman yang dipakai ulang. Bagian atas halaman
    (doctype, CSS, topbar, menu) disimpan di <code>header.php</code>. Bagian
    bawah (penutup konten, footer, penutup HTML) disimpan di
    <code>footer.php</code>.
  </p>
  <p>
    Dengan partial, halaman <code>index.php</code>, <code>produk.php</code>,
    <code>transaksi.php</code>, dan halaman form tidak perlu menulis ulang
    layout yang sama.
  </p>
</div>

<div class="card">
  <h2>2. Peta folder</h2>
  <pre>smk4/
└── kasirqu/
    ├── template/
    │   ├── partials/
    │   │   ├── header.php      &lt;- atas halaman
    │   │   └── footer.php      &lt;- bawah halaman
    │   ├── assets/
    │   │   └── style.css       &lt;- CSS untuk halaman template
    │   ├── index.php           &lt;- dashboard
    │   ├── produk.php          &lt;- contoh tabel produk
    │   ├── transaksi.php       &lt;- contoh transaksi
    │   ├── form.php            &lt;- ringkasan form
    │   ├── form_pengenalan.php
    │   ├── form_komponen.php
    │   ├── form_output.php
    │   ├── form_latihan.php
    │   ├── form_get.php
    │   ├── form_post.php
    │   └── panduan_partial.php &lt;- halaman ini
    └── crud/
        ├── partials/
        │   ├── header.php      &lt;- layout khusus CRUD
        │   └── footer.php
        ├── assets/
        │   └── style.css       &lt;- CSS khusus CRUD
        ├── index.php
        ├── tambah.php
        ├── edit.php
        ├── hapus.php
        ├── koneksi.php
        ├── panduan_koneksi.php
        ├── panduan_crud.php
        └── panduan.php         &lt;- hub panduan</pre>

  <p class="muted">
    Folder <code>template/</code> dan <code>crud/</code> sama-sama punya
    partial sendiri. Jangan mencampur CSS atau partial keduanya karena menu dan
    tampilannya berbeda.
  </p>
</div>

<div class="card">
  <h2>3. Isi <code>partials/header.php</code></h2>
  <pre>&lt;?php
$judul = $judul ?? 'KasirQu';
$halaman = $halaman ?? '';
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="id"&gt;
&lt;head&gt;
  &lt;meta charset="UTF-8"&gt;
  &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
  &lt;title&gt;&lt;?= htmlspecialchars($judul) ?&gt; - KasirQu&lt;/title&gt;
  &lt;link rel="stylesheet" href="assets/style.css"&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;header class="topbar"&gt;
    &lt;div class="topbar-inner"&gt;
      &lt;a class="brand" href="index.php"&gt;KasirQu&lt;/a&gt;
      &lt;nav class="menu"&gt;
        &lt;a href="index.php"&gt;Dashboard&lt;/a&gt;
        &lt;a href="produk.php"&gt;Produk&lt;/a&gt;
        &lt;a href="transaksi.php"&gt;Transaksi&lt;/a&gt;
        &lt;a href="form.php"&gt;Form&lt;/a&gt;
      &lt;/nav&gt;
    &lt;/div&gt;
  &lt;/header&gt;
  &lt;main class="wrap"&gt;</pre>

  <p>Bagian penting header:</p>
  <table>
    <thead>
      <tr>
        <th>Bagian</th>
        <th>Fungsi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>$judul = $judul ?? 'KasirQu'</code></td>
        <td>Mengambil judul dari halaman; jika tidak diisi, memakai “KasirQu”.</td>
      </tr>
      <tr>
        <td><code>$halaman = $halaman ?? ''</code></td>
        <td>Mengambil nama halaman aktif untuk menandai menu.</td>
      </tr>
      <tr>
        <td><code>htmlspecialchars($judul)</code></td>
        <td>Mengamankan judul sebelum masuk ke tag <code>&lt;title&gt;</code>.</td>
      </tr>
      <tr>
        <td><code>&lt;link rel="stylesheet" href="assets/style.css"&gt;</code></td>
        <td>Memuat CSS dari folder <code>assets/</code>.</td>
      </tr>
      <tr>
        <td><code>&lt;main class="wrap"&gt;</code></td>
        <td>Membuka area konten. Tag ini ditutup oleh footer.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>4. Isi <code>partials/footer.php</code></h2>
  <pre>  &lt;/main&gt;
  &lt;footer class="foot"&gt;
    KasirQu &amp;middot; Toko Maju Jaya &amp;middot; &lt;?= date('Y') ?&gt;
  &lt;/footer&gt;
&lt;/body&gt;
&lt;/html&gt;</pre>

  <p>
    Footer menutup <code>&lt;main&gt;</code> yang dibuka header, menampilkan
    keterangan toko, lalu menutup <code>&lt;body&gt;</code> dan
    <code>&lt;html&gt;</code>. Karena itu, header dan footer harus dipanggil
    berpasangan.
  </p>
</div>

<div class="card">
  <h2>5. Cara memakai partial di halaman</h2>
  <pre>&lt;?php
$judul = 'Produk';
$halaman = 'produk';
require __DIR__ . '/partials/header.php';
?&gt;

&lt;h1&gt;Daftar Produk&lt;/h1&gt;
&lt;p&gt;Isi halaman ada di sini.&lt;/p&gt;

&lt;?php require __DIR__ . '/partials/footer.php'; ?&gt;</pre>

  <p>Urutannya selalu:</p>
  <ol class="steps">
    <li>Set <code>$judul</code> dan <code>$halaman</code>.</li>
    <li><code>require</code> header.</li>
    <li>Tulis konten halaman.</li>
    <li><code>require</code> footer di paling bawah.</li>
  </ol>
</div>

<div class="card">
  <h2>6. Menu aktif dan nilai <code>$halaman</code></h2>
  <table>
    <thead>
      <tr>
        <th>Halaman</th>
        <th><code>$halaman</code></th>
        <th>Menu aktif</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>index.php</code></td>
        <td><code>dashboard</code></td>
        <td>Dashboard</td>
      </tr>
      <tr>
        <td><code>produk.php</code></td>
        <td><code>produk</code></td>
        <td>Produk</td>
      </tr>
      <tr>
        <td><code>transaksi.php</code></td>
        <td><code>transaksi</code></td>
        <td>Transaksi</td>
      </tr>
      <tr>
        <td><code>form.php</code></td>
        <td><code>form</code></td>
        <td>Form</td>
      </tr>
      <tr>
        <td><code>panduan_partial.php</code></td>
        <td><code>panduan</code></td>
        <td>Panduan</td>
      </tr>
    </tbody>
  </table>

  <p>
    Di header, menu aktif dicek dengan kode seperti ini:
  </p>
  <pre>&lt;a href="produk.php"
   class="&lt;?= $halaman === 'produk' ? 'active' : '' ?&gt;"&gt;
  Produk
&lt;/a&gt;</pre>
  <p>
    Kalau nilai <code>$halaman</code> tidak sama, menu tidak akan menyala.
  </p>
</div>

<div class="card">
  <h2>7. Aturan path CSS</h2>
  <p>
    Header menulis <code>href="assets/style.css"</code>. Artinya browser
    mencari CSS relatif terhadap halaman yang sedang dibuka.
  </p>
  <table>
    <thead>
      <tr>
        <th>Halaman</th>
        <th>CSS yang dicari</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>template/index.php</code></td>
        <td><code>template/assets/style.css</code></td>
        <td><span class="pill ok">Benar</span></td>
      </tr>
      <tr>
        <td><code>template/produk.php</code></td>
        <td><code>template/assets/style.css</code></td>
        <td><span class="pill ok">Benar</span></td>
      </tr>
      <tr>
        <td><code>template/sub/index.php</code></td>
        <td><code>template/sub/assets/style.css</code></td>
        <td><span class="pill no">Rusak</span></td>
      </tr>
    </tbody>
  </table>
  <p>
    Karena itu, halaman konten sebaiknya tetap di folder yang sama dengan
    <code>assets/</code>. Kalau halaman dipindah ke subfolder, ubah path CSS
    menjadi <code>../assets/style.css</code>.
  </p>
</div>

<div class="card">
  <h2>8. Partial template vs partial CRUD</h2>
  <table>
    <thead>
      <tr>
        <th>Aspek</th>
        <th><code>template/partials/</code></th>
        <th><code>crud/partials/</code></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Menu</td>
        <td>Dashboard, Produk, Transaksi, Form, Panduan</td>
        <td>Produk, Tambah, Koneksi, Panduan</td>
      </tr>
      <tr>
        <td>CSS</td>
        <td><code>template/assets/style.css</code></td>
        <td><code>crud/assets/style.css</code></td>
      </tr>
      <tr>
        <td>Tujuan</td>
        <td>Latihan template dan form (P12-P13)</td>
        <td>Modul koneksi dan CRUD (P14)</td>
      </tr>
      <tr>
        <td>Database</td>
        <td>Tidak perlu</td>
        <td>Dipakai oleh halaman CRUD</td>
      </tr>
    </tbody>
  </table>
  <p class="muted">
    Konsepnya sama: header membuka layout, footer menutupnya. Yang berbeda hanya
    isi menu dan CSS.
  </p>
</div>

<div class="card">
  <h2>9. Kesalahan umum</h2>
  <table>
    <thead>
      <tr>
        <th>Masalah</th>
        <th>Akibat</th>
        <th>Perbaikan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>$judul</code> tidak diset</td>
        <td>Judul memakai nilai default.</td>
        <td>Set sebelum <code>require</code> header.</td>
      </tr>
      <tr>
        <td><code>$halaman</code> salah nilai</td>
        <td>Menu tidak aktif.</td>
        <td>Samakan dengan pengecekan di header.</td>
      </tr>
      <tr>
        <td>Halaman dipindah ke subfolder</td>
        <td>CSS tidak ditemukan.</td>
        <td>Ubah path CSS atau kembalikan ke folder semula.</td>
      </tr>
      <tr>
        <td>Menulis <code>&lt;html&gt;</code> lagi di halaman konten</td>
        <td>Struktur HTML dobel.</td>
        <td>Biarkan doctype dan <code>&lt;html&gt;</code> hanya di header.</td>
      </tr>
      <tr>
        <td>Footer tidak dipanggil</td>
        <td><code>&lt;main&gt;</code> tidak tertutup.</td>
        <td>Panggil footer di akhir halaman.</td>
      </tr>
      <tr>
        <td>Membuka <code>partials/header.php</code> langsung</td>
        <td>Halaman terlihat setengah jadi.</td>
        <td>Buka halaman konten seperti <code>index.php</code>.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>10. Checklist praktik</h2>
  <ul class="steps">
    <li>Buka <code>index.php</code>, <code>produk.php</code>, dan <code>transaksi.php</code>; perhatikan topbar dan footer yang sama.</li>
    <li>Buka <code>partials/header.php</code>; cari tag pembuka <code>&lt;main&gt;</code>.</li>
    <li>Buka <code>partials/footer.php</code>; cari tag penutup <code>&lt;/main&gt;</code>.</li>
    <li>Pastikan menu aktif berubah saat berpindah halaman.</li>
    <li>Buat halaman baru di folder yang sama, lalu panggil header dan footer.</li>
  </ul>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Salin <code>produk.php</code> menjadi <code>pelanggan.php</code>.</li>
    <li>Set <code>$judul = 'Pelanggan';</code> dan <code>$halaman = 'pelanggan';</code>.</li>
    <li>Tambahkan menu Pelanggan di <code>partials/header.php</code>.</li>
    <li>Pastikan halaman baru tetap memakai CSS yang sama.</li>
    <li>Uji menu aktif saat halaman Pelanggan dibuka.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="index.php">Lihat Dashboard</a>
  <a class="btn btn-secondary" href="produk.php">Lihat Produk</a>
  <a class="btn btn-secondary" href="../crud/panduan.php">Kembali ke Panduan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
