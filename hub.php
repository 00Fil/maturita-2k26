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
<link rel="stylesheet" href="hub.css">
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
  <div class="titlebar"><span class="wt">Presentazione — i miei 10 minuti</span></div>
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
      <div class="ftools"><b>I miei 10 minuti</b><span>la presentazione della persona, poi la parola alla commissione</span><span class="fseg"><button class="on" data-view="gallery">Galleria</button><button data-view="list">Elenco</button></span></div>
      <div class="fgrid">
        <button class="fitem lgcard" data-open="w-io" data-tag="rifl" style="animation-delay:.05s"><span class="fbadge" style="background:#FF9500">01</span><b>Su di me</b><span>Chi sono: il filo che tiene insieme violoncello, sport e la voglia di costruire cose.</span><span class="tt">Apri · Informazioni</span></button>
        <button class="fitem lgcard" data-open="w-skills" data-tag="fuori" style="animation-delay:.1s"><span class="fbadge" style="background:#5856D6">02</span><b>Fuori dall'aula</b><span>Quello che faccio fuori da scuola: progetti, concorsi, volontariato.</span><span class="tt">Apri · Launchpad</span></button>
        <button class="fitem lgcard" data-open="w-fsl" data-tag="fsl" style="animation-delay:.15s"><span class="fbadge" style="background:#34C759">03</span><b>CS Metal Europe</b><span>240 ore di alternanza in due anni: cosa ho fatto e cosa mi ha insegnato.</span><span class="tt">Apri · Calendario</span></button>
        <button class="fitem lgcard" data-open="w-fine" data-tag="rifl" style="animation-delay:.2s"><span class="fbadge" style="background:#AF52DE">04</span><b>Dove voglio andare</b><span>L'alternanza mi ha orientato: la direzione dopo il diploma.</span><span class="tt">Apri · Mappe</span></button>
        <button class="fitem lgcard" data-open="w-coll" data-tag="fsl" style="animation-delay:.25s"><span class="fbadge" style="background:#30B0C7">05</span><b>Spotlight</b><span>L'ultima parola prima delle domande della commissione.</span><span class="tt">Apri · Spotlight</span></button>
      </div>
      <div class="pathbar">Apertura mia, poi le domande dei professori <i>·</i> 5 momenti <i>·</i> un'app per ognuno</div>
    </div>
  </div>
</section>

<section class="win a-orange" id="w-io" style="left:12%;top:9%;width:660px">
  <div class="titlebar"><span class="wt">Su di me — Informazioni</span></div>
  <div class="wbody cmain" style="display:flex;flex-direction:column">
    <div class="chead">
      <span class="cavatar">FC</span>
      <div class="cid"><h2>Filippo Corsini</h2><p>18 anni · 5ª Informatica · IIS Cerebotani</p></div>
      <div class="cacts"><a class="cact" href="mailto:ciao@denuvo.studio"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="m2 7 10 6L22 7"/></svg>mail</a><a class="cact" href="https://github.com/00Fil" target="_blank" rel="noopener"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 8-4 4 4 4"/><path d="m16 8 4 4-4 4"/><path d="m13 5-2 14"/></svg>GitHub</a></div>
    </div>
    <div class="crows">
      <div class="crow lgcard"><span class="k" style="color:#FF9500">Il filo</span><div><b>«Da bambino guardavo i videogiochi. Poi ho iniziato a guardare dietro i videogiochi.»</b><p>È la curiosità che mi ha portato qui: capire come funzionano le cose, e poi provare a costruirle.</p></div></div>
      <div class="crow lgcard"><span class="k" style="color:#AF52DE">Dal 2018</span><div><b>Violoncello</b><p>Mi ha insegnato la costanza: i risultati veri arrivano solo se ci torni ogni giorno.</p></div></div>
      <div class="crow lgcard"><span class="k" style="color:#34C759">Sport</span><div><b>Nuoto, atletica, pallavolo, basket, palestra</b><p>Ho provato tanti sport: ognuno mi ha insegnato che si migliora un po' alla volta, non tutto insieme.</p></div></div>
      <div class="crow lgcard"><span class="k" style="color:#0A84FF">Sempre</span><div><b>Costruire cose</b><p>Siti, piccoli programmi, progetti: la curiosità è diventata il mestiere che voglio fare.</p></div></div>
    </div>
    <div class="cnote"><b>Perché parto da qui:</b> il colloquio si apre con una riflessione sul mio percorso, dentro e fuori da scuola. Non un elenco: queste sono le cose che mi hanno formato davvero.</div>
  </div>
