<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PCTO — Maturità 2026</title>
<style>
/* ============================================================
   STILE — replica del MorphingButton:
   palette neutra, radius 32 fisso, spring 240/18/1.1
   ============================================================ */
:root {
  --scene: #FAFAFA;

  --container: #F4F4F4;
  --container-hover: #ebeaea;
  --surface: #FEFEFE;
  --surface-hover: #fafafa;
  --border: rgba(231,230,230,0.65);

  --text: #18181B;
  --text-strong: rgba(0,0,0,0.9);
  --placeholder: #A1A1AA;
  --error: #DC2626;

  --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
  --shadow-panel: 0 1px 2px 0 rgba(0,0,0,0.05);

  --r: 32px;
  --r-full: 999px;

  /* spring { stiffness: 240, damping: 18, mass: 1.1 } */
  --spring: cubic-bezier(0.3, 1.36, 0.46, 1);
  --spring-soft: cubic-bezier(0.22, 1, 0.36, 1);
  --dur: 0.8s;

  /* sfondo aurora — pastelli puliti e desaturati */
  --aur-1: rgba(186,190,255,0.55);
  --aur-2: rgba(255,214,222,0.5);
  --aur-3: rgba(199,235,224,0.5);
  --aur-4: rgba(255,236,200,0.45);
}

@media (prefers-color-scheme: dark) {
  :root {
    --scene: #0A0A0A;
    --container: #1C1C1E;
    --container-hover: #252529;
    --surface: #2C2C2E;
    --surface-hover: #3A3A3C;
    --border: rgba(255,255,255,0.05);
    --text: #fefefe;
    --text-strong: #fefefe;
    --placeholder: #B2B2B2;
    --error: #F87171;
    --shadow-sm: 0 10px 15px -3px rgba(0,0,0,0.35);
    --shadow-panel: 0 20px 25px -5px rgba(0,0,0,0.45);

    --aur-1: rgba(99,102,241,0.16);
    --aur-2: rgba(236,121,160,0.10);
    --aur-3: rgba(45,212,191,0.10);
    --aur-4: rgba(250,204,21,0.07);
  }
}

* { margin: 0; padding: 0; box-sizing: border-box; }
html, body { height: 100%; }

body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  background: var(--scene);
  color: var(--text);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  -webkit-font-smoothing: antialiased;
}

/* ------------------------------------------------------------
   SFONDO — aurora morbida
   ------------------------------------------------------------ */
.bg { position: fixed; inset: 0; overflow: hidden; }
.bg .blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(110px);
  will-change: transform;
}
.bg .b1 {
  width: 60vmax; height: 60vmax;
  top: -25vmax; left: -15vmax;
  background: radial-gradient(circle, var(--aur-1), transparent 65%);
  animation: drift1 26s ease-in-out infinite alternate;
}
.bg .b2 {
  width: 55vmax; height: 55vmax;
  bottom: -28vmax; right: -18vmax;
  background: radial-gradient(circle, var(--aur-2), transparent 65%);
  animation: drift2 32s ease-in-out infinite alternate;
}
.bg .b3 {
  width: 45vmax; height: 45vmax;
  top: 45%; left: -20vmax;
  background: radial-gradient(circle, var(--aur-3), transparent 65%);
  animation: drift3 38s ease-in-out infinite alternate;
}
.bg .b4 {
  width: 40vmax; height: 40vmax;
  top: -18vmax; right: -10vmax;
  background: radial-gradient(circle, var(--aur-4), transparent 65%);
  animation: drift4 30s ease-in-out infinite alternate;
}
@keyframes drift1 { from { transform: translate(0,0) scale(1); }    to { transform: translate(8vmax, 6vmax) scale(1.12); } }
@keyframes drift2 { from { transform: translate(0,0) scale(1.08); } to { transform: translate(-7vmax, -5vmax) scale(1); } }
@keyframes drift3 { from { transform: translate(0,0) scale(1); }    to { transform: translate(10vmax, -6vmax) scale(1.15); } }
@keyframes drift4 { from { transform: translate(0,0) scale(1.1); }  to { transform: translate(-6vmax, 7vmax) scale(1); } }

.bg .wash {
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse 90% 70% at 50% 50%, transparent 30%, var(--scene) 100%);
}

.stage {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  width: 100%;
  padding: 16px;
}

/* ------------------------------------------------------------
   MORPH
   ------------------------------------------------------------ */
.morph {
  position: relative;
  width: 280px;
  height: 64px;
  border-radius: var(--r);
  background: var(--container);
  border: 1.1px solid var(--border);
  overflow: hidden;
  will-change: width, height;
  animation: enter var(--dur) var(--spring) both;
  transition:
    width var(--dur) var(--spring),
    height var(--dur) var(--spring),
    background 0.3s ease,
    box-shadow 0.3s ease;
}
.stage.open .morph {
  width: min(376px, calc(100vw - 32px));
  box-shadow: var(--shadow-panel);
}
@keyframes enter {
  from { opacity: 0; transform: scale(0.9); }
  to   { opacity: 1; transform: scale(1); }
}

