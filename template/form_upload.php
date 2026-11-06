<?php
$judul = 'Upload Foto';
$halaman = 'form';
require __DIR__ . '/partials/header.php';

$pesanErrorUpload = [
    UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas upload_max_filesize di php.ini.',
    UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas yang diizinkan form.',
    UPLOAD_ERR_PARTIAL => 'File hanya terunggah sebagian. Coba ulangi.',
    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang dipilih.',
    UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara untuk upload tidak tersedia.',
    UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file ke disk.',
    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP.',
];

$sudahKirim = $_SERVER['REQUEST_METHOD'] === 'POST';
$error = [];
$pesan = [];
$nama = '';
$fotoTersimpan = '';

if ($sudahKirim) {
    $nama = trim($_POST['nama'] ?? '');

    if ($nama === '') {
        $error[] = 'Nama pengunggah wajib diisi.';
    }

    $file = $_FILES['foto'] ?? null;

    if ($file === null) {
        $error[] = 'Pilih file foto terlebih dahulu.';
    } elseif (!is_array($file) || !isset($file['error']) || is_array($file['error'])) {
        $error[] = 'File foto tidak valid. Pilih satu file gambar.';
    } elseif ($file['error'] === UPLOAD_ERR_NO_FILE) {
        $error[] = 'Pilih file foto terlebih dahulu.';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $kodeError = (int) $file['error'];
        $error[] = $pesanErrorUpload[$kodeError] ?? 'Upload gagal dengan kode ' . $kodeError . '.';
    } else {
        $ukuranMaks = 2 * 1024 * 1024;

        if ((int) $file['size'] > $ukuranMaks) {
            $error[] = 'Ukuran foto maksimal 2 MB.';
        }

        $infoGambar = @getimagesize($file['tmp_name']);
        $tipeDiizinkan = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
        ];

        if ($infoGambar === false || !isset($tipeDiizinkan[$infoGambar[2]])) {
            $error[] = 'File harus berupa gambar JPG, PNG, atau WEBP.';
        }

        if (count($error) === 0) {
            $ext = $tipeDiizinkan[$infoGambar[2]];
            $namaSimpan = 'foto_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $folderUpload = __DIR__ . '/uploads';

            if (!is_dir($folderUpload)) {
                mkdir($folderUpload, 0775, true);
            }

            $tujuan = $folderUpload . DIRECTORY_SEPARATOR . $namaSimpan;

            if (move_uploaded_file($file['tmp_name'], $tujuan)) {
                $fotoTersimpan = 'uploads/' . $namaSimpan;
                $pesan[] = 'Foto berhasil diunggah dan disimpan di folder uploads.';
            } else {
                $error[] = 'Foto gagal dipindahkan. Periksa izin tulis folder uploads.';
            }
        }
    }
}
?>

<p class="tag">P05 &middot; Upload Foto</p>
<h1>Upload Foto/Image dari Form</h1>
<p class="lead">
  Form tidak hanya mengirim teks. Dengan <code>enctype="multipart/form-data"</code>
  dan <code>$_FILES</code>, PHP bisa menerima file gambar, memvalidasinya, lalu
  menyimpannya ke folder <code>uploads/</code>.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Upload belum berhasil:</strong>
    <ul>
      <?php foreach ($error as $pesanError): ?>
        <li><?= htmlspecialchars($pesanError) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php foreach ($pesan as $teksPesan): ?>
  <div class="alert ok"><?= htmlspecialchars($teksPesan) ?></div>
<?php endforeach; ?>

<div class="card">
  <h2>1. Coba upload foto</h2>
  <form method="post" action="form_upload.php" enctype="multipart/form-data">
    <div class="form-grid">
      <div class="field">
        <label for="nama">Nama Pengunggah</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>"
               placeholder="Contoh: Rina" required>
      </div>

      <div class="field">
        <label for="foto">File Foto</label>
        <input type="file" id="foto" name="foto"
               accept="image/jpeg,image/png,image/webp" required>
        <span class="hint">JPG, PNG, atau WEBP. Maksimal 2 MB.</span>
      </div>
    </div>

    <button class="btn" type="submit" name="kirim" value="1">Upload Foto</button>
  </form>
