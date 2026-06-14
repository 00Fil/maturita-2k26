/*
 * sound.js — Motore audio dell'interfaccia in stile macOS.
 *
 * Tutti i suoni sono SINTETIZZATI in tempo reale con la Web Audio API: niente
 * file audio, niente sample protetti da copyright. La sintesi modella le
 * caratteristiche timbriche dei suoni di sistema di macOS (frequenze, inviluppi,
 * parzialità inarmoniche, filtri) per avvicinarsi il più possibile all'originale.
 * Una replica 1:1 perfetta richiederebbe i campioni proprietari di Apple; questa
 * è la migliore approssimazione ottenibile via sintesi.
 *
 * API pubblica:
 *   window.Snd.<nome>()  — riproduce un suono (es. Snd.tink(), Snd.funk()).
 *   Snd.play('tink')     — alias per nome.
 *   Snd.unlock()         — sblocca l'AudioContext dopo la prima interazione.
 *
 * Retro-compatibilità (NON rimuovere, usate da index.php e dal listener globale):
 *   sndAudio(), sndNote(), sndVol(), sndClick(), sndGo()
 */
(function () {
  'use strict';

  /* ----------------------------------------------------------------------- *
   *  Catena audio principale
   * ----------------------------------------------------------------------- */
  let sndCtx = null;   // AudioContext
  let sndBus = null;   // ingresso "asciutto" condiviso
  let dryGain = null;  // mandata diretta
  let wetGain = null;   // mandata al riverbero
  let master = null;   // gain finale
  let verb = null;      // ConvolverNode (riverbero generato)

  /* Crea (una sola volta) il grafo audio e lo restituisce. */
  function sndAudio() {
    if (!sndCtx) {
      sndCtx = new (window.AudioContext || window.webkitAudioContext)();

      // Lowpass morbido: smussa le alte come l'output di sistema di un Mac.
      const lp = sndCtx.createBiquadFilter();
      lp.type = 'lowpass';
      lp.frequency.value = 12000;
      lp.Q.value = 0.5;

      master = sndCtx.createGain();
      master.gain.value = 0.9;

      // Ingresso condiviso dei suoni.
      sndBus = sndCtx.createGain();

      // Mandate parallele: diretto + riverbero.
      dryGain = sndCtx.createGain();
      dryGain.gain.value = 1.0;
      wetGain = sndCtx.createGain();
      wetGain.gain.value = 0.0; // i singoli suoni alzano il wet quando serve

      verb = sndCtx.createConvolver();
      verb.buffer = makeImpulse(1.8, 2.6);

      sndBus.connect(dryGain);
      sndBus.connect(wetGain);
      wetGain.connect(verb);
      dryGain.connect(lp);
      verb.connect(lp);
      lp.connect(master);
      master.connect(sndCtx.destination);
    }
    if (sndCtx.state === 'suspended') sndCtx.resume();
    return sndCtx;
  }

  /* Riverbero a impulso sintetico (rumore con decadimento esponenziale). */
  function makeImpulse(seconds, decay) {
    const ctx = sndCtx;
    const rate = ctx.sampleRate;
    const len = Math.max(1, Math.floor(seconds * rate));
    const buf = ctx.createBuffer(2, len, rate);
    for (let ch = 0; ch < 2; ch++) {
      const d = buf.getChannelData(ch);
      for (let i = 0; i < len; i++) {
        const t = i / len;
        d[i] = (Math.random() * 2 - 1) * Math.pow(1 - t, decay);
      }
    }
    return buf;
  }

  /* Buffer di rumore bianco riutilizzabile per click/percussioni. */
  let _noise = null;
  function noiseBuffer() {
    if (!_noise) {
      const ctx = sndAudio();
      const len = Math.floor(ctx.sampleRate * 1.0);
      _noise = ctx.createBuffer(1, len, ctx.sampleRate);
      const d = _noise.getChannelData(0);
      for (let i = 0; i < len; i++) d[i] = Math.random() * 2 - 1;
    }
    return _noise;
  }

  function now() { return sndAudio().currentTime; }

  /* Volume globale, allineato al centro di controllo del desktop (0..1). */
  function sndVol() {
    const raw = localStorage.getItem('cc-vol');
    const v = parseFloat(raw == null ? '25' : raw);
    return isNaN(v) ? 0.25 : Math.max(0, Math.min(1, v / 100));
  }

  /* ----------------------------------------------------------------------- *
   *  Primitive di sintesi
   * ----------------------------------------------------------------------- */

  /*
   * Una "voce" oscillatore con inviluppo esponenziale percussivo.
   * opt: { freq, type, at, dur, peak, attack, end, detune, dest, glideTo, glideAt }
   */
  function voice(opt) {
    const ctx = sndAudio();
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    const at = opt.at != null ? opt.at : ctx.currentTime + 0.005;
    const dur = opt.dur != null ? opt.dur : 0.2;
    const peak = Math.max(opt.peak != null ? opt.peak : 0.1, 0.0002);
    const attack = opt.attack != null ? opt.attack : 0.006;

    o.type = opt.type || 'sine';
    o.frequency.setValueAtTime(opt.freq, at);
    if (opt.detune) o.detune.value = opt.detune;
    if (opt.glideTo) {
      o.frequency.exponentialRampToValueAtTime(
        Math.max(opt.glideTo, 1),
        opt.glideAt != null ? opt.glideAt : at + dur
      );
    }

    g.gain.setValueAtTime(0.0001, at);
    g.gain.exponentialRampToValueAtTime(peak, at + attack);
    g.gain.exponentialRampToValueAtTime(0.0001, at + dur);

    o.connect(g).connect(opt.dest || sndBus);
    o.start(at);
    o.stop(at + dur + 0.05);
    return { o: o, g: g };
  }

  /* Compatibilità storica: nota sinusoidale percussiva sul bus principale. */
  function sndNote(freq, at, dur, peak) {
    voice({ freq: freq, at: at, dur: dur, peak: peak, type: 'sine' });
  }

  /* Burst di rumore filtrato (per click meccanici, otturatore, cestino). */
  function noise(opt) {
    const ctx = sndAudio();
    const src = ctx.createBufferSource();
    src.buffer = noiseBuffer();
    src.loop = true;
    const bp = ctx.createBiquadFilter();
    bp.type = opt.type || 'bandpass';
    bp.frequency.value = opt.freq || 2000;
    bp.Q.value = opt.Q != null ? opt.Q : 1;
    const g = ctx.createGain();
    const at = opt.at != null ? opt.at : ctx.currentTime + 0.005;
    const dur = opt.dur != null ? opt.dur : 0.05;
    const peak = Math.max(opt.peak != null ? opt.peak : 0.1, 0.0002);
    g.gain.setValueAtTime(0.0001, at);
    g.gain.exponentialRampToValueAtTime(peak, at + (opt.attack || 0.002));
    g.gain.exponentialRampToValueAtTime(0.0001, at + dur);
    src.connect(bp).connect(g).connect(opt.dest || sndBus);
    src.start(at);
    src.stop(at + dur + 0.05);
  }

  /* Alza temporaneamente la mandata al riverbero per un suono "in ambiente". */
  function withReverb(amount, ms) {
    sndAudio();
    const t = sndCtx.currentTime;
    wetGain.gain.cancelScheduledValues(t);
    wetGain.gain.setValueAtTime(amount, t);
    wetGain.gain.setTargetAtTime(0.0, t + (ms || 300) / 1000, 0.25);
  }

  function safe(fn) { try { if (sndVol() > 0) fn(); } catch (e) {} }

  /* ----------------------------------------------------------------------- *
   *  Suoni di sistema macOS (approssimazioni sintetizzate)
   * ----------------------------------------------------------------------- */

  /* Tink — click cristallino acuto. */
  function sTink() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 1760, at: t, dur: 0.13, peak: v * 0.10, attack: 0.003 });
    voice({ freq: 3520, at: t, dur: 0.09, peak: v * 0.035, attack: 0.003 });
    voice({ freq: 5280, at: t, dur: 0.05, peak: v * 0.012, attack: 0.002 });
  }); }

  /* Pop — bolla breve (rimozione/feedback). */
  function sPop() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 980, glideTo: 560, glideAt: t + 0.06, at: t, dur: 0.08, peak: v * 0.12, type: 'sine' });
    noise({ freq: 1600, Q: 1.2, at: t, dur: 0.02, peak: v * 0.03 });
  }); }

  /* Tock — tic secco (tastiera/segmented). */
  function sTock() { safe(function () {
    const v = sndVol(), t = now() + 0.003;
    noise({ freq: 2200, Q: 0.8, at: t, dur: 0.02, peak: v * 0.08 });
    voice({ freq: 720, at: t, dur: 0.03, peak: v * 0.04, type: 'triangle' });
  }); }

  /* Bottle / Boop — goccia d'acqua (sweep ascendente). */
  function sBottle() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 600, glideTo: 1300, glideAt: t + 0.05, at: t, dur: 0.10, peak: v * 0.10, type: 'sine' });
    voice({ freq: 1200, glideTo: 2600, glideAt: t + 0.05, at: t, dur: 0.07, peak: v * 0.03, type: 'sine' });
  }); }

  /* Funk — doppio honk discendente con "wah" (alert classico). */
  function sFunk() { safe(function () {
    const v = sndVol(), t = now() + 0.01;
    function honk(f, start, len, peak) {
      const o = sndCtx.createOscillator();
      const bp = sndCtx.createBiquadFilter();
      const g = sndCtx.createGain();
      o.type = 'sawtooth';
      o.frequency.value = f;
      bp.type = 'bandpass';
      bp.Q.value = 4;
      bp.frequency.setValueAtTime(f * 1.2, start);
      bp.frequency.linearRampToValueAtTime(f * 3.5, start + len * 0.5);
      bp.frequency.linearRampToValueAtTime(f * 1.2, start + len);
      g.gain.setValueAtTime(0.0001, start);
      g.gain.exponentialRampToValueAtTime(peak, start + 0.02);
      g.gain.setValueAtTime(peak, start + len * 0.7);
      g.gain.exponentialRampToValueAtTime(0.0001, start + len);
      o.connect(bp).connect(g).connect(sndBus);
      o.start(start); o.stop(start + len + 0.05);
    }
    honk(415.30, t, 0.16, v * 0.12);          // G#4
    honk(311.13, t + 0.17, 0.22, v * 0.12);   // D#4
  }); }

  /* Glass — alert moderno (campana inarmonica con riverbero). */
  function sGlass() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    withReverb(0.5, 600);
    const parts = [1245, 1865, 2490, 3110, 4150];
    const peaks = [0.09, 0.06, 0.045, 0.025, 0.012];
    parts.forEach(function (f, i) {
      voice({ freq: f, at: t + i * 0.012, dur: 0.5 - i * 0.06, peak: v * peaks[i], attack: 0.003 });
    });
  }); }

  /* Basso — tonfo grave discendente. */
  function sBasso() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 165, glideTo: 70, glideAt: t + 0.35, at: t, dur: 0.4, peak: v * 0.16, type: 'sine' });
    voice({ freq: 330, glideTo: 140, glideAt: t + 0.3, at: t, dur: 0.25, peak: v * 0.05, type: 'sawtooth' });
  }); }

  /* Blow — soffio (rumore filtrato in salita + sinusoide). */
  function sBlow() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    const ctx = sndCtx;
    const src = ctx.createBufferSource();
    src.buffer = noiseBuffer(); src.loop = true;
    const bp = ctx.createBiquadFilter();
    bp.type = 'bandpass'; bp.Q.value = 2;
    bp.frequency.setValueAtTime(500, t);
    bp.frequency.exponentialRampToValueAtTime(1400, t + 0.25);
    const g = ctx.createGain();
    g.gain.setValueAtTime(0.0001, t);
    g.gain.exponentialRampToValueAtTime(v * 0.12, t + 0.08);
    g.gain.exponentialRampToValueAtTime(0.0001, t + 0.3);
    src.connect(bp).connect(g).connect(sndBus);
    src.start(t); src.stop(t + 0.35);
    voice({ freq: 520, glideTo: 880, glideAt: t + 0.25, at: t, dur: 0.28, peak: v * 0.04, type: 'sine' });
  }); }

  /* Frog — gracidio (impulsi modulati gravi). */
  function sFrog() { safe(function () {
    const v = sndVol();
    const t0 = now() + 0.005;
    for (let i = 0; i < 5; i++) {
      const t = t0 + i * 0.055;
      voice({ freq: 180 + i * 12, at: t, dur: 0.04, peak: v * 0.10, type: 'square' });
    }
  }); }

  /* Hero — coro lungo e riverberante. */
  function sHero() { safe(function () {
    const v = sndVol(), t = now() + 0.01;
    withReverb(0.7, 1400);
    const chord = [392.0, 523.25, 659.25, 783.99]; // G C E G
    chord.forEach(function (f, i) {
      const o = sndCtx.createOscillator();
      const g = sndCtx.createGain();
      o.type = 'sine'; o.frequency.value = f; o.detune.value = (i - 1.5) * 4;
      g.gain.setValueAtTime(0.0001, t);
      g.gain.exponentialRampToValueAtTime(v * 0.06, t + 0.25);
      g.gain.setValueAtTime(v * 0.06, t + 0.9);
      g.gain.exponentialRampToValueAtTime(0.0001, t + 1.6);
      o.connect(g).connect(sndBus);
      o.start(t); o.stop(t + 1.7);
    });
  }); }

  /* Morse — due beep telegrafici. */
  function sMorse() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 1000, at: t, dur: 0.06, peak: v * 0.10, type: 'sine', attack: 0.004 });
    voice({ freq: 1000, at: t + 0.12, dur: 0.16, peak: v * 0.10, type: 'sine', attack: 0.004 });
  }); }

  /* Ping — sonar pulito. */
  function sPing() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    withReverb(0.25, 500);
    voice({ freq: 1000, at: t, dur: 0.45, peak: v * 0.12, attack: 0.004 });
    voice({ freq: 2000, at: t, dur: 0.18, peak: v * 0.02, attack: 0.004 });
  }); }

  /* Purr — fusa gravi modulate. */
  function sPurr() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    const ctx = sndCtx;
    const o = ctx.createOscillator();
    const lfo = ctx.createOscillator();
    const lfoG = ctx.createGain();
    const g = ctx.createGain();
    o.type = 'triangle'; o.frequency.value = 85;
    lfo.type = 'sine'; lfo.frequency.value = 24;
    lfoG.gain.value = v * 0.06;
    g.gain.setValueAtTime(0.0001, t);
    g.gain.exponentialRampToValueAtTime(v * 0.08, t + 0.05);
    g.gain.exponentialRampToValueAtTime(0.0001, t + 0.5);
    lfo.connect(lfoG).connect(g.gain);
    o.connect(g).connect(sndBus);
    o.start(t); lfo.start(t);
    o.stop(t + 0.55); lfo.stop(t + 0.55);
  }); }

  /* Submarine — sonar profondo con coda riverberante. */
  function sSubmarine() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    withReverb(0.8, 1600);
    voice({ freq: 120, at: t, dur: 1.2, peak: v * 0.16, attack: 0.01 });
    voice({ freq: 240, at: t, dur: 0.6, peak: v * 0.03 });
  }); }

  /* Sosumi — "doi-oing" con bend di pitch. */
  function sSosumi() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    const o = sndCtx.createOscillator();
    const g = sndCtx.createGain();
    o.type = 'sine';
    o.frequency.setValueAtTime(520, t);
    o.frequency.linearRampToValueAtTime(660, t + 0.06);
    o.frequency.linearRampToValueAtTime(500, t + 0.16);
    o.frequency.linearRampToValueAtTime(600, t + 0.26);
    g.gain.setValueAtTime(0.0001, t);
    g.gain.exponentialRampToValueAtTime(v * 0.12, t + 0.01);
    g.gain.exponentialRampToValueAtTime(0.0001, t + 0.35);
    o.connect(g).connect(sndBus);
    o.start(t); o.stop(t + 0.4);
  }); }

  /* ----------------------------------------------------------------------- *
   *  Suoni di interazione dell'app
   * ----------------------------------------------------------------------- */

  /* Click leggero dei controlli (retro-compatibile). */
  function sndClick() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    sndNote(1318.51, t, 0.045, v * 0.05);
    sndNote(2637.02, t, 0.03, v * 0.012);
  }); }

  /* Tick del cursore volume. */
  function sVolume() { safe(function () {
    const v = sndVol(), t = now() + 0.003;
    voice({ freq: 1500, at: t, dur: 0.03, peak: v * 0.06, type: 'sine', attack: 0.002 });
  }); }

  /* Toggle interruttore (on/off). */
  function sToggle(on) { safe(function () {
    const v = sndVol(), t = now() + 0.004;
    if (on === false) {
      voice({ freq: 760, glideTo: 520, glideAt: t + 0.05, at: t, dur: 0.07, peak: v * 0.09 });
    } else {
      voice({ freq: 620, glideTo: 920, glideAt: t + 0.05, at: t, dur: 0.07, peak: v * 0.09 });
    }
  }); }

  /* Otturatore fotocamera (screenshot). */
  function sScreenshot() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    noise({ freq: 2600, Q: 0.7, at: t, dur: 0.03, peak: v * 0.14 });
    noise({ freq: 1800, Q: 0.7, at: t + 0.05, dur: 0.05, peak: v * 0.12 });
  }); }

  /* Svuota cestino (accartocciamento di carta). */
  function sTrash() { safe(function () {
    const v = sndVol();
    const t0 = now() + 0.005;
    for (let i = 0; i < 14; i++) {
      const t = t0 + Math.random() * 0.4;
      noise({ freq: 1500 + Math.random() * 3000, Q: 1.5, at: t, dur: 0.03, peak: v * 0.05 });
    }
  }); }

  /* Invio messaggio (whoosh ascendente). */
  function sSend() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 500, glideTo: 1500, glideAt: t + 0.18, at: t, dur: 0.2, peak: v * 0.08, type: 'sine' });
  }); }

  /* Ricezione messaggio (due note ascendenti). */
  function sReceive() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 880, at: t, dur: 0.12, peak: v * 0.09 });
    voice({ freq: 1318.51, at: t + 0.08, dur: 0.16, peak: v * 0.08 });
  }); }

  /* Blocco schermo. */
  function sLock() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 600, glideTo: 300, glideAt: t + 0.1, at: t, dur: 0.12, peak: v * 0.10 });
    noise({ freq: 1200, Q: 1, at: t, dur: 0.03, peak: v * 0.04 });
  }); }

  /* Sblocco schermo. */
  function sUnlock() { safe(function () {
    const v = sndVol(), t = now() + 0.005;
    voice({ freq: 400, glideTo: 800, glideAt: t + 0.1, at: t, dur: 0.12, peak: v * 0.10 });
    voice({ freq: 1200, at: t + 0.06, dur: 0.08, peak: v * 0.03 });
  }); }

  /* Startup chime — accordo F# major lungo e riverberante. */
  function sStartup() { safe(function () {
    const v = sndVol(), t = now() + 0.02;
    withReverb(0.9, 2600);
    // F#2 C#3 F#3 A#3 C#4 F#4 (accordo di Fa# maggiore)
    const notes = [92.50, 138.59, 185.00, 233.08, 277.18, 369.99];
    notes.forEach(function (f, i) {
      ['sine', 'triangle'].forEach(function (tp, k) {
        const o = sndCtx.createOscillator();
        const g = sndCtx.createGain();
        o.type = tp; o.frequency.value = f; o.detune.value = (k ? 6 : -6);
        const peak = v * (0.10 - i * 0.012) * (k ? 0.4 : 1);
        g.gain.setValueAtTime(0.0001, t);
        g.gain.exponentialRampToValueAtTime(Math.max(peak, 0.0003), t + 0.12);
        g.gain.setValueAtTime(Math.max(peak, 0.0003), t + 1.0);
        g.gain.exponentialRampToValueAtTime(0.0001, t + 2.6);
        o.connect(g).connect(sndBus);
        o.start(t); o.stop(t + 2.7);
      });
    });
  }); }

  /* Accesso riuscito: accordo ascendente (retro-compatibile, ora con riverbero). */
  function sndGo() { safe(function () {
    const v = sndVol(), t = now() + 0.01;
    withReverb(0.35, 700);
    sndNote(392, t, 0.3, v * 0.08);
    sndNote(523.25, t + 0.07, 0.3, v * 0.07);
    sndNote(659.25, t + 0.14, 0.35, v * 0.06);
    sndNote(783.99, t + 0.21, 0.4, v * 0.05);
  }); }

  /* ----------------------------------------------------------------------- *
   *  API pubblica + retro-compatibilità
   * ----------------------------------------------------------------------- */
  const Snd = {
    ctx: sndAudio,
    vol: sndVol,
    unlock: function () { try { sndAudio(); } catch (e) {} },
    // suoni di sistema
    tink: sTink, pop: sPop, tock: sTock, bottle: sBottle, boop: sBottle,
    funk: sFunk, glass: sGlass, basso: sBasso, blow: sBlow, frog: sFrog,
    hero: sHero, morse: sMorse, ping: sPing, purr: sPurr,
    submarine: sSubmarine, sosumi: sSosumi,
    // interazione
    click: sndClick, volume: sVolume, toggle: sToggle, screenshot: sScreenshot,
    trash: sTrash, send: sSend, receive: sReceive, lock: sLock, unlock_ui: sUnlock,
    startup: sStartup, go: sndGo,
    play: function (name, arg) { if (typeof this[name] === 'function') this[name](arg); }
  };
  window.Snd = Snd;

  // Funzioni globali storiche (usate da index.php e da altri script).
  window.sndAudio = sndAudio;
  window.sndNote = sndNote;
  window.sndVol = sndVol;
  window.sndClick = sndClick;
  window.sndGo = sndGo;

  // Listener globale: click leggero sui controlli (comportamento preesistente).
  document.addEventListener('click', function (e) {
    if (e.target.closest('button, .dots i')) sndClick();
  }, true);

  // Sblocca l'AudioContext alla prima interazione utente (policy autoplay).
  ['pointerdown', 'keydown', 'touchstart'].forEach(function (ev) {
    window.addEventListener(ev, function unlockOnce() {
      try { sndAudio(); } catch (e) {}
      ['pointerdown', 'keydown', 'touchstart'].forEach(function (e2) {
        window.removeEventListener(e2, unlockOnce);
      });
    }, { once: false, capture: true });
  });
})();