</section>

<section class="win a-indigo" id="w-skills" style="left:17%;top:7%;width:680px">
  <div class="titlebar"><span class="wt">Fuori dall'aula — Launchpad</span></div>
  <div class="wbody as-body">
    <div class="as-hero lgcard"><span class="as-kicker">Fuori da scuola</span><h3>Quello che faccio quando suona la campanella</h3><p>Le cose che ho costruito e vissuto fuori dall'aula contano quanto i voti: lì ho imparato a stare con gli altri e a portare a termine quello che inizio.</p></div>
    <div class="as-duo">
      <div class="as-mini lgcard" style="background:#0A84FF"><span class="as-kicker">Concorso · vinto</span><h4>Volo tra le Righe</h4><p>Una playlist narrativa su un romanzo: testo, musica e immagini per raccontarlo. Ho vinto il concorso.</p></div>
      <div class="as-mini lgcard" style="background:#34C759"><span class="as-kicker">Dal progetto alla startup</span><h4>GPOI · Sportly</h4><p>Il project work di gestione progetto diventato qualcosa di vero: dall'idea al prototipo online.</p></div>
    </div>
    <div class="as-sec">Le altre cose che mi tengono vivo</div>
    <div class="aprows">
      <div class="aprow lgcard"><span class="apic" style="background:#1C1C1E;color:#7AC74F">Mc</span><div><b>Network Minecraft</b><p>Il primo servizio vero che ho gestito, con persone vere dall'altra parte.</p></div><span class="getb">Fuori</span></div>
      <div class="aprow lgcard"><span class="apic" style="background:#5856D6">ds</span><div><b>denuvo.studio</b><p>Il mio spazio online: dove pubblico i progetti e curo ogni dettaglio.</p></div><span class="getb">Fuori</span></div>
      <div class="aprow lgcard"><span class="apic" style="background:#FF9500">Vo</span><div><b>Volontariato</b><p>Festa del Sorriso e Torneo dei Roncai: organizzare e servire gli altri.</p></div><span class="getb">Fuori</span></div>
      <div class="aprow lgcard"><span class="apic" style="background:#AF52DE">Ms</span><div><b>Musica &amp; sport</b><p>Il violoncello e l'allenamento: la disciplina che mi porto ovunque.</p></div><span class="getb">Fuori</span></div>
    </div>
    <div class="sf-note">Non sono attività separate: sono tutti modi diversi di fare la stessa cosa — costruire qualcosa e prendermene la responsabilità.</div>
  </div>
</section>