.closed {
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  cursor: pointer;
  border: none;
  background: transparent;
  font-family: inherit;
  color: var(--text);
  white-space: nowrap;
  transition: opacity 0.35s ease, transform var(--dur) var(--spring), filter 0.3s ease, background 0.3s ease;
}
.closed:hover { background: var(--container-hover); }
.closed:active { transform: scale(0.97); }
.closed .cap {
  display: flex;
  transform-origin: right center;
  color: var(--text-strong);
  transition: opacity var(--dur) var(--spring), transform var(--dur) var(--spring), filter 0.35s ease;
}
.closed .cap svg { width: 26px; height: 26px; }
.closed .label {
  font-size: 20px;
  font-weight: 700;
  letter-spacing: -0.025em;
}
.stage.open .closed {
  opacity: 0;
  pointer-events: none;
  transform: scale(0.85);
  filter: blur(4px);
}
.stage.open .closed .cap { opacity: 0; transform: scale(0); filter: blur(4px); }

.panel {
  position: absolute;
  top: 0; left: 0; right: 0;
  padding: 20px 18px 18px;
  opacity: 0;
  transform: translateX(-10px);
  filter: blur(4px);
  pointer-events: none;
  transition:
    opacity 0.5s var(--spring-soft) 0.18s,
    transform var(--dur) var(--spring) 0.18s,
    filter 0.5s var(--spring-soft) 0.18s;
}
.stage.open .panel {
  opacity: 1;
  transform: translateX(0);
  filter: blur(0);
  pointer-events: auto;
}

.host { position: relative; }
.pane {
  transition: opacity 0.45s var(--spring-soft), transform 0.45s var(--spring-soft), filter 0.45s var(--spring-soft);
}
.pane.is-hidden {
  opacity: 0;
  transform: scale(0);
  filter: blur(4px);
  pointer-events: none;
  position: absolute;
  top: 0; left: 0; right: 0;
}

.eyebrow {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: var(--placeholder);
  margin-bottom: 4px;
  padding-left: 6px;
}
.panel h1 {
  font-size: 20px;
  font-weight: 700;
  letter-spacing: -0.025em;
  color: var(--text);
  margin-bottom: 14px;
  padding-left: 6px;
}

form { display: flex; flex-direction: column; gap: 8px; }

.input-pill {
  display: flex;
  align-items: center;
  gap: 10px;
  height: 56px;
  border-radius: 28px;
  background: var(--surface);
  box-shadow: var(--shadow-sm);
  padding: 0 8px 0 7px;
  transition: background 0.3s ease;
}
.input-pill:focus-within { background: var(--surface-hover); }
.input-pill .ico {
  width: 42px; height: 42px;
  flex-shrink: 0;
  border-radius: var(--r-full);
  background: var(--container);
  display: flex; align-items: center; justify-content: center;
  color: var(--placeholder);
  transition: color 0.3s ease;
}
.input-pill:focus-within .ico { color: var(--text-strong); }
.input-pill .ico svg { width: 18px; height: 18px; }
.input-pill input {
  flex: 1;
  min-width: 0;
  border: none;
  outline: none;
  background: transparent;
  font-family: inherit;
  font-size: 17px;
  font-weight: 600;
  color: var(--text);
}
.input-pill input::placeholder { color: var(--placeholder); font-weight: 500; }

.eye {
  width: 38px; height: 38px;
  flex-shrink: 0;
  border: none;
  background: transparent;
  border-radius: var(--r-full);
  color: var(--placeholder);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: color 0.2s ease, transform 0.4s var(--spring);
}
.eye:hover { color: var(--text); }
.eye:active { transform: scale(0.88); }
.eye svg { width: 17px; height: 17px; }

.btn {
  height: 56px;
  margin-top: 2px;
  border: none;
  border-radius: 28px;
  background: var(--surface);
  color: var(--text);
  font-family: inherit;
  font-size: 18px;
  font-weight: 700;
  letter-spacing: -0.025em;
  white-space: nowrap;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  box-shadow: var(--shadow-sm);
  transition: transform 0.4s var(--spring), background 0.3s ease, opacity 0.3s ease;
}
.btn:hover { background: var(--surface-hover); }
.btn:active { transform: scale(0.97); }
.btn:disabled { opacity: 0.55; cursor: wait; }
.btn svg { width: 17px; height: 17px; }

/* messaggio d'errore dal backend */
.err {
  font-size: 13px;
  font-weight: 600;
  color: var(--error);
  text-align: center;
  padding-top: 8px;
  min-height: 0;
  opacity: 0;
  transform: translateY(-4px);
  transition: opacity 0.3s ease, transform 0.4s var(--spring);
}
.err.show { opacity: 1; transform: translateY(0); }

