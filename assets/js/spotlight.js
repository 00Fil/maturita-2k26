/* ============================================================================
   spotlight.js — Overlay di ricerca in stile Spotlight (hub.php)
   ----------------------------------------------------------------------------
   Apre un campo Spotlight che "digita" la frase di chiusura della
   presentazione. Si apre dai trigger [data-spot], si chiude col clic fuori
   o con Escape. Usa i suoni sndOpen/sndClose definiti in desktop.js (se ci sono).
   Lo stile vive in assets/css/macos.css (.spot / .spot-box / ...).
   ============================================================================ */
(function () {
  var spot = document.getElementById('spot');
  if (!spot) return;
  var box = spot.querySelector('.spot-box');
  var type = spot.querySelector('.spot-type');
  var TEXT = 'Le parole non sono mai neutre';
  var timer = null;

  function openSpot() {
    if (spot.classList.contains('on')) return;
    spot.classList.add('on');
    spot.setAttribute('aria-hidden', 'false');
    if (typeof sndOpen === 'function') { try { sndOpen(); } catch (e) {} }
    type.textContent = '';
    var i = 0;
    clearInterval(timer);
    timer = setInterval(function () {
      type.textContent = TEXT.slice(0, ++i);
      if (i >= TEXT.length) clearInterval(timer);
    }, 48);
  }
  function closeSpot() {
    if (!spot.classList.contains('on')) return;
    spot.classList.remove('on');
    spot.setAttribute('aria-hidden', 'true');
    clearInterval(timer);
    if (typeof sndClose === 'function') { try { sndClose(); } catch (e) {} }
  }

  document.addEventListener('click', function (e) {
    var t = e.target.closest('[data-spot]');
    if (t) { e.preventDefault(); openSpot(); return; }
    if (spot.classList.contains('on') && !box.contains(e.target)) closeSpot();
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeSpot(); });
})();
