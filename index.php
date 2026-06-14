<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PCTO — Maturità 2026</title>
<!-- precarica il logo della scuola: serve subito al loader -->
<link rel="preload" href="assets/iisc-logo.png" as="image" fetchpriority="high">
<link rel="stylesheet" href="macos-system.css?v=<?= @filemtime(__DIR__ . '/macos-system.css') ?>">
</head>
<body data-surface="lock">

<!-- PRELOADER (logo scuola) — primo elemento: appare subito -->
<div id="boot" aria-hidden="true">
  <img src="assets/iisc-logo.png" alt="" fetchpriority="high" decoding="async">
  <div class="bbar"><span id="bbarFill"></span></div>
</div>

<!-- SFONDO VIDEO (poster di fallback: bg.png) -->
<video class="wallpaper" id="bgVideo" autoplay muted loop playsinline preload="auto" poster="assets/bg.png">
  <source src="assets/lock.mp4" type="video/mp4">
</video>
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
    <div class="avatar" id="avatar">
      <img class="avatar-img" id="avatarImg" src="assets/profile.jpg" alt="Filippo Corsini" decoding="async">
      <span class="avatar-mono" id="avatarMono">FC</span>
    </div>

    <form id="form" novalidate>
      <div class="name">Filippo Corsini</div>

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
<script src="login.js"></script>
</body>
</html>
