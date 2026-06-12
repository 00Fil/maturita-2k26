<?php
/* ============================================================
   hub.php — il desktop in stile macOS
   accessibile solo dopo il login (sessione attiva)
   ============================================================ */
session_start();
if (!isset($_SESSION['nome'])) { header('Location: index.php'); exit; }
$nome = htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Desktop · Maturità 2026</title>
<link rel="stylesheet" href="hub.css">
</head>
<body>

<!-- sprite SVG: icone a tratto, stesse linee del login -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
<symbol id="i-cap" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3 1 8l11 5 9-4.09V14h2V8L12 3zM5 11.18V15c0 1.66 3.13 3 7 3s7-1.34 7-3v-3.82l-7 3.18-7-3.18z"/></symbol>
<symbol id="i-person" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></symbol>
<symbol id="i-factory" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V9l6 4V9l6 4V5h6v16H3z"/><path d="M8 17h.01M12 17h.01M16 17h.01"/></symbol>
<symbol id="i-brief" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></symbol>
<symbol id="i-wrench" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
<symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
<symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
<symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></symbol>
<symbol id="i-filetext" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></symbol>
<symbol id="i-fileuser" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><circle cx="12" cy="13" r="2"/><path d="M8.5 18.5c.5-1.5 2-2.5 3.5-2.5s3 1 3.5 2.5"/></symbol>
<symbol id="i-out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></symbol>
<symbol id="i-server" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><path d="M6 6h.01M6 18h.01"/></symbol>
<symbol id="i-wifi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><path d="M12 20h.01"/></symbol>
<symbol id="i-batt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="18" height="10" rx="2.5"/><path d="M23 11v2"/><rect x="4.5" y="9.5" width="11" height="5" rx="1" fill="currentColor" stroke="none"/></symbol>
<symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></symbol>
<symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></symbol>
<symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
<symbol id="i-db" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></symbol>
<symbol id="i-target" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></symbol>
<symbol id="i-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></symbol>
<symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8L12 2z"/></symbol>
<symbol id="i-code" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></symbol>
<symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></symbol>
<symbol id="i-scale" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M9 21h6M3 7h18"/><path d="M6 7l-3 6a3 3 0 0 0 6 0L6 7zM18 7l-3 6a3 3 0 0 0 6 0l-3-6z"/></symbol>
<symbol id="i-music" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></symbol>
<symbol id="i-image" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></symbol>
<symbol id="i-pen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></symbol>
<symbol id="i-monitor" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></symbol>
</defs></svg>

<!-- wallpaper -->
<div class="bg"><div class="blob b1"></div><div class="blob b2"></div><div class="blob b3"></div><div class="blob b4"></div><div class="wash"></div></div>

<!-- menu bar -->
<nav class="menubar">
  <span class="logo"><svg><use href="#i-cap"/></svg></span>
  <span class="appname">Maturità 2026</span>
  <button class="mitem hideS" data-open="w-pres">Presentazione</button>
  <button class="mitem hideS" data-open="w-esp">Percorso</button>
  <button class="mitem hideS" data-open="w-prog">Progetto</button>
  <a class="mitem hideS" href="schema.php">Dietro le quinte</a>
  <div class="right">
    <span class="sico hideS"><svg><use href="#i-wifi"/></svg></span>
    <span class="sico hideS"><svg><use href="#i-batt"/></svg></span>
    <span class="who hideS"><span class="dot"></span><?= $nome ?></span>
    <span class="clock" id="clock"></span>
    <a class="mitem exit" href="logout.php" title="Esci"><svg><use href="#i-out"/></svg>Esci</a>
  </div>
</nav>

<!-- file sul desktop -->
<div class="deskicons">
  <button class="dicon" data-open="w-esp"><span class="fico red"><svg><use href="#i-filetext"/></svg></span><span>Diario di bordo.pdf</span></button>
  <button class="dicon" data-open="w-chi"><span class="fico blue"><svg><use href="#i-fileuser"/></svg></span><span>Curriculum dello studente.pdf</span></button>
</div>

