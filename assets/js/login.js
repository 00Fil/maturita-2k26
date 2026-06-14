/* ============================================================
   login.js — Logica della schermata di blocco (lock screen)
   ------------------------------------------------------------
   - Orologio live in italiano.
   - Invio del codice al backend PHP (login.php): contratto
     INVARIATO (POST nome + codice → JSON { ok, messaggio, passi? }).
   - Preloader sugli asset locali (font + immagini): niente piu
     video, quindi avvio piu rapido e sblocco senza salti.
   - Sblocco rapido: lo sfondo "entra" verso il desktop, che usa
     lo stesso bg.png → transizione continua.
   - Modalita demo (?demo=1): widget "dietro le quinte".

   Dipende da audio.js per il suono di accesso (window.sndGo).
   ============================================================ */
(function () {
  'use strict';

  /* ----------------------------------------------------------
     OROLOGIO LIVE (stile macOS, locale italiano)
     ---------------------------------------------------------- */
  var dateEl = document.getElementById('date');
  var timeEl = document.getElementById('time');
  function tickClock() {
    var now = new Date();
    timeEl.textContent = now.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
    var d = now.toLocaleDateString('it-IT', { weekday: 'long', day: 'numeric', month: 'long' });
    dateEl.textContent = d.charAt(0).toUpperCase() + d.slice(1);
  }
  tickClock();
  setInterval(tickClock, 5000);

  /* ----------------------------------------------------------
     RIFERIMENTI
     ---------------------------------------------------------- */
  var NOME_UTENTE = 'Filippo Corsini';
  var form      = document.getElementById('form');
  var codeInput = document.getElementById('codeInput');
  var pwd       = document.getElementById('pwd');
  var goBtn     = document.getElementById('goBtn');
  var hintBtn   = document.getElementById('hintBtn');
  var caption   = document.getElementById('caption');
  var demoEnter = document.getElementById('demoEnter');

  var CAPTION_DEFAULT = 'Inserisci la password per accedere';
  var submitted = false;

  /* suono di accesso (audio.js); silenzioso se non disponibile */
  function suonoAccesso() {
    if (typeof window.sndGo === 'function') { try { window.sndGo(); } catch (e) {} }
  }

  /* foto profilo: se manca, mostra le iniziali “FC” */
  var avatar    = document.getElementById('avatar');
  var avatarImg = document.getElementById('avatarImg');
  avatarImg.addEventListener('error', function () { avatar.classList.add('no-photo'); });

  function setCaption(text, isError) {
    caption.textContent = text;
    caption.classList.toggle('error', !!isError);
  }

  /* la freccia compare solo con del testo */
  codeInput.addEventListener('input', function () {
    pwd.classList.toggle('has-text', codeInput.value.length > 0);
    if (caption.classList.contains('error')) setCaption(CAPTION_DEFAULT, false);
  });

  /* “?” → suggerimento gentile (mai il codice in chiaro) */
  hintBtn.addEventListener('click', function () {
    setCaption("Usa il codice d'accesso del PCTO.", false);
  });

  function shakeError(messaggio) {
    pwd.classList.remove('shake');
    void pwd.offsetWidth;          // forza il reflow per riavviare l'animazione
    pwd.classList.add('shake');
    if (messaggio) setCaption(messaggio, true);
    codeInput.value = '';
    pwd.classList.remove('has-text');
  }

  /* sblocco → desktop. Rapido (~0.5s) e in continuita con bgSettle. */
  function vaiAlDesktop() {
    document.body.classList.add('unlock');
    setTimeout(function () { window.location.replace('hub.php'); }, 500);
  }

  /* ----------------------------------------------------------
     INVIO — chiama il backend PHP (login.php). Contratto invariato.
     ---------------------------------------------------------- */
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (submitted) return;
    if (!codeInput.value) { shakeError(); codeInput.focus(); return; }

    goBtn.disabled = true;

    var dati = new URLSearchParams();
    dati.append('nome', NOME_UTENTE);
    dati.append('codice', codeInput.value);
    if (demoMode) dati.append('demo', '1');

    var t0 = performance.now();
    fetch('login.php', { method: 'POST', body: dati })
      .then(function (risposta) {
        return risposta.json().then(function (json) {
          mostraDemo(json, performance.now() - t0, risposta.status);

          if (!json.ok) {
            shakeError(json.messaggio || 'Qualcosa e andato storto.');
            goBtn.disabled = false;
            return;
          }

          /* accesso riuscito */
          submitted = true;
          suonoAccesso();
          var pretty = NOME_UTENTE.split(/\s+/)[0];

          if (demoMode) {
            /* in demo si resta sulla pagina per spiegare i passaggi */
            setCaption('Accesso riuscito, ' + pretty + ' · i passaggi sono qui sotto.', false);
            goBtn.disabled = false;
            demoEnter.hidden = false;
          } else {
            setCaption('Accesso riuscito · apro il desktop…', false);
            vaiAlDesktop();
          }
        });
      })
      .catch(function (err) {
        if (demoMode) avviaDemo([
          { titolo: 'Il browser invia la richiesta', dettaglio: 'fetch POST → login.php (nome + codice)' },
          { titolo: 'Nessuna risposta dal server', dettaglio: String(err), stato: 'errore' }
        ]);
        shakeError('Server non raggiungibile.');
        goBtn.disabled = false;
      });
  });

  demoEnter.addEventListener('click', vaiAlDesktop);

  /* ----------------------------------------------------------
     PRELOADER — la barra si completa sugli asset locali (font +
     immagini). Niente video: avvio rapido. Sblocco garantito
     comunque entro 5s come rete di sicurezza.
     ---------------------------------------------------------- */
  (function preload() {
    var boot = document.getElementById('boot');
    var fill = document.getElementById('bbarFill');

    var tasks = [];
    tasks.push(
      document.fonts && document.fonts.ready
        ? document.fonts.ready.catch(function () {})
        : Promise.resolve()
    );
    ['assets/iisc-logo.png', 'assets/profile.jpg', 'assets/bg.png'].forEach(function (src) {
      tasks.push(new Promise(function (res) {
        var im = new Image();
        im.onload = im.onerror = function () { res(); };
        im.src = src;
      }));
    });

    // avanzamento barra a ogni asset pronto
    var totale = tasks.length;
    var fatti = 0;
    tasks.forEach(function (t) {
      t.then(function () {
        fatti++;
        fill.style.width = Math.round((fatti / totale) * 100) + '%';
      });
    });

    // completamento (eseguito una sola volta)
    var finito = false;
    function completa() {
      if (finito) return;
      finito = true;
      fill.style.width = '100%';
      setTimeout(function () {
        boot.classList.add('done');
        document.body.classList.add('ready');
        setTimeout(function () {
          try { codeInput.focus({ preventScroll: true }); } catch (e) {}
        }, 350);
      }, 260);
    }

    // tempo minimo per un'animazione elegante, poi completa appena pronti
    var minimo = new Promise(function (res) { setTimeout(res, 650); });
    Promise.all([Promise.all(tasks), minimo]).then(completa).catch(completa);
    setTimeout(completa, 5000);   // rete di sicurezza dura
  })();

  /* ----------------------------------------------------------
     DEMO — visualizzazione schematica dei passaggi del backend.
     Attiva SOLO con ?demo=1 nell'URL.
     ---------------------------------------------------------- */
  var demoMode = new URLSearchParams(location.search).get('demo') === '1';
  if (demoMode) document.body.classList.add('demo');

  var nClient = document.getElementById('nClient');
  var nServer = document.getElementById('nServer');
  var nDb     = document.getElementById('nDb');
  var wCS     = document.getElementById('wCS');
  var wSD     = document.getElementById('wSD');
  var dstepEl = document.getElementById('dstep');
  var ddot    = document.getElementById('ddot');
  var dtEl    = document.getElementById('dt');
  var dmsEl   = document.getElementById('dms');
  var ddEl    = document.getElementById('dd');
  var dsqlEl  = document.getElementById('dsql');
  var dotsEl  = document.getElementById('dots');
  var dcount  = document.getElementById('dcount');
  var cPrev   = document.getElementById('cPrev');
  var cPlay   = document.getElementById('cPlay');
  var cNext   = document.getElementById('cNext');
  var icoPlay  = document.getElementById('icoPlay');
  var icoPause = document.getElementById('icoPause');

  var passi = [];
  var idx = -1;
  var timer = null;

  function zonaDi(p) {
    var t = (p.titolo || '').toLowerCase();
    if (t.indexOf('browser invia') !== -1) return 'cs';
    if (t.indexOf('risposta') !== -1)      return 'sc';
    if (t.indexOf('mysql') !== -1 || t.indexOf('connessione') !== -1) return 'sd';
    if (t.indexOf('query') !== -1 || t.indexOf('lettura') !== -1)     return 'db';
    return 'server';
  }

  function evidenzia(p) {
    [nClient, nServer, nDb].forEach(function (n) { n.classList.remove('on', 'ko'); });
    [wCS, wSD].forEach(function (w) { w.classList.remove('fwd', 'rev', 'ko'); });
    var ko = p.stato === 'errore';
    var cls = ko ? 'ko' : 'on';
    var zona = zonaDi(p);
    var wire = null, verso = null;
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
    var p = passi[i];
    dstepEl.classList.add('swap');
    setTimeout(function () {
      aggiornaCard(p);
      evidenzia(p);
      dstepEl.classList.remove('swap');
    }, 170);
    Array.prototype.forEach.call(dotsEl.children, function (d, j) {
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
    timer = setInterval(function () {
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

  cPlay.addEventListener('click', function () {
    if (timer) { pausa(); return; }
    if (idx === passi.length - 1) mostra(0);
    riproduci();
  });
  cPrev.addEventListener('click', function () { pausa(); mostra(idx - 1); });
  cNext.addEventListener('click', function () { pausa(); mostra(idx + 1); });

  function avviaDemo(nuoviPassi) {
    pausa();
    passi = nuoviPassi;
    dotsEl.innerHTML = '';
    passi.forEach(function (p, i) {
      var d = document.createElement('i');
      d.addEventListener('click', function () { pausa(); mostra(i); });
      dotsEl.appendChild(d);
    });
    mostra(0);
    if (passi.length > 1) riproduci();
  }

  function mostraDemo(json, msTotali, statoHttp) {
    if (!demoMode || !json.passi) return;
    avviaDemo([
      { titolo: 'Il browser invia la richiesta', dettaglio: 'fetch POST → login.php (nome + codice)' }
    ].concat(json.passi, [
      {
        titolo: 'Risposta JSON al browser',
        dettaglio: 'HTTP ' + statoHttp + ' · Tempo totale (andata e ritorno): ' + Math.round(msTotali) + ' ms',
        stato: json.ok ? 'ok' : 'errore',
        sql: JSON.stringify({ ok: json.ok, messaggio: json.messaggio || undefined })
      }
    ]));
  }
})();
