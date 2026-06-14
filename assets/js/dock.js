/* ============================================================================
   dock.js — Dock con effetto lente, auto-hide e rimbalzo
   La magnificazione segue il puntatore (variabile CSS --s su ogni icona).
   Il dock si nasconde da solo e riappare avvicinandosi al bordo inferiore.
   Espone dockWake() per le altre parti dell'interfaccia.
   ========================================================================== */
(function () {
  const dock = document.getElementById('dock');
  if (!dock) return;
  const dockIcons = Array.from(dock.querySelectorAll('.dapp .ai'));
  const DOCK_GROW = 0.8;     /* ingrandimento massimo */
  const DOCK_RANGE = 160;    /* raggio d'influenza del puntatore (px) */
  const LERP = 0.25;         /* morbidezza dell'interpolazione */
  let targetS = dockIcons.map(() => 1);
  let currentS = dockIcons.map(() => 1);
  let rafOn = false;

  function dockFrame() {
    let still = true;
    dockIcons.forEach((ic, i) => {
      currentS[i] += (targetS[i] - currentS[i]) * LERP;
      if (Math.abs(targetS[i] - currentS[i]) > 0.002) still = false;
      ic.style.setProperty('--s', currentS[i].toFixed(3));
    });
    if (still && targetS.every(s => s === 1) && currentS.every(s => Math.abs(s - 1) < 0.003)) { rafOn = false; return; }
    requestAnimationFrame(dockFrame);
  }
  function wakeMagnify() { if (!rafOn) { rafOn = true; requestAnimationFrame(dockFrame); } }

  dock.addEventListener('pointermove', e => {
    dockIcons.forEach((ic, i) => {
      const r = ic.getBoundingClientRect();
      const d = Math.abs(e.clientX - (r.left + r.width / 2));
      targetS[i] = d < DOCK_RANGE ? 1 + DOCK_GROW * (0.5 + 0.5 * Math.cos(Math.PI * d / DOCK_RANGE)) : 1;
    });
    wakeMagnify();
  });
  dock.addEventListener('pointerleave', () => { targetS = targetS.map(() => 1); wakeMagnify(); });

  /* --- Auto-hide (le regole .dock.autohide stanno nel design system) --- */
  let dockHot = false;
  let dockGrace = 0;
  function dockShow() { dock.classList.remove('autohide'); }
  function dockHide() { if (!dockHot && performance.now() >= dockGrace) dock.classList.add('autohide'); }
  function dockWake(ms) { dockShow(); dockGrace = performance.now() + (ms || 1500); }
  document.addEventListener('pointermove', e => {
    if (e.clientY >= window.innerHeight - 80) dockShow();
    else if (e.clientY < window.innerHeight - 110) dockHide();
  });
  dock.addEventListener('pointerenter', () => { dockHot = true; dockShow(); });
  dock.addEventListener('pointerleave', () => { dockHot = false; dockHide(); });
  setTimeout(() => dock.classList.add('autohide'), 2800);

  /* --- Click sulle icone: rimbalzo + apertura finestra / svuota (cestino) --- */
  dock.querySelectorAll('.dapp').forEach(d => {
    const btn = d.querySelector('.ai');
    btn.addEventListener('click', () => {
      btn.classList.remove('bounce');
      void btn.offsetWidth;
      btn.classList.add('bounce');
      if (d.dataset.w && typeof openWin === 'function') openWin(d.dataset.w);
      if (d.dataset.act === 'trash' && typeof closeAll === 'function') closeAll();
    });
  });

  window.dockWake = dockWake;
})();