</div>

<?php if ($fotoTersimpan !== ''): ?>
  <div class="result">
    <h2>Foto Berhasil Diunggah</h2>
    <p>Nama: <strong><?= htmlspecialchars($nama) ?></strong></p>
    <p>File: <code><?= htmlspecialchars($fotoTersimpan) ?></code></p>
    <img class="upload-preview" src="<?= htmlspecialchars($fotoTersimpan) ?>" alt="Foto unggahan">
    <p class="hint">Foto ini disimpan di folder <code>template/uploads/</code>.</p>
  </div>
<?php endif; ?>

<div class="card">
  <h2>2. Kenapa perlu enctype?</h2>
  <p>
    Form yang mengirim file wajib memakai atribut
    <code>enctype="multipart/form-data"</code>. Tanpa atribut itu, browser hanya
    mengirim nama file, bukan isi gambarnya.
  </p>
  <pre>&lt;form method="post" action="form_upload.php"
      enctype="multipart/form-data"&gt;
  &lt;input type="file" name="foto"&gt;
  &lt;button type="submit"&gt;Upload&lt;/button&gt;
&lt;/form&gt;</pre>
  <p>
    Atribut <code>accept</code> hanya membantu browser menampilkan pilihan file.
    Pemeriksaan tetap harus dilakukan di server.
  </p>
</div>

<div class="card">
  <h2>3. Membaca file dengan $_FILES</h2>
  <p>
    File tidak dibaca lewat <code>$_POST</code>, tetapi lewat
    <code>$_FILES['foto']</code>. Isinya:
  </p>
  <table>
    <thead>
      <tr>
        <th>Kunci</th>
        <th>Arti</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>name</code></td>
        <td>Nama file asli dari komputer pengguna.</td>
      </tr>
      <tr>
        <td><code>type</code></td>
        <td>Jenis file menurut browser. Tidak boleh dipercaya sendirian.</td>
      </tr>
      <tr>
        <td><code>tmp_name</code></td>
        <td>Lokasi sementara file di server.</td>
      </tr>
      <tr>
        <td><code>error</code></td>
        <td>Kode status upload. <code>0</code> berarti berhasil.</td>
      </tr>
      <tr>
        <td><code>size</code></td>
        <td>Ukuran file dalam byte.</td>
      </tr>
    </tbody>
  </table>
  <pre>$file = $_FILES['foto'];

if ($file['error'] === UPLOAD_ERR_OK) {
    $namaAsli = $file['name'];
    $lokasiSementara = $file['tmp_name'];
    $ukuran = $file['size'];
}</pre>
</div>

<div class="card">
  <h2>4. Peta folder upload</h2>
  <pre>kasirqu/template/
├── form_upload.php       &lt;- halaman ini
├── assets/
│   └── style.css
└── uploads/
    ├── index.html        &lt;- mencegah daftar file terbuka
    └── foto_xxxx.jpg     &lt;- hasil upload</pre>

  <p>
    File disimpan dengan nama acak, misalnya
    <code>foto_9f2c1a7b3e4d5f60.jpg</code>. Nama asli tidak dipakai langsung
    agar tidak menimpa file lain dan menghindari nama yang berbahaya.
  </p>
</div>

<div class="card">
  <h2>5. Validasi file di server</h2>
  <ol class="steps">
    <li>Cek kode <code>error</code> dari <code>$_FILES</code>.</li>
    <li>Batasi ukuran file, misalnya maksimal 2 MB.</li>
    <li>Pastikan isi file benar-benar gambar dengan <code>getimagesize()</code>.</li>
    <li>Batasi tipe gambar: JPG, PNG, atau WEBP.</li>
    <li>Buat nama file baru yang acak.</li>
    <li>Pindahkan file dengan <code>move_uploaded_file()</code>.</li>
  </ol>

  <pre>$info = getimagesize($file['tmp_name']);

$tipeDiizinkan = [
    IMAGETYPE_JPEG =&gt; 'jpg',
    IMAGETYPE_PNG  =&gt; 'png',
    IMAGETYPE_WEBP =&gt; 'webp',
];

