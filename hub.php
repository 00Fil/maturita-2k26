<?php
/* ============================================================
   hub.php — Il desktop della presentazione
   - protetto dalla SESSIONE: senza login si torna a index.php
   - stesso design system del login: palette neutra, radius 32,
     spring 240/18/1.1, aurora bg, micro-interazioni
   - simulazione di desktop macOS: menu bar, finestre, dock
   - la finestra "Presentazione" è già aperta all'arrivo
   ============================================================ */
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

$nome = htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Desktop · PCTO Maturità 2026</title>
<style>
/* ============================================================
   STILE — stesso design system del login:
   palette neutra, radius 32, spring 240/18/1.1, aurora bg
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
  --ok: #22C55E;
  --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
  --shadow-panel: 0 20px 25px -5px rgba(0,0,0,0.10), 0 8px 10px -6px rgba(0,0,0,0.06);
  --r: 32px;
  --r-full: 999px;
  --spring: cubic-bezier(0.3, 1.36, 0.46, 1);
  --spring-soft: cubic-bezier(0.22, 1, 0.36, 1);
  --dur: 0.8s;

  --aur-1: rgba(186,190,255,0.55);
  --aur-2: rgba(255,214,222,0.5);
  --aur-3: rgba(199,235,224,0.5);
  --aur-4: rgba(255,236,200,0.45);

  --glass: rgba(254,254,254,0.74);
  --glass-bar: rgba(250,250,250,0.62);

  /* tinte pastello coerenti con l'aurora */
  --t-lav: #EDEEFF;   --t-lav-d: #5B5FD6;
  --t-rose: #FFEAEF;  --t-rose-d: #D6566F;
  --t-mint: #E3F6EE;  --t-mint-d: #2E9E76;
  --t-peach: #FFF1DE; --t-peach-d: #C77F1F;
  --t-sky: #E4F2FD;   --t-sky-d: #2A7FC4;
  --t-lilac: #F6EAFB; --t-lilac-d: #9A4FBE;
  --t-lemon: #FBF6DE; --t-lemon-d: #9C861B;
}
@media (prefers-color-scheme: dark) {
  :root {
    --scene: #0A0A0A;
    --container: #1C1C1E;
    --container-hover: #252529;
    --surface: #2C2C2E;
    --surface-hover: #3A3A3C;
    --border: rgba(255,255,255,0.07);
    --text: #fefefe;
    --text-strong: #fefefe;
    --placeholder: #9b9ba3;
    --error: #F87171;
    --shadow-sm: 0 10px 15px -3px rgba(0,0,0,0.35);
    --shadow-panel: 0 24px 40px -8px rgba(0,0,0,0.55);
    --aur-1: rgba(99,102,241,0.16);
    --aur-2: rgba(236,121,160,0.10);
    --aur-3: rgba(45,212,191,0.10);
    --aur-4: rgba(250,204,21,0.07);
    --glass: rgba(34,34,36,0.74);
    --glass-bar: rgba(20,20,22,0.58);
    --t-lav: #232440;   --t-lav-d: #A5A8FF;
    --t-rose: #3a2229;  --t-rose-d: #FF9FB4;
    --t-mint: #1d322a;  --t-mint-d: #7FE0BC;
    --t-peach: #382c1b; --t-peach-d: #FFC97E;
    --t-sky: #1d2c3a;   --t-sky-d: #8CC8F5;
    --t-lilac: #321f3c; --t-lilac-d: #DCA8F0;
    --t-lemon: #33301a; --t-lemon-d: #E8D36C;
  }
}
* { margin: 0; padding: 0; box-sizing: border-box; }
html, body { height: 100%; }
body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  background: var(--scene);
  color: var(--text);
  overflow: hidden;
  -webkit-font-smoothing: antialiased;
  user-select: none;
}
svg { display: block; }

/* ------------------------------------------------------------
   SFONDO — la stessa aurora del login, come wallpaper
   ------------------------------------------------------------ */
