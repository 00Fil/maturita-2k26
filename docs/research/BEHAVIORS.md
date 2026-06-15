# BEHAVIORS — Apple Maps rebuild

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