if ($info === false || !isset($tipeDiizinkan[$info[2]])) {
    $error[] = 'File harus berupa gambar JPG, PNG, atau WEBP.';
}</pre>

  <p>
    <code>getimagesize()</code> membaca isi file, bukan hanya ekstensinya.
    File yang hanya diganti nama menjadi <code>.jpg</code> tetap akan ditolak
    jika isinya bukan gambar.
  </p>

  <pre>$namaSimpan = 'foto_' . bin2hex(random_bytes(8)) . '.jpg';
$tujuan = __DIR__ . '/uploads/' . $namaSimpan;

if (move_uploaded_file($file['tmp_name'], $tujuan)) {
    $fotoTersimpan = 'uploads/' . $namaSimpan;
}</pre>
  <p>
    <code>move_uploaded_file()</code> dipakai karena file upload berada di
    lokasi sementara. File sementara akan hilang setelah request selesai.
  </p>
</div>

<div class="card">
  <h2>6. Batas ukuran dan php.ini</h2>
  <p>
    Selain validasi di PHP, server juga punya batas upload:
  </p>
  <table>
    <thead>
      <tr>
        <th>Pengaturan</th>
        <th>Fungsi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>upload_max_filesize</code></td>
        <td>Batas maksimal satu file upload.</td>
      </tr>
      <tr>
        <td><code>post_max_size</code></td>
        <td>Batas total data POST, termasuk file.</td>
      </tr>
      <tr>
        <td><code>file_uploads</code></td>
        <td>Harus <code>On</code> agar upload diizinkan.</td>
      </tr>
    </tbody>
  </table>
  <p class="hint">
    Jika file 2 MB ditolak padahal batas PHP 2 MB, periksa juga
    <code>post_max_size</code>.
  </p>
</div>

<div class="card">
  <h2>7. Keamanan upload</h2>
  <ul class="steps">
    <li>Jangan percaya <code>$_FILES['foto']['type']</code>; periksa isi file.</li>
    <li>Jangan memakai nama file asli langsung.</li>
    <li>Batasi ekstensi dan ukuran file.</li>
    <li>Jangan izinkan file PHP masuk ke folder upload.</li>
    <li>Di server produksi, simpan file di luar folder yang bisa dieksekusi.</li>
    <li>Untuk aplikasi nyata, pertimbangkan penyimpanan di object storage.</li>
  </ul>
</div>

<div class="card">
  <h2>8. Kesalahan umum</h2>
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
        <td>Lupa <code>enctype</code></td>
        <td><code>$_FILES</code> kosong.</td>
        <td>Tambahkan <code>enctype="multipart/form-data"</code>.</td>
      </tr>
      <tr>
        <td>Membaca lewat <code>$_POST['foto']</code></td>
        <td>File tidak terbaca.</td>
        <td>Gunakan <code>$_FILES['foto']</code>.</td>
      </tr>
      <tr>
        <td>Folder <code>uploads/</code> tidak ada</td>
        <td><code>move_uploaded_file()</code> gagal.</td>
        <td>Buat folder dan pastikan bisa ditulis.</td>
      </tr>
      <tr>
        <td>Hanya mengecek ekstensi</td>
        <td>File palsu bisa lolos.</td>
        <td>Gunakan <code>getimagesize()</code>.</td>
      </tr>
      <tr>
        <td>Ukuran file terlalu besar</td>
        <td>Upload gagal atau ditolak.</td>
        <td>Periksa batas PHP dan validasi ukuran.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Upload satu foto JPG atau PNG, lalu pastikan foto tampil di halaman hasil.</li>
    <li>Coba upload file PDF. Pastikan server menolaknya.</li>
    <li>Coba upload gambar lebih dari 2 MB. Catat pesan yang muncul.</li>
    <li>Buka folder <code>template/uploads/</code> dan lihat nama file hasil upload.</li>
    <li>Ubah teks tombol dan judul halaman, lalu uji kembali.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="form_latihan.php">Lanjut: Latihan Form</a>
  <a class="btn btn-secondary" href="form.php">Kembali ke Ringkasan Form</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
