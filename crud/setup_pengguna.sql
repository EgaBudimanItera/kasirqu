-- ============================================================
-- kasirqu/crud/setup_pengguna.sql
-- Tabel pengguna untuk materi Session & Laporan (Pertemuan 17).
--
-- Impor lewat phpMyAdmin ke database db_penjualan, atau:
--   mysql -u root db_penjualan < kasirqu/crud/setup_pengguna.sql
--
-- Akun demo:
--   admin  / admin123   (role admin)
--   kasir1 / kasir123   (role kasir)
--
-- Hash di bawah dibuat dengan password_hash('...', PASSWORD_DEFAULT).
-- Kalau ingin mengganti password, buat hash baru lewat PHP:
--   php -r "echo password_hash('passwordbaru', PASSWORD_DEFAULT);"
-- ============================================================

USE db_penjualan;

CREATE TABLE IF NOT EXISTS pengguna (
  id_pengguna   INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(30)  NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL,
  nama_lengkap  VARCHAR(50)  NOT NULL,
  role          ENUM('admin','kasir') DEFAULT 'kasir',
  id_kasir      INT NULL,
  status        ENUM('aktif','nonaktif') DEFAULT 'aktif',
  dibuat_pada   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO pengguna (username, password, nama_lengkap, role, id_kasir, status)
VALUES (
  'admin',
  '$2y$10$RyFoVYnot0wdhwwO1s5xeeprLka1KLvg.cR0seFstrmG338MtKdtG',
  'Pemilik Toko',
  'admin',
  NULL,
  'aktif'
)
ON DUPLICATE KEY UPDATE
  nama_lengkap = VALUES(nama_lengkap),
  role         = VALUES(role),
  status       = VALUES(status);

INSERT INTO pengguna (username, password, nama_lengkap, role, id_kasir, status)
VALUES (
  'kasir1',
  '$2y$10$xzZL9oLAghDYz3nBrZpyWOx/JAZdE9SsYC7yyFKjTXcQAfi00LK02',
  'Kasir Satu',
  'kasir',
  1,
  'aktif'
)
ON DUPLICATE KEY UPDATE
  nama_lengkap = VALUES(nama_lengkap),
  role         = VALUES(role),
  id_kasir     = VALUES(id_kasir),
  status       = VALUES(status);
