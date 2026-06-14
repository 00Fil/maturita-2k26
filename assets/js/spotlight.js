/* ============================================================================
   spotlight.js — Overlay "Spotlight" del desktop (#spot)
   ----------------------------------------------------------------------------
   Estratto dallo <script> inline che stava in hub.php. Apre l'overlay e digita
   la frase di chiusura. Dipende dalle funzioni globali sndOpen()/sndClose()
   definite in desktop.js: per questo desktop.js va caricato PRIMA di questo file.
   ========================================================================== */
(function () {
  var spot = document.getElementById("spot");
  if (!spot) return;

  var box = spot.querySelector(".spot-box");
  var type = spot.querySelector(".spot-type");
  var TEXT = "Le parole non sono mai neutre";
  var timer = null;

  /* Apre lo Spotlight e digita la frase un carattere alla volta. */
  function openSpot() {
    if (spot.classList.contains("on")) return;
    spot.classList.add("on");
    spot.setAttribute("aria-hidden", "false");
    if (typeof sndOpen === "function") { try { sndOpen(); } catch (e) {} }
    type.textContent = "";
    var i = 0;
    clearInterval(timer);
    timer = setInterval(function () {
      type.textContent = TEXT.slice(0, ++i);
      if (i >= TEXT.length) clearInterval(timer);
    }, 48);
  }

  /* Chiude lo Spotlight. */
  function closeSpot() {
    if (!spot.classList.contains("on")) return;
    spot.classList.remove("on");
    spot.setAttribute("aria-hidden", "true");
    clearInterval(timer);
    if (typeof sndClose === "function") { try { sndClose(); } catch (e) {} }
  }

  /* Apertura dai trigger [data-spot]; click fuori dal box → chiusura. */
  document.addEventListener("click", function (e) {
    var t = e.target.closest("[data-spot]");
    if (t) { e.preventDefault(); openSpot(); return; }
    if (spot.classList.contains("on") && !box.contains(e.target)) closeSpot();
  });
  document.addEventListener("keydown", function (e) { if (e.key === "Escape") closeSpot(); });
})();
