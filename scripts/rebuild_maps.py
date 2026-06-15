from pathlib import Path
root = Path('/data/maturita-work/maturita-2k26-main')
(root/'docs/research/components').mkdir(parents=True, exist_ok=True)
(root/'docs/design-references').mkdir(parents=True, exist_ok=True)

behaviors = '''# BEHAVIORS — Apple Maps rebuild

## Interaction sweep
- **Interaction model principale:** click-driven + map-driven. La sezione non è scroll-based: dentro la finestra Maps l’utente cambia tappa cliccando sidebar, pallini, marker o mappa.
- **Animazioni di apertura:** eredita `.win.open` dal sistema macOS: `winIn .55s var(--spring)` con scale/translate.
- **Panel Apple Maps:** top card e bottom sheet entrano con `mapsPanelIn` e `mapsSheetIn`, rispettivamente translate+blur e translateY.
- **Cambio tappa:** JS aggiorna `current`, sposta `#maps-cam` con transform e `#maps-puck` con transform SVG; la linea percorsa usa `stroke-dashoffset` su `#maps-route-progress`.
- **Timing:** `1.05s cubic-bezier(.32,.72,0,1)` per camera, puck e progress route; hover/press locali tra `.18s` e `.35s` usando `var(--spring-pop)` / `var(--spring)`.
- **Hover states:** sidebar rows, route stops, controls, markers e CTA hanno feedback morbidi: background translucido, scale `.985/.93`, saturazione/ombra marker.
- **Responsive:** sotto 860px la sidebar sparisce, la mappa occupa tutta la finestra e top/bottom panels si compattano.

## Stati principali
1. FSL (PCTO): partenza esperienziale, marker verde, istruzione “Parti dal PCTO”.
2. Diploma: tappa di consolidamento, marker blu, route progress ~0.33.
3. Università a Brescia: tappa formativa, marker viola/blu, route progress ~0.66.
4. Opportunità all’estero / America: arrivo, marker rosso, route progress 1.
'''
(root/'docs/research/BEHAVIORS.md').write_text(behaviors)

topology = '''# PAGE_TOPOLOGY — Maps window

## Sezione ricostruita
- **Window:** `#w-fine.maps-window` dentro desktop macOS esistente.
- **Fixed/sticky:** finestra assoluta gestita dal sistema `.win`; dentro Maps, controlli e panels sono overlay assoluti sopra SVG map.
- **Layout:** CSS grid a 2 colonne: sidebar 292px + map canvas flessibile.
- **Z layers:** SVG map base (0), overlay shading pseudo Apple Maps (`.maps-main::after`), controls/top card/bottom sheet (z 5), sidebar a sinistra.

## Componenti
1. **MapsSidebar** — search, preferiti/guide, card percorso e tappe generate da JS.
2. **MapsCanvas** — SVG dark map con strade, acqua, aree verdi, route e marker.
3. **MapsInstructionCard** — card verde superiore con freccia/indicazione e testo di tappa.
4. **MapsBottomSheet** — ETA/caption, dots e CTA Avanti.
5. **MapsControls** — zoom/reset stile Apple Maps.

## Dipendenze
- `hub.js` inizializza `[data-maps-navigator]` e richiede gli ID: `maps-cam`, `maps-puck`, `maps-route-progress`, `data-maps-stops`, `data-maps-dots`, `data-maps-title`, `data-maps-kicker`, `data-maps-copy`, `data-maps-icon`, `data-maps-eta`, `data-maps-sub`, `data-maps-recent`.
- `macos.css` contiene il design system e il blocco Maps.
'''
(root/'docs/research/PAGE_TOPOLOGY.md').write_text(topology)