.success {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 9px;
  padding: 8px 0 4px;
}
.check {
  width: 56px; height: 56px;
  border-radius: var(--r-full);
  background: var(--surface);
  box-shadow: var(--shadow-sm);
  color: var(--text-strong);
  display: flex; align-items: center; justify-content: center;
  animation: pop var(--dur) var(--spring) both;
}
.check svg { width: 26px; height: 26px; }
.success strong { font-size: 20px; font-weight: 700; letter-spacing: -0.025em; }
.success p { font-size: 13px; color: var(--placeholder); font-weight: 500; }
@keyframes pop {
  from { opacity: 0; transform: scale(0); filter: blur(4px); }
  to   { opacity: 1; transform: scale(1); filter: blur(0); }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20% { transform: translateX(-8px); }
  40% { transform: translateX(8px); }
  60% { transform: translateX(-5px); }
  80% { transform: translateX(5px); }
}
.morph.shake { animation: shake 0.45s var(--spring-soft); }

/* ------------------------------------------------------------
   DEMO (?demo=1) — widget schematico integrato sotto il login
   ------------------------------------------------------------ */
body.demo { overflow-y: auto; }

.demo-widget {
  display: none;
  width: min(376px, calc(100vw - 32px)); /* identica al pannello aperto */
  border-radius: var(--r);
  background: var(--container);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-panel);
  padding: 18px 18px 14px;
}
body.demo .demo-widget {
  display: block;
  animation: demoIn var(--dur) var(--spring) 0.25s both;
}
@keyframes demoIn {
  from { opacity: 0; transform: translateY(14px) scale(0.96); filter: blur(4px); }
  to   { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
}

/* intestazione — stessa lingua dell'eyebrow del form */
.demo-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 0 6px;
  margin-bottom: 14px;
}
.demo-eyebrow {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: var(--placeholder);
}
.dcount {
  font-size: 11px;
  font-weight: 700;
  font-variant-numeric: tabular-nums;
  letter-spacing: 0.02em;
  color: var(--placeholder);
  background: var(--surface);
  box-shadow: var(--shadow-sm);
  border-radius: var(--r-full);
  padding: 3px 10px;
  opacity: 0;
  transform: scale(0.8);
  transition: opacity 0.3s ease, transform 0.4s var(--spring);
}
.dcount.show { opacity: 1; transform: scale(1); }

/* schema: Browser → Server PHP → MySQL */
.schema {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  padding: 0 6px;
  margin-bottom: 16px;
}
.node {
  display: flex; flex-direction: column; align-items: center; gap: 7px;
  transition: transform 0.5s var(--spring);
}
.node .nico {
  width: 48px; height: 48px;
  border-radius: var(--r-full); /* cerchi, come le icone del form */
  background: var(--surface);
  box-shadow: var(--shadow-sm);
  color: var(--placeholder);
  display: flex; align-items: center; justify-content: center;
  transition: color 0.3s ease, box-shadow 0.35s ease;
}
.node .nico svg { width: 19px; height: 19px; }
.node .nlab {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--placeholder);
  transition: color 0.3s ease;
}
.node.on, .node.ko { transform: translateY(-2px); }
.node.on .nico, .node.ko .nico { color: var(--text-strong); }
.node.on .nlab, .node.ko .nlab { color: var(--text); }
.node.on .nico { box-shadow: var(--shadow-sm), 0 0 0 1.5px rgba(34, 197, 94, 0.55), 0 4px 16px -2px rgba(34, 197, 94, 0.35); }
.node.ko .nico { box-shadow: var(--shadow-sm), 0 0 0 1.5px rgba(220, 38, 38, 0.5), 0 4px 16px -2px rgba(220, 38, 38, 0.3); }

/* fili allineati al centro esatto delle icone (48px → 24px) */
.wire {
  position: relative;
  flex: 1;
  height: 1.5px;
  border-radius: 1px;
  background: var(--border);
  margin-top: 23px;
}
.wire .pk {
  position: absolute; top: 50%; left: 0;
  width: 7px; height: 7px; border-radius: 50%;
  background: #22C55E;
  box-shadow: 0 0 8px rgba(34, 197, 94, 0.55);
  transform: translate(-50%, -50%);
  opacity: 0;
}
.wire.ko .pk { background: var(--error); box-shadow: 0 0 8px rgba(220, 38, 38, 0.55); }
.wire.fwd .pk { animation: pkFwd 1.3s var(--spring-soft) infinite; }
.wire.rev .pk { animation: pkRev 1.3s var(--spring-soft) infinite; }
@keyframes pkFwd { 0% { left: 0; opacity: 0; } 18% { opacity: 1; } 82% { opacity: 1; } 100% { left: 100%; opacity: 0; } }
@keyframes pkRev { 0% { left: 100%; opacity: 0; } 18% { opacity: 1; } 82% { opacity: 1; } 100% { left: 0; opacity: 0; } }

