<?php
// ============================================================
// kasirqu/crud/presentasi_p11.php
// Slide Pertemuan 11 (Update & Delete produk) dalam browser.
//
// Gambar slide + manifest dibuat otomatis oleh:
//     python scripts/export_pertemuan11_slides.py
// setelah deck pertemuan-11/Pertemuan_11_Update_Delete_Produk.pptx diubah.
//
// Gaya dan navigasi memakai assets/presentasi.css + presentasi.js
// (sama seperti halaman P16), hanya data slide-nya yang berbeda.
// ============================================================

$manifestPath = __DIR__ . '/assets/slide-p11/manifest.json';
$manifest = [];

if (is_file($manifestPath)) {
    $isi = json_decode((string) file_get_contents($manifestPath), true);
    if (is_array($isi)) {
        $manifest = $isi;
    }
}

$slides = $manifest['slides'] ?? [];
$jumlah = count($slides);
$judul = 'Slide P11 - Update & Delete Produk';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Slide mengajar Pertemuan 11: update dan delete data produk KasirQu.">
  <title><?= htmlspecialchars($judul) ?> - KasirQu</title>
  <link rel="stylesheet" href="assets/presentasi.css">
</head>
<body class="presentasi-body">
  <noscript>
    <style>
      .toolbar, .panah, .catatan, .daftar-slide, .tirai { display: none !important; }
      body.presentasi-body { background: #F4F6FA; overflow: auto; }
      .panggung { width: min(1100px, calc(100vw - 32px)); height: auto; margin: 16px auto; background: transparent; box-shadow: none; border-radius: 0; }
      .slide { display: block; margin-bottom: 16px; background: #fff; box-shadow: 0 8px 24px rgba(0, 0, 0, .18); }
      .slide img { height: auto; }
    </style>
    <p style="max-width: 1100px; margin: 16px auto; padding: 12px 16px; background: #FFF3E0; border-left: 4px solid #E87D0E; color: #262B33;">
      JavaScript mati, jadi navigasi slide tidak aktif. Semua slide ditampilkan
      berurutan di bawah ini.
    </p>
  </noscript>
  <header class="toolbar" id="toolbar">
    <a class="toolbar-brand" href="index.php">
      KasirQu <small>P11 &middot; Update &amp; Delete</small>
    </a>

    <div class="toolbar-progress" aria-hidden="true">
      <span class="toolbar-progress-bar" id="progressBar"></span>
    </div>

    <div class="toolbar-aksi">
      <span class="penghitung" id="slideCounter" aria-live="polite">1 / <?= $jumlah ?></span>
      <button class="tombol" type="button" data-action="prev" aria-label="Slide sebelumnya" title="Slide sebelumnya (Panah kiri)">&larr;</button>
      <button class="tombol" type="button" data-action="next" aria-label="Slide berikutnya" title="Slide berikutnya (Panah kanan / Space)">&rarr;</button>
      <button class="tombol tombol-teks" type="button" id="notesButton" aria-pressed="false" title="Tampilkan catatan pelatih (N)">Catatan</button>
      <button class="tombol tombol-teks" type="button" id="outlineButton" aria-expanded="false" aria-controls="outlinePanel" title="Daftar slide (O)">Daftar Slide</button>
      <button class="tombol tombol-teks" type="button" id="fullscreenButton" title="Layar penuh (F)">Fullscreen</button>
      <a class="tombol tombol-teks" href="panduan_crud.php">Panduan</a>
      <a class="tombol tombol-teks" href="../crud_latihan/index.php">Latihan</a>
    </div>
  </header>

  <?php if ($jumlah === 0): ?>
    <main class="kosong">
      <h1>Slide belum tersedia</h1>
      <p>
        Jalankan perintah berikut dari folder <code>smk4</code> untuk membuat
        gambar slide dan manifest:
      </p>
      <pre>python scripts/export_pertemuan11_slides.py</pre>
      <p>
        Selama itu, materi tetap bisa dibaca di
        <a href="panduan_crud.php">Panduan CRUD</a>.
      </p>
    </main>
  <?php else: ?>
    <main class="panggung" id="stage" aria-label="Slide Pertemuan 11">
      <?php foreach ($slides as $i => $slide): ?>
        <?php
        $nomor = $i + 1;
        $judulSlide = (string) ($slide['judul'] ?? ('Slide ' . $nomor));
        $berkas = (string) ($slide['berkas'] ?? '');
        ?>
        <section class="slide<?= $nomor === 1 ? ' aktif' : '' ?>"
                 id="slide-<?= $nomor ?>"
                 data-title="<?= htmlspecialchars($judulSlide) ?>"
                 aria-hidden="<?= $nomor === 1 ? 'false' : 'true' ?>"
                 aria-label="Slide <?= $nomor ?>: <?= htmlspecialchars($judulSlide) ?>">
          <img src="assets/slide-p11/<?= htmlspecialchars($berkas) ?>"
               alt="Slide <?= $nomor ?>: <?= htmlspecialchars($judulSlide) ?>"
               width="<?= (int) ($manifest['lebar'] ?? 1920) ?>"
               height="<?= (int) ($manifest['tinggi'] ?? 1080) ?>"
               <?= $nomor <= 2 ? 'decoding="async"' : 'loading="lazy" decoding="async"' ?>>
        </section>
      <?php endforeach; ?>
    </main>

    <button class="panah panah-kiri" type="button" data-action="prev" aria-label="Slide sebelumnya">&larr;</button>
    <button class="panah panah-kanan" type="button" data-action="next" aria-label="Slide berikutnya">&rarr;</button>

    <section class="catatan" id="notesPanel" aria-label="Catatan pelatih" aria-hidden="true">
      <div class="catatan-kepala">
        <strong id="notesTitle">Catatan pelatih</strong>
        <span class="catatan-petunjuk">N = tampil/sembunyi &middot; isi catatan diambil dari speaker notes deck</span>
        <button class="tombol tombol-teks" type="button" id="notesClose" aria-label="Tutup catatan">Tutup</button>
      </div>
      <div class="catatan-isi" id="notesBody"></div>
    </section>

    <div class="tirai" id="outlineBackdrop" hidden></div>
    <aside class="daftar-slide" id="outlinePanel" aria-hidden="true" aria-label="Daftar slide">
      <div class="daftar-kepala">
        <strong>Daftar Slide</strong>
        <button class="tombol tombol-teks" type="button" id="outlineClose">Tutup</button>
      </div>
      <ol class="daftar-isi" id="outlineList"></ol>
    </aside>

    <script>
      window.DATA_SLIDE = <?= json_encode(array_map(function ($slide, $i) {
          return [
              'nomor' => $i + 1,
              'judul' => (string) ($slide['judul'] ?? ('Slide ' . ($i + 1))),
              'catatan' => (string) ($slide['catatan'] ?? ''),
          ];
      }, $slides, array_keys($slides)), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="assets/presentasi.js"></script>
  <?php endif; ?>
</body>
</html>