<!-- PRESENTAZIONE (aperta all'avvio, come l'app di supporto) -->
<section class="win open" id="w-pres" style="left:calc(50% - 345px);top:60px;width:690px;height:calc(100vh - 200px)">
  <div class="titlebar"><span class="wt">Presentazione — Filippo Corsini</span></div>
  <div class="wbody">
    <div class="pres-hero">
      <div class="pava">FC</div>
      <h1>Ciao <?= $nome ?>, io sono <em>Filippo</em>.</h1>
      <p>Benvenuto sul mio desktop. Qui racconto il mio percorso per l'esame di Maturità 2026: apri le app qui sotto o usa il Dock per scoprire chi sono, l'azienda che mi ha ospitato, cosa ho fatto in 240 ore di stage e cosa ho imparato davvero.</p>
    </div>
    <div class="pres-grid">
      <div class="pcard" data-open="w-chi" style="animation-delay:.05s"><span class="ai g-orange"><svg><use href="#i-person"/></svg></span><b>Chi sono</b><span class="sub">5ª Informatica all'ITIS Cerebotani · credito 29</span></div>
      <div class="pcard" data-open="w-az" style="animation-delay:.1s"><span class="ai g-teal"><svg><use href="#i-factory"/></svg></span><b>L'azienda</b><span class="sub">CS Metal Europe: acciai speciali a Bedizzole</span></div>
      <div class="pcard" data-open="w-esp" style="animation-delay:.15s"><span class="ai g-green"><svg><use href="#i-brief"/></svg></span><b>L'esperienza</b><span class="sub">240 ore di stage in due anni, passo passo</span></div>
      <div class="pcard" data-open="w-comp" style="animation-delay:.2s"><span class="ai g-indigo"><svg><use href="#i-wrench"/></svg></span><b>Competenze</b><span class="sub">Strumenti e soft skill, con livelli onesti</span></div>
      <div class="pcard" data-open="w-prog" style="animation-delay:.25s"><span class="ai g-pink"><svg><use href="#i-lock"/></svg></span><b>Questo progetto</b><span class="sub">Come funziona il sito che stai usando ora</span></div>
      <div class="pcard" data-open="w-coll" style="animation-delay:.3s"><span class="ai g-purple"><svg><use href="#i-link"/></svg></span><b>Collegamenti</b><span class="sub">I ponti con le materie d'esame</span></div>
      <div class="pcard" data-open="w-extra" style="animation-delay:.35s"><span class="ai g-yellow"><svg><use href="#i-rocket"/></svg></span><b>Oltre la scuola</b><span class="sub">Volontariato, musica, arte e altri progetti</span></div>
    </div>
  </div>
</section>

<!-- CHI SONO -->
<section class="win" id="w-chi" style="left:110px;top:88px;width:520px;height:560px">
  <div class="titlebar"><span class="wt">Chi sono</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-orange"><svg><use href="#i-person"/></svg></span><div><h2>Chi sono</h2><p>Filippo Corsini · 5ª Informatica</p></div></div>
    <div class="stats"><div class="stat"><b>5ª</b><span>Informatica</span></div><div class="stat"><b>29</b><span>Credito</span></div><div class="stat"><b>240</b><span>Ore PCTO</span></div></div>
    <div class="rows">
      <div class="row"><span class="ai g-blue"><svg><use href="#i-cap"/></svg></span><div><b>La scuola</b><span class="sub">ITIS “Luigi Cerebotani” di Lonato del Garda (BS), indirizzo Informatica e Telecomunicazioni.</span></div></div>
      <div class="row"><span class="ai g-indigo"><svg><use href="#i-target"/></svg></span><div><b>Il credito scolastico</b><span class="sub">9 punti in terza, 9 in quarta e 11 in quinta: 29 in totale, con una crescita costante negli anni.</span></div></div>
      <div class="row"><span class="ai g-pink"><svg><use href="#i-heart"/></svg></span><div><b>Cosa mi piace</b><span class="sub">Lo sviluppo web e la grafica, i progetti concreti che finiscono in mano a persone vere, e la musica.</span></div></div>
    </div>
    <div class="chips"><span class="chip">PHP</span><span class="chip">HTML/CSS</span><span class="chip">JavaScript</span><span class="chip">Photoshop</span><span class="chip">Illustrator</span><span class="chip">Excel</span></div>
  </div>
</section>