/* card del passaggio — stessa lingua delle pillole del form */
.dstep {
  background: var(--surface);
  border-radius: 22px;
  box-shadow: var(--shadow-sm);
  padding: 14px 16px;
  min-height: 78px; /* il widget non "salta" tra un passaggio e l'altro */
  transition: opacity 0.3s var(--spring-soft), transform 0.45s var(--spring), filter 0.3s var(--spring-soft);
}
.dstep.swap { opacity: 0; transform: translateY(6px) scale(0.98); filter: blur(4px); }
.dhead { display: flex; align-items: center; gap: 8px; }
.ddot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--placeholder); flex-shrink: 0;
  transition: background 0.3s ease, box-shadow 0.3s ease;
}
.ddot.ok { background: #22C55E; box-shadow: 0 0 8px rgba(34, 197, 94, 0.5); }
.ddot.ko { background: var(--error); box-shadow: 0 0 8px rgba(220, 38, 38, 0.45); }
.dt { font-size: 14px; font-weight: 700; letter-spacing: -0.015em; flex: 1; }
.dms {
  font-size: 11px;
  font-weight: 700;
  font-variant-numeric: tabular-nums;
  color: var(--placeholder);
  background: var(--container);
  border-radius: var(--r-full);
  padding: 3px 9px;
  white-space: nowrap;
}
.dd { font-size: 12.5px; color: var(--placeholder); font-weight: 500; margin-top: 4px; padding-left: 16px; line-height: 1.5; }
#dsql {
  margin: 8px 0 2px 16px;
  background: var(--container);
  border-radius: 14px;
  padding: 10px 12px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  font-size: 11.5px;
  line-height: 1.55;
  color: var(--text);
  white-space: pre-wrap;
  word-break: break-word;
}

/* controllerino: indietro · play/pausa · avanti · puntini */
.ctrl { display: flex; align-items: center; gap: 7px; padding: 12px 2px 2px; }
.cbtn {
  width: 36px; height: 36px;
  border: none; border-radius: var(--r-full);
  background: var(--surface);
  box-shadow: var(--shadow-sm);
  color: var(--text);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: transform 0.4s var(--spring), background 0.3s ease, opacity 0.3s ease;
}
.cbtn:hover { background: var(--surface-hover); }
.cbtn:active { transform: scale(0.88); }
.cbtn:disabled { opacity: 0.35; cursor: default; }
.cbtn svg { width: 13px; height: 13px; }
.cbtn.main { width: 44px; height: 44px; }
.cbtn.main svg { width: 16px; height: 16px; }
.dots {
  display: flex; flex-wrap: wrap; justify-content: flex-end;
  gap: 6px; margin-left: auto; padding-right: 6px;
}
.dots i {
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--border);
  cursor: pointer;
  transition: background 0.3s ease, transform 0.4s var(--spring);
}
.dots i:hover { background: var(--placeholder); }
.dots i.done { background: var(--placeholder); }
.dots i.on { background: #22C55E; transform: scale(1.4); }

/* in dark mode il bordo è quasi invisibile: fili e puntini più leggibili */
@media (prefers-color-scheme: dark) {
  .wire { background: rgba(255, 255, 255, 0.14); }
  .dots i { background: rgba(255, 255, 255, 0.16); }
}

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.001s !important; transition-duration: 0.001s !important; }
}

/* ------------------------------------------------------------
   CURSORI — i cursori reali di macOS (apple_cursor di ful1e5, MIT)
   ------------------------------------------------------------ */
body { cursor: url("assets/cursors/arrow.svg") 9 4, default; }
button, a, .dots i { cursor: url("assets/cursors/pointer.svg") 10 5, pointer; }
input, textarea { cursor: url("assets/cursors/text.svg") 14 14, text; }
</style>
</head>
<body>

<div class="bg">
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="blob b3"></div>
  <div class="blob b4"></div>
  <div class="wash"></div>
</div>

