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
<link rel="stylesheet" href="macos.css?v=<?= @filemtime(__DIR__ . '/macos.css') ?>">
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

<section class="win open a-blue finder-window" id="w-pres" style="left:5.5%;top:6%;width:980px">
  <div class="titlebar finder-titlebar"><span class="wt">Applications</span>
    <div class="finder-nav" aria-hidden="true">
      <button type="button"><svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7"/></svg></button>
      <button type="button"><svg viewBox="0 0 24 24"><path d="m9 5 7 7-7 7"/></svg></button>
    </div>
    <div class="finder-viewbar" aria-hidden="true">
      <button class="on" type="button"><svg viewBox="0 0 24 24"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"/></svg></button>
      <button type="button"><svg viewBox="0 0 24 24"><path d="M8 6h12M8 12h12M8 18h12"/><path d="M4 6h.01M4 12h.01M4 18h.01"/></svg></button>
      <button type="button"><svg viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M10 5v14M16 5v14"/></svg></button>
      <button type="button"><svg viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M4 15h16"/></svg></button>
    </div>
    <div class="finder-actions" aria-hidden="true">
      <button type="button"><svg viewBox="0 0 24 24"><path d="M12 5v10"/><path d="m8 9 4-4 4 4"/><path d="M5 15v4h14v-4"/></svg></button>
      <button type="button"><svg viewBox="0 0 24 24"><path d="M20 12.5 12.5 20a2.1 2.1 0 0 1-3 0L4 14.5a2.1 2.1 0 0 1 0-3L11.5 4H20z"/><path d="M16 8h.01"/></svg></button>
      <button type="button" class="more">•••</button>
      <button type="button"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg></button>
    </div>
  </div>
  <div class="wbody finder-app" aria-label="Finder Applications">
    <aside class="finder-sidebar">
      <div class="finder-side-top">
        <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M4 7h6l1.6 2H20v9H4z"/></svg></span>Shared</button>
        <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M12 8v5l3 2"/><circle cx="12" cy="12" r="8"/></svg></span>Recents</button>
      </div>
      <div class="finder-side-label">Favorites</div>
      <button class="finder-side-row selected"><span><svg viewBox="0 0 24 24"><path d="M12 3 4 20h16z"/><path d="M8.7 14h6.6"/></svg></span>Applications</button>
      <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="M4 15h16"/></svg></span>Desktop</button>
      <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v5h5"/></svg></span>Documents</button>
      <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M12 4v10"/><path d="m8 10 4 4 4-4"/><path d="M5 20h14"/></svg></span>Downloads</button>
      <div class="finder-side-label">Locations</div>
      <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M6 18h12a4 4 0 0 0 0-8 6 6 0 0 0-11.3-2A4.8 4.8 0 0 0 6 18z"/></svg></span>iCloud Drive</button>
      <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M4 11 12 4l8 7"/><path d="M6 10v10h12V10"/></svg></span><?= $nome ?></button>
      <button class="finder-side-row"><span><svg viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/><path d="M8 18v2h8v-2"/></svg></span>On My Mac</button>
    </aside>
    <main class="finder-main">
      <div class="finder-path"><b>Applications</b><span>6 applicazioni</span></div>
      <div class="finder-grid">
        <button class="finder-app-icon" data-open="w-pres" data-desc="Indice della presentazione: da qui apri tutte le sezioni del percorso.">
          <span class="finder-img"><?= appicon('finder.webp', '/original/file-manager.svg') ?></span><b>Finder</b><small>Presentazione</small>
        </button>
        <button class="finder-app-icon" data-open="w-io" data-desc="App Note in dark mode: curriculum, attività e progetti raccontati in modo sintetico.">
          <span class="finder-img"><?= appicon('notes.png', '/src/apps/scalable/accessories-text-editor.svg') ?></span><b>Notes</b><small>Su di me</small>
        </button>
        <button class="finder-app-icon" data-open="w-fsl" data-desc="La sezione dedicata al PCTO presso CS Metal Europe e al lavoro svolto in azienda.">
          <span class="finder-img"><?= appicon('calendar.webp', '/original/calendar.svg') ?></span><b>Calendar</b><small>CS Metal Europe</small>
        </button>
        <button class="finder-app-icon" data-open="w-fine" data-desc="Una mappa interattiva del percorso dopo il diploma e degli obiettivi futuri.">
          <span class="finder-img"><?= appicon('maps.webp', '/original/gnome-maps.svg') ?></span><b>Maps</b><small>Dove voglio andare</small>
        </button>
        <button class="finder-app-icon" data-spot data-desc="Apre Spotlight con la frase conclusiva della presentazione.">
          <span class="finder-img"><?= appicon('safari.webp', '/src/apps/scalable/safari.svg') ?></span><b>Spotlight</b><small>Chiusura</small>
        </button>
        <button class="finder-app-icon" data-act="trash" data-desc="Chiude tutte le finestre aperte e ripulisce il desktop.">
          <span class="finder-img"><?= appicon('trash.webp', '/src/places/scalable/user-trash.svg') ?></span><b>Trash</b><small>Chiudi tutto</small>
        </button>
      </div>
    </main>
  </div>