spec = '''# AppleMapsJourney Specification

## Overview
- **Target file:** `hub.php` section `#w-fine`
- **Screenshot:** user-provided Apple Maps references in chat
- **Interaction model:** click-driven + map-driven; no scroll interaction inside the Maps app

## DOM Structure
- `.maps-window` contains `.maps-titlebar` and `.maps-app[data-maps-navigator]`.
- `.maps-app` is a 2-column grid: `.maps-side` and `.maps-main`.
- `.maps-side`: search bar, labels, recent/favorite rows, `.maps-route-card` with mode tabs and `[data-maps-stops]` container, recent card.
- `.maps-main`: SVG `.maps-map`, `.maps-top-card`, `.maps-controls`, `.maps-compass`, `.maps-bottom-sheet`.
- SVG map groups: background tiles, roads/cities, `#maps-cam`, route base/progress, markers, `#maps-puck`.

## Computed Styles / Tokens
### Window
- background: `rgba(22,24,28,.78)`
- border-radius: `24px`
- shadow: `var(--shadow-win)` + hairline inset

### App
- height: `min(650px, calc(100vh - 120px))`
- grid-template-columns: `292px minmax(0, 1fr)`
- background: `#0b1018`
- foreground: `#f5f5f7`
- font: `SF Pro Display, -apple-system, BlinkMacSystemFont, Helvetica Neue, Arial`

### Sidebar
- background: vertical dark gradient `rgba(21,28,37,.96)` to `rgba(10,17,26,.96)`
- border-right: `1px solid rgba(255,255,255,.10)`
- padding: `12px`

### Top Card
- position: absolute top-left, width 344px
- border-radius: 21px
- background: `linear-gradient(180deg,#31ba53,#15913a)`
- box-shadow: green Apple Maps depth
- animation: `mapsPanelIn .55s cubic-bezier(.32,.72,0,1)`

### Bottom Sheet
- position: absolute left/right/bottom 16px
- border-radius: 21px
- background: `rgba(248,248,250,.80)`
- backdrop-filter: `blur(16px) saturate(1.75)`
- animation: `mapsSheetIn .55s cubic-bezier(.32,.72,0,1)`

## States & Behaviors
### Tappa attiva
- **Trigger:** click on `[data-maps-step]`, `.maps-dot`, `.maps-marker`, `.maps-map`, or `[data-maps-next]`.
- **State change:** `current` index updates; camera transform, puck transform, route progress, active sidebar rows/dots/markers and text content update.
- **Transition:** `1.05s cubic-bezier(.32,.72,0,1)` for map camera/route/puck.

### Zoom
- **Trigger:** click `data-maps-zoom="in|out"`.
- **State:** zoom clamps between `.82` and `1.45`; `render()` recomputes transform.

### Reset
- **Trigger:** click `[data-maps-reset]`.
- **State:** zoom becomes 1.

## Per-State Content
1. **FSL (PCTO)** — esperienza pratica, primo contatto con lavoro/metodo.
2. **Diploma** — chiusura maturità e consolidamento competenze.
3. **Università a Brescia** — Ingegneria Informatica, basi solide software.
4. **America / estero** — ricerca opportunità internazionale di carriera.

## Assets
- No external assets required for the map; SVG generated inline.
- Existing app icon: `assets/icons/maps.webp` with remote fallback.

## Responsive Behavior
- **Desktop 1440px:** sidebar + full map canvas.
- **Tablet 768px / Mobile 390px:** `.maps-side` hidden, top card width 300px, bottom sheet tighter, window width `92vw`.
'''
(root/'docs/research/components/apple-maps-journey.spec.md').write_text(spec)

