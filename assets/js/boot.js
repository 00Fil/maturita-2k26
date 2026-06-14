/* ============================================================================
   boot.js — Schermata di avvio (logo + barra di caricamento)
   La barra avanza con una curva morbida; se il logo non è ancora pronto resta
   sotto il 90%. Alla fine sfuma, riproduce il suono d'avvio e si rimuove.
   ========================================================================== */
(function () {
  const boot = document.getElementById('boot');
  if (!boot) return;

  const fill = boot.querySelector('.bbar span');
  const logo = boot.querySelector('img');
  const t0 = performance.now();
  const DUR = 2300;       /* durata nominale dell'avanzamento */
  const MAXWAIT = 6000;   /* attesa massima del logo prima di proseguire */
  let ready = !logo;
  let shown = 0;

  /* Se il logo non carica, lo sostituisco con l'icona vettoriale di sistema. */
  function fallback() {
    const s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    s.setAttribute('viewBox', '0 0 56 56');
    s.setAttribute('class', 'bfall');
    s.innerHTML = '<use href="#i-cap"/>';
    logo.replaceWith(s);
  }
  if (logo) {
    if (logo.complete) {
      if (logo.naturalWidth === 0) fallback();
      ready = true;
    } else {
      logo.addEventListener('load', () => { ready = true; });
      logo.addEventListener('error', () => { fallback(); ready = true; });
    }
  }

  function frame(now) {
    const el = now - t0;
    const t = Math.min(1, el / DUR);
    const eased = t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    let target = eased * 100;
    if (!ready && el < MAXWAIT) target = Math.min(target, 88);
    shown += (target - shown) * 0.14;
    if (target >= 100 && shown > 99.4) shown = 100;
    fill.style.width = shown.toFixed(2) + '%';
    if (shown < 100) { requestAnimationFrame(frame); return; }
    boot.classList.add('done');
    document.body.classList.remove('booting');
    if (typeof sndStart === 'function') sndStart();
    setTimeout(() => boot.remove(), 750);
  }
  requestAnimationFrame(frame);
})();
