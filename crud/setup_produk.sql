-- ============================================================
-- kasirqu/crud/setup_produk.sql
-- Jalankan file ini HANYA jika database db_penjualan belum ada
-- atau tabel produk belum ada.
--
-- Cara pakai:
--   1. Buka phpMyAdmin (http://localhost/phpmyadmin).
--   2. Pilih tab SQL.
--   3. Tempel isi file ini, lalu klik Go.
--
-- Catatan: file ini TIDAK menghapus tabel yang sudah ada.
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_penjualan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE db_penjualan;

CREATE TABLE IF NOT EXISTS produk (
  kode_produk VARCHAR(10) PRIMARY KEY,
  nama_produk VARCHAR(50) NOT NULL,
  harga_beli  DECIMAL(10, 2) NOT NULL,
  harga_jual  DECIMAL(10, 2) NOT NULL,
  stok_awal   INT DEFAULT 0
);

INSERT IGNORE INTO produk (kode_produk, nama_produk, harga_beli, harga_jual, stok_awal) VALUES
('BRG101', 'Indomie Goreng Rasa Ayam', 2500.00, 4200.00, 100),
('BRG102', 'Teh Botol Sosro 350ml',    4000.00, 5000.00, 40),
('BRG103', 'Aqua Botol 600ml',         2500.00, 3500.00, 60),
('BRG104', 'Beras 5 kg',              55000.00, 62000.00, 20),
('BRG105', 'Minyak Goreng 1 L',       13000.00, 15000.00, 30);