hub = (root/'hub.php').read_text()
old_start = hub.index('<section class="win a-blue maps-window" id="w-fine"')
old_end = hub.index('\n\n<div id="spot"', old_start)
new_section = r'''<section class="win a-blue maps-window" id="w-fine" style="left:9%;top:7%;width:1040px">
  <div class="titlebar maps-titlebar"><span class="wt">Dove voglio andare — Mappe</span></div>
  <div class="maps-app" data-maps-navigator>
    <aside class="maps-side" aria-label="Indicazioni percorso futuro">
      <div class="maps-search" role="search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
        <input value="Il mio percorso" aria-label="Cerca in Mappe" readonly>
        <button type="button" aria-label="Cancella">×</button>
      </div>

      <span class="maps-label">Guide</span>
      <button class="maps-side-row on" data-maps-step="0" type="button">
        <span class="maps-row-ico green">1</span><b>FSL · PCTO</b><small>Prima esperienza concreta</small>
      </button>
      <button class="maps-side-row" data-maps-step="1" type="button">
        <span class="maps-row-ico blue">2</span><b>Diploma</b><small>Conclusione della maturità</small>
      </button>
      <button class="maps-side-row" data-maps-step="2" type="button">
        <span class="maps-row-ico gray">3</span><b>Università a Brescia</b><small>Ingegneria Informatica</small>
      </button>
      <button class="maps-side-row" data-maps-step="3" type="button">
        <span class="maps-row-ico red">4</span><b>America / estero</b><small>Opportunità di carriera</small>
      </button>

      <div class="maps-route-card">
        <div class="maps-mode-tabs" aria-label="Modalità percorso">
          <button class="active" type="button">🚗</button><button type="button">🚶</button><button type="button">🎓</button><button type="button">✈️</button>
        </div>
        <div class="maps-route-stops" data-maps-stops aria-label="Tappe del percorso"></div>
        <button class="maps-add-stop" data-maps-next type="button">Prossima tappa</button>
      </div>

      <div class="maps-recent">
        <span>Selezionata</span>
        <b data-maps-recent>FSL · PCTO</b>
        <small>clicca sulla mappa o sulle tappe per avanzare</small>
      </div>
    </aside>

    <main class="maps-main" aria-label="Mappa animata del percorso futuro">
      <svg class="maps-map" viewBox="0 0 1200 720" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Percorso: FSL, Diploma, Università a Brescia, America">
        <defs>
          <linearGradient id="maps-water" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#15345c"/><stop offset="1" stop-color="#071d38"/></linearGradient>
          <linearGradient id="maps-land" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#163427"/><stop offset=".58" stop-color="#183622"/><stop offset="1" stop-color="#11291e"/></linearGradient>
          <filter id="maps-soft-shadow" x="-30%" y="-30%" width="160%" height="160%"><feDropShadow dx="0" dy="5" stdDeviation="5" flood-color="#000" flood-opacity=".25"/></filter>
        </defs>
        <rect width="1200" height="720" fill="url(#maps-land)"/>
        <path d="M760-40 C910 110 830 210 955 340 C1045 435 1115 470 1240 442 L1240-40Z" fill="url(#maps-water)" opacity=".92"/>
        <path d="M820 60 C930 180 865 235 970 335 C1060 420 1125 440 1208 410" fill="none" stroke="#245b91" stroke-width="2" opacity=".45"/>
        <path d="M-30 610 C120 520 208 568 322 470 C460 350 510 435 660 326 C780 238 862 250 980 122" fill="none" stroke="#234d3a" stroke-width="120" opacity=".36"/>
        <g opacity=".62" stroke-linecap="round" fill="none">
          <path d="M0 590 C180 528 236 546 365 475 C500 402 585 348 720 305 C850 265 975 250 1200 190" stroke="#6d7f86" stroke-width="7"/>
          <path d="M-20 505 C122 468 248 423 380 382 C540 332 685 310 870 270 C990 244 1080 210 1220 160" stroke="#51666d" stroke-width="4"/>
          <path d="M85 180 C215 255 335 232 456 280 C590 333 646 405 790 410 C890 415 1010 365 1160 330" stroke="#405b62" stroke-width="4"/>
          <path d="M250 685 C315 580 368 510 430 440 C510 348 570 290 610 190 C642 105 697 40 742-30" stroke="#526870" stroke-width="5"/>
          <path d="M520 730 C565 612 610 518 700 430 C790 342 820 260 812 150 C806 80 842 20 895-30" stroke="#415a62" stroke-width="4"/>
        </g>
        <g opacity=".45" stroke="#1a77b7" stroke-width="3" fill="none">
          <path d="M0 350 C160 310 250 330 380 300 C520 268 640 210 760 195"/>
          <path d="M80 690 C180 620 255 612 350 555 C470 482 540 430 650 392"/>
        </g>
        <g opacity=".16" stroke="#fff" stroke-width="1">
          <path d="M0 120H1200M0 240H1200M0 360H1200M0 480H1200M0 600H1200"/>
          <path d="M120 0V720M240 0V720M360 0V720M480 0V720M600 0V720M720 0V720M840 0V720M960 0V720M1080 0V720"/>
        </g>
        <g class="maps-city">
          <text x="130" y="610">PCTO</text><text x="390" y="420">Diploma</text><text x="650" y="262">Brescia</text><text x="955" y="95">America</text>
        </g>

        <g id="maps-cam">
          <path id="maps-route-base" d="M132 565 C230 502 305 498 392 438 C490 370 548 324 636 272 C748 207 858 132 1015 62" fill="none" stroke="rgba(15,105,210,.35)" stroke-width="22" stroke-linecap="round" stroke-linejoin="round"/>
          <path id="maps-route" d="M132 565 C230 502 305 498 392 438 C490 370 548 324 636 272 C748 207 858 132 1015 62" fill="none" stroke="#178bff" stroke-width="15" stroke-linecap="round" stroke-linejoin="round" filter="url(#maps-soft-shadow)"/>
          <path id="maps-route-progress" pathLength="1" d="M132 565 C230 502 305 498 392 438 C490 370 548 324 636 272 C748 207 858 132 1015 62" fill="none" stroke="#34c759" stroke-width="15" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1" stroke-dashoffset="1"/>

          <g class="maps-marker" data-maps-step="0" transform="translate(132 565)"><circle r="22" fill="#34c759"/><circle r="12" fill="#fff" opacity=".22"/><text class="num" x="0" y="4" text-anchor="middle">1</text><text x="-52" y="-34">FSL</text></g>
          <g class="maps-marker" data-maps-step="1" transform="translate(392 438)"><circle r="22" fill="#0a84ff"/><text class="num" x="0" y="4" text-anchor="middle">2</text><text x="-62" y="-34">Diploma</text></g>
          <g class="maps-marker" data-maps-step="2" transform="translate(636 272)"><circle r="22" fill="#5856d6"/><text class="num" x="0" y="4" text-anchor="middle">3</text><text x="-86" y="-34">Uni Brescia</text></g>
          <g class="maps-marker" data-maps-step="3" transform="translate(1015 62)"><circle r="24" fill="#ff3b30"/><text class="num" x="0" y="4" text-anchor="middle">4</text><text x="-62" y="48">America</text></g>

          <g id="maps-puck" transform="translate(132 565)">
            <circle r="18" fill="#0a84ff" stroke="#fff" stroke-width="5" filter="url(#maps-soft-shadow)"/>
            <circle r="5" fill="#fff"/>
          </g>
        </g>
      </svg>

      <section class="maps-top-card" aria-live="polite">
        <div class="maps-turn-icon" data-maps-icon>→</div>
        <div><span data-maps-kicker>Partenza</span><b data-maps-title>FSL · PCTO</b><p data-maps-copy>Il primo punto del viaggio: esperienza concreta, responsabilità e contatto con il mondo del lavoro.</p></div>
      </section>

      <div class="maps-controls" aria-label="Controlli mappa">
        <button data-maps-zoom="in" type="button" aria-label="Zoom avanti">+</button><span></span><button data-maps-zoom="out" type="button" aria-label="Zoom indietro">−</button>
      </div>
      <button class="maps-compass" data-maps-reset type="button" aria-label="Ricentra mappa"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 7 21l5-3 5 3z" fill="#ff3b30"/><path d="M12 3v15" stroke="#fff" opacity=".8"/></svg></button>

      <section class="maps-bottom-sheet">
        <div><b data-maps-eta>Inizio percorso</b><small data-maps-sub>FSL / PCTO · esperienza concreta</small></div>
        <div class="maps-dots" data-maps-dots aria-label="Avanzamento percorso"></div>
        <button class="maps-go" data-maps-next type="button">Avanti</button>
      </section>
    </main>
  </div>
</section>'''
hub = hub[:old_start] + new_section + hub[old_end:]
(root/'hub.php').write_text(hub)