</section>

<section class="win a-orange notes-real-window" id="w-io" style="left:6%;top:6%;width:1060px">
  <div class="titlebar notes-real-titlebar"><span class="wt">Su di me — Note</span></div>
  <div class="wbody notes-real-app" data-notes-app>
    <aside class="nr-sidebar" aria-label="Cartelle Note">
      <div class="nr-toolbar-space"></div>
      <button class="nr-folder selected" data-filter="all" type="button">
        <span class="nr-ico"><svg viewBox="0 0 24 24"><path d="M4 7h6l1.7 2H20v9H4z"/></svg></span>
        <b>Tutte le note</b>
      </button>
      <button class="nr-folder" data-filter="curriculum" type="button">
        <span class="nr-ico"><svg viewBox="0 0 24 24"><path d="M5 4h14v16H5z"/><path d="M8 9h8M8 13h6"/></svg></span>
        <b>Curriculum</b>
      </button>
      <button class="nr-folder" data-filter="pcto" type="button">
        <span class="nr-ico"><svg viewBox="0 0 24 24"><path d="M9 7V5.8A1.8 1.8 0 0 1 10.8 4h2.4A1.8 1.8 0 0 1 15 5.8V7"/><rect x="4" y="7" width="16" height="12" rx="2.4"/><path d="M4 12h16"/></svg></span>
        <b>PCTO</b>
      </button>
      <button class="nr-folder" data-filter="projects" type="button">
        <span class="nr-ico"><svg viewBox="0 0 24 24"><path d="M8 8h8v8H8z"/><path d="M4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/></svg></span>
        <b>Sportly</b>
      </button>
      <button class="nr-folder" data-filter="extra" type="button">
        <span class="nr-ico"><svg viewBox="0 0 24 24"><path d="M12 4v16M5 9h14M7 15h10"/><path d="M6 20h12"/></svg></span>
        <b>Attività extra</b>
      </button>
      <button class="nr-folder" data-filter="culture" type="button">
        <span class="nr-ico"><svg viewBox="0 0 24 24"><path d="M4 19V5l8-2 8 2v14"/><path d="M8 9h8M8 13h8"/></svg></span>
        <b>Cultura</b>
      </button>
      <div class="nr-label">Tag</div>
      <div class="nr-tags">
        <button data-filter="scuola" type="button">#scuola</button>
        <button data-filter="oratorio" type="button">#oratorio</button>
        <button data-filter="arte" type="button">#arte</button>
      </div>
    </aside>

    <aside class="nr-list" aria-label="Elenco note">
      <div class="nr-list-head">
        <h2 data-notes-title>Tutte le note</h2>
        <span data-notes-count>7 note</span>
      </div>
      <div class="nr-items" data-notes-list></div>
    </aside>

    <main class="nr-editor" aria-live="polite">
      <div class="nr-editor-toolbar">
        <button data-note-prev type="button" aria-label="Nota precedente"><svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7"/></svg></button>
        <div class="nr-toolbar-actions">
          <button data-note-action="font" type="button" aria-label="Dimensione testo">Aa</button>
          <button data-note-action="check" type="button" aria-label="Punti chiave"><svg viewBox="0 0 24 24"><path d="M5 7h.01M9 7h10M5 12h.01M9 12h10M5 17h.01M9 17h10"/></svg></button>
          <button data-note-action="focus" type="button" aria-label="Focus"><svg viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3M16 3h3a2 2 0 0 1 2 2v3M8 21H5a2 2 0 0 1-2-2v-3M16 21h3a2 2 0 0 0 2-2v-3"/></svg></button>
        </div>
      </div>
      <article class="nr-note" data-note-screen></article>
    </main>
  </div>