.bg { position: fixed; inset: 0; overflow: hidden; }
.bg .blob { position: absolute; border-radius: 50%; filter: blur(110px); will-change: transform; }
.bg .b1 { width: 60vmax; height: 60vmax; top: -25vmax; left: -15vmax; background: radial-gradient(circle, var(--aur-1), transparent 65%); animation: drift1 26s ease-in-out infinite alternate; }
.bg .b2 { width: 55vmax; height: 55vmax; bottom: -28vmax; right: -18vmax; background: radial-gradient(circle, var(--aur-2), transparent 65%); animation: drift2 32s ease-in-out infinite alternate; }
.bg .b3 { width: 45vmax; height: 45vmax; top: 45%; left: -20vmax; background: radial-gradient(circle, var(--aur-3), transparent 65%); animation: drift3 38s ease-in-out infinite alternate; }
.bg .b4 { width: 40vmax; height: 40vmax; top: -18vmax; right: -10vmax; background: radial-gradient(circle, var(--aur-4), transparent 65%); animation: drift4 30s ease-in-out infinite alternate; }
@keyframes drift1 { from { transform: translate(0,0) scale(1); }    to { transform: translate(8vmax, 6vmax) scale(1.12); } }
@keyframes drift2 { from { transform: translate(0,0) scale(1.08); } to { transform: translate(-7vmax, -5vmax) scale(1); } }
@keyframes drift3 { from { transform: translate(0,0) scale(1); }    to { transform: translate(10vmax, -6vmax) scale(1.15); } }
@keyframes drift4 { from { transform: translate(0,0) scale(1.1); }  to { transform: translate(-6vmax, 7vmax) scale(1); } }
.bg .wash { position: absolute; inset: 0; background: radial-gradient(ellipse 90% 70% at 50% 50%, transparent 30%, var(--scene) 100%); }

/* ------------------------------------------------------------
   MENU BAR
   ------------------------------------------------------------ */
.menubar {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 34px;
  z-index: 1000;
  display: flex;
  align-items: center;
  gap: 2px;
  padding: 0 12px;
  background: var(--glass-bar);
  backdrop-filter: blur(24px) saturate(1.4);
  -webkit-backdrop-filter: blur(24px) saturate(1.4);
  border-bottom: 1.1px solid var(--border);
  font-size: 13px;
  font-weight: 600;
  animation: barIn var(--dur) var(--spring) both;
}
@keyframes barIn { from { transform: translateY(-100%); } to { transform: translateY(0); } }
.menubar .logo { display: flex; color: var(--text-strong); padding: 0 6px; }
.menubar .logo svg { width: 17px; height: 17px; }
.menubar .appname { font-weight: 800; letter-spacing: -0.02em; padding: 3px 8px; }
.menubar .mitem {
  display: flex; align-items: center; gap: 6px;
  padding: 4px 10px;
  border-radius: 7px;
  color: var(--text);
  cursor: pointer;
  text-decoration: none;
  transition: background .25s ease, transform .4s var(--spring);
}
.menubar .mitem:hover { background: var(--container-hover); }
.menubar .mitem:active { transform: scale(.95); }
.menubar .right { margin-left: auto; display: flex; align-items: center; gap: 6px; }
.menubar .who {
  display: flex; align-items: center; gap: 7px;
  font-weight: 700;
  padding: 4px 10px;
}
.menubar .who .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--ok); box-shadow: 0 0 8px rgba(34,197,94,.6); }
.menubar .clock { font-variant-numeric: tabular-nums; font-weight: 600; opacity: .85; white-space: nowrap; padding: 0 6px; }
.menubar .exit svg { width: 14px; height: 14px; }

/* ------------------------------------------------------------
   ICONE SUL DESKTOP
   ------------------------------------------------------------ */
.deskicons {
  position: fixed;
  top: 56px; right: 16px;
  z-index: 1;
  display: flex; flex-direction: column; gap: 8px;
}
.dicon {
  width: 96px;
  display: flex; flex-direction: column; align-items: center; gap: 7px;
  padding: 12px 6px 10px;
  border-radius: 18px;
  cursor: pointer;
  transition: background .25s ease, transform .4s var(--spring);
}
.dicon:hover { background: rgba(127,127,127,0.10); }
.dicon:active { transform: scale(.92); }
.dicon .fico {
  width: 50px; height: 50px;
  border-radius: 14px;
  background: var(--surface);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-sm);
  display: flex; align-items: center; justify-content: center;
  color: var(--placeholder);
}
.dicon .fico svg { width: 22px; height: 22px; }
.dicon span:last-child { font-size: 11px; font-weight: 650; text-align: center; line-height: 1.25; text-shadow: 0 1px 2px var(--scene); }

/* ------------------------------------------------------------
   FINESTRE
   ------------------------------------------------------------ */