js_path = root/'hub.js'
js = js_path.read_text()
start = js.index('  const stops=[', js.index('/* Maps Navigator'))
end = js.index('  ];', start) + 4
new_stops = """  const stops=[\n    {x:132,y:565,p:0,icon:'→',kicker:'Partenza',title:'FSL · PCTO',eta:'Inizio percorso',sub:'esperienza concreta · primo contatto col lavoro',copy:'Il viaggio parte dal PCTO: un’esperienza pratica che trasforma la scuola in metodo, responsabilità e contatto reale con il mondo professionale.',explain:'Prima tappa: lavoro, metodo, responsabilità.'},\n    {x:392,y:438,p:.33,icon:'↱',kicker:'Tra poco',title:'Diploma',eta:'Tappa di consolidamento',sub:'maturità · competenze · consapevolezza',copy:'Il diploma chiude il percorso scolastico e diventa il ponte: non solo un traguardo, ma la prova di aver costruito basi tecniche e personali.',explain:'Il diploma come passaggio verso scelte più grandi.'},\n    {x:636,y:272,p:.66,icon:'↑',kicker:'Prosegui',title:'Università a Brescia',eta:'Direzione Brescia',sub:'Ingegneria Informatica · basi solide',copy:'La prossima direzione è Ingegneria Informatica a Brescia: approfondire software, sistemi e progettazione per crescere con basi più solide.',explain:'Studio verticale per diventare più forte nel software.'},\n    {x:1015,y:62,p:1,icon:'✈',kicker:'Arrivo',title:'Opportunità all’estero',eta:'Sogno America',sub:'carriera internazionale · software',copy:'La meta più ambiziosa è spostarmi, cercare opportunità all’estero e costruire una carriera nel software in un contesto internazionale: il sogno è l’America.',explain:'Meta finale: America, lavoro e crescita internazionale.'}\n  ];"""
js = js[:start] + new_stops + js[end:]
# make rows const harmless stale; generated and pre-existing both sync
js = js.replace("  const rows=[...app.querySelectorAll('[data-maps-step]')];\n", "")
js_path.write_text(js)

