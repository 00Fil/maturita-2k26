<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PCTO — Maturità 2026</title>
<style>
/* ============================================================
   index.php — SCHERMATA DI BLOCCO IN STILE macOS
   Replica fedele del lock screen: wallpaper, orologio, utente,
   campo password vetroso. Il backend resta invariato
   (POST nome + codice → login.php → hub.php).
   ============================================================ */

/* --- Font di sistema Apple (SF Pro Display) --- */
@font-face {
  font-family: "SF Pro Display";
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url("assets/fonts/SFPRODISPLAYREGULAR.OTF") format("opentype");
}
@font-face {
  font-family: "SF Pro Display";
  font-weight: 500;
  font-style: normal;
  font-display: swap;
  src: url("assets/fonts/SFPRODISPLAYMEDIUM.OTF") format("opentype");
}
@font-face {
  font-family: "SF Pro Display";
  font-weight: 700;
  font-style: normal;
  font-display: swap;
  src: url("assets/fonts/SFPRODISPLAYBOLD.OTF") format("opentype");
}

:root {
  --font: "SF Pro Display", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  --spring: cubic-bezier(0.3, 1.3, 0.5, 1);
  --ease: cubic-bezier(0.22, 1, 0.36, 1);
  --ok: #34C759;
  --err: #FF4D4D;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
html, body { height: 100%; }

body {
  font-family: var(--font);
  color: #fff;
  background: #0a1626;
  overflow: hidden;
  -webkit-font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
}
body.demo { overflow-y: auto; }

/* ------------------------------------------------------------
   WALLPAPER + velature per la leggibilità
   ------------------------------------------------------------ */
.wallpaper {
  position: fixed;
  inset: 0;
  background: url("assets/bg.png") center center / cover no-repeat;
  transform: scale(1.04);
  transition: transform 1.1s var(--ease), filter 0.9s var(--ease);
  z-index: 0;
  will-change: transform, filter;
}
/* leggera vignettatura + dim in alto e in basso, come su macOS */
.scrim {
  position: fixed;
  inset: 0;
  z-index: 1;
  pointer-events: none;
  background:
    linear-gradient(180deg, rgba(0,0,0,0.22) 0%, rgba(0,0,0,0) 22%, rgba(0,0,0,0) 64%, rgba(0,0,0,0.28) 100%),
    radial-gradient(120% 80% at 50% 84%, rgba(0,0,0,0.22) 0%, rgba(0,0,0,0) 60%);
}

/* ------------------------------------------------------------
   MENU BAR (sottile, translucida)
   ------------------------------------------------------------ */
.menubar {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 28px;
  z-index: 5;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 16px;
  padding: 0 16px;
  color: rgba(255,255,255,0.95);
  filter: drop-shadow(0 1px 1px rgba(0,0,0,0.25));
}
.menubar .mb-item { display: flex; align-items: center; }
.menubar .lang {
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.01em;
}
.menubar svg { display: block; }

/* ------------------------------------------------------------
   STRUTTURA DEL LOCK SCREEN
   ------------------------------------------------------------ */
.lock {
  position: relative;
  z-index: 3;
  min-height: 100vh;
  min-height: 100dvh;
  transition: opacity 0.7s var(--ease), transform 0.9s var(--ease), filter 0.7s var(--ease);
}

/* Orologio — ancorato in alto, centrato */
.clock {
  position: absolute;
  top: 8.5vh;
  left: 0; right: 0;
  text-align: center;
  user-select: none;
}
.clock .date {
  font-size: clamp(15px, 1.5vw, 19px);
  font-weight: 500;
  letter-spacing: 0.005em;
  text-shadow: 0 1px 4px rgba(0,0,0,0.28);
  margin-bottom: 2px;
  opacity: 0.98;
}
.clock .time {
  font-size: clamp(74px, 11.5vw, 132px);
  font-weight: 500;
  line-height: 1.02;
  letter-spacing: -0.02em;
  font-variant-numeric: tabular-nums;
  text-shadow: 0 2px 16px rgba(0,0,0,0.22);
}

/* Cluster utente — ancorato in basso, centrato */
.user {
  position: absolute;
  left: 0; right: 0;
  bottom: 9.5vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.avatar {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: linear-gradient(180deg, #dcdce1 0%, #a6a6ad 100%);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  overflow: hidden;
  box-shadow:
    0 4px 14px rgba(0,0,0,0.28),
    inset 0 0 0 0.5px rgba(255,255,255,0.55);
  margin-bottom: 12px;
}
.avatar svg { width: 78px; height: 78px; color: #fbfbfd; margin-bottom: -4px; }

#form { display: flex; flex-direction: column; align-items: center; }

.name {
  border: none;
  outline: none;
  background: transparent;
  text-align: center;
  font-family: var(--font);
  font-size: 19px;
  font-weight: 500;
  letter-spacing: 0.005em;
  color: #fff;
  text-shadow: 0 1px 4px rgba(0,0,0,0.32);
  width: 260px;
  height: 26px;
  margin-bottom: 14px;
  caret-color: #fff;
}
.name::placeholder { color: rgba(255,255,255,0.62); font-weight: 500; }

/* riga del campo password: pillola centrata + “?” a destra */
.pwd-wrap {
  position: relative;
  width: 100%;
  display: flex;
  justify-content: center;
}

.pwd {
  position: relative;
  display: flex;
  align-items: center;
  width: 232px;
  height: 34px;
  border-radius: 999px;
  padding: 0 4px 0 4px;
  background: rgba(255,255,255,0.17);
  border: 0.5px solid rgba(255,255,255,0.5);
  box-shadow:
    0 1px 3px rgba(0,0,0,0.16),
    inset 0 0.5px 0 rgba(255,255,255,0.4),
    inset 0 -0.5px 0 rgba(0,0,0,0.06);
  -webkit-backdrop-filter: blur(22px) saturate(150%);
  backdrop-filter: blur(22px) saturate(150%);
  transition: background 0.3s var(--ease), border-color 0.3s var(--ease), box-shadow 0.3s var(--ease);
}
.pwd:focus-within {
  background: rgba(255,255,255,0.24);
  border-color: rgba(255,255,255,0.7);
}
.pwd input {
  flex: 1;
  min-width: 0;
  border: none;
  outline: none;
  background: transparent;
  font-family: var(--font);
  font-size: 13px;
  font-weight: 500;
  color: #fff;
  text-align: center;
  padding: 0 24px;
  letter-spacing: 0.06em;
}
.pwd input::placeholder { color: rgba(255,255,255,0.88); font-weight: 500; letter-spacing: 0.005em; }
.pwd input::-ms-reveal { display: none; }

/* freccia di invio: compare solo quando c’è testo */
.go {
  position: absolute;
  right: 4px;
  top: 50%;
  width: 26px;
  height: 26px;
  border: 0.5px solid rgba(255,255,255,0.45);
  border-radius: 50%;
  background: rgba(255,255,255,0.28);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  opacity: 0;
  transform: translateY(-50%) scale(0.5);
  transition: opacity 0.25s var(--ease), transform 0.4s var(--spring), background 0.2s var(--ease);
  -webkit-backdrop-filter: blur(8px);
  backdrop-filter: blur(8px);
}
.pwd.has-text .go { opacity: 1; transform: translateY(-50%) scale(1); }
.go:hover { background: rgba(255,255,255,0.5); }
.go:active { transform: translateY(-50%) scale(0.9); }
.go svg { width: 14px; height: 14px; }

/* bottone “?” — a destra della pillola, senza spostarla dal centro */
.hint {
  position: absolute;
  top: 50%;
  left: calc(50% + 132px);
  transform: translateY(-50%);
  width: 30px;
  height: 30px;
  border: 0.5px solid rgba(255,255,255,0.42);
  border-radius: 50%;
  background: rgba(255,255,255,0.16);
  color: #fff;
  font-family: var(--font);
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  -webkit-backdrop-filter: blur(22px) saturate(150%);
  backdrop-filter: blur(22px) saturate(150%);
  transition: background 0.2s var(--ease), transform 0.4s var(--spring);
}
.hint:hover { background: rgba(255,255,255,0.3); }
.hint:active { transform: translateY(-50%) scale(0.9); }

/* didascalia / hint / errore */
.caption {
  margin-top: 18px;
  height: 16px;
  font-size: 12.5px;
  font-weight: 400;
  letter-spacing: 0.005em;
  color: rgba(255,255,255,0.82);
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
  text-align: center;
  transition: color 0.25s var(--ease), opacity 0.25s var(--ease);
}
.caption.error { color: #ffd2d2; }

/* bottone “Apri il desktop” (solo modalità demo, dopo l’accesso) */
.enter-desktop {
  margin-top: 16px;
  border: 0.5px solid rgba(255,255,255,0.45);
  border-radius: 999px;
  padding: 9px 20px;
  background: rgba(255,255,255,0.18);
  color: #fff;
  font-family: var(--font);
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  -webkit-backdrop-filter: blur(20px) saturate(150%);
  backdrop-filter: blur(20px) saturate(150%);
  transition: background 0.2s var(--ease), transform 0.4s var(--spring), opacity 0.4s var(--ease);
  animation: rise 0.5s var(--spring) both;
}
.enter-desktop:hover { background: rgba(255,255,255,0.32); }
.enter-desktop:active { transform: scale(0.96); }
@keyframes rise { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

/* shake del campo password — identico a macOS */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  15% { transform: translateX(-10px); }
  30% { transform: translateX(10px); }
  45% { transform: translateX(-8px); }
  60% { transform: translateX(8px); }
  75% { transform: translateX(-4px); }
  90% { transform: translateX(4px); }
}
.pwd.shake { animation: shake 0.5s var(--ease); }

/* entrata morbida del cluster all’avvio */
.user, .clock { animation: fadeUp 0.9s var(--ease) both; }
.clock { animation-delay: 0.05s; }
.user { animation-delay: 0.12s; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* sblocco: dissolvenza del lock + leggero zoom del wallpaper */
body.unlock .lock { opacity: 0; transform: scale(1.04); filter: blur(8px); pointer-events: none; }
body.unlock .wallpaper { transform: scale(1.1); filter: brightness(1.05); }

/* ============================================================
   DEMO (?demo=1) — widget “dietro le quinte” in vetro macOS
   ============================================================ */
.demo-widget {
  display: none;
  position: relative;
  z-index: 4;
  width: min(390px, calc(100vw - 32px));
  margin: 0 auto;
  border-radius: 22px;
  padding: 18px 18px 14px;
  background: rgba(28,28,34,0.46);
  border: 0.5px solid rgba(255,255,255,0.18);
  box-shadow: 0 18px 50px -12px rgba(0,0,0,0.55), inset 0 0.5px 0 rgba(255,255,255,0.16);
  -webkit-backdrop-filter: blur(34px) saturate(160%);
  backdrop-filter: blur(34px) saturate(160%);
  color: #fff;
}
body.demo .demo-widget {
  display: block;
  margin-top: calc(100vh - 250px);
  margin-bottom: 40px;
  animation: rise 0.7s var(--spring) 0.2s both;
}

.demo-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 0 4px;
  margin-bottom: 14px;
}
.demo-eyebrow {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: rgba(255,255,255,0.55);
}
.dcount {
  font-size: 11px;
  font-weight: 600;
  font-variant-numeric: tabular-nums;
  color: rgba(255,255,255,0.7);
  background: rgba(255,255,255,0.12);
  border-radius: 999px;
  padding: 3px 10px;
  opacity: 0;
  transform: scale(0.8);
  transition: opacity 0.3s ease, transform 0.4s var(--spring);
}
.dcount.show { opacity: 1; transform: scale(1); }

.schema { display: flex; align-items: flex-start; gap: 8px; padding: 0 4px; margin-bottom: 16px; }
.node { display: flex; flex-direction: column; align-items: center; gap: 7px; transition: transform 0.5s var(--spring); }
.node .nico {
  width: 48px; height: 48px;
  border-radius: 50%;
  background: rgba(255,255,255,0.1);
  border: 0.5px solid rgba(255,255,255,0.18);
  color: rgba(255,255,255,0.6);
  display: flex; align-items: center; justify-content: center;
  transition: color 0.3s ease, box-shadow 0.35s ease, background 0.3s ease;
}
.node .nico svg { width: 19px; height: 19px; }
.node .nlab {
  font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
  color: rgba(255,255,255,0.55); transition: color 0.3s ease;
}
.node.on, .node.ko { transform: translateY(-2px); }
.node.on .nico, .node.ko .nico { color: #fff; background: rgba(255,255,255,0.16); }
.node.on .nlab, .node.ko .nlab { color: #fff; }
.node.on .nico { box-shadow: 0 0 0 1.5px rgba(52,199,89,0.65), 0 4px 18px -2px rgba(52,199,89,0.45); }
.node.ko .nico { box-shadow: 0 0 0 1.5px rgba(255,77,77,0.6), 0 4px 18px -2px rgba(255,77,77,0.4); }

.wire { position: relative; flex: 1; height: 1.5px; border-radius: 1px; background: rgba(255,255,255,0.16); margin-top: 23px; }
.wire .pk {
  position: absolute; top: 50%; left: 0;
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--ok); box-shadow: 0 0 8px rgba(52,199,89,0.6);
  transform: translate(-50%, -50%); opacity: 0;
}
.wire.ko .pk { background: var(--err); box-shadow: 0 0 8px rgba(255,77,77,0.6); }
.wire.fwd .pk { animation: pkFwd 1.3s var(--ease) infinite; }
.wire.rev .pk { animation: pkRev 1.3s var(--ease) infinite; }
@keyframes pkFwd { 0% { left: 0; opacity: 0; } 18% { opacity: 1; } 82% { opacity: 1; } 100% { left: 100%; opacity: 0; } }
@keyframes pkRev { 0% { left: 100%; opacity: 0; } 18% { opacity: 1; } 82% { opacity: 1; } 100% { left: 0; opacity: 0; } }

.dstep {
  background: rgba(255,255,255,0.08);
  border: 0.5px solid rgba(255,255,255,0.12);
  border-radius: 16px;
  padding: 14px 16px;
  min-height: 78px;
  transition: opacity 0.3s var(--ease), transform 0.45s var(--spring), filter 0.3s var(--ease);
}
.dstep.swap { opacity: 0; transform: translateY(6px) scale(0.98); filter: blur(4px); }
.dhead { display: flex; align-items: center; gap: 8px; }
.ddot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.4); flex-shrink: 0; transition: background 0.3s ease, box-shadow 0.3s ease; }
.ddot.ok { background: var(--ok); box-shadow: 0 0 8px rgba(52,199,89,0.55); }
.ddot.ko { background: var(--err); box-shadow: 0 0 8px rgba(255,77,77,0.5); }
.dt { font-size: 14px; font-weight: 700; letter-spacing: -0.01em; flex: 1; color: #fff; }
.dms {
  font-size: 11px; font-weight: 600; font-variant-numeric: tabular-nums;
  color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.12);
  border-radius: 999px; padding: 3px 9px; white-space: nowrap;
}
.dd { font-size: 12.5px; color: rgba(255,255,255,0.66); font-weight: 400; margin-top: 4px; padding-left: 16px; line-height: 1.5; }
#dsql {
  margin: 8px 0 2px 16px;
  background: rgba(0,0,0,0.28);
  border-radius: 12px;
  padding: 10px 12px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  font-size: 11.5px; line-height: 1.55; color: #eaeaea;
  white-space: pre-wrap; word-break: break-word;
}

.ctrl { display: flex; align-items: center; gap: 7px; padding: 12px 2px 2px; }
.cbtn {
  width: 36px; height: 36px;
  border: 0.5px solid rgba(255,255,255,0.16);
  border-radius: 50%;
  background: rgba(255,255,255,0.1);
  color: #fff;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: transform 0.4s var(--spring), background 0.3s ease, opacity 0.3s ease;
}
.cbtn:hover { background: rgba(255,255,255,0.2); }
.cbtn:active { transform: scale(0.88); }
.cbtn:disabled { opacity: 0.32; cursor: default; }
.cbtn svg { width: 13px; height: 13px; }
.cbtn.main { width: 44px; height: 44px; }
.cbtn.main svg { width: 16px; height: 16px; }
.dots { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 6px; margin-left: auto; padding-right: 4px; }
.dots i { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.2); cursor: pointer; transition: background 0.3s ease, transform 0.4s var(--spring); }
.dots i:hover { background: rgba(255,255,255,0.45); }
.dots i.done { background: rgba(255,255,255,0.5); }
.dots i.on { background: var(--ok); transform: scale(1.4); }

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

<div class="wallpaper"></div>
<div class="scrim"></div>

<!-- MENU BAR -->
<div class="menubar">
  <span class="mb-item lang">IT</span>
  <span class="mb-item" aria-label="Centro di Controllo">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="6.5" width="18" height="4.2" rx="2.1"/><rect x="3" y="13.3" width="18" height="4.2" rx="2.1"/><circle cx="8" cy="8.6" r="1.1" fill="currentColor" stroke="none"/><circle cx="16" cy="15.4" r="1.1" fill="currentColor" stroke="none"/></svg>
  </span>
  <span class="mb-item" aria-label="Wi-Fi">
    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18.5a1.7 1.7 0 1 0 0 .01zM12 14.2c1.27 0 2.45.49 3.34 1.32a.7.7 0 0 0 .98-.02l.86-.87a.7.7 0 0 0-.02-1.01A8.1 8.1 0 0 0 12 11.5a8.1 8.1 0 0 0-5.14 1.84.7.7 0 0 0-.02 1.01l.86.87c.27.27.7.28.98.02A4.86 4.86 0 0 1 12 14.2zM12 7.5c2.93 0 5.62 1.06 7.7 2.82.3.25.74.23 1.01-.05l.85-.86a.7.7 0 0 0-.03-1.02A14.3 14.3 0 0 0 12 4.7 14.3 14.3 0 0 0 2.47 7.4a.7.7 0 0 0-.03 1.02l.85.86c.27.28.71.3 1.01.05A11.83 11.83 0 0 1 12 7.5z"/></svg>
  </span>
  <span class="mb-item" aria-label="Batteria">
    <svg width="28" height="16" viewBox="0 0 28 16" fill="none"><rect x="1" y="3.5" width="22" height="9" rx="2.6" stroke="currentColor" stroke-width="1" opacity="0.5"/><rect x="2.6" y="5.1" width="16" height="5.8" rx="1.4" fill="currentColor"/><path d="M24.6 6.2v3.6c1 -0.28 1.6 -0.92 1.6 -1.8s-0.6 -1.52 -1.6 -1.8z" fill="currentColor" opacity="0.5"/></svg>
  </span>
</div>

<!-- LOCK SCREEN -->
<div class="lock" id="lock">

  <div class="clock">
    <div class="date" id="date">—</div>
    <div class="time" id="time">—</div>
  </div>

  <div class="user">
    <div class="avatar">
      <svg viewBox="0 0 100 100" fill="currentColor" aria-hidden="true"><circle cx="50" cy="37" r="19"/><path d="M50 60c-17.5 0-31.5 11.4-34.4 26.6C20.9 92.5 34.6 96 50 96s29.1-3.5 34.4-9.4C81.5 71.4 67.5 60 50 60z"/></svg>
    </div>

    <form id="form" novalidate>
      <input id="nameInput" class="name" type="text" name="nome" placeholder="Nome" autocomplete="name" spellcheck="false">

      <div class="pwd-wrap">
        <div class="pwd" id="pwd">
          <input id="codeInput" type="password" name="codice" placeholder="Inserisci la password" autocomplete="off">
          <button class="go" id="goBtn" type="submit" aria-label="Accedi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h13M12 6l6 6-6 6"/></svg>
          </button>
        </div>
        <button class="hint" id="hintBtn" type="button" aria-label="Suggerimento">?</button>
      </div>

      <div class="caption" id="caption">Inserisci la password per accedere</div>
      <button class="enter-desktop" id="demoEnter" type="button" hidden>Apri il desktop</button>
    </form>
  </div>

</div>

<!-- DEMO (?demo=1) -->
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
    <div class="dd" id="dd">Compila il form e premi Invio: i passaggi del backend compariranno qui, uno alla volta.</div>
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

<script src="sound.js"></script>
<script>
/* ============================================================
   OROLOGIO LIVE (stile macOS, locale italiano)
   ============================================================ */
const dateEl = document.getElementById('date');
const timeEl = document.getElementById('time');
function tickClock() {
  const now = new Date();
  timeEl.textContent = now.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
  let d = now.toLocaleDateString('it-IT', { weekday: 'long', day: 'numeric', month: 'long' });
  dateEl.textContent = d.charAt(0).toUpperCase() + d.slice(1);
}
tickClock();
setInterval(tickClock, 5000);

/* ============================================================
   RIFERIMENTI
   ============================================================ */
const lock      = document.getElementById('lock');
const form      = document.getElementById('form');
const nameInput = document.getElementById('nameInput');
const codeInput = document.getElementById('codeInput');
const pwd       = document.getElementById('pwd');
const goBtn     = document.getElementById('goBtn');
const hintBtn   = document.getElementById('hintBtn');
const caption   = document.getElementById('caption');
const demoEnter = document.getElementById('demoEnter');

const CAPTION_DEFAULT = 'Inserisci la password per accedere';
let submitted = false;

function setCaption(text, isError) {
  caption.textContent = text;
  caption.classList.toggle('error', !!isError);
}

/* la freccia compare solo con del testo */
codeInput.addEventListener('input', () => {
  pwd.classList.toggle('has-text', codeInput.value.length > 0);
  if (caption.classList.contains('error')) setCaption(CAPTION_DEFAULT, false);
});

/* Invio nel nome → passa alla password */
nameInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') { e.preventDefault(); codeInput.focus(); }
});