.win {
  position: fixed;
  z-index: 10;
  display: none;
  flex-direction: column;
  border-radius: 22px;
  background: var(--glass);
  backdrop-filter: blur(28px) saturate(1.4);
  -webkit-backdrop-filter: blur(28px) saturate(1.4);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-panel);
  overflow: hidden;
  min-width: 320px;
}
.win.open { display: flex; animation: winIn .65s var(--spring) both; }
.win.closing { animation: winOut .3s var(--spring-soft) both; }
@keyframes winIn  { from { opacity: 0; transform: scale(.86) translateY(20px); filter: blur(6px); } to { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); } }
@keyframes winOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(.88) translateY(14px); filter: blur(6px); } }
.win.maxi { left: 10px !important; top: 44px !important; width: calc(100vw - 20px) !important; height: calc(100vh - 128px) !important; }

.titlebar {
  display: flex;
  align-items: center;
  gap: 8px;
  height: 46px;
  padding: 0 14px;
  flex-shrink: 0;
  cursor: grab;
  border-bottom: 1.1px solid var(--border);
  touch-action: none;
}
.titlebar:active { cursor: grabbing; }
.lights { display: flex; gap: 7px; }
.lights button {
  width: 13px; height: 13px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: rgba(0,0,0,.45);
  transition: transform .35s var(--spring), filter .2s ease;
}
.lights button svg { width: 7px; height: 7px; opacity: 0; transition: opacity .15s ease; }
.titlebar:hover .lights button svg { opacity: 1; }
.lights button:hover { filter: brightness(.92); transform: scale(1.12); }
.lights button:active { transform: scale(.9); }
.lights .c-close { background: #FF5F57; }
.lights .c-min   { background: #FEBC2E; }
.lights .c-max   { background: #28C840; }
.titlebar .wt {
  flex: 1;
  text-align: center;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: -0.01em;
  color: var(--placeholder);
  margin-right: 49px; /* compensa i semafori per centrare il titolo */
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.wbody { flex: 1; overflow-y: auto; padding: 22px 24px 24px; }
.wbody::-webkit-scrollbar { width: 8px; }
.wbody::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

/* contenuti comuni — stessa lingua delle pillole del login */
.eyebrow {
  display: flex; align-items: center; gap: 7px;
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.12em;
  color: var(--placeholder);
}
.eyebrow svg { width: 13px; height: 13px; }
.wbody h2 { font-size: 22px; font-weight: 800; letter-spacing: -0.03em; margin: 6px 0 4px; }
.wbody p.lead { font-size: 13.5px; line-height: 1.6; color: var(--placeholder); font-weight: 500; }
.rows { display: flex; flex-direction: column; gap: 8px; margin-top: 14px; }
.row {
  display: flex; align-items: flex-start; gap: 12px;
  background: var(--surface);
  border: 1.1px solid var(--border);
  border-radius: 18px;
  box-shadow: var(--shadow-sm);
  padding: 13px 15px;
  transition: background .3s ease;
}
.row:hover { background: var(--surface-hover); }
.row .rico {
  width: 38px; height: 38px; flex-shrink: 0;
  border-radius: var(--r-full);
  display: flex; align-items: center; justify-content: center;
  background: var(--container);
  color: var(--placeholder);
  font-size: 13px; font-weight: 800;
  transition: color .3s ease;
}
.row:hover .rico { color: var(--text-strong); }
.row .rico svg { width: 17px; height: 17px; }
.row b { font-size: 13.5px; font-weight: 750; letter-spacing: -0.01em; display: block; }
.row span.sub { font-size: 12.5px; color: var(--placeholder); font-weight: 500; line-height: 1.5; }
.chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; }
.chip {
  font-size: 12px; font-weight: 700;
  padding: 7px 13px;
  border-radius: var(--r-full);
  background: var(--surface);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-sm);
}
.pillbtn {
  display: inline-flex; align-items: center; gap: 9px;
  height: 46px; padding: 0 20px;
  margin: 14px 8px 0 0;
  border-radius: 23px;
  background: var(--surface);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-sm);
  font-weight: 750; font-size: 13.5px;
  color: var(--text);
  text-decoration: none;
  cursor: pointer;
  transition: transform .4s var(--spring), background .3s ease;
}
.pillbtn:hover { background: var(--surface-hover); transform: translateY(-2px); }
.pillbtn:active { transform: scale(.96); }
.pillbtn svg { width: 15px; height: 15px; }

/* ------------------------------------------------------------
   FINESTRA PRESENTAZIONE — stile app di supporto
   ------------------------------------------------------------ */
#w-pres .wbody { padding: 0; }
.pres-hero { text-align: center; padding: 36px 30px 24px; }
.pres-hero .pava {
  width: 74px; height: 74px;
  margin: 0 auto 14px;
  border-radius: var(--r-full);
  background: var(--container);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-sm);
  display: flex; align-items: center; justify-content: center;
  font-size: 25px; font-weight: 800; letter-spacing: -0.02em;
  animation: pop var(--dur) var(--spring) .2s both;
}
@keyframes pop { from { opacity: 0; transform: scale(0); filter: blur(4px); } to { opacity: 1; transform: scale(1); filter: blur(0); } }
.pres-hero h1 { font-size: clamp(23px, 3vw, 30px); font-weight: 800; letter-spacing: -0.035em; }
.pres-hero h1 em { font-style: normal; background: linear-gradient(90deg, var(--t-lav-d), var(--t-rose-d), var(--t-peach-d)); -webkit-background-clip: text; background-clip: text; color: transparent; }
.pres-hero p { margin: 8px auto 0; max-width: 54ch; font-size: 13.5px; line-height: 1.6; color: var(--placeholder); font-weight: 500; }
.pres-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(166px, 1fr));
  gap: 12px;
  padding: 4px 26px 26px;
}
.pcard {
  border: 1.1px solid var(--border);
  border-radius: 20px;
  box-shadow: var(--shadow-sm);
  padding: 16px;
  min-height: 122px;
  display: flex; flex-direction: column; justify-content: flex-end; gap: 3px;
  cursor: pointer;
  transition: transform .5s var(--spring), box-shadow .3s ease;
  animation: tileIn .7s var(--spring) both;
}
@keyframes tileIn { from { opacity: 0; transform: translateY(14px) scale(.95); filter: blur(4px); } to { opacity: 1; transform: none; filter: blur(0); } }
.pcard:hover { transform: translateY(-4px) scale(1.02); box-shadow: var(--shadow-panel); }
.pcard:active { transform: scale(.97); }
.pcard .pe { margin-bottom: auto; }
.pcard .pe svg { width: 25px; height: 25px; }
.pcard b { font-size: 14px; font-weight: 800; letter-spacing: -0.015em; }
.pcard span.sub { font-size: 11.5px; color: var(--placeholder); font-weight: 550; line-height: 1.35; }
.pcard.lav   { background: var(--t-lav); }   .pcard.lav .pe   { color: var(--t-lav-d); }
.pcard.rose  { background: var(--t-rose); }  .pcard.rose .pe  { color: var(--t-rose-d); }
.pcard.mint  { background: var(--t-mint); }  .pcard.mint .pe  { color: var(--t-mint-d); }
.pcard.peach { background: var(--t-peach); } .pcard.peach .pe { color: var(--t-peach-d); }
.pcard.sky   { background: var(--t-sky); }   .pcard.sky .pe   { color: var(--t-sky-d); }
.pcard.lilac { background: var(--t-lilac); } .pcard.lilac .pe { color: var(--t-lilac-d); }
.pcard.lemon { background: var(--t-lemon); } .pcard.lemon .pe { color: var(--t-lemon-d); }