<div class="stage" id="stage">
  <div class="morph" id="morph">

    <!-- PILLOLA INIZIALE -->
    <button class="closed" id="openBtn" type="button">
      <span class="cap">
        <svg viewBox="0 0 56 56" fill="currentColor"><path d="M33.9370467,26.5128481 C34.0071746,34.0650232 40.5622987,36.5781967 40.6349206,36.6102741 C40.579494,36.787517 39.5875211,40.1917925 37.1813698,43.708166 C35.1013579,46.748249 32.9425798,49.7771957 29.5418665,49.8399278 C26.2003256,49.9014987 25.1258336,47.8583854 21.3055063,47.8583854 C17.486333,47.8583854 16.2925236,49.7771906 13.129383,49.9014987 C9.84683186,50.0257159 7.34720367,46.6140516 5.24990601,43.5851051 C0.964332248,37.3892927 -2.31073284,26.0771949 2.08685175,18.4413444 C4.27147795,14.64935 8.17557011,12.2481009 12.4131131,12.1865253 C15.6364944,12.125039 18.6789832,14.3551087 20.649477,14.3551087 C22.6187214,14.3551087 26.3159929,11.6732544 30.2027979,12.0671186 C31.8299473,12.1348421 36.3973824,12.7243949 39.3302579,17.0173633 C39.0939405,17.1638624 33.880373,20.1989532 33.9370467,26.5128552 M27.6570067,7.96804814 C29.3997351,5.85854475 30.5726917,2.92192094 30.2526965,0 C27.7406844,0.100960353 24.7030975,1.67393506 22.9012871,3.78227787 C21.286519,5.64931362 19.8723452,8.63762341 20.2539138,11.5017132 C23.0538462,11.7183402 25.9141925,10.078893 27.6570067,7.96805157" transform="translate(8 3)"/></svg>
      </span>
      <span class="label">Avvia la presentazione</span>
    </button>

    <!-- PANNELLO -->
    <div class="panel" id="panel">
      <div class="host">

        <div class="pane" id="paneForm">
          <div class="eyebrow">PCTO · Maturità 2026</div>
          <h1>Accedi alla presentazione</h1>
          <form id="form" novalidate>
            <div class="input-pill">
              <span class="ico">
                <svg viewBox="0 0 56 56" fill="currentColor"><path d="M 28.0117 27.3672 C 33.0508 27.3672 37.3867 22.8672 37.3867 17.0078 C 37.3867 11.2187 33.0274 6.9297 28.0117 6.9297 C 22.9961 6.9297 18.6367 11.3125 18.6367 17.0547 C 18.6367 22.8672 22.9961 27.3672 28.0117 27.3672 Z M 13.2930 49.0703 L 42.7305 49.0703 C 46.4101 49.0703 47.7226 48.0156 47.7226 45.9531 C 47.7226 39.9062 40.1523 31.5625 28.0117 31.5625 C 15.8477 31.5625 8.2774 39.9062 8.2774 45.9531 C 8.2774 48.0156 9.5898 49.0703 13.2930 49.0703 Z"/></svg>
              </span>
              <input id="nameInput" type="text" name="nome" placeholder="Il tuo nome" autocomplete="name">
            </div>
            <div class="input-pill">
              <span class="ico">
                <svg viewBox="0 0 56 56" fill="currentColor"><path d="M 28.0000 4.2578 C 21.4609 4.2578 15.4844 8.9219 15.4844 18.5078 L 15.4844 24.1328 C 12.9531 24.4375 11.7109 25.9610 11.7109 28.9610 L 11.7109 46.8438 C 11.7109 50.2188 13.2578 51.7422 16.375 51.7422 L 39.625 51.7422 C 42.7422 51.7422 44.2891 50.2188 44.2891 46.8438 L 44.2891 28.9375 C 44.2891 25.9375 43.0469 24.3437 40.5156 24.0625 L 40.5156 18.5078 C 40.5156 8.9219 34.5391 4.2578 28.0000 4.2578 Z M 19.2578 17.9922 C 19.2578 11.4532 23.1484 7.8672 28.0000 7.8672 C 32.8515 7.8672 36.7422 11.4532 36.7422 17.9922 L 36.7422 24.0391 L 19.2578 24.0625 Z"/></svg>
              </span>
              <input id="codeInput" type="password" name="codice" placeholder="Codice d'accesso" autocomplete="off">
              <button class="eye" id="eyeBtn" type="button" aria-label="Mostra codice">
                <svg id="eyeOn" viewBox="0 0 56 56" fill="currentColor"><path d="M 28.0103 46.4025 C 44.5562 46.4025 56 33.0170 56 28.8443 C 56 24.6511 44.5354 11.2863 28.0103 11.2863 C 11.5883 11.2863 0 24.6511 0 28.8443 C 0 33.0170 11.6710 46.4025 28.0103 46.4025 Z M 28.0103 40.3501 C 21.5655 40.3501 16.4840 35.1240 16.4426 28.8443 C 16.4220 22.3995 21.5655 17.3387 28.0103 17.3387 C 34.4139 17.3387 39.5574 22.3995 39.5574 28.8443 C 39.5574 35.1240 34.4139 40.3501 28.0103 40.3501 Z M 28.0103 32.9963 C 30.3032 32.9963 32.2036 31.1166 32.2036 28.8443 C 32.2036 26.5515 30.3032 24.6717 28.0103 24.6717 C 25.6968 24.6717 23.7964 26.5515 23.7964 28.8443 C 23.7964 31.1166 25.6968 32.9963 28.0103 32.9963 Z"/></svg>
                <svg id="eyeOff" viewBox="0 0 56 56" fill="currentColor" style="display:none"><path d="M 43.9492 47.3227 C 44.2544 47.6280 44.6821 47.7909 45.0686 47.7909 C 45.8832 47.7909 46.6361 47.0580 46.6361 46.2234 C 46.6361 45.8163 46.4735 45.4092 46.1679 45.1038 L 12.1120 11.0682 C 11.8066 10.7629 11.3995 10.6204 10.9924 10.6204 C 10.1781 10.6204 9.4250 11.3532 9.4250 12.1674 C 9.4250 12.5949 9.5675 13.0020 9.8728 13.2870 Z M 45.8628 41.5619 C 52.2546 37.4295 56.0000 32.0555 56.0000 29.6738 C 56.0000 25.5415 44.7025 12.3710 28.0102 12.3710 C 24.5497 12.3710 21.3130 12.9613 18.3410 13.9384 L 23.6540 19.2311 C 24.9771 18.6611 26.4428 18.3354 28.0102 18.3354 C 34.3207 18.3354 39.3892 23.3226 39.3892 29.6738 C 39.3892 31.2209 39.0636 32.7069 38.4324 34.0097 Z M 28.0102 46.9766 C 31.7761 46.9766 35.2774 46.3049 38.4124 45.2056 L 33.0179 39.8112 C 31.5318 40.5848 29.8219 41.0122 28.0102 41.0122 C 21.6591 41.0122 16.6310 35.8621 16.6107 29.6738 C 16.6107 27.8418 17.0382 26.1115 17.8117 24.5848 L 10.7278 17.4600 C 4.0102 21.5924 0 27.2310 0 29.6738 C 0 33.7858 11.5013 46.9766 28.0102 46.9766 Z M 34.4835 29.2463 C 34.4835 25.6840 31.6133 22.7934 28.0102 22.7934 C 27.7456 22.7934 27.4809 22.8137 27.2367 22.8341 L 34.4428 30.0402 C 34.4632 29.7960 34.4835 29.5110 34.4835 29.2463 Z M 21.5166 29.2056 C 21.5166 32.7883 24.4682 35.6789 28.0306 35.6789 C 28.3156 35.6789 28.5802 35.6586 28.8652 35.6382 L 21.5573 28.3303 C 21.5369 28.6153 21.5166 28.9206 21.5166 29.2056 Z"/></svg>
              </button>
            </div>
            <button class="btn" id="submitBtn" type="submit">
              Entra
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
            <div class="err" id="errMsg"></div>
          </form>
        </div>

        <div class="pane is-hidden" id="paneSuccess">
          <div class="success">
            <span class="check">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </span>
            <strong id="successName">Benvenuto!</strong>
            <p id="successMsg">Accesso registrato nel database.</p>
          </div>
        </div>

      </div>
    </div>

  </div>

  <!-- DEMO (?demo=1) — widget schematico integrato sotto il login -->
  <aside class="demo-widget" id="demoWidget" aria-live="polite">

    <div class="demo-head">
      <span class="demo-eyebrow">Dietro le quinte</span>
      <span class="dcount" id="dcount">0 / 0</span>
    </div>

    <div class="schema">
      <div class="node" id="nClient">
        <span class="nico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </span>
        <span class="nlab">Browser</span>
      </div>
      <div class="wire" id="wCS"><i class="pk"></i></div>
      <div class="node" id="nServer">
        <span class="nico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01M7 17.5h.01"/></svg>
        </span>
        <span class="nlab">Server PHP</span>
      </div>
      <div class="wire" id="wSD"><i class="pk"></i></div>
      <div class="node" id="nDb">
        <span class="nico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg>
        </span>
        <span class="nlab">MySQL</span>
      </div>
    </div>

    <div class="dstep" id="dstep">
      <div class="dhead">
        <span class="ddot" id="ddot"></span>
        <span class="dt" id="dt">In attesa di un accesso…</span>
        <span class="dms" id="dms" hidden></span>
      </div>
      <div class="dd" id="dd">Compila il form e premi Entra: i passaggi del backend compariranno qui, uno alla volta.</div>
      <pre id="dsql" hidden></pre>
    </div>

    <div class="ctrl">
      <button class="cbtn" id="cPrev" type="button" disabled aria-label="Passo precedente">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h2v12H6zM9.5 12l8.5 6V6z"/></svg>
      </button>
      <button class="cbtn main" id="cPlay" type="button" aria-label="Riproduci o metti in pausa">
        <svg id="icoPlay" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
        <svg id="icoPause" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
      </button>
      <button class="cbtn" id="cNext" type="button" disabled aria-label="Passo successivo">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 6h2v12h-2zM14.5 12L6 18V6z"/></svg>
      </button>
      <div class="dots" id="dots"></div>
    </div>

  </aside>