css_path = root/'macos.css'
css = css_path.read_text()
append = r'''

/* Apple Maps Journey polish — leggibilità route e micro-interazioni */
.maps-map .maps-marker { cursor:pointer; transform-origin:center; }
.maps-map .maps-marker:hover { opacity:1; filter:drop-shadow(0 10px 14px rgba(0,0,0,.38)); }
.maps-go { transition:transform .22s var(--spring-pop), filter .18s ease, box-shadow .22s ease; }
.maps-go:hover { filter:brightness(1.06); box-shadow:0 10px 26px rgba(10,132,255,.38); }
.maps-go:active { transform:scale(.94); }
.maps-add-stop { transition:transform .22s var(--spring-pop), background .18s ease; }
.maps-add-stop:hover { background:rgba(10,132,255,.25); }
.maps-add-stop:active { transform:scale(.97); }
.maps-mode-tabs button { transition:background .18s ease, transform .22s var(--spring-pop); }
.maps-mode-tabs button:active { transform:scale(.92); }
.maps-route-stops .maps-stop-line.active span { background:#0a84ff; color:#fff; }
@media(max-width:620px){.maps-top-card{left:10px;top:10px;width:calc(100% - 68px);padding:12px;border-radius:18px}.maps-turn-icon{width:42px;height:42px;font-size:34px}.maps-top-card b{font-size:20px}.maps-bottom-sheet{gap:10px;padding:11px 12px}.maps-dots{gap:7px}.maps-go{padding:9px 13px}.maps-bottom-sheet b{font-size:16px}}
'''
if 'Apple Maps Journey polish' not in css:
    css += append
css_path.write_text(css)

print('Rebuild complete')
print('hub.php chars', len(hub), 'hub.js chars', len(js), 'macos.css chars', len(css))