/* ------------------------------------------------------------
   DOCK
   ------------------------------------------------------------ */
.dock {
  position: fixed;
  bottom: 14px; left: 50%;
  transform: translateX(-50%);
  z-index: 900;
  display: flex; align-items: flex-end; gap: 7px;
  padding: 9px 11px;
  border-radius: 26px;
  background: var(--glass-bar);
  backdrop-filter: blur(24px) saturate(1.4);
  -webkit-backdrop-filter: blur(24px) saturate(1.4);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-panel);
  animation: dockIn var(--dur) var(--spring) .15s both;
}
@keyframes dockIn { from { transform: translate(-50%, 130%); } to { transform: translate(-50%, 0); } }
.dock .sep { width: 1.1px; align-self: stretch; margin: 4px 3px; background: var(--border); }
.dapp { position: relative; display: flex; flex-direction: column; align-items: center; }
.dapp > button, .dapp > a {
  width: 50px; height: 50px;
  border: 1.1px solid var(--border);
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  box-shadow: var(--shadow-sm);
  text-decoration: none;
  background: var(--surface);
  color: var(--text-strong);
  transition: transform .45s var(--spring), box-shadow .3s ease;
}
.dapp > button svg, .dapp > a svg { width: 22px; height: 22px; }
.dapp > button:hover, .dapp > a:hover { transform: translateY(-10px) scale(1.16); box-shadow: var(--shadow-panel); }
.dapp > button:active, .dapp > a:active { transform: translateY(-4px) scale(1.0); }
.dapp .dot {
  position: absolute; bottom: -6px;
  width: 4px; height: 4px; border-radius: 50%;
  background: var(--placeholder);
  opacity: 0;
  transition: opacity .3s ease;
}
.dapp.running .dot { opacity: 1; }
.dapp .tip {
  position: absolute; bottom: 64px;
  padding: 5px 12px;
  border-radius: var(--r-full);
  background: var(--container);
  border: 1.1px solid var(--border);
  box-shadow: var(--shadow-sm);
  font-size: 11.5px; font-weight: 700;
  white-space: nowrap;
  opacity: 0;
  transform: translateY(4px) scale(.9);
  pointer-events: none;
  transition: opacity .25s ease, transform .4s var(--spring);
}
.dapp:hover .tip { opacity: 1; transform: translateY(0) scale(1); }
.dapp .lav   { background: var(--t-lav);   color: var(--t-lav-d); }
.dapp .rose  { background: var(--t-rose);  color: var(--t-rose-d); }
.dapp .mint  { background: var(--t-mint);  color: var(--t-mint-d); }
.dapp .peach { background: var(--t-peach); color: var(--t-peach-d); }
.dapp .sky   { background: var(--t-sky);   color: var(--t-sky-d); }
.dapp .lilac { background: var(--t-lilac); color: var(--t-lilac-d); }
.dapp .lemon { background: var(--t-lemon); color: var(--t-lemon-d); }

