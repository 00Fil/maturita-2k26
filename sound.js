/* ============================================================
   sound.js — Suoni dell'interfaccia in stile macOS
   ------------------------------------------------------------
   Tutti i suoni sono SINTETIZZATI in tempo reale con la Web Audio
   API: nessun file audio binario, nessun sample protetto da
   copyright Apple. Sono ricostruzioni "a orecchio" dei suoni di
   sistema macOS (timbro, inviluppi, partiali, filtri) — la
   replica perfetta 1:1 richiederebbe i sample originali, qui si
   punta alla massima fedelta possibile per via sintetica.

   API pubblica:
     window.Snd.click() / tink() / pop() / tock() / bottle() /
     funk() / glass() / basso() / blow() / frog() / hero() /
     morse() / ping() / purr() / submarine() / boop() /
     startup() / screenshot() / trash() / volume() / toggle(on) /
     send() / receive() / lock() / unlock() / go() / play(name)

   Retro-compatibilita (usate da index.php e dal listener globale):
     sndAudio(), sndNote(), sndVol(), sndClick(), sndGo()
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Contesto e catena master ---------- */
  var ctx = null;      // AudioContext
  var bus = null;      // ingresso "dry" comune
  var master = null;   // gain finale
  var reverbIn = null; // mandata al riverbero
  var inited = false;

  function buildReverb(context) {
    /* Impulso sintetico (decadimento esponenziale) per dare "aria"
       ai suoni cristallini (glass, hero, startup, submarine). */
    var rate = context.sampleRate;
    var len = Math.floor(rate * 1.8);
    var buf = context.createBuffer(2, len, rate);
    for (var ch = 0; ch < 2; ch++) {
      var d = buf.getChannelData(ch);
      for (var i = 0; i < len; i++) {
        var t = i / len;
        d[i] = (Math.random() * 2 - 1) * Math.pow(1 - t, 2.6);
      }
    }
    var conv = context.createConvolver();
    conv.buffer = buf;
    return conv;
  }

  function audio() {
    if (!inited) {
      try {
        ctx = new (window.AudioContext || window.webkitAudioContext)();
      } catch (e) { return null; }
      // Lowpass dolce: smussa le frequenze piu dure, come l'output di sistema.
      var lp = ctx.createBiquadFilter();
      lp.type = 'lowpass';
      lp.frequency.value = 12000;
      lp.Q.value = 0.4;

      master = ctx.createGain();
      master.gain.value = 0.9;

      bus = ctx.createGain();
      bus.gain.value = 1;
      bus.connect(lp);
      lp.connect(master);

      // Mandata riverbero (parallela, dosata).
      var verb = buildReverb(ctx);
      var verbGain = ctx.createGain();
      verbGain.gain.value = 0.5;
      reverbIn = ctx.createGain();
      reverbIn.gain.value = 1;
      reverbIn.connect(verb);
      verb.connect(verbGain);
      verbGain.connect(master);

      master.connect(ctx.destination);
      inited = true;
    }
    if (ctx && ctx.state === 'suspended') { try { ctx.resume(); } catch (e) {} }
    return ctx;
  }

  /* ---------- Volume (allineato al control center) ---------- */
  function vol() {
    var raw = localStorage.getItem('cc-vol');
    var v = parseFloat(raw == null ? 25 : raw);
    if (isNaN(v)) v = 25;
    return Math.max(0, Math.min(1, v / 100));
  }

  /* ---------- Mattoni di sintesi ---------- */
  function now(off) { return ctx.currentTime + (off || 0); }

  // Oscillatore con inviluppo ADSR esponenziale.
  function tone(o) {
    var osc = ctx.createOscillator();
    var g = ctx.createGain();
    osc.type = o.type || 'sine';
    osc.frequency.setValueAtTime(o.f0 || o.freq, o.t);
    if (o.f1 && o.f1 !== (o.f0 || o.freq)) {
      // glissato di frequenza
      osc.frequency.exponentialRampToValueAtTime(Math.max(o.f1, 1), o.t + o.dur);
    }
    if (o.detune) osc.detune.value = o.detune;
    var peak = Math.max(o.peak, 0.0002);
    var atk = o.atk == null ? 0.006 : o.atk;
    g.gain.setValueAtTime(0.0001, o.t);
    g.gain.exponentialRampToValueAtTime(peak, o.t + atk);
    g.gain.exponentialRampToValueAtTime(0.0001, o.t + o.dur);
    osc.connect(g);
    g.connect(o.dest || bus);
    if (o.verb && reverbIn) g.connect(reverbIn);
    osc.start(o.t);
    osc.stop(o.t + o.dur + 0.05);
    return osc;
  }

  function noiseBuf(dur) {
    var n = Math.floor(ctx.sampleRate * dur);
    var b = ctx.createBuffer(1, n, ctx.sampleRate);
    var d = b.getChannelData(0);
    for (var i = 0; i < n; i++) d[i] = Math.random() * 2 - 1;
    return b;
  }

  // Burst di rumore filtrato (click/whoosh/crumple).
  function noise(o) {
    var src = ctx.createBufferSource();
    src.buffer = noiseBuf(o.dur + 0.05);
    var f = ctx.createBiquadFilter();
    f.type = o.filter || 'bandpass';
    f.frequency.setValueAtTime(o.f0 || 1500, o.t);
    if (o.f1) f.frequency.exponentialRampToValueAtTime(Math.max(o.f1, 1), o.t + o.dur);
    f.Q.value = o.q == null ? 1 : o.q;
    var g = ctx.createGain();
    g.gain.setValueAtTime(0.0001, o.t);
    g.gain.exponentialRampToValueAtTime(Math.max(o.peak, 0.0002), o.t + (o.atk || 0.004));
    g.gain.exponentialRampToValueAtTime(0.0001, o.t + o.dur);
    src.connect(f); f.connect(g); g.connect(o.dest || bus);
    if (o.verb && reverbIn) g.connect(reverbIn);
    src.start(o.t);
    src.stop(o.t + o.dur + 0.06);
    return src;
  }

  // Campana inarmonica additiva (per tink/glass/hero).
  function bell(t, base, partials, dur, peak, verb) {
    for (var i = 0; i < partials.length; i++) {
      var p = partials[i];
      tone({ type: 'sine', f0: base * p[0], t: t, dur: dur * (p[2] || 1),
             peak: peak * p[1], atk: 0.004, verb: verb });
    }
  }

  function guard(fn) {
    var v = vol();
    if (!v) return;
    if (!audio()) return;
    try { fn(v); } catch (e) {}
  }

  /* ============================================================
     SUITE SUONI macOS
     ============================================================ */

  // Click leggero dei controlli (default UI).
  function click() { guard(function (v) {
    var t = now(0.004);
    tone({ type: 'sine', f0: 1318.51, t: t, dur: 0.05, peak: v * 0.05 });
    tone({ type: 'sine', f0: 2637.02, t: t, dur: 0.03, peak: v * 0.012 });
  }); }

  // Tink — click vetroso acuto.
  function tink() { guard(function (v) {
    var t = now(0.004);
    bell(t, 1, [[1720, 1, 1], [3440, 0.35, 0.8], [5160, 0.12, 0.6]], 0.13, v * 0.09, true);
  }); }

  // Pop — bolla/cork breve con glissato discendente.
  function pop() { guard(function (v) {
    var t = now(0.004);
    tone({ type: 'sine', f0: 1180, f1: 560, t: t, dur: 0.07, peak: v * 0.16, atk: 0.003 });
    noise({ filter: 'bandpass', f0: 1700, q: 6, t: t, dur: 0.02, peak: v * 0.03 });
  }); }

  // Tock — tick secco per tastiera/segmented.
  function tock() { guard(function (v) {
    var t = now(0.003);
    noise({ filter: 'bandpass', f0: 2200, q: 2.5, t: t, dur: 0.022, peak: v * 0.09, atk: 0.001 });
    tone({ type: 'sine', f0: 900, t: t, dur: 0.02, peak: v * 0.03 });
  }); }

  // Bottle / Boop — goccia d'acqua (glissato ascendente).
  function bottle() { guard(function (v) {
    var t = now(0.004);
    tone({ type: 'sine', f0: 620, f1: 1240, t: t, dur: 0.12, peak: v * 0.12, atk: 0.004, verb: true });
  }); }
  function boop() { guard(function (v) {
    var t = now(0.004);
    tone({ type: 'sine', f0: 880, t: t, dur: 0.08, peak: v * 0.12 });
  }); }

  // Funk — il classico "duh-doo" discendente con wah.
  function funk() { guard(function (v) {
    function honk(t, f) {
      var osc = ctx.createOscillator();
      var g = ctx.createGain();
      var bp = ctx.createBiquadFilter();
      osc.type = 'sawtooth';
      osc.frequency.value = f;
      bp.type = 'bandpass'; bp.Q.value = 4;
      bp.frequency.setValueAtTime(f * 1.2, t);
      bp.frequency.linearRampToValueAtTime(f * 3.2, t + 0.06);
      bp.frequency.linearRampToValueAtTime(f * 1.4, t + 0.22);
      g.gain.setValueAtTime(0.0001, t);
      g.gain.exponentialRampToValueAtTime(v * 0.16, t + 0.02);
      g.gain.exponentialRampToValueAtTime(0.0001, t + 0.26);
      osc.connect(bp); bp.connect(g); g.connect(bus);
      osc.start(t); osc.stop(t + 0.3);
    }
    var t = now(0.01);
    honk(t, 415.30);        // G#4
    honk(t + 0.16, 311.13); // Eb4
  }); }

  // Glass — alert moderno luminoso (arpeggio di partiali).
  function glass() { guard(function (v) {
    var t = now(0.01);
    var notes = [1244.5, 1661.2, 2217.5];
    for (var i = 0; i < notes.length; i++) {
      var tt = t + i * 0.045;
      bell(tt, notes[i] / 1244.5 * 1244.5, [[1, 1, 1], [2.76, 0.3, 0.7], [5.4, 0.1, 0.5]], 0.5, v * 0.06, true);
      tone({ type: 'sine', f0: notes[i], t: tt, dur: 0.55, peak: v * 0.06, atk: 0.004, verb: true });
    }
  }); }

  // Basso — thud grave discendente.
  function basso() { guard(function (v) {
    var t = now(0.005);
    tone({ type: 'sawtooth', f0: 165, f1: 70, t: t, dur: 0.4, peak: v * 0.18, atk: 0.006 });
    tone({ type: 'sine', f0: 110, f1: 55, t: t, dur: 0.45, peak: v * 0.14 });
  }); }

  // Blow — soffio: rumore filtrato in salita + sinusoide.
  function blow() { guard(function (v) {
    var t = now(0.005);
    noise({ filter: 'bandpass', f0: 400, f1: 1400, q: 1.2, t: t, dur: 0.32, peak: v * 0.10, atk: 0.05 });
    tone({ type: 'sine', f0: 520, f1: 760, t: t, dur: 0.3, peak: v * 0.05, atk: 0.05, verb: true });
  }); }

  // Frog — croak: pulsazioni gravi modulate.
  function frog() { guard(function (v) {
    var t = now(0.005);
    for (var i = 0; i < 5; i++) {
      var tt = t + i * 0.045;
      tone({ type: 'square', f0: 180 + i * 12, t: tt, dur: 0.04, peak: v * 0.08 });
    }
  }); }

  // Hero — "ahh" lungo e riverberato (epico).
  function hero() { guard(function (v) {
    var t = now(0.01);
    var fund = 523.25; // C5
    bell(t, fund, [[1, 1, 1], [2, 0.5, 0.9], [3, 0.3, 0.8], [4, 0.18, 0.7], [5, 0.1, 0.6]], 1.4, v * 0.05, true);
  }); }

  // Morse — due beep (dot-dash).
  function morse() { guard(function (v) {
    var t = now(0.005);
    tone({ type: 'sine', f0: 1000, t: t, dur: 0.08, peak: v * 0.12 });
    tone({ type: 'sine', f0: 1000, t: t + 0.14, dur: 0.2, peak: v * 0.12 });
  }); }

  // Ping — sonar pulito.
  function ping() { guard(function (v) {
    var t = now(0.005);
    tone({ type: 'sine', f0: 1000, t: t, dur: 0.5, peak: v * 0.12, atk: 0.002, verb: true });
  }); }

  // Purr — rumore grave con tremolo.
  function purr() { guard(function (v) {
    var t = now(0.005);
    var osc = ctx.createOscillator();
    var lfo = ctx.createOscillator();
    var lfoG = ctx.createGain();
    var g = ctx.createGain();
    osc.type = 'sine'; osc.frequency.value = 85;
    lfo.type = 'sine'; lfo.frequency.value = 22;
    lfoG.gain.value = v * 0.06;
    g.gain.setValueAtTime(0.0001, t);
    g.gain.exponentialRampToValueAtTime(v * 0.1, t + 0.05);
    g.gain.exponentialRampToValueAtTime(0.0001, t + 0.5);
    lfo.connect(lfoG); lfoG.connect(g.gain);
    osc.connect(g); g.connect(bus);
    osc.start(t); lfo.start(t);
    osc.stop(t + 0.55); lfo.stop(t + 0.55);
  }); }

  // Submarine — sonar profondo con coda riverberata.
  function submarine() { guard(function (v) {
    var t = now(0.005);
    tone({ type: 'sine', f0: 120, t: t, dur: 1.2, peak: v * 0.16, atk: 0.01, verb: true });
    tone({ type: 'sine', f0: 480, t: t, dur: 0.3, peak: v * 0.04, verb: true });
  }); }

  // Startup chime — il celebre accordo di Fa# maggiore.
  function startup() { guard(function (v) {
    var t = now(0.02);
    // F#2 C#3 F#3 A#3 C#4 F#4 (Hz approssimati)
    var notes = [92.50, 138.59, 185.00, 233.08, 277.18, 369.99];
    for (var i = 0; i < notes.length; i++) {
      var f = notes[i];
      // due osc leggermente scordati = coro/chorus
      tone({ type: 'sawtooth', f0: f, t: t, dur: 2.6, peak: v * 0.05, atk: 0.04, detune: -6, verb: true });
      tone({ type: 'sawtooth', f0: f, t: t, dur: 2.6, peak: v * 0.05, atk: 0.04, detune: 6, verb: true });
      tone({ type: 'sine', f0: f, t: t, dur: 2.6, peak: v * 0.04, atk: 0.04, verb: true });
    }
  }); }

  // Screenshot — otturatore fotocamera (due click meccanici).
  function screenshot() { guard(function (v) {
    var t = now(0.004);
    noise({ filter: 'highpass', f0: 2000, q: 0.7, t: t, dur: 0.03, peak: v * 0.12, atk: 0.001 });
    noise({ filter: 'highpass', f0: 1500, q: 0.7, t: t + 0.06, dur: 0.05, peak: v * 0.10, atk: 0.001 });
  }); }

  // Trash — accartocciamento (raffiche di rumore).
  function trash() { guard(function (v) {
    var t = now(0.004);
    for (var i = 0; i < 7; i++) {
      var tt = t + i * 0.04 + Math.random() * 0.02;
      noise({ filter: 'bandpass', f0: 1200 + Math.random() * 2500, q: 1.5,
              t: tt, dur: 0.05, peak: v * (0.05 + Math.random() * 0.05), atk: 0.002 });
    }
  }); }

  // Volume tick — il "pop" di feedback quando regoli il volume.
  function volume() { guard(function (v) {
    var t = now(0.003);
    tone({ type: 'sine', f0: 1050, f1: 760, t: t, dur: 0.06, peak: v * 0.12, atk: 0.002 });
  }); }

  // Toggle — switch on/off.
  function toggle(on) { guard(function (v) {
    var t = now(0.003);
    if (on === false) {
      tone({ type: 'sine', f0: 760, f1: 520, t: t, dur: 0.06, peak: v * 0.1 });
    } else {
      tone({ type: 'sine', f0: 760, f1: 1040, t: t, dur: 0.06, peak: v * 0.1 });
    }
  }); }

  // Send — whoosh di invio messaggio.
  function send() { guard(function (v) {
    var t = now(0.004);
    noise({ filter: 'bandpass', f0: 600, f1: 3200, q: 0.8, t: t, dur: 0.28, peak: v * 0.08, atk: 0.02 });
    tone({ type: 'sine', f0: 500, f1: 1200, t: t, dur: 0.26, peak: v * 0.05, verb: true });
  }); }

  // Receive — pop di messaggio ricevuto.
  function receive() { guard(function (v) {
    var t = now(0.004);
    tone({ type: 'sine', f0: 1400, f1: 1900, t: t, dur: 0.12, peak: v * 0.1, verb: true });
  }); }

  // Lock / Unlock.
  function lock() { guard(function (v) {
    var t = now(0.004);
    noise({ filter: 'bandpass', f0: 2600, q: 3, t: t, dur: 0.03, peak: v * 0.09, atk: 0.001 });
    tone({ type: 'sine', f0: 660, f1: 440, t: t + 0.02, dur: 0.1, peak: v * 0.06 });
  }); }
  function unlock() { guard(function (v) {
    var t = now(0.004);
    tone({ type: 'sine', f0: 440, f1: 660, t: t, dur: 0.1, peak: v * 0.06 });
    noise({ filter: 'bandpass', f0: 2600, q: 3, t: t + 0.06, dur: 0.03, peak: v * 0.09, atk: 0.001 });
  }); }

  // Login riuscito: accordo ascendente prima di aprire il desktop.
  function go() { guard(function (v) {
    var t = now(0.01);
    tone({ type: 'sine', f0: 392.00, t: t,        dur: 0.3,  peak: v * 0.08, verb: true });
    tone({ type: 'sine', f0: 523.25, t: t + 0.07, dur: 0.3,  peak: v * 0.07, verb: true });
    tone({ type: 'sine', f0: 659.25, t: t + 0.14, dur: 0.35, peak: v * 0.06, verb: true });
    tone({ type: 'sine', f0: 783.99, t: t + 0.21, dur: 0.4,  peak: v * 0.05, verb: true });
  }); }

  /* ============================================================
     API pubblica + retro-compatibilita
     ============================================================ */
  var Snd = {
    click: click, tink: tink, pop: pop, tock: tock, bottle: bottle, boop: boop,
    funk: funk, glass: glass, basso: basso, blow: blow, frog: frog, hero: hero,
    morse: morse, ping: ping, purr: purr, submarine: submarine, startup: startup,
    screenshot: screenshot, trash: trash, volume: volume, toggle: toggle,
    send: send, receive: receive, lock: lock, unlock: unlock, go: go,
    vol: vol, ctx: audio,
    play: function (name, arg) { if (typeof Snd[name] === 'function') Snd[name](arg); }
  };
  window.Snd = Snd;

  // Funzioni globali storiche (compat con index.php / hub.js).
  window.sndAudio = audio;
  window.sndVol = vol;
  window.sndClick = click;
  window.sndGo = go;
  window.sndNote = function (freq, at, dur, peak) {
    if (!audio()) return;
    tone({ type: 'sine', f0: freq, t: at, dur: dur, peak: peak });
  };

  // Listener globale: click leggero su bottoni e puntini del title bar.
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.addEventListener('click', function (e) {
    if (reduce) return;
    var el = e.target.closest && e.target.closest('button, .dots i, [data-snd]');
    if (!el) return;
    var custom = el.getAttribute && el.getAttribute('data-snd');
    if (custom && typeof Snd[custom] === 'function') Snd[custom]();
    else click();
  }, true);
})();
