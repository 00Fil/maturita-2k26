<?php
session_start();
if (!isset($_SESSION['nome'])) { header('Location: index.php'); exit; }
$nome = htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8');
$boot = empty($_SESSION['booted']) || isset($_GET['boot']);
$_SESSION['booted'] = true;

$RAW = 'https://raw.githubusercontent.com/vinceliuice/WhiteSur-icon-theme/3cc051a4709e67921a9d47cd2a3e0111bbe5e2bd';
function appicon(string $file, string $remote): string {
  global $RAW;
  return '<img src="assets/icons/' . $file . '" alt="" draggable="false" onerror="this.onerror=null;this.src=\'' . $RAW . $remote . '\'">';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Desktop · Maturità 2026</title>
<link rel="stylesheet" href="hub.css?v=<?= @filemtime(__DIR__ . '/hub.css') ?>">
<link rel="stylesheet" href="hub-polish.css?v=<?= @filemtime(__DIR__ . '/hub-polish.css') ?>">
<?php if ($boot): ?><link rel="preload" href="assets/iisc-logo.png" as="image" fetchpriority="high"><?php endif; ?>
</head>
<body<?= $boot ? ' class="booting"' : '' ?>>

<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
<symbol id="i-cap" viewBox="0 0 56 56" fill="currentColor"><path d="M33.9370467,26.5128481 C34.0071746,34.0650232 40.5622987,36.5781967 40.6349206,36.6102741 C40.579494,36.787517 39.5875211,40.1917925 37.1813698,43.708166 C35.1013579,46.748249 32.9425798,49.7771957 29.5418665,49.8399278 C26.2003256,49.9014987 25.1258336,47.8583854 21.3055063,47.8583854 C17.486333,47.8583854 16.2925236,49.7771906 13.129383,49.9014987 C9.84683186,50.0257159 7.34720367,46.6140516 5.24990601,43.5851051 C0.964332248,37.3892927 -2.31073284,26.0771949 2.08685175,18.4413444 C4.27147795,14.64935 8.17557011,12.2481009 12.4131131,12.1865253 C15.6364944,12.125039 18.6789832,14.3551087 20.649477,14.3551087 C22.6187214,14.3551087 26.3159929,11.6732544 30.2027979,12.0671186 C31.8299473,12.1348421 36.3973824,12.7243949 39.3302579,17.0173633 C39.0939405,17.1638624 33.880373,20.1989532 33.9370467,26.5128552 M27.6570067,7.96804814 C29.3997351,5.85854475 30.5726917,2.92192094 30.2526965,0 C27.7406844,0.100960353 24.7030975,1.67393506 22.9012871,3.78227787 C21.286519,5.64931362 19.8723452,8.63762341 20.2539138,11.5017132 C23.0538462,11.7183402 25.9141925,10.078893 27.6570067,7.96805157" transform="translate(8 3)"/></symbol>
<symbol id="i-wifi" viewBox="0 0 56 56" fill="currentColor"><path d="M 5.4648 25.0352 C 5.9102 25.4805 6.5664 25.4571 6.9882 25.0118 C 12.5195 19.1289 19.8320 16.0352 28.0117 16.0352 C 36.2382 16.0352 43.5742 19.1523 49.0819 25.0352 C 49.4803 25.4336 50.1135 25.4336 50.5354 24.9883 L 53.6525 21.8711 C 54.0274 21.4727 54.0274 20.9571 53.7226 20.5820 C 48.4258 14.0664 38.4648 9.2617 28.0117 9.2617 C 17.5586 9.2617 7.5976 14.0664 2.3007 20.5820 C 1.9726 20.9571 1.9961 21.4727 2.3711 21.8711 Z M 14.8398 34.4336 C 15.3086 34.9258 15.9180 34.8789 16.3633 34.3633 C 19.0820 31.3398 23.4882 29.1602 28.0117 29.2071 C 32.5820 29.1602 36.9648 31.4102 39.7070 34.4336 C 40.1523 34.9023 40.7382 34.9023 41.1836 34.4102 L 44.6758 30.9649 C 45.0507 30.5898 45.0976 30.0977 44.7461 29.6992 C 41.3476 25.5039 35.0429 22.4102 28.0117 22.4102 C 20.9804 22.4102 14.6758 25.5274 11.2773 29.6992 C 10.9258 30.0977 10.9726 30.5664 11.3476 30.9649 Z M 28.0117 46.7383 C 28.5039 46.7383 28.9492 46.4805 29.8164 45.6367 L 35.3007 40.3633 C 35.6523 40.0352 35.7226 39.5196 35.4180 39.1211 C 33.9414 37.2227 31.1758 35.5820 28.0117 35.5820 C 24.7773 35.5820 21.9648 37.2930 20.5117 39.2617 C 20.3007 39.5898 20.3711 40.0352 20.7226 40.3633 L 26.2070 45.6367 C 27.0742 46.4805 27.5195 46.7383 28.0117 46.7383 Z"/></symbol>
<symbol id="i-batt" viewBox="0 0 56 56" fill="currentColor"><path d="M 9.4633 42.0000 L 40.8481 42.0000 C 43.8786 42.0000 46.4304 41.7165 48.2379 39.9089 C 50.0457 38.1013 50.2935 35.5848 50.2935 32.5545 L 50.2935 23.4633 C 50.2935 20.4329 50.0457 17.8987 48.2379 16.1089 C 46.4127 14.3013 43.8786 14.0000 40.8481 14.0000 L 9.4101 14.0000 C 6.4329 14.0000 3.8810 14.3013 2.0734 16.1089 C .2658 17.9164 0 20.4329 0 23.4101 L 0 32.5545 C 0 35.5848 .2658 38.1190 2.0734 39.9089 C 3.8810 41.7165 6.4329 42.0000 9.4633 42.0000 Z M 8.9671 39.1468 C 7.1418 39.1468 5.2279 38.8987 4.1646 37.8355 C 3.0835 36.7544 2.8532 34.8759 2.8532 33.0506 L 2.8532 23.0202 C 2.8532 21.1595 3.0835 19.2456 4.1468 18.1823 C 5.2279 17.1013 7.1595 16.8532 9.0203 16.8532 L 41.3442 16.8532 C 43.1520 16.8532 45.0836 17.1190 46.1470 18.1823 C 47.2278 19.2633 47.4405 21.1418 47.4405 22.9671 L 47.4405 33.0506 C 47.4405 34.8759 47.2100 36.7544 46.1470 37.8355 C 45.0836 38.9165 43.1520 39.1468 41.3442 39.1468 Z M 8.6126 36.7367 L 41.6988 36.7367 C 43.0101 36.7367 43.7367 36.5595 44.3036 36.0101 C 44.8709 35.4430 45.0302 34.6988 45.0302 33.4051 L 45.0302 22.6127 C 45.0302 21.3013 44.8531 20.5747 44.3036 20.0076 C 43.7367 19.4405 43.0101 19.2810 41.6988 19.2810 L 8.6126 19.2810 C 7.3013 19.2810 6.5747 19.4405 6.0076 20.0076 C 5.4405 20.5747 5.2810 21.3013 5.2810 22.6127 L 5.2810 33.4051 C 5.2810 34.6988 5.4405 35.4430 6.0076 36.0101 C 6.5747 36.5595 7.3013 36.7367 8.6126 36.7367 Z M 52.7216 32.4835 C 54.1214 32.3949 56 30.6051 56 27.8937 C 56 25.2000 54.1214 23.4101 52.7216 23.3215 Z"/></symbol>
<symbol id="i-out" viewBox="0 0 56 56" fill="currentColor"><path d="M 13.7851 49.5742 L 42.2382 49.5742 C 47.1366 49.5742 49.5743 47.1367 49.5743 42.3086 L 49.5743 13.6914 C 49.5743 8.8633 47.1366 6.4258 42.2382 6.4258 L 13.7851 6.4258 C 8.9101 6.4258 6.4257 8.8398 6.4257 13.6914 L 6.4257 42.3086 C 6.4257 47.1602 8.9101 49.5742 13.7851 49.5742 Z M 42.2851 27.9414 C 42.2851 28.5508 42.0507 29.0196 41.5351 29.5352 L 32.5585 38.4883 C 32.2070 38.8164 31.7382 39.0508 31.1757 39.0508 C 30.0742 39.0508 29.2070 38.2071 29.2070 37.1055 C 29.2070 36.5196 29.4648 36.0274 29.8163 35.6758 L 33.1210 32.4414 L 36.3085 29.7461 L 30.6601 29.9571 L 16.9257 29.9571 C 15.7304 29.9571 14.9335 29.1133 14.9335 27.9414 C 14.9335 26.7696 15.7304 25.9258 16.9257 25.9258 L 30.6601 25.9258 L 36.2851 26.1602 L 33.1210 23.4883 L 29.8163 20.2071 C 29.4882 19.8789 29.2070 19.3633 29.2070 18.8008 C 29.2070 17.6992 30.0742 16.8555 31.1757 16.8555 C 31.7382 16.8555 32.2070 17.0430 32.5585 17.3945 L 41.5351 26.3711 C 42.0742 26.9102 42.2851 27.3789 42.2851 27.9414 Z"/></symbol>
</defs></svg>

<?php if ($boot): ?>
<div id="boot" aria-hidden="true"><img src="assets/iisc-logo.png" alt="" fetchpriority="high" decoding="async"><div class="bbar"><span></span></div></div>
<?php endif; ?>

<div class="bg"><div class="blob b1"></div><div class="blob b2"></div><div class="blob b3"></div></div>

<nav class="menubar">
  <button class="logo" data-open="w-pres" aria-label="Apri la presentazione"><svg><use href="#i-cap"/></svg></button>
  <span class="appname">Maturità 2026</span>
  <button class="mitem hideS" data-open="w-pres">Scaletta</button>
  <button class="mitem hideS" data-open="w-io">Su di me</button>
  <button class="mitem hideS" data-open="w-fsl">Azienda</button>
  <button class="mitem hideS" data-open="w-fine">Dopo</button>
  <div class="right">
    <span class="sico hideS"><span class="bpct" id="bpct"></span><svg><use href="#i-batt"/></svg></span>
    <span class="sico hideS" id="mb-wifi"><svg><use href="#i-wifi"/></svg></span>
    <button class="ccbtn" id="ccbtn" aria-label="Centro di Controllo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="2.75" y="3.75" width="18.5" height="7" rx="3.5"/><circle cx="6.75" cy="7.25" r="2.1" fill="currentColor" stroke="none"/><rect x="2.75" y="13.25" width="18.5" height="7" rx="3.5"/><circle cx="17.25" cy="16.75" r="2.1" fill="currentColor" stroke="none"/></svg></button>
    <span class="who hideS"><span class="dot"></span><?= $nome ?></span>
    <span class="clock" id="clock"></span>
    <a class="mitem reboot hideS" href="hub.php?boot=1"><svg viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"><path d="M44 28a16 16 0 1 1-4.7-11.3"/><path d="M40 6v11h-11"/></svg>Riavvia</a>
    <a class="mitem exit" href="logout.php"><svg viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round"><path d="M28 8v20"/><path d="M40.8 15.2a18 18 0 1 1-25.6 0"/></svg>Spegni</a>
  </div>
</nav>

<div id="dim" aria-hidden="true"></div>

<div class="ccpanel" id="ccpanel">
  <div class="ccgrid">
    <div class="ccmod ccconn">
      <button class="ccrow" id="cc-wifi" type="button">
        <span class="ccico on" id="cc-wifi-ic"><svg><use href="#i-wifi"/></svg></span>
        <span class="cctxt"><b>Wi-Fi</b><small id="cc-wifi-st">Connesso</small></span>
      </button>
      <button class="ccrow" id="cc-share" type="button">
        <span class="ccico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="2" fill="currentColor" stroke="none"/><path d="M7.4 16.6a6.5 6.5 0 0 1 0-9.2M16.6 7.4a6.5 6.5 0 0 1 0 9.2M4.6 19.4a10.5 10.5 0 0 1 0-14.8M19.4 4.6a10.5 10.5 0 0 1 0 14.8"/></svg></span>
        <span class="cctxt"><b>AirDrop</b><small>Condividi il sito</small></span>
      </button>
      <button class="ccrow" id="cc-copy" type="button">
        <span class="ccico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M10.2 13.8a3.6 3.6 0 0 0 5.4.4l2.6-2.6a3.6 3.6 0 1 0-5.1-5.1l-1.3 1.3"/><path d="M13.8 10.2a3.6 3.6 0 0 0-5.4-.4l-2.6 2.6a3.6 3.6 0 1 0 5.1 5.1l1.3-1.3"/></svg></span>
        <span class="cctxt"><b>Copia link</b><small id="cc-copy-st">Per la commissione</small></span>
      </button>
    </div>
    <div class="cccol">
      <button class="ccmod ccfocus" id="cc-pres" type="button">
        <span class="ccico" style="background:var(--purple)"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.8c0-1 1.1-1.6 2-1.1l9.2 6.2c.8.5.8 1.7 0 2.2L10 19.3c-.9.5-2-.1-2-1.1z"/></svg></span>
        <span class="cctxt"><b>Presentazione</b><small>Avvia i 10 minuti</small></span>
      </button>
      <div class="ccduo">
        <button class="ccmini" id="cc-full" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4H4v5M15 4h5v5M9 20H4v-5M15 20h5v-5"/></svg>Schermo intero</button>
        <button class="ccmini" id="cc-boot" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M12 3v8"/><path d="M6.3 6.6a8 8 0 1 0 11.4 0"/></svg>Riavvia demo</button>
      </div>
    </div>
  </div>
  <div class="ccmod ccsl">
    <b class="cclab">Schermo</b>
    <div class="ccrange"><input type="range" id="cc-bri" min="55" max="100" value="100" aria-label="Luminosità"><span class="cic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3.6"/><path d="M12 2.8v2M12 19.2v2M2.8 12h2M19.2 12h2M5.5 5.5l1.4 1.4M17.1 17.1l1.4 1.4M18.5 5.5l-1.4 1.4M6.9 17.1l-1.4 1.4"/></svg></span></div>
  </div>
  <div class="ccmod ccsl">
    <b class="cclab">Audio</b>
    <div class="ccrange"><input type="range" id="cc-vol" min="0" max="100" value="25" aria-label="Volume effetti"><span class="cic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5h3L11.5 6v12L7 14.5H4z" fill="currentColor" stroke="none"/><path d="M14.5 9.2a4 4 0 0 1 0 5.6M17.2 6.8a7.6 7.6 0 0 1 0 10.4"/></svg></span></div>
  </div>
</div>

<div class="deskicons">
  <button class="dicon" data-open="w-fsl"><span class="fico"><img src="assets/icons/pdf.svg" alt="" draggable="false"></span><span>Diario di bordo.pdf</span></button>
  <button class="dicon" data-open="w-io"><span class="fico"><img src="assets/icons/pdf.svg" alt="" draggable="false"></span><span>Curriculum dello studente.pdf</span></button>
</div>

<section class="win open a-blue" id="w-pres" style="left:7%;top:8%;width:760px">
  <div class="titlebar"><span class="wt">Presentazione</span></div>
  <div class="split">
    <aside class="sidebar">
      <div class="sb-title">Preferiti</div>
      <button class="sb-item on" data-open="w-pres"><span class="ic" style="background:#0A84FF">10′</span>Presentazione<span class="cnt">5</span></button>
      <button class="sb-item" data-open="w-io"><span class="ic" style="background:#FF9500">Io</span>Su di me</button>
      <button class="sb-item" data-open="w-skills"><span class="ic" style="background:#5856D6">Fu</span>Fuori dall'aula</button>
      <button class="sb-item" data-open="w-fsl"><span class="ic" style="background:#34C759">CS</span>CS Metal Europe</button>
      <button class="sb-item" data-open="w-fine"><span class="ic" style="background:#AF52DE">→</span>Dove voglio andare</button>
      <div class="sb-title">Tag</div>
      <button class="sb-item" data-tag="rifl"><span class="dotk" style="background:#0A84FF"></span>Riflessione</button>
      <button class="sb-item" data-tag="fuori"><span class="dotk" style="background:#5856D6"></span>Fuori dall'aula</button>
      <button class="sb-item" data-tag="fsl"><span class="dotk" style="background:#34C759"></span>Esperienza FSL</button>
    </aside>
    <div class="main">
      <div class="ftools"><b>I miei 10 minuti</b><span>Apro io raccontando la mia storia, poi rispondo alle domande della commissione.</span><span class="fseg"><button class="on" data-view="gallery">Galleria</button><button data-view="list">Elenco</button></span></div>
      <div class="fgrid">
        <button class="fitem lgcard" data-open="w-io" data-tag="rifl" style="animation-delay:.05s"><span class="fbadge" style="background:#FF9500">01</span><b>Su di me</b><span>Chi sono, cosa mi appassiona e da dove nasce la mia voglia di costruire.</span><span class="tt">Apri · Informazioni</span></button>
        <button class="fitem lgcard" data-open="w-skills" data-tag="fuori" style="animation-delay:.1s"><span class="fbadge" style="background:#5856D6">02</span><b>Fuori dall'aula</b><span>I progetti, i concorsi e il volontariato che ho seguito fuori dalla scuola.</span><span class="tt">Apri · Launchpad</span></button>
        <button class="fitem lgcard" data-open="w-fsl" data-tag="fsl" style="animation-delay:.15s"><span class="fbadge" style="background:#34C759">03</span><b>CS Metal Europe</b><span>Le 240 ore di alternanza in azienda e cosa mi hanno insegnato.</span><span class="tt">Apri · Calendario</span></button>
        <button class="fitem lgcard" data-open="w-fine" data-tag="rifl" style="animation-delay:.2s"><span class="fbadge" style="background:#AF52DE">04</span><b>Dove voglio andare</b><span>Il percorso che voglio seguire dopo il diploma.</span><span class="tt">Apri · Mappe</span></button>
        <button class="fitem lgcard" data-spot data-tag="fsl" style="animation-delay:.25s"><span class="fbadge" style="background:#30B0C7">05</span><b>Spotlight</b><span>La frase con cui voglio chiudere, prima delle domande.</span><span class="tt">Apri · Spotlight</span></button>
      </div>
    </div>
  </div>
</section>

<section class="win a-orange" id="w-io" style="left:8%;top:6%;width:880px">
  <div class="titlebar"><span class="wt">Su di me — Informazioni</span></div>
  <div class="wbody iox">
    <div class="iox-head">
      <span class="cavatar">FC</span>
      <div class="cid"><h2>Filippo Corsini</h2><p>Diploma Tecnico · Informatica e Telecomunicazioni — IIS «Cerebotani», Lonato</p></div>
      <div class="cacts"><a class="cact" href="mailto:ciao@denuvo.studio"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="m2 7 10 6L22 7"/></svg>mail</a><a class="cact" href="https://github.com/00Fil" target="_blank" rel="noopener"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 8-4 4 4 4"/><path d="m16 8 4 4-4 4"/><path d="m13 5-2 14"/></svg>GitHub</a></div>
    </div>
    <div class="iox-stats">
      <span class="iox-stat"><b>29</b><small>Credito</small></span>
      <span class="iox-stat"><b>240</b><small>Ore PCTO</small></span>
      <span class="iox-stat"><b>B2</b><small>Inglese QCER</small></span>
      <span class="iox-stat"><b>4</b><small>Livello EQF</small></span>
    </div>
    <div class="iox-cols">
      <div class="iox-col">
        <div class="iox-hero">
          <span class="as-kicker">In una riga</span>
          <h3>Capisco come funzionano le cose, poi le costruisco.</h3>
          <p>È la curiosità che mi ha portato a scegliere informatica, a realizzare progetti miei e a trasformarli in qualcosa di concreto — fino al mio primo contratto.</p>
        </div>
        <div class="iox-block">
          <div class="iox-lbl">Il percorso ufficiale</div>
          <div class="iox-row" style="--kc:#FF9500"><span class="iox-k">Indirizzo</span><div class="iox-tx"><b>Informatica e Telecomunicazioni.</b><p>Cinque anni all’IIS «Cerebotani» di Lonato tra programmazione, sistemi e reti. Diploma di livello EQF 4.</p></div></div>
          <div class="iox-row" style="--kc:#FF9500"><span class="iox-k">Competenze</span><div class="iox-tx"><b>Reti, sistemi, sviluppo, progetti.</b><p>Il profilo tecnico, con inglese B2 (QCER) per leggere la documentazione e lavorare in contesti internazionali.</p></div></div>
          <div class="iox-row" style="--kc:#34C759"><span class="iox-k">In azienda</span><div class="iox-tx"><b>240 ore alla CS Metal Europe.</b><p>Dati di produzione, comunicazione e un e-commerce vero con un gestionale in PHP: il mio primo contratto.</p></div></div>
        </div>
      </div>
      <div class="iox-col">
        <div class="iox-block">
          <div class="iox-lbl">Risultati e progetti</div>
          <div class="iox-card" style="--kc:#0A84FF"><span class="iox-tag">Concorso · vinto</span><b>«Volo tra le Righe 3.0»</b><p>Un romanzo raccontato con una playlist narrativa: vittoria nella categoria playlist.</p></div>
          <div class="iox-card" style="--kc:#5856D6"><span class="iox-tag">Progetto</span><b>Gestionale per l’oratorio di Bedizzole</b><p>Dal 2024, un software costruito con le competenze di scuola e tanti approfondimenti personali.</p></div>
          <div class="iox-card" style="--kc:#34C759"><span class="iox-tag">Online</span><b>denuvo.studio</b><p>Il sito dove pubblico quello che costruisco, curando ogni dettaglio dal codice alla grafica.</p></div>
        </div>
        <div class="iox-block">
          <div class="iox-lbl">Chi sono, oltre i voti</div>
          <div class="iox-card" style="--kc:#AF52DE"><span class="iox-tag">Dal 2018</span><b>Suono il violoncello</b><p>Mi ha insegnato la costanza: per migliorare mi esercito ogni giorno, con metodo.</p></div>
          <div class="iox-card" style="--kc:#FF9500"><span class="iox-tag">Comunità</span><b>Animatore in oratorio</b><p>Dal 2023, tra Torneo dei Roncai e Festa del Sorriso: organizzo e mi metto a disposizione.</p></div>
          <div class="iox-card" style="--kc:#FF3B30"><span class="iox-tag">Come sono</span><b>Determinato e attento ai dettagli</b><p>Porto a termine quello che inizio e lavoro bene in squadra, come nello sport.</p></div>
        </div>
      </div>
    </div>
    <div class="cnote">Le informazioni di questa scheda vengono dal mio <b>Curriculum dello studente</b> e dal percorso reale di questi cinque anni.</div>
  </div>
</section>

<section class="win a-indigo" id="w-skills" style="left:17%;top:7%;width:700px">
  <div class="titlebar"><span class="wt">Fuori dall'aula — Launchpad</span></div>
  <div class="wbody as-body">
    <div class="as-hero lgcard"><span class="as-kicker">Fuori da scuola</span><h3>Quello che faccio fuori dall'aula</h3><p>Fuori dalla scuola ho seguito progetti, concorsi e attività di volontariato. Sono le esperienze in cui ho imparato a collaborare e a portare a termine quello che inizio.</p></div>
    <div class="as-duo">
      <div class="as-mini lgcard" style="background:#0A84FF"><span class="as-kicker">Concorso · vinto</span><h4>Volo tra le Righe</h4><p>Ho presentato un romanzo con una playlist narrativa, unendo testo, musica e immagini. Il progetto ha vinto il concorso.</p></div>
      <div class="as-mini lgcard" style="background:#34C759"><span class="as-kicker">Dal progetto alla startup</span><h4>GPOI · Sportly</h4><p>Da un lavoro di gestione di progetto è nato Sportly, portato dall'idea iniziale fino al prototipo pubblicato online.</p></div>
    </div>
    <div class="as-sec">Le altre cose che mi tengono vivo</div>
    <div class="aprows">
      <div class="aprow lgcard"><span class="apic" style="background:#1C1C1E;color:#7AC74F">Mc</span><div><b>Server Minecraft</b><p>Il primo servizio online che ho gestito: un piccolo server con altre persone collegate a giocare insieme.</p></div><span class="getb">Fuori</span></div>
      <div class="aprow lgcard"><span class="apic" style="background:#5856D6">ds</span><div><b>denuvo.studio</b><p>Il mio sito personale, dove pubblico i progetti e curo ogni dettaglio, dal codice alla grafica.</p></div><span class="getb">Fuori</span></div>
      <div class="aprow lgcard"><span class="apic" style="background:#FF9500">Vo</span><div><b>Volontariato</b><p>Festa del Sorriso e Torneo dei Roncai: aiuto a organizzare e mi metto a disposizione degli altri.</p></div><span class="getb">Fuori</span></div>
      <div class="aprow lgcard"><span class="apic" style="background:#AF52DE">Ms</span><div><b>Musica e sport</b><p>Il violoncello e l'allenamento sono la disciplina che porto in tutto quello che faccio.</p></div><span class="getb">Fuori</span></div>
    </div>
    <div class="sf-note">Per me sono modi diversi di fare la stessa cosa: costruire qualcosa e portarlo fino in fondo.</div>
  </div>
</section>

<section class="win a-green" id="w-fsl" style="left:14%;top:8%;width:740px">
  <div class="titlebar"><span class="wt">CS Metal Europe — la mia alternanza</span></div>
  <div class="wbody" style="display:flex;flex-direction:column">
    <div class="cal-tb"><h3>Formazione scuola-lavoro <span>· CS Metal Europe</span></h3>
      <div class="cal-stats"><span class="cstat lgcard"><b>240</b><span>ore</span></span><span class="cstat lgcard"><b>2</b><span>anni</span></span><span class="cstat lgcard"><b>12</b><span>persone</span></span></div>
    </div>
    <div class="agenda">
      <div class="agroup"><h4><b>L'azienda e io</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#34C759"></span><span class="tm">Bedizzole</span><div><b>Acciai speciali, dentro il gruppo giapponese Proterial</b><p>CS Metal Europe è un'azienda di Bedizzole con dodici persone, parte del gruppo giapponese Proterial. Ho svolto 240 ore in due anni, in un unico percorso continuo.</p></div></div>
        </div>
      </div>
      <div class="agroup"><h4><b>Due anni di crescita</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#31A8FF"></span><span class="tm">3ª</span><div><b>I primi compiti: dati e grafica</b><p>Ho lavorato sui dati di produzione e sulle immagini dell'azienda, e ho seguito il sito e il blog. Così sono entrato nel modo di lavorare dell'azienda.</p></div></div>
          <div class="evt lgcard"><span class="bar" style="background:#FF9500"></span><span class="tm">4ª</span><div><b>Dal magazzino alla comunicazione</b><p>Sono passato dalla gestione del magazzino e dei materiali alla comunicazione dell'azienda sui social, vedendo la stessa realtà da due lati diversi.</p></div></div>
        </div>
      </div>
      <div class="agroup"><h4><b>La difficoltà che mi ha cambiato</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#FF3B30"></span><span class="tm">La lezione</span><div><b>Scrivere vuol dire pensare a chi legge</b><p>Preparando l'invito a un evento ho capito che ogni parola ha uno scopo e va scelta pensando a chi legge.</p></div></div>
        </div>
      </div>
      <div class="agroup"><h4><b>Il risultato</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#1D6F42"></span><span class="tm">Contratto</span><div><b>Un e-commerce vero e il mio primo contratto</b><p>Ho realizzato un negozio online e la vendita su Amazon, con un programma in PHP che mantiene allineato il catalogo. Per questo lavoro ho firmato il mio primo contratto.</p></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="win a-blue" id="w-fine" style="left:9%;top:7%;width:940px">
  <div class="titlebar"><span class="wt">Dove voglio andare — Mappe</span></div>
  <div class="nav" id="nav">
    <svg class="nav-map" id="nav-map" viewBox="0 0 1000 640" preserveAspectRatio="xMidYMid slice">
      <g id="nav-cam">
        <rect x="-320" y="-280" width="1660" height="1220" fill="#E9E6DD"/>
        <path d="M-320 120 C150 170 320 70 520 120 C720 170 880 90 1340 140 L1340 -280 L-320 -280 Z" fill="#A9D2F0"/>
        <path d="M-320 120 C150 170 320 70 520 120 C720 170 880 90 1340 140" stroke="#93C2E6" stroke-width="2" fill="none"/>
        <circle cx="120" cy="560" r="92" fill="#C9E6B4"/>
        <circle cx="-130" cy="700" r="120" fill="#C9E6B4"/>
        <circle cx="980" cy="560" r="86" fill="#C9E6B4"/>
        <circle cx="1120" cy="300" r="96" fill="#C9E6B4"/>
        <rect x="430" y="470" width="170" height="150" rx="22" fill="#C9E6B4"/>
        <path d="M-320 360 C220 340 480 392 1340 350" stroke="#fff" stroke-width="26" fill="none" stroke-linecap="round"/>
        <path d="M-320 520 C260 506 520 548 1340 500" stroke="#fff" stroke-width="15" fill="none" stroke-linecap="round"/>
        <path d="M-320 210 C240 196 560 232 1340 205" stroke="#fff" stroke-width="11" fill="none"/>
        <path d="M-320 760 C240 748 560 784 1340 756" stroke="#fff" stroke-width="13" fill="none"/>
        <path d="M-320 60 C200 50 520 92 1340 60" stroke="#fff" stroke-width="9" fill="none"/>
        <path d="M180 -280 C210 160 150 420 200 940" stroke="#fff" stroke-width="15" fill="none"/>
        <path d="M520 -280 C500 180 560 420 520 940" stroke="#fff" stroke-width="18" fill="none"/>
        <path d="M820 -280 C850 160 800 440 840 940" stroke="#fff" stroke-width="12" fill="none"/>
        <path d="M-80 -280 C-50 160 -110 420 -60 940" stroke="#fff" stroke-width="11" fill="none"/>
        <path d="M1120 -280 C1150 160 1090 440 1140 940" stroke="#fff" stroke-width="12" fill="none"/>
        <rect x="250" y="150" width="90" height="56" rx="9" fill="#DDD6C7"/>
        <rect x="120" y="250" width="64" height="58" rx="9" fill="#DDD6C7"/>
        <rect x="600" y="150" width="78" height="52" rx="9" fill="#DDD6C7"/>
        <rect x="700" y="430" width="80" height="56" rx="9" fill="#DDD6C7"/>
        <rect x="330" y="600" width="66" height="46" rx="9" fill="#DDD6C7"/>
        <rect x="900" y="250" width="70" height="50" rx="9" fill="#DDD6C7"/>
        <rect x="-180" y="320" width="76" height="58" rx="9" fill="#DDD6C7"/>
        <rect x="1180" y="430" width="80" height="56" rx="9" fill="#DDD6C7"/>
        <rect x="1010" y="120" width="72" height="50" rx="9" fill="#DDD6C7"/>
        <rect x="40" y="680" width="80" height="56" rx="9" fill="#DDD6C7"/>
        <!-- percorso: casing bianco + tracciato + parte percorsa -->
        <path id="nav-route-base" d="M170 520 C280 478 330 472 390 430 C480 372 510 360 610 300 C710 240 760 210 850 150" fill="none" stroke="#ffffff" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
        <path id="nav-route" d="M170 520 C280 478 330 472 390 430 C480 372 510 360 610 300 C710 240 760 210 850 150" fill="none" stroke="#A7BED6" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
        <path id="nav-route-done" pathLength="1" d="M170 520 C280 478 330 472 390 430 C480 372 510 360 610 300 C710 240 760 210 850 150" fill="none" stroke="#0A84FF" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1 1" stroke-dashoffset="1"/>
        <!-- segnaposto (senza didascalie) -->
        <g class="mk" id="m0" transform="translate(170 520)"><circle r="7" fill="#34C759" stroke="#fff" stroke-width="3"/></g>
        <g class="mk" id="m1" transform="translate(390 430)"><circle r="6" fill="#fff" stroke="#0A84FF" stroke-width="3"/></g>
        <g class="mk" id="m2" transform="translate(610 300)"><circle r="6" fill="#fff" stroke="#0A84FF" stroke-width="3"/></g>
        <g class="mk" id="m3" transform="translate(850 150)"><path d="M0 0 C-10 -14 -10 -25 0 -34 C10 -25 10 -14 0 0 Z" fill="#FF3B30" stroke="#fff" stroke-width="2.5"/><circle cx="0" cy="-22" r="5.5" fill="#fff"/></g>
        <!-- puck GPS -->
        <g id="nav-puck" transform="translate(170 520)">
          <circle r="13" fill="#0A84FF" opacity="0.18"><animate attributeName="r" values="11;22;11" dur="2s" repeatCount="indefinite"/><animate attributeName="opacity" values="0.20;0.03;0.20" dur="2s" repeatCount="indefinite"/></circle>
          <circle r="10" fill="#fff"/>
          <circle r="8" fill="#0A84FF"/>
          <path d="M0 -5 L4 4 L0 2 L-4 4 Z" fill="#fff"/>
        </g>
      </g>
    </svg>
    <div class="nav-stack">
      <div class="nav-bar">
        <span class="nav-man" id="nav-man"></span>
        <div class="nav-meta">
          <span class="nav-kick" id="nav-kick"></span>
          <div class="nav-dist" id="nav-dist"></div>
          <div class="nav-instr" id="nav-instr"></div>
        </div>
      </div>
    </div>
    <div class="nav-ctrl">
      <div class="nav-zoom"><button id="nav-zin" type="button" aria-label="Zoom avanti">+</button><span></span><button id="nav-zout" type="button" aria-label="Zoom indietro">−</button></div>
      <button class="nav-comp" id="nav-comp" type="button" aria-label="Nord"><svg viewBox="0 0 24 24"><path d="M12 4 L15 13 L12 11 L9 13 Z" fill="#FF3B30"/><path d="M12 20 L9 11 L12 13 L15 11 Z" fill="#8E8E93"/></svg></button>
    </div>
    <div class="nav-sheet">
      <div class="nav-sheet-main"><span class="nav-eta" id="nav-eta"></span><small id="nav-sub"></small></div>
      <div class="nav-dots" id="nav-dots"></div>
      <button class="nav-end" id="nav-end" type="button">Fine</button>
    </div>
  </div>
</section>

<div id="spot" class="spot" aria-hidden="true">
  <style>
    .spot{position:fixed;inset:0;z-index:4700;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding-top:20vh;background:rgba(0,0,0,.14);backdrop-filter:blur(7px) saturate(1.1);-webkit-backdrop-filter:blur(7px) saturate(1.1);opacity:0;pointer-events:none;transition:opacity .2s ease}
    .spot.on{opacity:1;pointer-events:auto}
    .spot-box{width:min(620px,92vw);display:flex;align-items:center;gap:18px;padding:15px 26px;border-radius:24px;background:rgba(245,245,247,.7);border:1px solid rgba(255,255,255,.65);box-shadow:0 26px 72px rgba(0,0,0,.34),inset 0 1px 0 rgba(255,255,255,.55);backdrop-filter:blur(42px) saturate(1.8);-webkit-backdrop-filter:blur(42px) saturate(1.8);transform:scale(.94) translateY(-10px);opacity:0;transition:transform .34s cubic-bezier(.2,1.35,.4,1),opacity .22s ease}
    .spot.on .spot-box{transform:none;opacity:1}
    .spot-ic{flex:none;color:#6e6e73}
    .spot-q{display:flex;align-items:center;min-height:32px}
    .spot-type{font-size:27px;font-weight:400;color:#1d1d1f;letter-spacing:-.015em;white-space:nowrap}
    .spot-cur{display:inline-block;width:2px;height:27px;margin-left:1px;background:#0a84ff;border-radius:1px;animation:spotcur 1.05s steps(1) infinite}
    @keyframes spotcur{50%{opacity:0}}
    .spot-sub{margin-top:20px;font-size:13px;font-weight:500;color:rgba(255,255,255,.92);text-shadow:0 1px 16px rgba(0,0,0,.55);opacity:0;transform:translateY(6px);transition:opacity .3s ease .12s,transform .3s ease .12s}
    .spot.on .spot-sub{opacity:1;transform:none}
  </style>
  <div class="spot-box">
    <svg class="spot-ic" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></svg>
    <span class="spot-q"><span class="spot-type"></span><span class="spot-cur"></span></span>
  </div>
  <p class="spot-sub">L'ultima parola prima delle domande</p>
</div>

<nav class="dock" id="dock">
  <span class="dapp" data-w="w-pres"><button class="ai" aria-label="Presentazione"><?= appicon('finder.webp', '/original/file-manager.svg') ?></button><span class="dot"></span><span class="tip">Presentazione · Finder</span></span>
  <span class="dapp" data-w="w-io"><button class="ai" aria-label="Su di me"><?= appicon('contacts.webp', '/src/apps/scalable/addressbook.svg') ?></button><span class="dot"></span><span class="tip">Su di me · Informazioni</span></span>
  <span class="dapp" data-w="w-skills"><button class="ai" aria-label="Fuori dall'aula"><?= appicon('appstore.webp', '/src/apps/scalable/software-store.svg') ?></button><span class="dot"></span><span class="tip">Fuori dall'aula · Launchpad</span></span>
  <span class="dapp" data-w="w-fsl"><button class="ai" aria-label="CS Metal Europe"><?= appicon('calendar.webp', '/original/calendar.svg') ?></button><span class="dot"></span><span class="tip">CS Metal Europe · Calendario</span></span>
  <span class="dapp" data-w="w-fine"><button class="ai" aria-label="Dove voglio andare"><?= appicon('maps.webp', '/original/gnome-maps.svg') ?></button><span class="dot"></span><span class="tip">Dove voglio andare · Mappe</span></span>
  <span class="dapp" data-spot><button class="ai" aria-label="Spotlight"><?= appicon('safari.webp', '/src/apps/scalable/safari.svg') ?></button><span class="dot"></span><span class="tip">Spotlight</span></span>
  <span class="dsep"></span>
  <span class="dapp" data-act="trash"><button class="ai" aria-label="Cestino: chiudi tutte le finestre"><?= appicon('trash.webp', '/src/places/scalable/user-trash.svg') ?></button><span class="tip">Cestino · chiudi tutto</span></span>
</nav>

<script src="hub.js"></script>
<script>
(function(){
  var spot=document.getElementById('spot');
  if(!spot)return;
  var box=spot.querySelector('.spot-box');
  var type=spot.querySelector('.spot-type');
  var TEXT='Le parole non sono mai neutre';
  var timer=null;
  function openSpot(){
    if(spot.classList.contains('on'))return;
    spot.classList.add('on');
    spot.setAttribute('aria-hidden','false');
    if(typeof sndOpen==='function'){try{sndOpen();}catch(e){}}
    type.textContent='';
    var i=0;
    clearInterval(timer);
    timer=setInterval(function(){
      type.textContent=TEXT.slice(0,++i);
      if(i>=TEXT.length)clearInterval(timer);
    },48);
  }
  function closeSpot(){
    if(!spot.classList.contains('on'))return;
    spot.classList.remove('on');
    spot.setAttribute('aria-hidden','true');
    clearInterval(timer);
    if(typeof sndClose==='function'){try{sndClose();}catch(e){}}
  }
  document.addEventListener('click',function(e){
    var t=e.target.closest('[data-spot]');
    if(t){e.preventDefault();openSpot();return;}
    if(spot.classList.contains('on')&&!box.contains(e.target))closeSpot();
  });
  document.addEventListener('keydown',function(e){if(e.key==='Escape')closeSpot();});
})();
</script>
<script>
(function(){
  var svg=document.getElementById('nav-map');
  var cam=document.getElementById('nav-cam');
  var route=document.getElementById('nav-route');
  var done=document.getElementById('nav-route-done');
  var puck=document.getElementById('nav-puck');
  if(!svg||!cam||!route||!done||!puck)return;
  var man=document.getElementById('nav-man');
  var kick=document.getElementById('nav-kick');
  var distEl=document.getElementById('nav-dist');
  var instr=document.getElementById('nav-instr');
  var etaEl=document.getElementById('nav-eta');
  var subEl=document.getElementById('nav-sub');
  var dotsWrap=document.getElementById('nav-dots');
  var endBtn=document.getElementById('nav-end');

  var L=route.getTotalLength();
  if(!isFinite(L)||L<=0)L=900;

  // tappe: coordinate reali, agganciate al tracciato (nessuna didascalia sulla mappa)
  var stops=[
    {id:'m0',x:170,y:520,name:'Maturità',stage:'Partenza',man:'straight'},
    {id:'m1',x:390,y:430,name:'PCTO',stage:'Prossima tappa',man:'straight'},
    {id:'m2',x:610,y:300,name:'Università',stage:'Prossima tappa',man:'straight'},
    {id:'m3',x:850,y:150,name:'Estero',stage:'Arrivo',man:'flag'}
  ];
  var n=stops.length;

  // trova la lunghezza lungo il path piu' vicina a (tx,ty): aggancio preciso al tracciato
  function lenAt(tx,ty){
    var best=0,bd=1e18,steps=1000,s,l,p,dx,dy,d;
    for(s=0;s<=steps;s++){
      l=L*s/steps;p=route.getPointAtLength(l);dx=p.x-tx;dy=p.y-ty;d=dx*dx+dy*dy;
      if(d<bd){bd=d;best=l;}
    }
    var span=L/steps,r,a,b,m1l,m2l,pa,pb,da,db;
    for(r=0;r<30;r++){
      a=Math.max(0,best-span);b=Math.min(L,best+span);
      m1l=a+(b-a)/3;m2l=b-(b-a)/3;
      pa=route.getPointAtLength(m1l);pb=route.getPointAtLength(m2l);
      da=(pa.x-tx)*(pa.x-tx)+(pa.y-ty)*(pa.y-ty);
      db=(pb.x-tx)*(pb.x-tx)+(pb.y-ty)*(pb.y-ty);
      if(da<db){best=m1l;}else{best=m2l;}
      span=(b-a)/3;
    }
    return best;
  }
  stops.forEach(function(st){st.len=lenAt(st.x,st.y);});
  // snap dei marker esattamente sul tracciato
  stops.forEach(function(st){var p=route.getPointAtLength(st.len);st.x=p.x;st.y=p.y;});

  // pallini di avanzamento (bottom sheet)
  var dots=[],j,bb;
  for(j=0;j<n;j++){
    bb=document.createElement('button');
    bb.type='button';bb.className='nav-dot';bb.setAttribute('data-i',j);bb.setAttribute('aria-label',stops[j].name);
    dotsWrap.appendChild(bb);dots.push(bb);
  }

  function manSvg(t){
    if(t==='flag')return '<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 29V4"/><path d="M9 5h15l-4 6 4 6H9"/></svg>';
    return '<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 27V8"/><path d="M8 16l8-8 8 8"/></svg>';
  }

  // parametri camera
  var FX=500,FY=400,BASE=1.5;
  var idx=0;
  var cur={len:stops[0].len,zoom:1};
  var tgt={len:stops[0].len,zoom:1};

  function pointAt(l){return route.getPointAtLength(Math.max(0,Math.min(L,l)));}
  function angleAt(l){var a=pointAt(Math.max(0,l-3)),b=pointAt(Math.min(L,l+3));return Math.atan2(b.y-a.y,b.x-a.x)*180/Math.PI;}

  function paintUI(){
    var arrived=(idx===n-1),k,g,s,nx;
    if(arrived){
      man.innerHTML=manSvg('flag');
      kick.textContent='Arrivo';
      distEl.textContent=stops[n-1].name;
      instr.textContent='Sei arrivato a destinazione';
      etaEl.textContent='Arrivato';
      subEl.textContent='Percorso completato';
    }else{
      nx=stops[idx+1];
      man.innerHTML=manSvg(nx.man);
      kick.textContent=nx.stage;
      distEl.textContent=nx.name;
      instr.textContent='Prosegui lungo il percorso';
      etaEl.textContent=stops[n-1].name;
      subEl.textContent='Tappa '+(idx+1)+' di '+n;
    }
    for(k=0;k<dots.length;k++){dots[k].className='nav-dot'+(k<idx?' done':'')+(k===idx?' on':'');}
    for(s=0;s<stops.length;s++){g=document.getElementById(stops[s].id);if(g){g.classList.toggle('on',s===idx);g.classList.toggle('done',s<idx);}}
  }

  function applyFrame(){
    var S=BASE*cur.zoom,inv=1/S,p=pointAt(cur.len),s,g;
    cam.setAttribute('transform','translate('+(FX-S*p.x).toFixed(2)+' '+(FY-S*p.y).toFixed(2)+') scale('+S.toFixed(4)+')');
    puck.setAttribute('transform','translate('+p.x.toFixed(2)+' '+p.y.toFixed(2)+') rotate('+(angleAt(cur.len)+90).toFixed(2)+') scale('+inv.toFixed(4)+')');
    for(s=0;s<stops.length;s++){g=document.getElementById(stops[s].id);if(g)g.setAttribute('transform','translate('+stops[s].x.toFixed(2)+' '+stops[s].y.toFixed(2)+') scale('+inv.toFixed(4)+')');}
    done.setAttribute('stroke-dashoffset',(1-cur.len/L).toFixed(5));
  }

  var raf=null;
  function frame(){
    cur.len+=(tgt.len-cur.len)*0.12;
    cur.zoom+=(tgt.zoom-cur.zoom)*0.12;
    if(Math.abs(tgt.len-cur.len)<0.35&&Math.abs(tgt.zoom-cur.zoom)<0.0015){cur.len=tgt.len;cur.zoom=tgt.zoom;}
    applyFrame();
    if(cur.len!==tgt.len||cur.zoom!==tgt.zoom){raf=requestAnimationFrame(frame);}else{raf=null;}
  }
  function animate(){if(!raf)raf=requestAnimationFrame(frame);}

  function goTo(i){idx=((i%n)+n)%n;tgt.len=stops[idx].len;paintUI();animate();if(typeof sndOpen==='function'){try{sndOpen();}catch(e){}}}

  svg.addEventListener('click',function(){goTo(idx+1);});
  dotsWrap.addEventListener('click',function(e){var t=e.target.closest('.nav-dot');if(!t)return;e.stopPropagation();goTo(parseInt(t.getAttribute('data-i'),10));});
  var zin=document.getElementById('nav-zin'),zout=document.getElementById('nav-zout'),comp=document.getElementById('nav-comp');
  if(zin)zin.addEventListener('click',function(e){e.stopPropagation();tgt.zoom=Math.min(2.6,tgt.zoom+0.3);animate();});
  if(zout)zout.addEventListener('click',function(e){e.stopPropagation();tgt.zoom=Math.max(0.8,tgt.zoom-0.3);animate();});
  if(comp)comp.addEventListener('click',function(e){e.stopPropagation();tgt.zoom=1;animate();});
  if(endBtn)endBtn.addEventListener('click',function(e){e.stopPropagation();var w=document.getElementById('w-fine');if(typeof closeWin==='function'&&w)closeWin(w);});

  // stato iniziale immediato (nessun salto all'apertura)
  paintUI();
  applyFrame();
})();
</script>
</body>
</html>