@media (max-width: 700px) {
  .menubar .mitem.hideS, .menubar .who span.txt { display: none; }
  .deskicons { display: none; }
  .win { left: 3vw !important; top: 44px !important; width: 94vw !important; height: calc(100vh - 140px) !important; }
}
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

<!-- ============ MENU BAR ============ -->
<header class="menubar">
  <span class="logo">
    <svg viewBox="0 0 640 512" fill="currentColor"><path d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg>
  </span>
  <span class="appname">Maturità 2026</span>
  <span class="mitem hideS" data-open="w-pres">Presentazione</span>
  <span class="mitem hideS" data-open="w-esp">Percorso</span>
  <span class="mitem hideS" data-open="w-prog">Progetto</span>
  <a class="mitem hideS" href="schema.php">Dietro le quinte</a>
  <div class="right">
    <span class="who"><span class="dot"></span><span class="txt"><?= $nome ?></span></span>
    <span class="clock" id="clock"></span>
    <a class="mitem exit" href="logout.php" title="Esci">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
      Esci
    </a>
  </div>
</header>

<!-- ============ ICONE SUL DESKTOP ============ -->
<div class="deskicons">
  <div class="dicon" data-open="w-esp">
    <span class="fico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8M16 17H8M10 9H8"/></svg></span>
    <span>Diario di bordo.pdf</span>
  </div>
  <div class="dicon" data-open="w-chi">
    <span class="fico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><circle cx="12" cy="13" r="2"/><path d="M8 19a4 4 0 0 1 8 0"/></svg></span>
    <span>Curriculum dello studente.pdf</span>
  </div>
</div>

<!-- ============ FINESTRA: PRESENTAZIONE (già aperta) ============ -->
<section class="win open" id="w-pres" style="left: max(2vw, calc(50vw - 390px)); top: 68px; width: min(780px, 96vw); height: min(620px, calc(100vh - 158px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close" aria-label="Chiudi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min" aria-label="Riduci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max" aria-label="Ingrandisci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">Presentazione — PCTO · Maturità 2026</span>
  </div>
  <div class="wbody">
    <div class="pres-hero">
      <div class="pava">FC</div>
      <h1>Ciao <?= $nome ?>, io sono <em>Filippo</em>.</h1>
      <p>Benvenuto nel mio desktop. Qui c'è tutto il mio percorso di Formazione Scuola-Lavoro: l'azienda, le 240 ore di esperienza, le competenze che ho costruito e il progetto che stai usando in questo momento. Apri una sezione per cominciare.</p>
    </div>
    <div class="pres-grid">
      <article class="pcard lav" data-open="w-chi" style="animation-delay:.05s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.34 0-10 1.67-10 5v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2c0-3.33-6.66-5-10-5z"/></svg></span>
        <b>Chi sono</b><span class="sub">5ªI Informatica · ITIS Cerebotani</span>
      </article>
      <article class="pcard rose" data-open="w-az" style="animation-delay:.1s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1M12 18h1M7 18h1"/></svg></span>
        <b>L'azienda</b><span class="sub">CS Metal Europe · Bedizzole</span>
      </article>
      <article class="pcard mint" data-open="w-esp" style="animation-delay:.15s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>
        <b>L'esperienza</b><span class="sub">240 ore in due anni</span>
      </article>
      <article class="pcard peach" data-open="w-comp" style="animation-delay:.2s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></span>
        <b>Le competenze</b><span class="sub">Strumenti e soft skill</span>
      </article>
      <article class="pcard sky" data-open="w-prog" style="animation-delay:.25s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-3.1 0H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg></span>
        <b>Il progetto</b><span class="sub">Questo sito, dietro le quinte</span>
      </article>
      <article class="pcard lilac" data-open="w-coll" style="animation-delay:.3s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></span>
        <b>I collegamenti</b><span class="sub">Le materie d'esame</span>
      </article>
      <article class="pcard lemon" data-open="w-extra" style="animation-delay:.35s">
        <span class="pe"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg></span>
        <b>Oltre la scuola</b><span class="sub">Progetti e volontariato</span>
      </article>
    </div>
  </div>
