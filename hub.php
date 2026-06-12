<?php
/* ============================================================
   hub.php — il desktop: la presentazione per il colloquio
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
<symbol id="i-list" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></symbol>
<symbol id="i-person" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></symbol>
<symbol id="i-brief" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></symbol>
<symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8L12 2z"/></symbol>
<symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
<symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
<symbol id="i-flag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><path d="M4 22v-7"/></symbol>
<symbol id="i-filetext" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></symbol>
<symbol id="i-fileuser" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><circle cx="12" cy="13" r="2"/><path d="M8.5 18.5c.5-1.5 2-2.5 3.5-2.5s3 1 3.5 2.5"/></symbol>
<symbol id="i-out" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></symbol>
<symbol id="i-wifi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><path d="M12 20h.01"/></symbol>
<symbol id="i-batt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="18" height="10" rx="2.5"/><path d="M23 11v2"/><rect x="4.5" y="9.5" width="11" height="5" rx="1" fill="currentColor" stroke="none"/></symbol>
<symbol id="i-monitor" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></symbol>
<symbol id="i-music" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></symbol>
<symbol id="i-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></symbol>
<symbol id="i-code" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></symbol>
<symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
<symbol id="i-target" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></symbol>
<symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></symbol>
<symbol id="i-server" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><path d="M6 6h.01M6 18h.01"/></symbol>
<symbol id="i-scale" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M9 21h6M3 7h18"/><path d="M6 7l-3 6a3 3 0 0 0 6 0L6 7zM18 7l-3 6a3 3 0 0 0 6 0l-3-6z"/></symbol>
<symbol id="i-compass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16.24 7.76l-2.12 6.36-6.36 2.12 2.12-6.36 6.36-2.12z"/></symbol>
</defs></svg>

<!-- sfondo: la scena del login, sempre dietro a tutto -->
<div class="bg"><div class="blob b1"></div><div class="blob b2"></div><div class="blob b3"></div></div>

<!-- menu bar -->
<nav class="menubar">
  <span class="logo"><svg><use href="#i-cap"/></svg></span>
  <span class="appname">Maturità 2026</span>
  <button class="mitem hideS" data-open="w-pres">Scaletta</button>
  <button class="mitem hideS" data-open="w-fsl">Percorso</button>
  <button class="mitem hideS" data-open="w-prog">Progetto</button>
  <div class="right">
    <span class="sico hideS"><svg><use href="#i-wifi"/></svg></span>
    <span class="sico hideS"><svg><use href="#i-batt"/></svg></span>
    <span class="who hideS"><span class="dot"></span><?= $nome ?></span>
    <span class="clock" id="clock"></span>
    <a class="mitem exit" href="logout.php"><svg><use href="#i-out"/></svg>Esci</a>
  </div>
</nav>

<!-- file sul desktop -->
<div class="deskicons">
  <button class="dicon a-pink" data-open="w-fsl"><span class="fico"><svg><use href="#i-filetext"/></svg></span><span>Diario di bordo.pdf</span></button>
  <button class="dicon a-blue" data-open="w-io"><span class="fico"><svg><use href="#i-fileuser"/></svg></span><span>Curriculum dello studente.pdf</span></button>
</div>

<!-- SCALETTA — aperta all'avvio: il percorso dei 10 minuti -->
<section class="win open a-blue" id="w-pres" style="left:calc(50% - 290px);top:64px;width:580px;height:calc(100vh - 200px)">
  <div class="titlebar"><span class="wt">La mia presentazione — 10 minuti</span></div>
  <div class="wbody">
    <div class="pres-hero">
      <div class="pava">FC</div>
      <h1>Filippo Corsini · <em>10 minuti</em></h1>
      <p>Questo desktop è la mia presentazione per il colloquio: sei capitoli, dal percorso personale all'esperienza in azienda, fino a questo progetto. Ogni app è un capitolo.</p>
    </div>
    <div class="caps">
      <button class="cap a-orange" data-open="w-io" style="animation-delay:.05s"><span class="num">1</span><span><b>Da dove parto</b><span class="sub">Il filo conduttore: costruire cose che le persone usano</span></span><span class="time">1'</span></button>
      <button class="cap a-green" data-open="w-fsl" style="animation-delay:.1s"><span class="num">2</span><span><b>Il percorso in azienda</b><span class="sub">Due anni in CS Metal Europe, 240 ore</span></span><span class="time">3'</span></button>
      <button class="cap a-indigo" data-open="w-skills" style="animation-delay:.15s"><span class="num">3</span><span><b>Cosa ho imparato</b><span class="sub">Tre lezioni dal lavoro vero, oltre gli strumenti</span></span><span class="time">2'</span></button>
      <button class="cap a-pink" data-open="w-prog" style="animation-delay:.2s"><span class="num">4</span><span><b>Il progetto</b><span class="sub">Questo sito: dal login al desktop</span></span><span class="time">2'</span></button>
      <button class="cap a-teal" data-open="w-coll" style="animation-delay:.25s"><span class="num">5</span><span><b>I collegamenti</b><span class="sub">Le materie dentro l'esperienza</span></span><span class="time">1'</span></button>
      <button class="cap a-purple" data-open="w-fine" style="animation-delay:.3s"><span class="num">6</span><span><b>Cosa porto via</b><span class="sub">Il bilancio e il prossimo passo</span></span><span class="time">1'</span></button>
    </div>
  </div>
</section>

<!-- 1 · DA DOVE PARTO -->
<section class="win a-orange" id="w-io" style="left:130px;top:90px;width:500px;height:520px">
  <div class="titlebar"><span class="wt">1 · Da dove parto</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai"><svg><use href="#i-person"/></svg></span><div><h2>Da dove parto</h2><p>Filippo Corsini · 5ª Informatica, ITIS Cerebotani</p></div></div>
    <p class="lead">Il filo conduttore del mio percorso è semplice: <strong>mi piace costruire cose che le persone usano davvero</strong>. Non è nato a scuola, né in azienda: è nato fuori — e scuola e azienda gli hanno dato un metodo.</p>
    <div class="rows">
      <div class="row"><span class="ai"><svg><use href="#i-code"/></svg></span><div><b>Il gestionale dell'oratorio</b><span class="sub">Dal 2024 ho progettato e mantengo un piccolo gestionale per l'oratorio di Bedizzole: il mio primo software con utenti veri, che si lamentano se qualcosa non funziona.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-heart"/></svg></span><div><b>Animatore ed educatore</b><span class="sub">Dal 2023 seguo i ragazzi dell'oratorio: prima delle tecnologie, ho imparato a lavorare con le persone.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-music"/></svg></span><div><b>Sei anni di violoncello</b><span class="sub">Dal 2018 al 2024: la disciplina dell'esercizio quotidiano, che è la stessa che serve per imparare a programmare.</span></div></div>
    </div>
  </div>
</section>

<!-- 2 · IL PERCORSO IN AZIENDA -->
<section class="win a-green" id="w-fsl" style="left:180px;top:80px;width:560px;height:580px">
  <div class="titlebar"><span class="wt">2 · Il percorso in azienda</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai"><svg><use href="#i-brief"/></svg></span><div><h2>Il percorso in azienda</h2><p>CS Metal Europe S.r.l. · Bedizzole (BS)</p></div></div>
    <p class="lead">Un centro servizi siderurgico del gruppo giapponese Proterial: <strong>12 persone</strong> che lavorano e distribuiscono acciai speciali. Piccola abbastanza da vedere tutto, vera abbastanza da contare.</p>
    <div class="stats"><div class="stat"><b>240</b><span>Ore totali</span></div><div class="stat"><b>2</b><span>Anni scolastici</span></div><div class="stat"><b>12</b><span>Persone in azienda</span></div></div>
    <div class="tl">
      <div class="tle"><span class="tag">Terza · aprile 2024 · 120 ore</span><b>Imparare osservando</b><p>Analisi dei dati con Excel, contenuti promozionali con Photoshop e Illustrator, l'invito per un evento aziendale, la sistemazione del sito WordPress con la nuova sezione blog e le prime ore sul gestionale Embyon.</p></div>
      <div class="tle"><span class="tag">Quarta · a.s. 2024/25 · 120 ore</span><b>Lavorare con autonomia</b><p>Stessa azienda, ruolo diverso: conoscevo persone e strumenti, così i compiti sono diventati miei dall'inizio alla fine — meno supervisione, più responsabilità. È qui che lo stage è diventato lavoro.</p></div>
    </div>
  </div>
</section>

<!-- 3 · COSA HO IMPARATO -->
<section class="win a-indigo" id="w-skills" style="left:230px;top:96px;width:520px;height:520px">
  <div class="titlebar"><span class="wt">3 · Cosa ho imparato</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai"><svg><use href="#i-spark"/></svg></span><div><h2>Cosa ho imparato</h2><p>Tre lezioni, oltre gli strumenti</p></div></div>
    <div class="rows">
      <div class="row"><span class="ai"><svg><use href="#i-target"/></svg></span><div><b>Gli strumenti passano, il metodo resta</b><span class="sub">Excel, Photoshop, WordPress, un ERP: ognuno si impara. Quello che resta è il modo di affrontare un compito nuovo: capire, provare, chiedere.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-users"/></svg></span><div><b>Il lavoro è fatto di persone</b><span class="sub">In un ufficio piccolo i compiti si incastrano: consegnare in ritardo o capire male una richiesta ha un costo per qualcun altro. Ascoltare bene è metà del lavoro.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-scale"/></svg></span><div><b>La precisione non è un dettaglio</b><span class="sub">Un dato sbagliato in un gestionale diventa un ordine sbagliato. Ricontrollare non è perdere tempo: è il lavoro.</span></div></div>
    </div>
    <div class="chips"><span class="chip">Excel</span><span class="chip">Photoshop</span><span class="chip">Illustrator</span><span class="chip">WordPress</span><span class="chip">Embyon (ERP)</span></div>
  </div>
</section>

<!-- 4 · IL PROGETTO -->
<section class="win a-pink" id="w-prog" style="left:280px;top:86px;width:560px;height:560px">
  <div class="titlebar"><span class="wt">4 · Il progetto</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai"><svg><use href="#i-lock"/></svg></span><div><h2>Il progetto</h2><p>Questo sito: login PHP + MySQL, costruito da me</p></div></div>
    <p class="lead">Non una simulazione: <strong>il sito che state usando adesso</strong>. Per entrare avete passato un login vero, con le stesse difese che ho visto servire in azienda.</p>
    <div class="steps">
      <div class="step"><span class="n">1</span><div><b>Il browser invia il codice</b><span class="sub">Il form spedisce il codice in POST senza ricaricare la pagina.</span><code>fetch('login.php', { method: 'POST', … })</code></div></div>
      <div class="step"><span class="n">2</span><div><b>PHP verifica in sicurezza</b><span class="sub">Il codice non esiste in chiaro: confronto con hash bcrypt e sessione rigenerata contro il session fixation.</span><code>password_verify($codice, $hash)</code></div></div>
      <div class="step"><span class="n">3</span><div><b>MySQL registra l'accesso</b><span class="sub">Ogni ingresso finisce nel database via PDO con prepared statement, la difesa standard contro le SQL injection.</span><code>INSERT INTO accessi (nome, ip) VALUES (?, ?)</code></div></div>
    </div>
    <a class="pillbtn ghost" href="index.php?demo=1"><svg><use href="#i-monitor"/></svg>Rivedi il login in modalità demo</a>
  </div>
</section>

<!-- 5 · I COLLEGAMENTI -->
<section class="win a-teal" id="w-coll" style="left:210px;top:92px;width:540px;height:540px">
  <div class="titlebar"><span class="wt">5 · I collegamenti</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai"><svg><use href="#i-link"/></svg></span><div><h2>I collegamenti</h2><p>Le materie dentro l'esperienza, non accanto</p></div></div>
    <div class="rows">
      <div class="row"><span class="ai"><svg><use href="#i-code"/></svg></span><div><b>Informatica</b><span class="sub">Database, PHP e sicurezza: hash, sessioni e prepared statement — visti in azienda, rifatti qui con le mie mani.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-server"/></svg></span><div><b>Sistemi e Reti</b><span class="sub">Il viaggio di una richiesta HTTP dal browser al database, attraverso Apache e i container Docker di questo sito.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-globe"/></svg></span><div><b>Inglese</b><span class="sub">In un gruppo giapponese la documentazione tecnica e il gestionale parlano inglese: usarlo è la normalità.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-scale"/></svg></span><div><b>Educazione civica</b><span class="sub">Artt. 1, 4 e 35–36 della Costituzione: il lavoro come diritto, dovere e dignità — visto da dentro un'azienda vera.</span></div></div>
    </div>
  </div>
</section>

<!-- 6 · COSA PORTO VIA -->
<section class="win a-purple" id="w-fine" style="left:260px;top:100px;width:500px;height:480px">
  <div class="titlebar"><span class="wt">6 · Cosa porto via</span></div>
  <div class="wbody">
    <div class="whead"><span class="ai"><svg><use href="#i-compass"/></svg></span><div><h2>Cosa porto via</h2><p>Il bilancio, in tre righe</p></div></div>
    <div class="rows">
      <div class="row"><span class="ai"><svg><use href="#i-target"/></svg></span><div><b>Una conferma</b><span class="sub">Costruire software che le persone usano è quello che voglio fare: l'oratorio me lo aveva suggerito, l'azienda me lo ha confermato.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-users"/></svg></span><div><b>Una scoperta</b><span class="sub">Le competenze tecniche aprono la porta, ma sono le persone a farti crescere: il tutor, i colleghi, i ragazzi dell'oratorio.</span></div></div>
      <div class="row"><span class="ai"><svg><use href="#i-flag"/></svg></span><div><b>Una direzione</b><span class="sub">Dopo il diploma voglio continuare a studiare e a costruire: progetti sempre più grandi, sempre con utenti veri. Questo desktop è il primo passo.</span></div></div>
    </div>
  </div>
</section>

<!-- dock: un'app per capitolo, un colore per app -->
<nav class="dock" id="dock">
  <div class="dapp a-blue" data-w="w-pres"><span class="tip">Scaletta</span><button class="ai"><svg width="24" height="24"><use href="#i-list"/></svg></button><span class="dot"></span></div>
  <div class="dapp a-orange" data-w="w-io"><span class="tip">Da dove parto</span><button class="ai"><svg width="24" height="24"><use href="#i-person"/></svg></button><span class="dot"></span></div>
  <div class="dapp a-green" data-w="w-fsl"><span class="tip">Il percorso in azienda</span><button class="ai"><svg width="24" height="24"><use href="#i-brief"/></svg></button><span class="dot"></span></div>
  <div class="dapp a-indigo" data-w="w-skills"><span class="tip">Cosa ho imparato</span><button class="ai"><svg width="24" height="24"><use href="#i-spark"/></svg></button><span class="dot"></span></div>
  <div class="dapp a-pink" data-w="w-prog"><span class="tip">Il progetto</span><button class="ai"><svg width="24" height="24"><use href="#i-lock"/></svg></button><span class="dot"></span></div>
  <div class="dapp a-teal" data-w="w-coll"><span class="tip">I collegamenti</span><button class="ai"><svg width="24" height="24"><use href="#i-link"/></svg></button><span class="dot"></span></div>
  <div class="dapp a-purple" data-w="w-fine"><span class="tip">Cosa porto via</span><button class="ai"><svg width="24" height="24"><use href="#i-compass"/></svg></button><span class="dot"></span></div>
</nav>

<script src="hub.js"></script>
</body>
</html>
