/* ============================================================
   kasirqu/crud/assets/presentasi.js
   Navigasi slide P16: panah, keyboard, swipe, daftar slide,
   panel catatan pelatih, dan mode fullscreen.
   ============================================================ */

(function () {
  "use strict";

  var slides = Array.prototype.slice.call(document.querySelectorAll(".slide"));
  var stage = document.getElementById("stage");

  if (slides.length === 0 || !stage) {
    return;
  }

  var data = window.DATA_SLIDE || [];
  var counter = document.getElementById("slideCounter");
  var progressBar = document.getElementById("progressBar");
  var fullscreenButton = document.getElementById("fullscreenButton");

  var outlinePanel = document.getElementById("outlinePanel");
  var outlineBackdrop = document.getElementById("outlineBackdrop");
  var outlineList = document.getElementById("outlineList");
  var outlineButton = document.getElementById("outlineButton");
  var outlineClose = document.getElementById("outlineClose");

  var notesButton = document.getElementById("notesButton");
  var notesPanel = document.getElementById("notesPanel");
  var notesBody = document.getElementById("notesBody");
  var notesTitle = document.getElementById("notesTitle");
  var notesClose = document.getElementById("notesClose");

  var currentSlide = 0;
  var touchStartX = null;

  function slideDariHash() {
    var cocok = window.location.hash.match(/^#slide-(\d+)$/);

    if (!cocok) {
      return 0;
    }

    return Math.max(0, Math.min(slides.length - 1, parseInt(cocok[1], 10) - 1));
  }

  function setHash(index) {
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "#slide-" + (index + 1));
    }
  }

  function slideData(index) {
    return data[index] || { nomor: index + 1, judul: "Slide " + (index + 1), catatan: "" };
  }

  function perbaruiCatatan(index) {
    var info = slideData(index);

    if (notesTitle) {
      notesTitle.textContent = "Catatan pelatih - slide " + (index + 1) + ": " + info.judul;
    }

    if (!notesBody) {
      return;
    }

    notesBody.textContent = "";

    if (info.catatan) {
      notesBody.textContent = info.catatan;
    } else {
      var kosong = document.createElement("span");
      kosong.className = "kosong-catatan";
      kosong.textContent = "Slide ini belum punya catatan pelatih.";
      notesBody.appendChild(kosong);
    }

    notesBody.scrollTop = 0;
  }

  function perbaruiOutline(index) {
    if (!outlineList) {
      return;
    }

    Array.prototype.forEach.call(outlineList.querySelectorAll("button"), function (tombol, i) {
      tombol.classList.toggle("is-active", i === index);
      tombol.setAttribute("aria-current", i === index ? "true" : "false");
    });
  }

  function tampilkanSlide(index, opsi) {
    var pengaturan = opsi || {};
    var tujuan = Math.max(0, Math.min(slides.length - 1, index));

    slides.forEach(function (slide, i) {
      var aktif = i === tujuan;

      slide.classList.toggle("aktif", aktif);
      slide.setAttribute("aria-hidden", aktif ? "false" : "true");

      if (aktif) {
        var gambar = slide.querySelector("img");
        if (gambar && gambar.loading === "lazy") {
          gambar.loading = "eager";
        }
      }
    });

    currentSlide = tujuan;

    if (counter) {
      counter.textContent = (tujuan + 1) + " / " + slides.length;
    }

    if (progressBar) {
      progressBar.style.width = (((tujuan + 1) / slides.length) * 100) + "%";
    }

    Array.prototype.forEach.call(document.querySelectorAll('[data-action="prev"]'), function (tombol) {
      tombol.disabled = tujuan === 0;
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-action="next"]'), function (tombol) {
      tombol.disabled = tujuan === slides.length - 1;
    });

    perbaruiOutline(tujuan);
    perbaruiCatatan(tujuan);

    if (pengaturan.updateHash !== false) {
      setHash(tujuan);
    }
  }

  function slideBerikut() {
    tampilkanSlide(currentSlide + 1);
  }

  function slideSebelum() {
    tampilkanSlide(currentSlide - 1);
  }

  function buatOutline() {
    if (!outlineList) {
      return;
    }

    slides.forEach(function (slide, index) {
      var info = slideData(index);
      var item = document.createElement("li");
      var tombol = document.createElement("button");

      tombol.type = "button";
      tombol.textContent = info.judul;
      tombol.addEventListener("click", function () {
        tampilkanSlide(index);
        tutupOutline();
      });

      item.appendChild(tombol);
      outlineList.appendChild(item);
    });
  }

  function bukaOutline() {
    if (!outlinePanel || !outlineBackdrop) {
      return;
    }

    outlinePanel.classList.add("is-open");
    outlinePanel.setAttribute("aria-hidden", "false");
    outlineBackdrop.hidden = false;

    if (outlineButton) {
      outlineButton.setAttribute("aria-expanded", "true");
    }

    perbaruiOutline(currentSlide);

    var aktif = outlineList ? outlineList.querySelector(".is-active") : null;
    if (aktif) {
      aktif.scrollIntoView({ block: "center" });
    }
  }

  function tutupOutline() {
    if (!outlinePanel || !outlineBackdrop) {
      return;
    }

    outlinePanel.classList.remove("is-open");
    outlinePanel.setAttribute("aria-hidden", "true");
    outlineBackdrop.hidden = true;

    if (outlineButton) {
      outlineButton.setAttribute("aria-expanded", "false");
    }
  }

  function alihkanOutline() {
    if (outlinePanel && outlinePanel.classList.contains("is-open")) {
      tutupOutline();
    } else {
      bukaOutline();
    }
  }

  function bukaCatatan() {
    document.body.classList.add("catatan-terbuka");

    if (notesPanel) {
      notesPanel.setAttribute("aria-hidden", "false");
    }

    if (notesButton) {
      notesButton.setAttribute("aria-pressed", "true");
    }
  }

  function tutupCatatan() {
    document.body.classList.remove("catatan-terbuka");

    if (notesPanel) {
      notesPanel.setAttribute("aria-hidden", "true");
    }

    if (notesButton) {
      notesButton.setAttribute("aria-pressed", "false");
    }
  }

  function alihkanCatatan() {
    if (document.body.classList.contains("catatan-terbuka")) {
      tutupCatatan();
    } else {
      bukaCatatan();
    }
  }

  function perbaruiTombolFullscreen() {
    if (!fullscreenButton) {
      return;
    }

    fullscreenButton.textContent = document.fullscreenElement ? "Keluar Fullscreen" : "Fullscreen";
  }

  function alihkanFullscreen() {
    if (!document.documentElement.requestFullscreen) {
      return;
    }

    if (document.fullscreenElement) {
      document.exitFullscreen();
      return;
    }

    document.documentElement.requestFullscreen().catch(function () {
      // Browser bisa menolak fullscreen; presentasi tetap jalan.
    });
  }

  function sedangMengetik(target) {
    var tag = (target.tagName || "").toLowerCase();

    return tag === "input" || tag === "textarea" || tag === "select" || target.isContentEditable;
  }

  document.addEventListener("click", function (event) {
    var tombol = event.target.closest("[data-action]");

    if (!tombol) {
      return;
    }

    var aksi = tombol.getAttribute("data-action");

    if (aksi === "next") {
      slideBerikut();
    }

    if (aksi === "prev") {
      slideSebelum();
    }
  });

  if (outlineButton) {
    outlineButton.addEventListener("click", alihkanOutline);
  }

  if (outlineClose) {
    outlineClose.addEventListener("click", tutupOutline);
  }

  if (outlineBackdrop) {
    outlineBackdrop.addEventListener("click", tutupOutline);
  }

  if (notesButton) {
    notesButton.addEventListener("click", alihkanCatatan);
  }

  if (notesClose) {
    notesClose.addEventListener("click", tutupCatatan);
  }

  if (fullscreenButton) {
    fullscreenButton.addEventListener("click", alihkanFullscreen);
  }

  document.addEventListener("fullscreenchange", perbaruiTombolFullscreen);

  document.addEventListener("keydown", function (event) {
    if (sedangMengetik(event.target)) {
      return;
    }

    var tombol = event.key;

    if (tombol === "ArrowRight" || tombol === "ArrowDown" || tombol === " " || tombol === "PageDown") {
      event.preventDefault();
      slideBerikut();
      return;
    }

    if (tombol === "ArrowLeft" || tombol === "ArrowUp" || tombol === "PageUp") {
      event.preventDefault();
      slideSebelum();
      return;
    }

    if (tombol === "Home") {
      event.preventDefault();
      tampilkanSlide(0);
      return;
    }

    if (tombol === "End") {
      event.preventDefault();
      tampilkanSlide(slides.length - 1);
      return;
    }

    if (tombol === "f" || tombol === "F") {
      event.preventDefault();
      alihkanFullscreen();
      return;
    }

    if (tombol === "o" || tombol === "O") {
      event.preventDefault();
      alihkanOutline();
      return;
    }

    if (tombol === "n" || tombol === "N") {
      event.preventDefault();
      alihkanCatatan();
      return;
    }

    if (tombol === "Escape") {
      if (outlinePanel && outlinePanel.classList.contains("is-open")) {
        tutupOutline();
        return;
      }

      if (document.body.classList.contains("catatan-terbuka")) {
        tutupCatatan();
      }
    }
  });

  window.addEventListener("hashchange", function () {
    tampilkanSlide(slideDariHash(), { updateHash: false });
  });

  stage.addEventListener("touchstart", function (event) {
    if (event.touches.length === 1) {
      touchStartX = event.touches[0].clientX;
    }
  }, { passive: true });

  stage.addEventListener("touchend", function (event) {
    if (touchStartX === null) {
      return;
    }

    var jarak = event.changedTouches[0].clientX - touchStartX;

    if (Math.abs(jarak) > 50) {
      if (jarak < 0) {
        slideBerikut();
      } else {
        slideSebelum();
      }
    }

    touchStartX = null;
  }, { passive: true });

  buatOutline();
  perbaruiTombolFullscreen();
  tampilkanSlide(slideDariHash(), { updateHash: false });
})();