</section>

<!-- ============ FINESTRA: CHI SONO ============ -->
<section class="win" id="w-chi" style="left: 6vw; top: 88px; width: min(480px, 94vw); height: min(540px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">Chi sono</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.34 0-10 1.67-10 5v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2c0-3.33-6.66-5-10-5z"/></svg>Profilo</span>
    <h2>Filippo Corsini</h2>
    <p class="lead">Studente di 5ªI — Informatica e Telecomunicazioni, ITIS "Luigi Cerebotani" di Lonato del Garda (BS).</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg viewBox="0 0 640 512" fill="currentColor"><path d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg></span><div><b>Indirizzo di studi</b><span class="sub">Tecnico — Informatica e Telecomunicazioni, articolazione Informatica.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span><div><b>Credito scolastico: 29 punti</b><span class="sub">9 in terza · 9 in quarta · 11 in quinta.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></span><div><b>Cosa mi piace</b><span class="sub">Sviluppo web e gestionali: realizzo progetti veri per persone vere, dentro e fuori la scuola.</span></div></div>
    </div>
  </div>
</section>

<!-- ============ FINESTRA: AZIENDA ============ -->
<section class="win" id="w-az" style="left: 12vw; top: 108px; width: min(500px, 94vw); height: min(540px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">L'azienda — CS Metal Europe S.r.l.</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1M12 18h1M7 18h1"/></svg>Dove ho lavorato</span>
    <h2>CS Metal Europe S.r.l.</h2>
    <p class="lead">Via Benaco 86, Bedizzole (BS) · servizi di taglio su misura di acciai speciali, rivenditore esclusivo Proterial per l'Italia.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.4 2.4 0 0 1 0-3.4l2.6-2.6a2.4 2.4 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2M11.5 9.5l2-2M8.5 6.5l2-2M17.5 15.5l2-2"/></svg></span><div><b>La struttura</b><span class="sub">7.500 m² tra magazzino con macchine di taglio ad alta precisione e uffici di nuova generazione.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span><div><b>Il team</b><span class="sub">12 dipendenti: una realtà piccola dove si vede tutto il flusso, dall'ordine alla consegna.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg></span><div><b>Il gestionale</b><span class="sub">Embyon (TeamSystem): ordini, magazzino e amministrazione collegati in un unico sistema.</span></div></div>
    </div>
  </div>
</section>

<!-- ============ FINESTRA: ESPERIENZA ============ -->
<section class="win" id="w-esp" style="left: 18vw; top: 78px; width: min(540px, 94vw); height: min(580px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">L'esperienza — 240 ore</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>Formazione Scuola-Lavoro</span>
    <h2>240 ore in due anni</h2>
    <p class="lead">Ufficio commerciale e logistico, affiancato dalla tutor aziendale Delia Pea.</p>
    <div class="rows">
      <div class="row"><span class="rico">3ª</span><div><b>Terza · a.s. 2023/24 · 120 ore</b><span class="sub">Fogli di calcolo per l'analisi dei dati, contenuti promozionali con Photoshop e Illustrator, invito per un evento aziendale, sistemazione del sito WordPress e nuova sezione blog, osservazione del gestionale Embyon.</span></div></div>
      <div class="row"><span class="rico">4ª</span><div><b>Quarta · a.s. 2024/25 · 120 ore</b><span class="sub">Ritorno in azienda con più autonomia sugli strumenti già conosciuti.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span><div><b>Cosa mi porto a casa</b><span class="sub">Come funziona davvero un'azienda: il rapporto col cliente, le scadenze, i dati che diventano decisioni.</span></div></div>
    </div>
  </div>
</section>