<!-- L'AZIENDA -->
<section class="win" id="w-az" style="left:160px;top:104px;width:540px;height:540px">
  <div class="titlebar"><span class="wt">L'azienda — CS Metal Europe</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-teal"><svg><use href="#i-factory"/></svg></span><div><h2>CS Metal Europe S.r.l.</h2><p>Bedizzole (Brescia) · gruppo Proterial</p></div></div>
    <div class="stats"><div class="stat"><b>7.500</b><span>m² di stabilimento</span></div><div class="stat"><b>12</b><span>Persone</span></div><div class="stat"><b>2</b><span>Anni di stage</span></div></div>
    <div class="rows">
      <div class="row"><span class="ai g-blue"><svg><use href="#i-globe"/></svg></span><div><b>Chi è</b><span class="sub">Centro servizi siderurgico del gruppo giapponese Proterial: lavora e distribuisce acciai speciali per utensili e stampi.</span></div></div>
      <div class="row"><span class="ai g-orange"><svg><use href="#i-grid"/></svg></span><div><b>Dove</b><span class="sub">Via Benaco 86, Bedizzole (BS): un capannone di 7.500 m² tra il lago di Garda e Brescia.</span></div></div>
      <div class="row"><span class="ai g-green"><svg><use href="#i-users"/></svg></span><div><b>Il mio tutor</b><span class="sub">Delia Pea mi ha seguito in tutte le attività, dal marketing all'ufficio gestionale.</span></div></div>
      <div class="row"><span class="ai g-indigo"><svg><use href="#i-db"/></svg></span><div><b>Gli strumenti</b><span class="sub">Il gestionale Embyon (TeamSystem) per ordini, clienti e magazzino: il mio primo contatto con un ERP usato davvero in produzione.</span></div></div>
    </div>
  </div>
</section>

<!-- L'ESPERIENZA -->
<section class="win" id="w-esp" style="left:210px;top:78px;width:560px;height:580px">
  <div class="titlebar"><span class="wt">L'esperienza — 240 ore di stage</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-green"><svg><use href="#i-brief"/></svg></span><div><h2>L'esperienza</h2><p>240 ore in due anni scolastici, stessa azienda</p></div></div>
    <div class="tl">
      <div class="tle"><span class="tag">3ª · 8–27 aprile 2024 · 120 ore</span><b>Il primo stage</b><p>Analisi dati con Excel, contenuti promozionali con Photoshop e Illustrator, l'invito ufficiale per un evento aziendale, la sistemazione del sito WordPress con l'apertura della sezione blog e le prime ore di osservazione sul gestionale Embyon.</p></div>
      <div class="tle"><span class="tag">4ª · a.s. 2024/25 · 120 ore</span><b>Il ritorno in azienda</b><p>Tornare nello stesso posto cambia tutto: conoscevo già persone e strumenti, così ho gestito i compiti con più autonomia, dall'inizio alla fine, con meno supervisione e più responsabilità.</p></div>
      <div class="tle"><span class="tag">5ª · giugno 2026</span><b>Il colloquio di maturità</b><p>Questo desktop raccoglie tutto il percorso: l'ho costruito io, con le tecnologie viste a scuola e in azienda, per raccontarlo alla commissione in modo chiaro e interattivo.</p></div>
    </div>
  </div>
</section>