</section>

<section class="win a-green pcto-calendar-window" id="w-fsl" style="left:8%;top:7%;width:1040px">
  <div class="titlebar pcto-titlebar"><span class="wt">CS Metal Europe — Calendario PCTO</span></div>
  <div class="wbody pcto-cal-app" data-pcto-calendar>
    <aside class="pcto-side">
      <div class="pcto-side-head">
        <span class="pcto-cloud">iCloud</span>
        <h3>PCTO</h3>
        <p>Due anni di tirocinio, letti come calendario operativo.</p>
      </div>
      <button class="pcto-year-card active" data-pcto-year="2024" type="button">
        <span class="pcto-check blue"></span>
        <div><b>3ª I · Aprile 2024</b><small>08/04 — 27/04 · 120 ore</small></div>
      </button>
      <button class="pcto-year-card" data-pcto-year="2025" type="button">
        <span class="pcto-check pink"></span>
        <div><b>4ª I · Novembre 2024</b><small>11/11 — 30/11 · 120 ore</small></div>
      </button>
      <div class="pcto-side-label">Azienda</div>
      <div class="pcto-company-card">
        <b>CS Metal Europe SRL</b>
        <span>Via Benaco 86, Bedizzole</span>
        <span>Ufficio commerciale/logistico · magazzino</span>
      </div>
      <div class="pcto-mini-month" data-pcto-mini></div>
    </aside>

    <main class="pcto-main">
      <header class="pcto-toolbar">
        <button class="pcto-round" data-pcto-prev type="button" aria-label="Anno precedente"><svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7"/></svg></button>
        <div class="pcto-month-title"><h2 data-pcto-title>Aprile 2024</h2><span data-pcto-subtitle>3ª I · CS Metal Europe</span></div>
        <button class="pcto-round" data-pcto-next type="button" aria-label="Anno successivo"><svg viewBox="0 0 24 24"><path d="m9 5 7 7-7 7"/></svg></button>
        <div class="pcto-segment" role="tablist" aria-label="Periodo PCTO">
          <button class="active" data-pcto-year="2024" type="button">2023/24</button>
          <button data-pcto-year="2025" type="button">2024/25</button>
        </div>
      </header>

      <section class="pcto-calendar-grid" data-pcto-grid aria-label="Calendario mese PCTO"></section>
    </main>

    <aside class="pcto-detail" data-pcto-detail aria-live="polite"></aside>
  </div>
</section>

