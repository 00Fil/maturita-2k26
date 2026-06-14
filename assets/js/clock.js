/* ============================================================================
   clock.js — Orologio della menu bar + timer della presentazione
   Quando il timer è attivo, l'orologio mostra il conto alla rovescia di 10
   minuti al posto della data. Espone avviaTimerPresentazione() per il
   Centro di Controllo.
   ========================================================================== */
(function () {
  const clockEl = document.getElementById('clock');
  if (!clockEl) return;

  const PRES_DURATA_MS = 10 * 60 * 1000;
  let presScadenza = null; /* timestamp (ms) di fine; null = mostra data e ora */

  function tick() {
    if (presScadenza !== null) {
      let restoSec = Math.round((presScadenza - Date.now()) / 1000);
      if (restoSec < 0) restoSec = 0;
      const min = Math.floor(restoSec / 60);
      const sec = restoSec % 60;
      clockEl.textContent = min + ':' + String(sec).padStart(2, '0');
      clockEl.classList.toggle('timer-finito', restoSec === 0);
      clockEl.classList.toggle('timer-quasi', restoSec > 0 && restoSec <= 60);
      return;
    }
    const d = new Date();
    const giorno = d.toLocaleDateString('it-IT', { weekday: 'short', day: 'numeric', month: 'short' });
    const ora = d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
    clockEl.textContent = giorno.charAt(0).toUpperCase() + giorno.slice(1) + '  ' + ora;
  }

  /* Avvia (o riavvia) il countdown di 10 minuti. */
  window.avviaTimerPresentazione = function () {
    presScadenza = Date.now() + PRES_DURATA_MS;
    clockEl.classList.add('timer-on');
    clockEl.classList.remove('timer-finito', 'timer-quasi');
    tick();
  };

  tick();
  setInterval(tick, 1000);
})();