</div>

<script src="sound.js"></script>
<script>
const stage = document.getElementById('stage');
const morph = document.getElementById('morph');
const panel = document.getElementById('panel');
const paneForm = document.getElementById('paneForm');
const paneSuccess = document.getElementById('paneSuccess');
const nameInput = document.getElementById('nameInput');
const codeInput = document.getElementById('codeInput');
const submitBtn = document.getElementById('submitBtn');
const errMsg = document.getElementById('errMsg');
let submitted = false;

function setOpenHeight() {
  morph.style.height = panel.offsetHeight + 'px';
}

function openMorph() {
  stage.classList.add('open');
  requestAnimationFrame(() => {
    requestAnimationFrame(setOpenHeight);
  });
  setTimeout(() => nameInput.focus({ preventScroll: true }), 600);
}

function closeMorph() {
  if (submitted) return;
  stage.classList.remove('open');
  morph.style.height = '64px';
}

document.getElementById('openBtn').addEventListener('click', (e) => {
  e.stopPropagation();
  openMorph();
});

document.addEventListener('mousedown', (e) => {
  if (stage.classList.contains('open') && !morph.contains(e.target)) {
    closeMorph();
  }
});

function shakeError(messaggio) {
  morph.classList.remove('shake');
  void morph.offsetWidth;
  morph.classList.add('shake');
  if (messaggio) {
    errMsg.textContent = messaggio;
    errMsg.classList.add('show');
    requestAnimationFrame(setOpenHeight);
  }
}