<section class="win a-green" id="w-fsl" style="left:14%;top:8%;width:720px">
  <div class="titlebar"><span class="wt">CS Metal Europe — la mia alternanza</span></div>
  <div class="wbody" style="display:flex;flex-direction:column">
    <div class="cal-tb"><h3>Formazione scuola-lavoro <span>· CS Metal Europe</span></h3>
      <div class="cal-stats"><span class="cstat lgcard"><b>240</b><span>ore</span></span><span class="cstat lgcard"><b>2</b><span>anni</span></span><span class="cstat lgcard"><b>12</b><span>persone</span></span></div>
    </div>
    <div class="agenda">
      <div class="agroup"><h4><b>L'azienda e io</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#34C759"></span><span class="tm">Bedizzole</span><div><b>Acciai speciali · gruppo giapponese Proterial</b><p>Una realtà piccola, 12 persone, dentro un gruppo enorme. 240 ore in due anni: non due esperienze slegate, un percorso che cresce.</p></div></div>
        </div>
      </div>
      <div class="agroup"><h4><b>Due anni di crescita</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#31A8FF"></span><span class="tm">3ª</span><div><b>Dati e grafica</b><p>Excel per i dati di produzione, Photoshop e Illustrator per le immagini, il sito in WordPress con il blog.</p></div></div>
          <div class="evt lgcard"><span class="bar" style="background:#FF9500"></span><span class="tm">4ª</span><div><b>Dal magazzino alla comunicazione</b><p>Dall'inventario e i DPI alla comunicazione digitale: Instagram, LinkedIn, Canva. Ho visto l'azienda da due lati.</p></div></div>
        </div>
      </div>
      <div class="agroup"><h4><b>La difficoltà che mi ha cambiato</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#FF3B30"></span><span class="tm">La lezione</span><div><b>Scrivere è progettare per chi legge</b><p>Un invito a un evento da scrivere: ho capito che ogni parola ha uno scopo. E la verità onesta: all'inizio erano compiti molto operativi — l'autonomia me la sono guadagnata.</p></div></div>
        </div>
      </div>
      <div class="agroup"><h4><b>Il risultato</b></h4>
        <div class="evts">
          <div class="evt lgcard"><span class="bar" style="background:#1D6F42"></span><span class="tm">Contratto</span><div><b>Un e-commerce vero — e il primo contratto</b><p>WordPress/WooCommerce, vendita su Amazon, un plugin in PHP che sincronizza il catalogo. Il mio primo contratto: lì gli artt. 1, 4, 35-36 della Costituzione e la sicurezza in azienda hanno smesso di essere teoria.</p></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="win a-purple" id="w-fine" style="left:19%;top:10%;width:820px">
  <div class="titlebar"><span class="wt">Dove voglio andare — Mappe</span></div>
  <div class="mapwrap">
    <div class="mpanel">
      <span class="msearch"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>Da CS Metal all'Università</span>
      <div class="stop lgcard"><span class="pinz"><span class="pin" style="background:#34C759">A</span><span class="lne"></span></span><div><b>IIS Cerebotani · 2021–2026</b><p>Cinque anni di Informatica e Telecomunicazioni: le basi tecniche e il metodo di studio.</p></div></div>
      <div class="stop lgcard"><span class="pinz"><span class="pin" style="background:#0A84FF">B</span><span class="lne"></span></span><div><b>CS Metal Europe · 240 ore</b><p>La tappa che mi ha orientato: l'azienda mi ha fatto capire cosa voglio fare davvero.</p></div></div>
      <div class="stop lgcard"><span class="pinz"><span class="pin" style="background:#FF3B30">C</span></span><div><b>Università · Informatica</b><p>La direzione dopo il diploma: continuare a costruire software, con basi più solide.</p></div></div>
      <div class="eta"><span>In sintesi</span>L'alternanza mi ha mostrato dove voglio andare. Oggi lavoro con l'AI come copilota — uno strumento, non una scorciatoia. Da qui in poi, la parola alla commissione.</div>
    </div>
    <div class="mappane">
      <svg viewBox="0 0 640 420" preserveAspectRatio="xMidYMid slice">
        <rect width="640" height="420" fill="#E9E4DA"/>
        <path d="M-20 90 C140 70 240 130 400 110 S620 60 660 80" stroke="#fff" stroke-width="14" fill="none"/>
        <path d="M-20 230 C120 250 300 200 460 240 S640 280 660 260" stroke="#fff" stroke-width="20" fill="none"/>
        <path d="M-20 350 C180 330 340 380 660 340" stroke="#fff" stroke-width="12" fill="none"/>
        <path d="M120 -20 C140 120 90 260 130 440" stroke="#fff" stroke-width="12" fill="none"/>
        <path d="M330 -20 C310 140 360 280 330 440" stroke="#fff" stroke-width="16" fill="none"/>
        <path d="M520 -20 C540 120 500 300 530 440" stroke="#fff" stroke-width="10" fill="none"/>
        <rect x="150" y="120" width="70" height="46" rx="7" fill="#DDD6C8"/>
        <rect x="380" y="150" width="58" height="40" rx="7" fill="#DDD6C8"/>
        <rect x="230" y="280" width="80" height="50" rx="7" fill="#DDD6C8"/>
        <rect x="470" y="300" width="60" height="42" rx="7" fill="#DDD6C8"/>
        <circle cx="90" cy="330" r="46" fill="#CFE3C2"/>
        <circle cx="590" cy="110" r="38" fill="#CFE3C2"/>
        <path d="M106 340 C200 300 280 220 330 172 C390 120 470 130 518 120" stroke="#0A84FF" stroke-width="4" stroke-dasharray="2 9" stroke-linecap="round" fill="none"/>
      </svg>
      <span class="mpin" style="left:16.5%;top:81%"><span class="mtag">IIS Cerebotani</span><span class="mdot" style="background:#34C759"></span></span>
      <span class="mpin" style="left:51.5%;top:41%"><span class="mtag">CS Metal Europe</span><span class="mdot" style="background:#0A84FF"></span></span>
      <span class="mpin" style="left:81%;top:28.5%"><span class="mtag">Università</span><span class="mdot" style="background:#FF3B30"></span></span>
    </div>
  </div>
