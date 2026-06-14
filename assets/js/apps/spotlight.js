/* ============================================================================
   apps/spotlight.js — Overlay "Spotlight" con testo digitato a macchina
   Estratto dallo <script> inline di hub.php (comportamento invariato). Si apre
   dall'icona [data-spot] o con ⌘Spazio, scrive una frase a effetto macchina da
   scrivere e si chiude con Esc o cliccando fuori dal riquadro.
   ========================================================================== */
(function () {
  const spot = document.getElementById('spot');
  if (!spot) return;
  const box = spot.querySelector('.spot-box');
  const type = spot.querySelector('.spot-type');
  const TEXT = 'Le parole non sono mai neutre';
  let timer = null;

  function open() {
    if (spot.classList.contains('on')) return;
    spot.classList.add('on');
    spot.setAttribute('aria-hidden', 'false');
    if (typeof sndOpen === 'function') { try { sndOpen(); } catch (e) {} }
    type.textContent = '';
    let i = 0;
    clearInterval(timer);
    timer = setInterval(() => {
      type.textContent = TEXT.slice(0, ++i);
      if (i >= TEXT.length) clearInterval(timer);
    }, 48);
  }
  function close() {
    if (!spot.classList.contains('on')) return;
    spot.classList.remove('on');
    spot.setAttribute('aria-hidden', 'true');
    clearInterval(timer);
    if (typeof sndClose === 'function') { try { sndClose(); } catch (e) {} }
  }

  document.addEventListener('click', e => {
    const t = e.target.closest('[data-spot]');
    if (t) { e.preventDefault(); open(); return; }
    if (spot.classList.contains('on') && !box.contains(e.target)) close();
  });
  document.addEventListener('keydown', e => {
    if ((e.metaKey || e.ctrlKey) && e.code === 'Space') { e.preventDefault(); spot.classList.contains('on') ? close() : open(); }
    else if (e.key === 'Escape') close();
  });
})();
