<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PCTO — Maturità 2026</title>
<!-- precarica il logo della scuola: serve subito al loader -->
<link rel="preload" href="assets/iisc-logo.png" as="image" fetchpriority="high">
<link rel="stylesheet" href="assets/css/macos-system.css?v=<?= @filemtime(__DIR__ . '/assets/css/macos-system.css') ?>">
</head>
<body class="login-screen">

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

<script src="assets/js/sound.js"></script>
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
const NOME_UTENTE = 'Filippo Corsini';
const lock      = document.getElementById('lock');
const form      = document.getElementById('form');
const codeInput = document.getElementById('codeInput');
const pwd       = document.getElementById('pwd');
const goBtn     = document.getElementById('goBtn');
const hintBtn   = document.getElementById('hintBtn');
const caption   = document.getElementById('caption');
const demoEnter = document.getElementById('demoEnter');

const CAPTION_DEFAULT = 'Inserisci la password per accedere';
let submitted = false;

/* foto profilo: se manca, mostra le iniziali “FC” */
const avatar    = document.getElementById('avatar');
const avatarImg = document.getElementById('avatarImg');
avatarImg.addEventListener('error', () => { avatar.classList.add('no-photo'); });

function setCaption(text, isError) {
  caption.textContent = text;
  caption.classList.toggle('error', !!isError);
}

/* la freccia compare solo con del testo */
codeInput.addEventListener('input', () => {
  pwd.classList.toggle('has-text', codeInput.value.length > 0);
  if (caption.classList.contains('error')) setCaption(CAPTION_DEFAULT, false);
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

  if (!codeInput.value) { shakeError(); codeInput.focus(); return; }

  goBtn.disabled = true;

  try {
    const dati = new URLSearchParams();
    dati.append('nome', NOME_UTENTE);
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
    const pretty = NOME_UTENTE.split(/\s+/)[0];

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

/* ============================================================
   PRELOADER — la barra si completa sugli asset locali (font +
   immagini). Il video è best-effort: ha un tetto massimo e NON
   può mai bloccare il caricamento. Sblocco garantito entro 5s.
   ============================================================ */
(function preload() {
  const boot  = document.getElementById('boot');
  const fill  = document.getElementById('bbarFill');
  const video = document.getElementById('bgVideo');

  // asset che bloccano davvero: font + immagini locali (rapidi)
  const tasks = [];
  tasks.push(
    document.fonts && document.fonts.ready
      ? document.fonts.ready.catch(() => {})
      : Promise.resolve()
  );
  ['assets/iisc-logo.png', 'assets/profile.jpg', 'assets/bg.png'].forEach((src) => {
    tasks.push(new Promise((res) => {
      const im = new Image();
      im.onload = im.onerror = () => res();
      im.src = src;
    }));
  });

  // video di sfondo: best-effort con tetto massimo (max 3s).
  // NB: con un <source> che fallisce, l'evento 'error' arriva sul <source>,
  // non sul <video> — per questo ascolto entrambi + timeout di sicurezza.
  tasks.push(new Promise((res) => {
    if (!video) return res();
    let done = false;
    const fine = () => { if (!done) { done = true; res(); } };
    if (video.readyState >= 3) return fine();
    video.addEventListener('canplay', fine, { once: true });
    video.addEventListener('loadeddata', fine, { once: true });
    video.addEventListener('error', fine, { once: true });
    const source = video.querySelector('source');
    if (source) source.addEventListener('error', fine, { once: true });
    setTimeout(fine, 3000);
    try { video.load(); } catch (e) {}
  }));

  // avanzamento barra a ogni asset pronto
  const totale = tasks.length;
  let fatti = 0;
  tasks.forEach((t) => t.then(() => {
    fatti++;
    fill.style.width = Math.round((fatti / totale) * 100) + '%';
  }));

  // completamento (eseguito una sola volta)
  let finito = false;
  function completa() {
    if (finito) return;
    finito = true;
    fill.style.width = '100%';
    setTimeout(() => {
      boot.classList.add('done');
      document.body.classList.add('ready');
      try { video && video.play(); } catch (e) {}
      setTimeout(() => { try { codeInput.focus({ preventScroll: true }); } catch (e) {} }, 350);
    }, 260);
  }

  // tempo minimo per un'animazione elegante, poi completa appena pronti
  const minimo = new Promise((res) => setTimeout(res, 650));
  Promise.all([Promise.all(tasks), minimo]).then(completa).catch(completa);
  // rete di sicurezza dura: il sito si sblocca comunque entro 5s
  setTimeout(completa, 5000);
})();

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