</section>

<section class="win a-teal" id="w-coll" style="left:21%;top:12%;width:640px">
  <div class="titlebar"><span class="wt">Spotlight</span></div>
  <div class="wbody" style="display:flex;align-items:center;justify-content:center;min-height:300px;padding:44px 30px">
    <style>@keyframes spotcur{50%{opacity:0}}.spotcur{display:inline-block;width:2px;height:1em;margin-left:3px;background:#1d1d1f;vertical-align:-2px;animation:spotcur 1.1s steps(1) infinite}</style>
    <div style="width:min(540px,100%)">
      <div class="lgcard" style="display:flex;align-items:center;gap:14px;background:rgba(255,255,255,.72);border:1px solid rgba(255,255,255,.6);border-radius:18px;padding:17px 20px;box-shadow:0 14px 48px rgba(0,0,0,.14)">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8a8a8e" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></svg>
        <span style="font-size:23px;font-weight:500;color:#1d1d1f;letter-spacing:-.01em">Le parole non sono mai neutre<span class="spotcur"></span></span>
      </div>
      <p style="text-align:center;margin:20px 0 0;font-size:12.5px;font-weight:500;color:rgba(60,60,67,.5)">L'ultima parola prima delle domande</p>
    </div>
  </div>
</section>

<nav class="dock" id="dock">
  <span class="dapp" data-w="w-pres"><button class="ai" aria-label="Presentazione"><?= appicon('finder.webp', '/original/file-manager.svg') ?></button><span class="dot"></span><span class="tip">Presentazione · Finder</span></span>
  <span class="dapp" data-w="w-io"><button class="ai" aria-label="Su di me"><?= appicon('contacts.webp', '/src/apps/scalable/addressbook.svg') ?></button><span class="dot"></span><span class="tip">Su di me · Informazioni</span></span>
  <span class="dapp" data-w="w-skills"><button class="ai" aria-label="Fuori dall'aula"><?= appicon('appstore.webp', '/src/apps/scalable/software-store.svg') ?></button><span class="dot"></span><span class="tip">Fuori dall'aula · Launchpad</span></span>
  <span class="dapp" data-w="w-fsl"><button class="ai" aria-label="CS Metal Europe"><?= appicon('calendar.webp', '/original/calendar.svg') ?></button><span class="dot"></span><span class="tip">CS Metal Europe · Calendario</span></span>
  <span class="dapp" data-w="w-fine"><button class="ai" aria-label="Dove voglio andare"><?= appicon('maps.webp', '/original/gnome-maps.svg') ?></button><span class="dot"></span><span class="tip">Dove voglio andare · Mappe</span></span>
  <span class="dapp" data-w="w-coll"><button class="ai" aria-label="Spotlight"><?= appicon('safari.webp', '/src/apps/scalable/safari.svg') ?></button><span class="dot"></span><span class="tip">Spotlight</span></span>
  <span class="dsep"></span>
  <span class="dapp" data-act="trash"><button class="ai" aria-label="Cestino: chiudi tutte le finestre"><?= appicon('trash.webp', '/src/places/scalable/user-trash.svg') ?></button><span class="tip">Cestino · chiudi tutto</span></span>
</nav>

<script src="hub.js"></script>
</body>
</html>
