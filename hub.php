<?php
/* ============================================================
   hub.php — il “desktop” dopo il login
   Protetto da sessione: senza login si torna a index.php
   (stesso guard di schema.php)
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
<link rel="stylesheet" href="hub.css">
</head>
<body>

<!-- sprite icone: stessi due linguaggi del login (riempite + tratto 2px arrotondato) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
<symbol id="i-cap" viewBox="0 0 640 512"><path fill="currentColor" d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3.9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></symbol>
<symbol id="i-person" viewBox="0 0 24 24"><path fill="currentColor" d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.34 0-10 1.67-10 5v3h20v-3c0-3.33-6.66-5-10-5z"/></symbol>
<symbol id="i-lock" viewBox="0 0 24 24"><path fill="currentColor" d="M18 8h-1V6a5 5 0 0 0-10 0v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zM9 6a3 3 0 0 1 6 0v2H9zm3 11a2 2 0 1 1 2-2 2 2 0 0 1-2 2z"/></symbol>
<symbol id="i-factory" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1M12 18h1M7 18h1"/></g></symbol>
<symbol id="i-brief" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></g></symbol>
<symbol id="i-wrench" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
<symbol id="i-link" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></g></symbol>
<symbol id="i-rocket" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></g></symbol>
<symbol id="i-filetext" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></g></symbol>
<symbol id="i-fileuser" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M14 2v6h6"/><circle cx="12" cy="13" r="2"/><path d="M16 19a4 4 0 0 0-8 0"/></g></symbol>
<symbol id="i-out" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></g></symbol>
<symbol id="i-star" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></symbol>
<symbol id="i-code" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></g></symbol>
<symbol id="i-ruler" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2M11.5 9.5l2-2M8.5 6.5l2-2M17.5 15.5l2-2"/></g></symbol>
<symbol id="i-users" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></g></symbol>
<symbol id="i-grid" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/></symbol>
<symbol id="i-target" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></g></symbol>
<symbol id="i-phone" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
<symbol id="i-db" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></g></symbol>
<symbol id="i-monitor" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></g></symbol>
<symbol id="i-shield" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
<symbol id="i-server" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><path d="M6 6h.01M6 18h.01"/></g></symbol>
<symbol id="i-arrow" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></symbol>
<symbol id="i-net" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/></g></symbol>
<symbol id="i-globe" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></g></symbol>
<symbol id="i-book" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></g></symbol>
<symbol id="i-scale" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10M12 3v18M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/></g></symbol>
<symbol id="i-spark" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/></symbol>
<symbol id="i-heart" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></symbol>
<symbol id="i-music" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></g></symbol>
<symbol id="i-image" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></g></symbol>
<symbol id="i-pen" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5zM2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></g></symbol>
</defs></svg>

<!-- sfondo: la stessa aurora del login -->
<div class="bg" aria-hidden="true">
  <div class="blob b1"></div><div class="blob b2"></div>
  <div class="blob b3"></div><div class="blob b4"></div>
  <div class="wash"></div>
</div>

<!-- menu bar -->
<header class="menubar">
  <span class="logo"><svg><use href="#i-cap"/></svg></span>
  <span class="appname">Maturità 2026</span>
  <button class="mitem hideS" data-open="w-pres">Presentazione</button>
  <button class="mitem hideS" data-open="w-esp">Percorso</button>
  <button class="mitem hideS" data-open="w-prog">Progetto</button>
  <a class="mitem hideS" href="schema.php">Dietro le quinte</a>
  <div class="right">
    <span class="who"><span class="dot"></span><span class="txt"><?= $nome ?></span></span>
    <span class="clock" id="clock"></span>
    <a class="mitem exit" href="logout.php" title="Esci"><svg><use href="#i-out"/></svg>Esci</a>
  </div>
</header>

<!-- icone sul desktop -->
<div class="deskicons">
  <button class="dicon" data-open="w-esp">
    <span class="fico"><svg><use href="#i-filetext"/></svg></span>
    <span>Diario di bordo.pdf</span>
  </button>
  <button class="dicon" data-open="w-chi">
    <span class="fico"><svg><use href="#i-fileuser"/></svg></span>
    <span>Curriculum dello studente.pdf</span>
  </button>
</div>

<!-- finestra principale: presentazione (aperta all'arrivo, stile app di supporto) -->
<section class="win open" id="w-pres" style="left:max(12px, calc(50% - 345px)); top:60px; width:min(690px, 96vw); height:min(580px, calc(100vh - 150px));">
  <div class="titlebar"><span class="wt">Presentazione · Filippo Corsini</span></div>
  <div class="wbody">
    <div class="pres-hero">
      <div class="pava">FC</div>
      <h1>Ciao <?= $nome ?>, io sono <em>Filippo</em>.</h1>
      <p>Studente di Informatica all'ITIS “Cerebotani” di Lonato. Questo desktop raccoglie il mio percorso per il colloquio di maturità: apri le finestre per esplorare PCTO, progetto e collegamenti.</p>
    </div>
    <div class="pres-grid">
      <div class="pcard lav" data-open="w-chi" style="animation-delay:.05s"><span class="pe"><svg><use href="#i-person"/></svg></span><b>Chi sono</b><span class="sub">Profilo e crediti</span></div>
      <div class="pcard rose" data-open="w-az" style="animation-delay:.1s"><span class="pe"><svg><use href="#i-factory"/></svg></span><b>L'azienda</b><span class="sub">CS Metal Europe</span></div>
      <div class="pcard mint" data-open="w-esp" style="animation-delay:.15s"><span class="pe"><svg><use href="#i-brief"/></svg></span><b>Il percorso</b><span class="sub">240 ore di PCTO</span></div>
      <div class="pcard peach" data-open="w-comp" style="animation-delay:.2s"><span class="pe"><svg><use href="#i-wrench"/></svg></span><b>Competenze</b><span class="sub">Tecniche e trasversali</span></div>
      <div class="pcard sky" data-open="w-prog" style="animation-delay:.25s"><span class="pe"><svg><use href="#i-lock"/></svg></span><b>Il progetto</b><span class="sub">Questo sito di accesso</span></div>
      <div class="pcard lilac" data-open="w-coll" style="animation-delay:.3s"><span class="pe"><svg><use href="#i-link"/></svg></span><b>Collegamenti</b><span class="sub">Materie d'esame</span></div>
      <div class="pcard lemon" data-open="w-extra" style="animation-delay:.35s"><span class="pe"><svg><use href="#i-rocket"/></svg></span><b>Extra</b><span class="sub">Oltre la scuola</span></div>
    </div>
  </div>
</section>

<!-- chi sono -->
<section class="win" id="w-chi" style="left:8vw; top:90px; width:min(520px, 92vw); height:min(480px, calc(100vh - 170px));">
  <div class="titlebar"><span class="wt">Chi sono</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-person"/></svg>Profilo</div>
    <h2>Filippo Corsini</h2>
    <p class="lead">Classe 5ª, indirizzo Tecnico — Informatica.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg><use href="#i-cap"/></svg></span><div><b>ITIS “Luigi Cerebotani” · Lonato del Garda</b><span class="sub">Indirizzo Informatica e Telecomunicazioni, articolazione Informatica.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-star"/></svg></span><div><b>Credito scolastico: 29 punti</b><span class="sub">9 in terza + 9 in quarta + 11 in quinta.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-code"/></svg></span><div><b>Cosa mi piace</b><span class="sub">Sviluppo web, design di interfacce e progetti concreti: imparare costruendo cose che le persone usano davvero.</span></div></div>
    </div>
  </div>
</section>

<!-- azienda -->
<section class="win" id="w-az" style="left:14vw; top:110px; width:min(540px, 92vw); height:min(500px, calc(100vh - 180px));">
  <div class="titlebar"><span class="wt">L'azienda ospitante</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-factory"/></svg>Stage</div>
    <h2>CS Metal Europe S.r.l.</h2>
    <p class="lead">Via Benaco 86, Bedizzole (BS). Lavorazione e commercio di acciai speciali, parte del gruppo Proterial. Tutor aziendale: Delia Pea.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg><use href="#i-ruler"/></svg></span><div><b>Struttura da 7.500 m²</b><span class="sub">Magazzino, area lavorazioni e uffici dove ho svolto le mie attività.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-users"/></svg></span><div><b>Team di 12 persone</b><span class="sub">Una realtà piccola: si vede da vicino come ogni ruolo contribuisce all'azienda.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-grid"/></svg></span><div><b>Gestionale Embyon (TeamSystem)</b><span class="sub">Ho osservato come ordini, magazzino e fatturazione passano da un unico software.</span></div></div>
    </div>
  </div>
</section>

<!-- percorso -->
<section class="win" id="w-esp" style="left:20vw; top:80px; width:min(560px, 92vw); height:min(520px, calc(100vh - 160px));">
  <div class="titlebar"><span class="wt">Il percorso · 240 ore</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-brief"/></svg>PCTO</div>
    <h2>Due anni in azienda</h2>
    <p class="lead">240 ore di formazione scuola-lavoro in CS Metal Europe, tra terza e quarta.</p>
    <div class="rows">
      <div class="row"><span class="rico">3ª</span><div><b>2023/24 · 120 ore (8–27 aprile 2024)</b><span class="sub">Fogli Excel per l'ufficio, contenuti promozionali con Photoshop e Illustrator, invito per un evento aziendale, sistemazione del sito WordPress con una nuova sezione blog.</span></div></div>
      <div class="row"><span class="rico">4ª</span><div><b>2024/25 · 120 ore</b><span class="sub">Ritorno in azienda con più autonomia sui task e maggiore continuità sugli strumenti già conosciuti.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-target"/></svg></span><div><b>Cosa mi ha lasciato</b><span class="sub">Capire come lavora una vera azienda: scadenze, richieste dei clienti e l'importanza di comunicare bene ciò che si sta facendo.</span></div></div>
    </div>
  </div>
</section>

<!-- competenze -->
<section class="win" id="w-comp" style="left:26vw; top:120px; width:min(540px, 92vw); height:min(500px, calc(100vh - 180px));">
  <div class="titlebar"><span class="wt">Competenze</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-wrench"/></svg>Skill</div>
    <h2>Cosa ho imparato</h2>
    <div class="chips">
      <span class="chip">Excel</span><span class="chip">Photoshop</span><span class="chip">Illustrator</span><span class="chip">WordPress</span><span class="chip">HTML &amp; CSS</span><span class="chip">Gestionale Embyon</span>
    </div>
    <div class="rows">
      <div class="row"><span class="rico"><svg><use href="#i-users"/></svg></span><div><b>Lavoro in team</b><span class="sub">Coordinarsi con colleghi e tutor, chiedere feedback, rispettare le consegne.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-phone"/></svg></span><div><b>Rapporto col cliente</b><span class="sub">Tradurre una richiesta generica in un risultato concreto (grafiche, sito, documenti).</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-db"/></svg></span><div><b>Gestione dei dati</b><span class="sub">Ordine e precisione: dai fogli Excel al gestionale aziendale.</span></div></div>
    </div>
  </div>
</section>

<!-- progetto -->
<section class="win" id="w-prog" style="left:30vw; top:95px; width:min(560px, 92vw); height:min(520px, calc(100vh - 170px));">
  <div class="titlebar"><span class="wt">Il progetto · come funziona questo sito</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-lock"/></svg>Dietro le quinte</div>
    <h2>Accesso, sessione, desktop</h2>
    <p class="lead">Il sito che stai usando è parte del progetto: login in PHP con database MySQL, e questo desktop raggiungibile solo dopo l'accesso.</p>
    <div class="rows">
      <div class="row"><span class="rico"><svg><use href="#i-monitor"/></svg></span><div><b>Richiesta senza ricaricare</b><span class="sub">Il form invia i dati con fetch in POST e il server risponde in JSON.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-shield"/></svg></span><div><b>Sicurezza</b><span class="sub">Codice verificato con hash bcrypt, sessione rigenerata al login, query con prepared statements.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-db"/></svg></span><div><b>Persistenza</b><span class="sub">Ogni accesso viene registrato su MySQL tramite PDO.</span></div></div>
    </div>
    <a class="pillbtn" href="schema.php"><svg><use href="#i-server"/></svg>Guarda lo schema tecnico<svg><use href="#i-arrow"/></svg></a>
  </div>
</section>

<!-- collegamenti -->
<section class="win" id="w-coll" style="left:34vw; top:115px; width:min(540px, 92vw); height:min(520px, calc(100vh - 180px));">
  <div class="titlebar"><span class="wt">Collegamenti con le materie</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-link"/></svg>Colloquio</div>
    <h2>Dal PCTO alle discipline</h2>
    <div class="rows">
      <div class="row"><span class="rico"><svg><use href="#i-net"/></svg></span><div><b>Sistemi e Reti</b><span class="sub">Client-server, HTTP e sessioni: gli stessi concetti dietro al login del progetto.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-db"/></svg></span><div><b>Informatica</b><span class="sub">PHP, database relazionali, sicurezza delle applicazioni web.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-globe"/></svg></span><div><b>Inglese</b><span class="sub">Documentazione tecnica e terminologia del settore IT.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-book"/></svg></span><div><b>Italiano</b><span class="sub">Il lavoro e la sua rappresentazione nella letteratura del Novecento.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-scale"/></svg></span><div><b>Educazione civica</b><span class="sub">Costituzione e lavoro: artt. 1, 4, 35-36 e la sicurezza nei luoghi di lavoro.</span></div></div>
    </div>
  </div>
</section>

<!-- extra -->
<section class="win" id="w-extra" style="left:38vw; top:85px; width:min(560px, 92vw); height:min(540px, calc(100vh - 160px));">
  <div class="titlebar"><span class="wt">Extra · oltre la scuola</span></div>
  <div class="wbody">
    <div class="eyebrow"><svg><use href="#i-rocket"/></svg>Esperienze</div>
    <h2>Cosa faccio fuori dall'aula</h2>
    <div class="rows">
      <div class="row"><span class="rico"><svg><use href="#i-code"/></svg></span><div><b>Pseudo-gestionale per l'oratorio di Bedizzole</b><span class="sub">2024–2026 · Un'applicazione costruita da zero per gestire le attività: il mio progetto personale più vicino al PCTO.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-spark"/></svg></span><div><b>Animatore ed educatore in oratorio</b><span class="sub">Da giugno 2023 · Responsabilità verso i più piccoli, organizzazione di attività e lavoro di squadra.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-heart"/></svg></span><div><b>Volontariato</b><span class="sub">2022–2026 · Festa del Sorriso e Torneo dei Roncai.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-music"/></svg></span><div><b>Violoncello</b><span class="sub">2018–2024 · Scuola di musica “Elia Marini” di Calcinato: disciplina e costanza.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-image"/></svg></span><div><b>Mostra “L'ultimo inverno” · MuSa, Salò</b><span class="sub">17/01/2026 · Partecipazione al progetto espositivo.</span></div></div>
      <div class="row"><span class="rico"><svg><use href="#i-pen"/></svg></span><div><b>Concorso letterario “Volo tra le Righe 3.0”</b><span class="sub">2025 · Scrittura creativa oltre il codice.</span></div></div>
    </div>
  </div>
</section>

<!-- dock -->
<nav class="dock">
  <div class="dapp" data-w="w-pres"><button class="lav"><svg><use href="#i-cap"/></svg></button><span class="tip">Presentazione</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-chi"><button class="rose"><svg><use href="#i-person"/></svg></button><span class="tip">Chi sono</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-az"><button class="mint"><svg><use href="#i-factory"/></svg></button><span class="tip">L'azienda</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-esp"><button class="peach"><svg><use href="#i-brief"/></svg></button><span class="tip">Il percorso</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-comp"><button class="sky"><svg><use href="#i-wrench"/></svg></button><span class="tip">Competenze</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-prog"><button class="lilac"><svg><use href="#i-lock"/></svg></button><span class="tip">Il progetto</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-coll"><button class="lemon"><svg><use href="#i-link"/></svg></button><span class="tip">Collegamenti</span><span class="dot"></span></div>
  <div class="dapp" data-w="w-extra"><button class="lav"><svg><use href="#i-rocket"/></svg></button><span class="tip">Extra</span><span class="dot"></span></div>
  <div class="sep"></div>
  <div class="dapp"><a href="schema.php"><svg><use href="#i-server"/></svg></a><span class="tip">Dietro le quinte</span><span class="dot"></span></div>
</nav>

<script src="hub.js"></script>
</body>
</html>
