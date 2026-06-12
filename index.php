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
  width: min(420px, calc(100vw - 32px));
  border-radius: var(--r);
  background: var(--container);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-panel);
  padding: 12px;
}
body.demo .demo-widget {
  display: block;
  animation: demoIn var(--dur) var(--spring) 0.25s both;
}
@keyframes demoIn {
  from { opacity: 0; transform: translateY(14px) scale(0.96); filter: blur(4px); }
  to   { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
}

/* schema: Browser → Server PHP → MySQL */
.schema { display: flex; align-items: center; gap: 10px; padding: 8px 10px 14px; }
.node {
  display: flex; flex-direction: column; align-items: center; gap: 6px;
  color: var(--placeholder);
  transition: color 0.3s ease, transform 0.5s var(--spring);
}
.node .nico {
  width: 46px; height: 46px;
  border-radius: 16px;
  background: var(--surface);
  box-shadow: var(--shadow-sm);
  display: flex; align-items: center; justify-content: center;
  transition: box-shadow 0.3s ease;
}
.node .nico svg { width: 20px; height: 20px; }
.node .nlab { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; }
.node.on, .node.ko { color: var(--text); transform: translateY(-2px); }
.node.on .nico { box-shadow: 0 0 0 1.5px rgba(34, 197, 94, 0.4), 0 0 18px rgba(34, 197, 94, 0.25); }
.node.ko .nico { box-shadow: 0 0 0 1.5px rgba(220, 38, 38, 0.4), 0 0 18px rgba(220, 38, 38, 0.2); }

.wire { position: relative; flex: 1; height: 2px; border-radius: 2px; background: var(--border); }
.wire .pk {
  position: absolute; top: 50%; left: 0;
  width: 8px; height: 8px; border-radius: 50%;
  background: #22C55E;
  box-shadow: 0 0 10px rgba(34, 197, 94, 0.7);
  transform: translate(-50%, -50%);
  opacity: 0;
}
.wire.ko .pk { background: var(--error); box-shadow: 0 0 10px rgba(220, 38, 38, 0.7); }
.wire.fwd .pk { animation: pkFwd 1.2s ease-in-out infinite; }
.wire.rev .pk { animation: pkRev 1.2s ease-in-out infinite; }
@keyframes pkFwd { 0% { left: 0; opacity: 0; } 15% { opacity: 1; } 85% { opacity: 1; } 100% { left: 100%; opacity: 0; } }
@keyframes pkRev { 0% { left: 100%; opacity: 0; } 15% { opacity: 1; } 85% { opacity: 1; } 100% { left: 0; opacity: 0; } }

/* card del passaggio corrente */
.dstep {
  background: var(--surface);
  border-radius: 18px;
  box-shadow: var(--shadow-sm);
  padding: 11px 13px;
  transition: opacity 0.3s var(--spring-soft), transform 0.45s var(--spring), filter 0.3s var(--spring-soft);
}
.dstep.swap { opacity: 0; transform: translateY(6px) scale(0.98); filter: blur(4px); }
.dhead { display: flex; align-items: center; gap: 8px; }
.ddot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--placeholder); flex-shrink: 0;
  transition: background 0.3s ease, box-shadow 0.3s ease;
}
.ddot.ok { background: #22C55E; box-shadow: 0 0 10px rgba(34, 197, 94, 0.6); }
.ddot.ko { background: var(--error); box-shadow: 0 0 10px rgba(220, 38, 38, 0.5); }
.dt { font-size: 13.5px; font-weight: 700; letter-spacing: -0.01em; flex: 1; }
.dms { font-size: 11px; font-weight: 700; color: var(--placeholder); background: var(--container); border-radius: var(--r-full); padding: 2px 8px; white-space: nowrap; }
.dd { font-size: 12.5px; color: var(--placeholder); font-weight: 500; margin-top: 3px; padding-left: 16px; line-height: 1.45; }
#dsql {
  margin: 7px 0 2px 16px;
  background: var(--container);
  border-radius: 12px;
  padding: 9px 11px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  font-size: 11.5px;
  line-height: 1.5;
  color: var(--text);
  white-space: pre-wrap;
  word-break: break-word;
}

/* controllerino: indietro · play/pausa · avanti · puntini */
.ctrl { display: flex; align-items: center; gap: 6px; padding: 10px 4px 2px; }
.cbtn {
  width: 34px; height: 34px;
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
.cbtn.main { width: 40px; height: 40px; }
.cbtn.main svg { width: 15px; height: 15px; }
.dots { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 5px; margin-left: auto; padding-right: 4px; }
.dots i { width: 6px; height: 6px; border-radius: 50%; background: var(--border); cursor: pointer; transition: background 0.3s ease, transform 0.4s var(--spring); }
.dots i.done { background: var(--placeholder); }
.dots i.on { background: #22C55E; transform: scale(1.35); }

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.001s !important; transition-duration: 0.001s !important; }
}
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
        <svg viewBox="0 0 640 512" fill="currentColor"><path d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg>
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
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.34 0-10 1.67-10 5v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2c0-3.33-6.66-5-10-5z"/></svg>
              </span>
              <input id="nameInput" type="text" name="nome" placeholder="Il tuo nome" autocomplete="name">
            </div>
            <div class="input-pill">
              <span class="ico">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-3.1 0H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
              </span>
              <input id="codeInput" type="password" name="codice" placeholder="Codice d'accesso" autocomplete="off">
              <button class="eye" id="eyeBtn" type="button" aria-label="Mostra codice">
                <svg id="eyeOn" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                <svg id="eyeOff" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78 3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
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
            <p>Accesso registrato nel database.</p>
          </div>
        </div>

      </div>
    </div>

  </div>

  <!-- DEMO (?demo=1) — widget schematico integrato sotto il login -->
  <aside class="demo-widget" id="demoWidget" aria-live="polite">

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
    const raw = nameInput.value.trim().split(/\s+/)[0];
    const pretty = raw.charAt(0).toUpperCase() + raw.slice(1);
    document.getElementById('successName').textContent = 'Benvenuto, ' + pretty + '!';

    paneForm.classList.add('is-hidden');
    paneSuccess.classList.remove('is-hidden');
    requestAnimationFrame(setOpenHeight);

    /* niente redirect: in demo si resta qui per spiegare i passaggi col controllerino */

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
