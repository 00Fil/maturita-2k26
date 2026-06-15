# Performance hardening · repo PCTO

Obiettivo: preferire un avvio più controllato e una UI fluida su hardware sconosciuto.

## Cosa è stato aggiunto

- **Performance guard runtime** in `hub.js` e `login.js`
  - rileva `prefers-reduced-motion`, `saveData`, core CPU, memoria device e pointer coarse;
  - applica automaticamente le classi `perf-low`, `perf-mid`, `perf-coarse`;
  - osserva i frame reali e scala la grafica se il browser inizia a perdere frame.

- **Preload controllato degli asset critici**
  - durante il boot desktop vengono precaricate e decodificate le immagini pesanti usate nelle app;
  - il loader può durare di più, ma l’esperienza dopo il boot è più stabile.

- **Asset ottimizzati e collegati nel runtime**
  - wallpaper: `assets/optimized/bg.webp`;
  - video lock screen leggero: `assets/optimized/lock-lite.mp4`, 1280×720 a 30 fps;
  - immagini Note: `assets/notes/optimized/*.webp`;
  - immagini Spotlight: `assets/spotlight-gallery/optimized/*.webp`;
  - icone app mancanti ricostruite localmente da `assets/icons-b64`, così non dipendono più da GitHub esterno.

- **Fallback grafico progressivo** in `macos.css`
  - su device medi riduce blur e ombre;
  - su device lenti disattiva blur pesanti, halo, filtri e animazioni costose;
  - mantiene coerenza visiva senza bloccare l’interfaccia.

- **Ottimizzazioni JavaScript**
  - Dock misurato una sola volta e aggiornato via `requestAnimationFrame`;
  - `pointermove` pesanti resi passivi e throttled;
  - rendering di Note e Calendario rinviato con `requestIdleCallback` quando possibile.

## Verifiche fatte

- `node --check hub.js`
- `node --check login.js`
- `node --check sound.js`
- controllo dei riferimenti agli asset originali pesanti: il runtime ora punta agli asset ottimizzati;
- controllo icone: tutte le icone usate da `appicon()` sono presenti localmente.

Nota: nel sandbox non è disponibile PHP, quindi non ho potuto eseguire `php -l`. Ho comunque verificato la struttura dei file PHP e la sintassi JavaScript.
