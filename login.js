/* ============================================================
   login.js — comportamento della lock screen macOS
   - orologio live
   - input password / feedback
   - chiamata fetch a login.php
   - demo tecnica opzionale con ?demo=1
   ============================================================ */
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
      setTimeout(() => window.location.replace('hub.php'), 430);
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
  setTimeout(() => window.location.replace('hub.php'), 430);
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