<!-- ============ FINESTRA: COMPETENZE ============ -->
<section class="win" id="w-comp" style="left: 24vw; top: 118px; width: min(490px, 94vw); height: min(560px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">Le competenze</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>Cosa ho imparato</span>
    <h2>Strumenti e soft skill</h2>
    <p class="lead">Tecnologie usate in azienda, ogni giorno, su attività reali.</p>
    <div class="chips">
      <span class="chip">Excel</span><span class="chip">Photoshop</span><span class="chip">Illustrator</span>
      <span class="chip">WordPress</span><span class="chip">HTML / CSS</span><span class="chip">Embyon</span>
    </div>
    <div class="rows">
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span><div><b>Lavoro in team</b><span class="sub">Coordinarsi con colleghi e tutor su attività con scadenze vere.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span><div><b>Rapporto col cliente</b><span class="sub">Capire le esigenze e tradurle in contenuti e materiali concreti.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg></span><div><b>Gestione dei dati</b><span class="sub">Grandi quantità di dati trattate con ordine, precisione e metodo.</span></div></div>
    </div>
  </div>
</section>

<!-- ============ FINESTRA: PROGETTO ============ -->
<section class="win" id="w-prog" style="left: 30vw; top: 92px; width: min(520px, 94vw); height: min(580px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">Il progetto — dietro le quinte</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-3.1 0H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>Questo sito</span>
    <h2>Dal browser al database</h2>
    <p class="lead">Il sito che stai usando è parte del progetto: login animato, backend PHP 8 e MySQL, containerizzato con Docker.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span><div><b>Richiesta</b><span class="sub">Il form invia nome e codice con fetch POST a login.php: la pagina non si ricarica mai.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg></span><div><b>Sicurezza</b><span class="sub">password_verify() su hash bcrypt, sessione rigenerata con session_regenerate_id(), prepared statement contro la SQL injection.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg></span><div><b>Persistenza</b><span class="sub">Ogni accesso registrato su MySQL via PDO, con gestione delle eccezioni e codici HTTP corretti.</span></div></div>
    </div>
    <a class="pillbtn" href="schema.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01M7 17.5h.01"/></svg>
      Dietro le quinte
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
  </div>
</section>

<!-- ============ FINESTRA: COLLEGAMENTI ============ -->
<section class="win" id="w-coll" style="left: 36vw; top: 128px; width: min(500px, 94vw); height: min(560px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">I collegamenti — materie d'esame</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>Verso il colloquio</span>
    <h2>Dall'esperienza alle materie</h2>
    <p class="lead">Cosa lega il percorso PCTO alle discipline dell'orale.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.59 13.51 6.83 3.98M15.41 6.51l-6.82 3.98"/></svg></span><div><b>Sistemi e Reti</b><span class="sub">Client-server, HTTP, sessioni e sicurezza: il cuore del progetto.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg></span><div><b>Informatica</b><span class="sub">Basi di dati relazionali, SQL e il gestionale aziendale Embyon.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg></span><div><b>Inglese</b><span class="sub">Lessico tecnico e documentazione degli strumenti professionali.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span><div><b>Italiano</b><span class="sub">Comunicazione efficace: dal blog aziendale all'esposizione orale.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/></svg></span><div><b>Educazione civica</b><span class="sub">Il lavoro nella Costituzione: diritti, doveri e sicurezza in azienda.</span></div></div>
    </div>
  </div>
</section>

<!-- ============ FINESTRA: OLTRE LA SCUOLA ============ -->
<section class="win" id="w-extra" style="left: 42vw; top: 84px; width: min(540px, 94vw); height: min(600px, calc(100vh - 168px));">
  <div class="titlebar">
    <div class="lights">
      <button class="c-close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      <button class="c-min"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
      <button class="c-max"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
    </div>
    <span class="wt">Oltre la scuola</span>
  </div>
  <div class="wbody">
    <span class="eyebrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>Dal Curriculum dello studente</span>
    <h2>Progetti e volontariato</h2>
    <p class="lead">Quello che faccio fuori dall'aula — e che racconta chi sono quanto i voti.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></span><div><b>Gestionale per l'oratorio di Bedizzole · 2024–2026</b><span class="sub">Sviluppo autonomo di uno pseudo-gestionale usato davvero dall'oratorio: il mio progetto più grande.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg></span><div><b>Animatore ed educatore · dal 2023</b><span class="sub">Attività educative e ricreative per bambini e ragazzi, in corso.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg></span><div><b>Volontariato · 2022–2026</b><span class="sub">Festa del Sorriso e Torneo dei Roncai: organizzazione e supporto eventi.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg></span><div><b>Violoncello · 2018–2024</b><span class="sub">Corso alla scuola di musica "Elia Marini" di Calcinato.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg></span><div><b>Mostra "L'ultimo inverno" · MuSa, Salò · 2026</b><span class="sub">Partecipazione al progetto espositivo del 17 gennaio 2026.</span></div></div>
      <div class="row"><span class="rico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></span><div><b>Concorso "Volo tra le Righe 3.0" · 2025</b><span class="sub">Partecipazione al concorso letterario nazionale.</span></div></div>
    </div>
  </div>