function clearError() {
  errMsg.classList.remove('show');
  errMsg.textContent = '';
}

/* ============================================================
   INVIO — chiama il backend PHP (login.php)
   ============================================================ */
document.getElementById('form').addEventListener('submit', async (e) => {
  e.preventDefault();
  clearError();

  if (!nameInput.value.trim()) { shakeError(); nameInput.focus(); return; }
  if (!codeInput.value) { shakeError(); codeInput.focus(); return; }

  submitBtn.disabled = true;

  try {
    const dati = new URLSearchParams();
    dati.append('nome', nameInput.value.trim());
    dati.append('codice', codeInput.value);
    if (demoMode) dati.append('demo', '1');

    const t0 = performance.now();
    const risposta = await fetch('login.php', { method: 'POST', body: dati });
    const json = await risposta.json();
    mostraDemo(json, performance.now() - t0, risposta.status);

    if (!json.ok) {
      shakeError(json.messaggio || 'Qualcosa è andato storto.');
      submitBtn.disabled = false;
      return;
    }

    /* accesso riuscito */
    submitted = true;
    sndGo();
    const raw = nameInput.value.trim().split(/\s+/)[0];
    const pretty = raw.charAt(0).toUpperCase() + raw.slice(1);
    document.getElementById('successName').textContent = 'Benvenuto, ' + pretty + '!';

    paneForm.classList.add('is-hidden');
    paneSuccess.classList.remove('is-hidden');
    requestAnimationFrame(setOpenHeight);

    /* in demo si resta qui per spiegare i passaggi col controllerino;
       altrimenti si entra nel desktop (hub.php) */
    if (!demoMode) {
      document.getElementById('successMsg').textContent = 'Accesso registrato. Apro il desktop…';
      setTimeout(() => window.location.replace('hub.php'), 1100);
    }

  } catch (err) {
    if (demoMode) avviaDemo([
      { titolo: 'Il browser invia la richiesta', dettaglio: 'fetch POST → login.php (nome + codice)' },
      { titolo: 'Nessuna risposta dal server', dettaglio: String(err), stato: 'errore' }
    ]);
    shakeError('Server non raggiungibile.');
    submitBtn.disabled = false;
  }
});

window.addEventListener('resize', () => {
  if (stage.classList.contains('open')) setOpenHeight();
});

/* ============================================================
   DEMO — visualizzazione schematica dei passaggi del backend
   Attivazione SOLO con ?demo=1 nell'URL (nessun tasto visibile)
   ============================================================ */
const demoMode = new URLSearchParams(location.search).get('demo') === '1';
if (demoMode) document.body.classList.add('demo');

const nClient = document.getElementById('nClient');
const nServer = document.getElementById('nServer');
const nDb     = document.getElementById('nDb');
const wCS     = document.getElementById('wCS');
const wSD     = document.getElementById('wSD');
const dstepEl = document.getElementById('dstep');
const ddot    = document.getElementById('ddot');
const dtEl    = document.getElementById('dt');
const dmsEl   = document.getElementById('dms');
const ddEl    = document.getElementById('dd');
const dsqlEl  = document.getElementById('dsql');
const dotsEl  = document.getElementById('dots');
const dcount  = document.getElementById('dcount');
const cPrev   = document.getElementById('cPrev');
const cPlay   = document.getElementById('cPlay');
const cNext   = document.getElementById('cNext');
const icoPlay  = document.getElementById('icoPlay');
const icoPause = document.getElementById('icoPause');

let passi = [];   /* i passaggi dell'ultimo accesso */
let idx = -1;     /* passaggio attualmente mostrato */
let timer = null; /* autoplay attivo (null = in pausa) */

/* A quale parte dello schema appartiene un passaggio?
   cs = browser → server · sc = server → browser
   sd = server → MySQL   · db = dentro MySQL · server = dentro PHP */
function zonaDi(p) {
  const t = (p.titolo || '').toLowerCase();
  if (t.includes('browser invia')) return 'cs';
  if (t.includes('risposta'))      return 'sc';
  if (t.includes('mysql') || t.includes('connessione')) return 'sd';
  if (t.includes('query') || t.includes('lettura'))     return 'db';
  return 'server';
}