/* “?” → suggerimento gentile (mai il codice in chiaro) */
hintBtn.addEventListener('click', () => {
  setCaption("Usa il codice d'accesso del PCTO.", false);
});

function shakeError(messaggio) {
  pwd.classList.remove('shake');
  void pwd.offsetWidth;
  pwd.classList.add('shake');
  if (messaggio) setCaption(messaggio, true);
  codeInput.value = '';
  pwd.classList.remove('has-text');
}

/* ============================================================
   INVIO — chiama il backend PHP (login.php). Contratto invariato.
   ============================================================ */
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  if (submitted) return;

  if (!nameInput.value.trim()) {
    setCaption('Inserisci il tuo nome per continuare.', true);
    nameInput.focus();
    return;
  }
  if (!codeInput.value) { shakeError(); codeInput.focus(); return; }

  goBtn.disabled = true;

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
      goBtn.disabled = false;
      return;
    }

    /* accesso riuscito */
    submitted = true;
    sndGo();
    const raw = nameInput.value.trim().split(/\s+/)[0];
    const pretty = raw.charAt(0).toUpperCase() + raw.slice(1);

    if (demoMode) {
      /* in demo si resta sulla pagina per spiegare i passaggi col controllerino */
      setCaption('Accesso riuscito, ' + pretty + ' · i passaggi sono qui sotto.', false);
      goBtn.disabled = false;
      demoEnter.hidden = false;
    } else {
      setCaption('Accesso riuscito · apro il desktop…', false);
      document.body.classList.add('unlock');
      setTimeout(() => window.location.replace('hub.php'), 720);
    }
  } catch (err) {
    if (demoMode) avviaDemo([
      { titolo: 'Il browser invia la richiesta', dettaglio: 'fetch POST → login.php (nome + codice)' },
      { titolo: 'Nessuna risposta dal server', dettaglio: String(err), stato: 'errore' }
    ]);
    shakeError('Server non raggiungibile.');
    goBtn.disabled = false;
  }
});

demoEnter.addEventListener('click', () => {
  document.body.classList.add('unlock');
  setTimeout(() => window.location.replace('hub.php'), 720);
});

/* focus iniziale sul nome */
window.addEventListener('load', () => {
  setTimeout(() => nameInput.focus({ preventScroll: true }), 500);
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

let passi = [];
let idx = -1;
let timer = null;

function zonaDi(p) {
  const t = (p.titolo || '').toLowerCase();
  if (t.includes('browser invia')) return 'cs';
  if (t.includes('risposta'))      return 'sc';
  if (t.includes('mysql') || t.includes('connessione')) return 'sd';
  if (t.includes('query') || t.includes('lettura'))     return 'db';
  return 'server';
}

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
  if (idx === passi.length - 1) mostra(0);
  riproduci();
});
cPrev.addEventListener('click', () => { pausa(); mostra(idx - 1); });
cNext.addEventListener('click', () => { pausa(); mostra(idx + 1); });

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
</script>
</body>
</html>