<!-- COMPETENZE -->
<section class="win" id="w-comp" style="left:250px;top:96px;width:530px;height:580px">
  <div class="titlebar"><span class="wt">Competenze</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-indigo"><svg><use href="#i-wrench"/></svg></span><div><h2>Competenze</h2><p>Cosa so usare e cosa ho allenato sul campo</p></div></div>
    <div class="skills">
      <div class="skill"><div class="sk-head"><span>Excel</span><span>90%</span></div><div class="bar"><div class="fill" data-v="90"></div></div></div>
      <div class="skill"><div class="sk-head"><span>HTML &amp; CSS</span><span>85%</span></div><div class="bar"><div class="fill" data-v="85"></div></div></div>
      <div class="skill"><div class="sk-head"><span>WordPress</span><span>80%</span></div><div class="bar"><div class="fill" data-v="80"></div></div></div>
      <div class="skill"><div class="sk-head"><span>Photoshop</span><span>75%</span></div><div class="bar"><div class="fill" data-v="75"></div></div></div>
      <div class="skill"><div class="sk-head"><span>Illustrator</span><span>70%</span></div><div class="bar"><div class="fill" data-v="70"></div></div></div>
      <div class="skill"><div class="sk-head"><span>Embyon (ERP)</span><span>60%</span></div><div class="bar"><div class="fill" data-v="60"></div></div></div>
    </div>
    <div class="rows">
      <div class="row"><span class="ai g-pink"><svg><use href="#i-users"/></svg></span><div><b>Lavoro in squadra</b><span class="sub">In un ufficio di 12 persone i compiti si incastrano: ho imparato a chiedere, ascoltare e consegnare nei tempi.</span></div></div>
      <div class="row"><span class="ai g-orange"><svg><use href="#i-spark"/></svg></span><div><b>Comunicazione</b><span class="sub">Dal contenuto promozionale all'invito per l'evento: dire le cose con il tono giusto per chi le riceve.</span></div></div>
      <div class="row"><span class="ai g-teal"><svg><use href="#i-target"/></svg></span><div><b>Precisione sui dati</b><span class="sub">Nei fogli Excel e nel gestionale un numero sbagliato costa: ricontrollare è parte del lavoro.</span></div></div>
    </div>
  </div>
</section>

<!-- QUESTO PROGETTO -->
<section class="win" id="w-prog" style="left:290px;top:86px;width:570px;height:580px">
  <div class="titlebar"><span class="wt">Questo progetto — login PHP + MySQL</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-pink"><svg><use href="#i-lock"/></svg></span><div><h2>Questo progetto</h2><p>Il sito che stai usando: cosa succede a ogni accesso</p></div></div>
    <div class="steps">
      <div class="step"><span class="n">1</span><div><b>Il browser invia il codice</b><span class="sub">Il form della pagina di accesso spedisce il codice con una richiesta in POST senza ricaricare la pagina.</span><code>fetch('login.php', { method: 'POST', … })</code></div></div>
      <div class="step"><span class="n">2</span><div><b>PHP verifica in sicurezza</b><span class="sub">Il codice non è mai salvato in chiaro: viene confrontato con un hash bcrypt e, se è corretto, la sessione viene rigenerata per bloccare il session fixation.</span><code>password_verify($codice, $hash)</code></div></div>
      <div class="step"><span class="n">3</span><div><b>MySQL registra l'accesso</b><span class="sub">Ogni ingresso finisce nella tabella accessi tramite PDO con prepared statement, la difesa standard contro le SQL injection.</span><code>INSERT INTO accessi (nome, ip) VALUES (?, ?)</code></div></div>
    </div>
    <a class="pillbtn" href="schema.php"><svg><use href="#i-server"/></svg>Guarda lo schema tecnico</a>
    <a class="pillbtn ghost" href="index.php?demo=1"><svg><use href="#i-monitor"/></svg>Rivedi la demo del login</a>
  </div>
</section>

<!-- COLLEGAMENTI -->
<section class="win" id="w-coll" style="left:190px;top:92px;width:560px;height:570px">
  <div class="titlebar"><span class="wt">Collegamenti con le materie</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-purple"><svg><use href="#i-link"/></svg></span><div><h2>Collegamenti</h2><p>I ponti tra lo stage e le materie d'esame</p></div></div>
    <div class="rows">
      <div class="row"><span class="ai g-blue"><svg><use href="#i-server"/></svg></span><div><b>Sistemi e Reti</b><span class="sub">L'infrastruttura dietro questo sito: il server Apache, i container Docker e il viaggio di una richiesta HTTP dal browser al database.</span></div></div>
      <div class="row"><span class="ai g-indigo"><svg><use href="#i-code"/></svg></span><div><b>Informatica</b><span class="sub">Database MySQL, PHP e sicurezza: hash bcrypt, sessioni e prepared statement, visti in azienda e rifatti qui con le mie mani.</span></div></div>
      <div class="row"><span class="ai g-teal"><svg><use href="#i-globe"/></svg></span><div><b>Inglese</b><span class="sub">In un gruppo internazionale giapponese la documentazione tecnica e il gestionale parlano inglese: usarlo è la normalità, non l'eccezione.</span></div></div>
      <div class="row"><span class="ai g-orange"><svg><use href="#i-book"/></svg></span><div><b>Italiano</b><span class="sub">Raccontare un'esperienza tecnica a chi tecnico non è: la scrittura chiara come strumento di lavoro, dall'invito all'evento a questa presentazione.</span></div></div>
      <div class="row"><span class="ai g-green"><svg><use href="#i-scale"/></svg></span><div><b>Educazione civica</b><span class="sub">Artt. 1, 4 e 35–36 della Costituzione: il lavoro come diritto, dovere e dignità, visti da dentro un'azienda vera.</span></div></div>
    </div>
  </div>
