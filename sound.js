/* Suoni di interfaccia della pagina di accesso (Web Audio, in stile macOS). */
let sndCtx = null, sndBus = null;

function sndAudio() {
  if (!sndCtx) {
    sndCtx = new (window.AudioContext || window.webkitAudioContext)();
    const lp = sndCtx.createBiquadFilter();
    lp.type = 'lowpass';
    lp.frequency.value = 2400;
    lp.Q.value = 0.5;
    sndBus = sndCtx.createGain();
    sndBus.connect(lp);
    lp.connect(sndCtx.destination);
  }
  if (sndCtx.state === 'suspended') sndCtx.resume();
  return sndCtx;
}

function sndNote(freq, at, dur, peak) {
  const o = sndCtx.createOscillator();
  const g = sndCtx.createGain();
  o.type = 'sine';
  o.frequency.value = freq;
  g.gain.setValueAtTime(0.0001, at);
  g.gain.exponentialRampToValueAtTime(Math.max(peak, 0.0002), at + 0.008);
  g.gain.exponentialRampToValueAtTime(0.0001, at + dur);
  o.connect(g).connect(sndBus);
  o.start(at);
  o.stop(at + dur + 0.04);
}

/* Stesso volume regolato dal centro di controllo del desktop. */
function sndVol() {
  const v = parseFloat(localStorage.getItem('cc-vol') ?? 25);
  return isNaN(v) ? 0.25 : v / 100;
}

/* Click leggero dei controlli. */
function sndClick() {
  const v = sndVol();
  if (!v) return;
  try {
    const t = sndAudio().currentTime + 0.005;
    sndNote(1318.51, t, 0.045, v * 0.05);
    sndNote(2637.02, t, 0.03, v * 0.012);
  } catch (e) {}
}

/* Accesso riuscito: accordo ascendente prima di aprire il desktop. */
function sndGo() {
  const v = sndVol();
  if (!v) return;
  try {
    const t = sndAudio().currentTime + 0.01;
    sndNote(392, t, 0.3, v * 0.08);
    sndNote(523.25, t + 0.07, 0.3, v * 0.07);
    sndNote(659.25, t + 0.14, 0.35, v * 0.06);
    sndNote(783.99, t + 0.21, 0.4, v * 0.05);
  } catch (e) {}
}

document.addEventListener('click', e => {
  if (e.target.closest('button, .dots i')) sndClick();
}, true);