/* Accende nodi e fili dello schema in base al passaggio corrente */
function evidenzia(p) {
  [nClient, nServer, nDb].forEach(n => n.classList.remove('on', 'ko'));
  [wCS, wSD].forEach(w => w.classList.remove('fwd', 'rev', 'ko'));
  const ko = p.stato === 'errore';
  const cls = ko ? 'ko' : 'on';
  const zona = zonaDi(p);
  let wire = null, verso = null;
  if (zona === 'cs')      { nClient.classList.add(cls); nServer.classList.add(cls); wire = wCS; verso = 'fwd'; }
  else if (zona === 'sc') { nServer.classList.add(cls); nClient.classList.add(cls); wire = wCS; verso = 'rev'; }
  else if (zona === 'sd') { nServer.classList.add(cls); nDb.classList.add(cls);     wire = wSD; verso = 'fwd'; }
  else if (zona === 'db') { nDb.classList.add(cls); }
  else                    { nServer.classList.add(cls); }
  if (wire) {
    wire.classList.add(verso);
    if (ko) wire.classList.add('ko');
  }
}

/* Aggiorna la card con titolo, dettaglio, SQL e tempo */
function aggiornaCard(p) {
  ddot.className = 'ddot ' + (p.stato === 'errore' ? 'ko' : 'ok');
  dtEl.textContent = p.titolo;
  dmsEl.hidden = p.ms == null;
  dmsEl.textContent = p.ms != null ? p.ms + ' ms' : '';
  ddEl.hidden = !p.dettaglio;
  ddEl.textContent = p.dettaglio || '';
  dsqlEl.hidden = !p.sql;
  dsqlEl.textContent = p.sql || '';
}

/* Mostra il passaggio i (con una piccola transizione della card) */
function mostra(i) {
  if (i < 0 || i >= passi.length) return;
  idx = i;
  const p = passi[i];
  dstepEl.classList.add('swap');
  setTimeout(() => {
    aggiornaCard(p);
    evidenzia(p);
    dstepEl.classList.remove('swap');
  }, 170);
  [...dotsEl.children].forEach((d, j) => {
    d.classList.toggle('on', j === i);
    d.classList.toggle('done', j < i);
  });
  cPrev.disabled = i === 0;
  cNext.disabled = i === passi.length - 1;
  dcount.textContent = (i + 1) + ' / ' + passi.length;
  dcount.classList.add('show');
}

/* Controllerino: pausa per fermarsi a spiegare, poi riprendi */
function riproduci() {
  if (timer || passi.length === 0) return;
  timer = setInterval(() => {
    if (idx < passi.length - 1) mostra(idx + 1);
    else pausa();
  }, 2400);
  icoPlay.style.display = 'none';
  icoPause.style.display = 'block';
}

function pausa() {
  clearInterval(timer);
  timer = null;
  icoPlay.style.display = 'block';
  icoPause.style.display = 'none';
}

cPlay.addEventListener('click', () => {
  if (timer) { pausa(); return; }
  if (idx === passi.length - 1) mostra(0); /* replay dall'inizio */
  riproduci();
});
cPrev.addEventListener('click', () => { pausa(); mostra(idx - 1); });
cNext.addEventListener('click', () => { pausa(); mostra(idx + 1); });

/* Avvia la sequenza con i passaggi appena ricevuti dal backend */
function avviaDemo(nuoviPassi) {
  pausa();
  passi = nuoviPassi;
  dotsEl.innerHTML = '';
  passi.forEach((p, i) => {
    const d = document.createElement('i');
    d.addEventListener('click', () => { pausa(); mostra(i); });
    dotsEl.appendChild(d);
  });
  mostra(0);
  if (passi.length > 1) riproduci();
}

function mostraDemo(json, msTotali, statoHttp) {
  if (!demoMode || !json.passi) return;
  avviaDemo([
    { titolo: 'Il browser invia la richiesta', dettaglio: 'fetch POST → login.php (nome + codice)' },
    ...json.passi,
    {
      titolo: 'Risposta JSON al browser',
      dettaglio: 'HTTP ' + statoHttp + ' · Tempo totale (andata e ritorno): ' + Math.round(msTotali) + ' ms',
      stato: json.ok ? 'ok' : 'errore',
      sql: JSON.stringify({ ok: json.ok, messaggio: json.messaggio || undefined })
    }
  ]);
}

/* Eye toggle */
const eyeOn = document.getElementById('eyeOn');
const eyeOff = document.getElementById('eyeOff');
document.getElementById('eyeBtn').addEventListener('click', () => {
  const show = codeInput.type === 'password';
  codeInput.type = show ? 'text' : 'password';
  eyeOn.style.display = show ? 'none' : 'block';
  eyeOff.style.display = show ? 'block' : 'none';
});
</script>
</body>
</html>