</section>

<!-- OLTRE LA SCUOLA -->
<section class="win" id="w-extra" style="left:230px;top:82px;width:580px;height:580px">
  <div class="titlebar"><span class="wt">Oltre la scuola</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai g-yellow"><svg><use href="#i-rocket"/></svg></span><div><h2>Oltre la scuola</h2><p>Le esperienze extrascolastiche del mio curriculum</p></div></div>
    <div class="rows">
      <div class="row"><span class="ai g-blue"><svg><use href="#i-code"/></svg></span><div><b>Pseudo-gestionale per l'oratorio</b><span class="sub">Ho progettato e mantengo un piccolo gestionale per l'oratorio di Bedizzole: il mio primo software con utenti veri.</span></div><span class="when">2024–2026</span></div>
      <div class="row"><span class="ai g-pink"><svg><use href="#i-heart"/></svg></span><div><b>Animatore ed educatore</b><span class="sub">Animazione ed educazione dei ragazzi all'oratorio: responsabilità, pazienza e organizzazione.</span></div><span class="when">dal 2023</span></div>
      <div class="row"><span class="ai g-green"><svg><use href="#i-users"/></svg></span><div><b>Volontariato</b><span class="sub">Festa del Sorriso e Torneo dei Roncai: dietro le quinte degli eventi della mia comunità.</span></div><span class="when">2022–2026</span></div>
      <div class="row"><span class="ai g-orange"><svg><use href="#i-music"/></svg></span><div><b>Violoncello</b><span class="sub">Sei anni di studio alla scuola di musica “Elia Marini” di Calcinato: disciplina e orecchio.</span></div><span class="when">2018–2024</span></div>
      <div class="row"><span class="ai g-purple"><svg><use href="#i-image"/></svg></span><div><b>Mostra “L'ultimo inverno”</b><span class="sub">Esposizione al MuSa di Salò: l'arte come altro modo di comunicare.</span></div><span class="when">17/01/2026</span></div>
      <div class="row"><span class="ai g-teal"><svg><use href="#i-pen"/></svg></span><div><b>“Volo tra le Righe 3.0”</b><span class="sub">Concorso letterario: la scrittura, ancora una volta.</span></div><span class="when">2025</span></div>
    </div>
  </div>
</section>

<!-- dock -->
<nav class="dock" id="dock">
  <div class="dapp" data-w="w-pres"><span class="tip">Presentazione</span><button class="ai g-blue"><svg><use href="#i-cap"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-chi"><span class="tip">Chi sono</span><button class="ai g-orange"><svg><use href="#i-person"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-az"><span class="tip">L'azienda</span><button class="ai g-teal"><svg><use href="#i-factory"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-esp"><span class="tip">L'esperienza</span><button class="ai g-green"><svg><use href="#i-brief"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-comp"><span class="tip">Competenze</span><button class="ai g-indigo"><svg><use href="#i-wrench"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-prog"><span class="tip">Il progetto</span><button class="ai g-pink"><svg><use href="#i-lock"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-coll"><span class="tip">Collegamenti</span><button class="ai g-purple"><svg><use href="#i-link"/></svg></button><span class="dot"></span></div>
  <div class="dapp" data-w="w-extra"><span class="tip">Oltre la scuola</span><button class="ai g-yellow"><svg><use href="#i-rocket"/></svg></button><span class="dot"></span></div>
  <div class="sep"></div>
  <div class="dapp"><span class="tip">Dietro le quinte</span><a class="ai g-dark" href="schema.php"><svg><use href="#i-server"/></svg></a></div>
</nav>

<script src="hub.js"></script>
</body>
</html>