<section class="win a-blue maps-window" id="w-fine" style="left:9%;top:6.5%;width:1020px">
  <div class="titlebar maps-titlebar"><span class="wt">Dove voglio andare — Mappe</span></div>
  <div class="maps-app" data-maps-navigator>

    <aside class="mw-side" aria-label="Indicazioni del percorso">
      <div class="mw-side-head">
        <button class="mw-back" type="button" aria-label="Indietro"><svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7"/></svg></button>
        <h2>Indicazioni</h2>
      </div>

      <div class="mw-modes" role="tablist" aria-label="Tipo di percorso">
        <button class="active" type="button" aria-label="In auto"><svg viewBox="0 0 24 24"><path d="M5 11l1.5-4.5A2 2 0 0 1 8.4 5h7.2a2 2 0 0 1 1.9 1.5L19 11M5 11h14v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H8v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1z"/><circle cx="7.5" cy="14" r="1"/><circle cx="16.5" cy="14" r="1"/></svg></button>
        <button type="button" aria-label="A piedi"><svg viewBox="0 0 24 24"><circle cx="12" cy="4.5" r="1.8"/><path d="M11 9l-2 4 2 1 1 5M13 9l1 3 3 1M11 9h2l1.5 1"/></svg></button>
        <button type="button" aria-label="Mezzi pubblici"><svg viewBox="0 0 24 24"><rect x="6" y="4" width="12" height="13" rx="3"/><path d="M6 12h12M9 20l-1.5 2M15 20l1.5 2"/><circle cx="9" cy="15" r="1"/><circle cx="15" cy="15" r="1"/></svg></button>
      </div>

      <div class="mw-route">
        <div class="mw-route-line"></div>
        <div class="mw-endpoint"><span class="mw-pt start"></span><div><small>Da</small><b>Oggi · Maturità</b></div></div>
        <div class="mw-endpoint"><span class="mw-pt end"></span><div><small>A</small><b>Estero · America</b></div></div>
      </div>

      <div class="mw-steps-head">Passaggi</div>
      <div class="mw-steps" data-maps-stops aria-label="Tappe del percorso"></div>
    </aside>

    <main class="mw-map-wrap" aria-label="Mappa del percorso">
      <svg class="maps-map mw-map" viewBox="0 0 1200 760" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Percorso: FSL, Diploma, Università a Brescia, estero">
        <defs>
          <linearGradient id="mw-water" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#17324e"/><stop offset="1" stop-color="#0d2236"/></linearGradient>
          <filter id="mw-pin-sh" x="-50%" y="-50%" width="200%" height="200%"><feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#000" flood-opacity=".35"/></filter>
        </defs>

        <g id="maps-cam">
          <rect x="-200" y="-200" width="1600" height="1160" fill="#23262c"/>
          <path d="M0 150 C220 120 420 175 600 150 C820 120 1010 60 1200 90 L1200 -60 L0 -60 Z" fill="url(#mw-water)"/>
          <path d="M150 760 C150 600 250 560 300 470 C360 360 300 300 360 210" fill="none" stroke="#2c4d33" stroke-width="150" stroke-linecap="round" opacity=".5"/>
          <path d="M820 760 C880 640 980 600 1080 560 C1180 520 1220 470 1240 420" fill="none" stroke="#2c4d33" stroke-width="130" stroke-linecap="round" opacity=".45"/>
          <g stroke-linecap="round" fill="none">
            <path d="M-40 520 C220 470 360 430 520 360 C700 282 860 250 1240 170" stroke="#3a3e45" stroke-width="9"/>
            <path d="M40 700 C260 600 380 560 520 470 C700 360 880 320 1240 250" stroke="#33373d" stroke-width="6"/>
            <path d="M260 760 C300 600 420 470 540 400 C660 330 720 250 760 120" stroke="#33373d" stroke-width="6"/>
            <path d="M700 760 C760 600 820 470 900 380 C980 290 1040 220 1080 120" stroke="#2f333a" stroke-width="5"/>
          </g>
          <path id="maps-route-base" d="M180 628 C300 560 360 548 470 486 C600 414 660 372 770 300 C880 228 980 150 1040 96" fill="none" stroke="#0a4da8" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" opacity=".55"/>
          <path id="maps-route" d="M180 628 C300 560 360 548 470 486 C600 414 660 372 770 300 C880 228 980 150 1040 96" fill="none" stroke="#0a84ff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round"/>
          <path id="maps-route-progress" pathLength="1" d="M180 628 C300 560 360 548 470 486 C600 414 660 372 770 300 C880 228 980 150 1040 96" fill="none" stroke="#34c759" stroke-width="11" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1" stroke-dashoffset="1"/>

          <g class="mw-pin" data-maps-step="0" transform="translate(180 628)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#34c759" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">1</text></g>
          <g class="mw-pin" data-maps-step="1" transform="translate(470 486)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#0a84ff" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">2</text></g>
          <g class="mw-pin" data-maps-step="2" transform="translate(770 300)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#5856d6" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">3</text></g>
          <g class="mw-pin" data-maps-step="3" transform="translate(1040 96)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#ff453a" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">4</text></g>

          <g id="maps-puck" transform="translate(180 628)"><circle r="15" fill="#0a84ff" opacity=".18"/><circle r="8" fill="#0a84ff" stroke="#fff" stroke-width="3"/></g>
        </g>
      </svg>

      <div class="mw-banner" aria-live="polite">
        <div class="mw-banner-icon" data-maps-icon>→</div>
        <div class="mw-banner-text"><span data-maps-kicker>Partenza</span><b data-maps-title>FSL · PCTO</b><p data-maps-copy>Il primo punto del viaggio.</p></div>
      </div>

      <div class="mw-zoom" aria-label="Zoom mappa">
        <button data-maps-zoom="in" type="button" aria-label="Avvicina">+</button>
        <span></span>
        <button data-maps-zoom="out" type="button" aria-label="Allontana">−</button>
      </div>

      <div class="mw-sheet">
        <div class="mw-sheet-text"><b data-maps-eta>Inizio percorso</b><small data-maps-sub>FSL / PCTO</small></div>
        <div class="mw-dots" data-maps-dots aria-label="Avanzamento"></div>
        <button class="mw-go" data-maps-next type="button">Avanti</button>
      </div>
    </main>

  </div>
