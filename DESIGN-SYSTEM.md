# Design system macOS

Questo progetto usa **un solo file CSS**: `macos-system.css`.

## Principio

La UI deve sembrare un sistema operativo coerente, non una collezione di pagine. Il linguaggio nasce dai componenti già più vicini a macOS:

- Dock applicazioni
- Finestre esterne: semaforo, angoli, ombre, titlebar
- Centro di Controllo
- Login screen / lock screen

I contenuti delle app, invece, devono usare componenti comuni e un'identità chiara.

## Regole di manutenzione

1. Non creare nuovi CSS separati per singole app.
2. Aggiungi token in `:root` solo se sono riutilizzabili.
3. Usa superfici coerenti: blur controllato, hairline, ombre Apple-like, radius sistematici.
4. Ogni app deve avere:
   - titlebar nativa del sistema;
   - corpo con griglia o lista leggibile;
   - colore accento;
   - componenti riusabili (`lgcard`, righe, chip, sidebar, control center style).
5. Le animazioni devono essere brevi, elastiche ma non divergenti tra login e desktop.

## File principali

- `macos-system.css` — design system unico
- `index.php` — lock screen
- `login.js` — logica login / demo backend
- `hub.php` — desktop e markup app
- `hub.js` — finestre, dock, control center, audio
- `hub-extras.js` — Spotlight e Mappe

## Fix principali già applicati

- Eliminati `hub.css` e `hub-polish.css` come fonti separate.
- Spostato il CSS inline del login nel design system.
- Spostato il CSS inline di Spotlight nel design system.
- Creati moduli JS separati per login ed extra del desktop.
- Stilate le app che avevano contenuti senza identità completa, in particolare `Su di me` e `Dove voglio andare`.
- Reso il Dockerfile più pulito e offline-friendly, senza fetch esterni non necessari.
