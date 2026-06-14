# Refactor macOS UI — report tecnico

## Obiettivo
Portare il progetto verso un linguaggio visivo unico, vicino a macOS, correggendo i contenuti delle app privi di stile e rendendo la repo più modulare.

## Interventi principali

### 1. Design system unico
- Creato `macos-system.css`, unico CSS runtime caricato da `index.php` e `hub.php`.
- Accentrati token globali `--mac-*` per colori, vetro, ombre, raggi, animazioni e spring.
- Separati gli scope `body[data-surface="lock"]` e `body[data-surface="desktop"]` per evitare collisioni tra login e desktop.

### 2. Login screen
- Ridotto il blur bianco/latteo del campo password.
- Campo password più scuro, più solido, con focus ring Apple-like.
- Transizione login → desktop più breve e coerente, senza effetto lento e divergente.

### 3. App e contenuti
- Rivisitata l’app “Su di me / Informazioni”: griglia, card, statistiche e sezioni con identità visiva coerente.
- Rivisitata l’app “Mappe”: pannello indicazioni, lista tappe, controlli zoom/bussola e label con stile Apple Maps.
- Spostato Spotlight nel design system, eliminando CSS inline.
- Preservati i componenti già forti: dock, finestre, semafori, angoli e centro di controllo.

### 4. Struttura repo
- Estratto `login.js` da `index.php`.
- Estratti i moduli inline di `hub.php` dentro `hub.js`.
- Rimossi i vecchi `hub.css` e `hub-polish.css` dal runtime.
- Decodificate le icone locali da `assets/icons-b64` in `assets/icons`, evitando fallback remoti quando possibile.
- Aggiornato `README.md` con il nuovo contratto architetturale.

## Validazioni eseguite
- `node --check login.js`
- `node --check hub.js`
- `node --check sound.js`
- Controllo bilanciamento parentesi CSS: OK
- Controllo assenza di `<style>` e `<script>` inline in `index.php` / `hub.php`: OK
- Controllo riferimenti asset locali principali: OK

## Nota
Nel sandbox non è disponibile il comando `php`, quindi non è stato possibile eseguire `php -l`. La struttura PHP è stata mantenuta minimale: sono stati modificati soprattutto riferimenti CSS/JS e markup non invasivo.
