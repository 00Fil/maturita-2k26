# PAGE_TOPOLOGY — Maps window

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
