/* ============================================================================
   audio.js — Motore audio UNICO (Web Audio) in stile macOS
   ----------------------------------------------------------------------------
   Prima questo codice era duplicato: una copia in sound.js (login) e una dentro
   hub.js (desktop). Ora è un solo modulo condiviso da entrambe le pagine.
   Il volume è quello impostato dal Centro di Controllo e salvato in
   localStorage('cc-vol'), così login e desktop restano sempre allineati.
   Espone le funzioni snd* sull'oggetto window.
   ========================================================================== */
(function () {
  let actx = null, bus = null, tickAt = 0;

  /* Volume corrente (0..1) letto dal Centro di Controllo. */
  function vol() {
    const v = parseFloat(localStorage.getItem('cc-vol') ?? 25);
    return isNaN(v) ? 0.25 : v / 100;
  }

  /* Crea/sblocca il contesto audio con un filtro passa-basso morbido. */
  function ctx() {
    if (!actx) {
      actx = new (window.AudioContext || window.webkitAudioContext)();
      const lp = actx.createBiquadFilter();
      lp.type = 'lowpass'; lp.frequency.value = 2400; lp.Q.value = 0.5;
      bus = actx.createGain();
      bus.connect(lp); lp.connect(actx.destination);
    }
    if (actx.state === 'suspended') actx.resume();
    return actx;
  }

  /* Una singola nota sinusoidale con inviluppo esponenziale. */
  function note(freq, at, dur, peak) {
    const o = actx.createOscillator();
    const g = actx.createGain();
    o.type = 'sine';
    o.frequency.value = freq;
    g.gain.setValueAtTime(0.0001, at);
    g.gain.exponentialRampToValueAtTime(Math.max(peak, 0.0002), at + 0.008);
    g.gain.exponentialRampToValueAtTime(0.0001, at + dur);
    o.connect(g).connect(bus);
    o.start(at);
    o.stop(at + dur + 0.04);
  }

  /* Apertura finestra: accordo brillante ascendente. */
  window.sndOpen = function () {
    const v = vol(); if (!v) return;
    try { const t = ctx().currentTime + 0.01;
      note(523.25, t, 0.26, v * 0.11);
      note(783.99, t + 0.014, 0.22, v * 0.05);
      note(1046.5, t + 0.014, 0.17, v * 0.022);
    } catch (e) {}
  };
  /* Chiusura finestra: due note discendenti. */
  window.sndClose = function () {
    const v = vol(); if (!v) return;
    try { const t = ctx().currentTime + 0.01;
      note(415.3, t, 0.2, v * 0.1);
      note(523.25, t + 0.012, 0.15, v * 0.04);
    } catch (e) {}
  };
  /* Scatto sottile (trascinamento dei cursori). */
  window.sndTick = function () {
    const v = vol(); if (!v) return;
    const now = performance.now();
    if (now - tickAt < 85) return;
    tickAt = now;
    try { const t = ctx().currentTime + 0.005;
      note(987.77, t, 0.08, v * 0.13);
      note(1975.53, t, 0.05, v * 0.03);
    } catch (e) {}
  };
  /* Forza uno scatto ignorando l'antirimbalzo (evento 'change'). */
  window.sndTickForce = function () { tickAt = 0; window.sndTick(); };
  /* Click leggero generico dei comandi. */
  window.sndClick = function () {
    const v = vol(); if (!v) return;
    try { const t = ctx().currentTime + 0.005;
      note(1318.51, t, 0.045, v * 0.05);
      note(2637.02, t, 0.03, v * 0.012);
    } catch (e) {}
  };
  /* Suono d'avvio (fine boot): arpeggio caldo. Se l'audio è sospeso, attende
     il primo gesto dell'utente. */
  window.sndStart = function () {
    const v = vol(); if (!v) return;
    try {
      const a = ctx();
      if (a.state === 'suspended') {
        const once = () => { document.removeEventListener('pointerdown', once); window.sndStart(); };
        document.addEventListener('pointerdown', once);
        return;
      }
      const t = a.currentTime + 0.02;
      note(349.23, t, 0.7, v * 0.07);
      note(440, t + 0.05, 0.65, v * 0.06);
      note(523.25, t + 0.1, 0.6, v * 0.055);
      note(698.46, t + 0.16, 0.55, v * 0.04);
    } catch (e) {}
  };
  /* Spegnimento: accordo discendente di commiato. */
  window.sndExit = function () {
    const v = vol(); if (!v) return;
    try { const t = ctx().currentTime + 0.01;
      note(523.25, t, 0.22, v * 0.09);
      note(392, t + 0.09, 0.24, v * 0.07);
      note(261.63, t + 0.18, 0.34, v * 0.06);
    } catch (e) {}
  };
  /* Accesso riuscito (login): accordo ascendente prima del desktop. */
  window.sndGo = function () {
    const v = vol(); if (!v) return;
    try { const t = ctx().currentTime + 0.01;
      note(392, t, 0.3, v * 0.08);
      note(523.25, t + 0.07, 0.3, v * 0.07);
      note(659.25, t + 0.14, 0.35, v * 0.06);
      note(783.99, t + 0.21, 0.4, v * 0.05);
    } catch (e) {}
  };

  /* Click sonoro globale su pulsanti e link (esclusi i semafori). */
  document.addEventListener('click', e => {
    const el = e.target.closest('button, a');
    if (!el || el.closest('.lights')) return;
    window.sndClick();
  }, true);
})();
