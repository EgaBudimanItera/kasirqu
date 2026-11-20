/* ============================================================
   kasirqu/crud/assets/transaksi.js
   Interaksi form transaksi: tambah/hapus baris, isi harga,
   hitung subtotal dan total nota.
   ============================================================ */

(function () {
  var rupiah = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0
  });

  function angka(nilai) {
    var hasil = parseFloat(nilai);
    return isNaN(hasil) ? 0 : hasil;
  }

  function pasangBaris(baris) {
    var pilih = baris.querySelector(".pilih-produk");
    var jumlah = baris.querySelector(".input-jumlah");
    var harga = baris.querySelector(".input-harga");
    var subtotal = baris.querySelector("[data-subtotal]");
    var info = baris.querySelector(".info-produk");
    var hapus = baris.querySelector(".btn-hapus-baris");

    function perbaruiSubtotal() {
      var nilai = angka(jumlah.value) * angka(harga.value);
      subtotal.textContent = rupiah.format(nilai);
      hitungTotal();
    }

    if (pilih) {
      pilih.addEventListener("change", function () {
        var opsi = pilih.options[pilih.selectedIndex];
        var nama = opsi.getAttribute("data-nama") || "";
        var hargaProduk = opsi.getAttribute("data-harga") || "";
        var stok = opsi.getAttribute("data-stok") || "0";

        if (pilih.value !== "") {
          harga.value = hargaProduk;
          info.textContent = nama + " | stok tersedia: " + stok;
        } else {
          harga.value = "";
          info.textContent = "Pilih produk untuk melihat stok.";
        }

        perbaruiSubtotal();
      });
    }

    if (jumlah) {
      jumlah.addEventListener("input", perbaruiSubtotal);
    }

    if (harga) {
      harga.addEventListener("input", perbaruiSubtotal);
    }

    if (hapus) {
      hapus.addEventListener("click", function () {
        var semua = document.querySelectorAll("#tabel-item .baris-item");

        if (semua.length <= 1) {
          if (pilih) { pilih.value = ""; }
          if (jumlah) { jumlah.value = "1"; }
          if (harga) { harga.value = ""; }
          if (info) { info.textContent = "Pilih produk untuk melihat stok."; }
          perbaruiSubtotal();
          return;
        }

        baris.parentNode.removeChild(baris);
        hitungTotal();
      });
    }

    perbaruiSubtotal();
  }

  function hitungTotal() {
    var total = 0;
    var baris = document.querySelectorAll("#tabel-item .baris-item");

    for (var i = 0; i < baris.length; i++) {
      var jumlah = baris[i].querySelector(".input-jumlah");
      var harga = baris[i].querySelector(".input-harga");

      if (jumlah && harga) {
        total += angka(jumlah.value) * angka(harga.value);
      }
    }

    var kotakTotal = document.getElementById("total-nota");

    if (kotakTotal) {
      kotakTotal.textContent = rupiah.format(total);
    }
  }

  function tambahBaris() {
    var template = document.getElementById("template-baris");
    var tabel = document.getElementById("tabel-item");

    if (!template || !tabel) {
      return;
    }

    var isi = template.content.cloneNode(true);
    var tbody = tabel.querySelector("tbody") || tabel;
    tbody.appendChild(isi);

    var semua = tabel.querySelectorAll(".baris-item");
    var barisBaru = semua[semua.length - 1];
    pasangBaris(barisBaru);

    var pilih = barisBaru.querySelector(".pilih-produk");
    if (pilih) {
      pilih.focus();
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    // var tabel = document.getElementById("tabel-item");
    var tombol = document.getElementById("btn-tambah-baris");
    console.log('ini error disini');
    if (tabel) {
      var baris = tabel.querySelectorAll(".baris-itema  ");

      for (var i = 0; i < baris.length; i++) {
        pasangBaris(baris[i]);
      }
    }
    
    if (tombol) {
      tombol.addEventListener("click", tambahBaris);
    }

    hitungTotal();
  });
})();