</section>

<!-- ============ DOCK ============ -->
<nav class="dock" id="dock">
  <div class="dapp" data-w="w-pres">
    <button><svg viewBox="0 0 640 512" fill="currentColor"><path d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg></button>
    <span class="tip">Presentazione</span><span class="dot"></span>
  </div>
  <div class="sep"></div>
  <div class="dapp" data-w="w-chi">
    <button class="lav"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.34 0-10 1.67-10 5v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2c0-3.33-6.66-5-10-5z"/></svg></button>
    <span class="tip">Chi sono</span><span class="dot"></span>
  </div>
  <div class="dapp" data-w="w-az">
    <button class="rose"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1M12 18h1M7 18h1"/></svg></button>
    <span class="tip">L'azienda</span><span class="dot"></span>
  </div>
  <div class="dapp" data-w="w-esp">
    <button class="mint"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></button>
    <span class="tip">L'esperienza</span><span class="dot"></span>
  </div>
  <div class="dapp" data-w="w-comp">
    <button class="peach"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></button>
    <span class="tip">Le competenze</span><span class="dot"></span>
  </div>
  <div class="dapp" data-w="w-prog">
    <button class="sky"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-3.1 0H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg></button>
    <span class="tip">Il progetto</span><span class="dot"></span>
  </div>
  <div class="dapp" data-w="w-coll">
    <button class="lilac"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
    <span class="tip">I collegamenti</span><span class="dot"></span>
  </div>
  <div class="dapp" data-w="w-extra">
    <button class="lemon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg></button>
    <span class="tip">Oltre la scuola</span><span class="dot"></span>
  </div>
  <div class="sep"></div>
  <div class="dapp">
    <a href="schema.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01M7 17.5h.01"/></svg></a>
    <span class="tip">Dietro le quinte</span><span class="dot"></span>
  </div>
</nav>

<script>
/* ============================================================
   OROLOGIO — menu bar
   ============================================================ */
const clock = document.getElementById('clock');
function tick() {
  const d = new Date();
  const giorno = d.toLocaleDateString('it-IT', { weekday: 'short', day: 'numeric', month: 'short' });
  const ora = d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
  clock.textContent = giorno + '  ' + ora;
}
tick();
setInterval(tick, 10000);

/* ============================================================
   FINESTRE — apri / chiudi / focus / drag / ingrandisci
   (stesse molle e micro-interazioni del login)
   ============================================================ */
let zTop = 20;
const wins = [...document.querySelectorAll('.win')];

function syncDock() {
  document.querySelectorAll('.dapp[data-w]').forEach(d => {
    const w = document.getElementById(d.dataset.w);
    d.classList.toggle('running', !!w && w.classList.contains('open'));
  });
}

function focusWin(w) { w.style.zIndex = ++zTop; }

function openWin(id) {
  const w = document.getElementById(id);
  if (!w) return;
  if (!w.classList.contains('open')) {
    w.classList.remove('closing');
    w.classList.add('open');
  }
  focusWin(w);
  syncDock();
}

function closeWin(w) {
  w.classList.add('closing');
  setTimeout(() => { w.classList.remove('open', 'closing', 'maxi'); syncDock(); }, 280);
}

wins.forEach(w => {
  w.addEventListener('pointerdown', () => focusWin(w));

  const bar = w.querySelector('.titlebar');
  const [bClose, bMin, bMax] = w.querySelectorAll('.lights button');
  bClose.addEventListener('click', (e) => { e.stopPropagation(); closeWin(w); });
  bMin.addEventListener('click',   (e) => { e.stopPropagation(); closeWin(w); });
  bMax.addEventListener('click',   (e) => { e.stopPropagation(); w.classList.toggle('maxi'); focusWin(w); });

  /* trascinamento dalla barra del titolo */
  let sx = 0, sy = 0, ox = 0, oy = 0, dragging = false;
  bar.addEventListener('pointerdown', (e) => {
    if (e.target.closest('.lights') || w.classList.contains('maxi')) return;
    dragging = true;
    sx = e.clientX; sy = e.clientY;
    const r = w.getBound