</section>

<div id="spot" class="spot" aria-hidden="true">
  <div class="spot-box">
    <svg class="spot-ic" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></svg>
    <span class="spot-q"><span class="spot-type"></span><span class="spot-cur"></span></span>
  </div>
  <p class="spot-sub">L'ultima parola prima delle domande</p>
</div>

<nav class="dock" id="dock">
  <span class="dapp" data-w="w-pres"><button class="ai" aria-label="Presentazione"><?= appicon('finder.webp', '/original/file-manager.svg') ?></button><span class="dot"></span><span class="tip">Presentazione · Finder</span></span>
  <span class="dapp" data-w="w-io"><button class="ai" aria-label="Su di me"><?= appicon('notes.png', '/src/apps/scalable/accessories-text-editor.svg') ?></button><span class="dot"></span><span class="tip">Su di me · Note</span></span>
  <span class="dapp" data-w="w-fsl"><button class="ai" aria-label="CS Metal Europe"><?= appicon('calendar.webp', '/original/calendar.svg') ?></button><span class="dot"></span><span class="tip">CS Metal Europe · Calendario</span></span>
  <span class="dapp" data-w="w-fine"><button class="ai" aria-label="Dove voglio andare"><?= appicon('maps.webp', '/original/gnome-maps.svg') ?></button><span class="dot"></span><span class="tip">Dove voglio andare · Mappe</span></span>
  <span class="dapp" data-spot><button class="ai" aria-label="Spotlight"><?= appicon('safari.webp', '/src/apps/scalable/safari.svg') ?></button><span class="dot"></span><span class="tip">Spotlight</span></span>
  <span class="dsep"></span>
  <span class="dapp" data-act="trash"><button class="ai" aria-label="Cestino: chiudi tutte le finestre"><?= appicon('trash.webp', '/src/places/scalable/user-trash.svg') ?></button><span class="tip">Cestino · chiudi tutto</span></span>
</nav>

<script src="hub.js"></script>
</body>
</html>
