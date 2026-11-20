<?php
// ============================================================
// kasirqu/crud/login.php
// Form login materi P17. Akun demo ada di setup_pengguna.sql.
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['kasirqu'])) {
    header('Location: index.php');
    exit;
}

$flash = $_SESSION['kasirqu_flash'] ?? null;
unset($_SESSION['kasirqu_flash']);

$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - KasirQu</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <a class="brand" href="panduan.php">KasirQu <small>/ Session &amp; Laporan</small></a>
      <nav class="menu">
        <a href="panduan.php">Panduan</a>
        <a href="panduan_session.php">Session</a>
        <a href="panduan_laporan.php">Laporan</a>
      </nav>
    </div>
  </header>

  <main class="wrap" style="max-width: 520px;">
    <p class="tag">Pertemuan 17</p>
    <h1>Masuk ke KasirQu</h1>
    <p class="lead">
      Setelah login, session menyimpan siapa yang sedang bekerja.
      Halaman laporan hanya bisa dibuka oleh admin.
    </p>

    <?php if ($flash): ?>
      <?php
      $tipe = in_array($flash['type'] ?? '', ['info', 'success', 'warning', 'danger'], true)
          ? $flash['type']
          : 'info';
      $kelas = in_array($tipe, ['success', 'danger'], true) ? $tipe : 'ok';
      ?>
      <div class="alert <?= $kelas === 'danger' ? 'error' : 'ok' ?>">
        <?= htmlspecialchars($flash['text'] ?? '') ?>
      </div>
    <?php endif; ?>

    <?php if ($status === 'logout'): ?>
      <div class="alert ok">Kamu sudah keluar. Sampai jumpa.</div>
    <?php endif; ?>

    <div class="card">
      <form method="post" action="proses_login.php">
        <div class="field" style="margin-bottom: 14px;">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="admin" required autofocus>
        </div>

        <div class="field" style="margin-bottom: 18px;">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="admin123" required>
        </div>

        <button class="btn" type="submit">Login</button>
      </form>

      <hr style="border: none; border-top: 1px solid #E1E6EE; margin: 20px 0;">

      <p class="muted" style="margin-bottom: 6px;"><strong>Akun demo</strong></p>
      <ul class="muted" style="padding-left: 20px; margin-top: 0;">
        <li><code>admin</code> / <code>admin123</code> &mdash; boleh membuka laporan</li>
        <li><code>kasir1</code> / <code>kasir123</code> &mdash; ditolak di halaman laporan</li>
      </ul>
      <p class="muted" style="margin-bottom: 0;">
        Belum ada tabel <code>pengguna</code>? Impor
        <code>kasirqu/crud/setup_pengguna.sql</code>.
      </p>
    </div>

    <p class="muted">
      <a href="panduan_session.php">&larr; Baca panduan session</a>
    </p>
  </main>
</body>
</html>
