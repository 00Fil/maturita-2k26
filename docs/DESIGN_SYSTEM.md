# macOS Design System — PCTO Maturità 2026

Questo progetto usa **un solo file CSS** come fonte di verità visiva:

```
assets/css/macos-system.css
```

## Principi

- **Materiali macOS**: vetro non lattiginoso, blur calibrato, hairline, saturazione controllata.
- **Componenti coerenti**: login, desktop, menubar, dock, finestre, centro di controllo e contenuti app condividono token e movimenti.
- **Applicazioni con identità**: il contenuto non deve apparire “nudo”; ogni app deve avere superfici, card, gerarchie tipografiche e accento cromatico.
- **Animazioni Apple-like**: rapide, morbide, senza transizioni divergenti o eccessivamente lente.
- **Struttura pulita**: CSS in `assets/css`, JavaScript in `assets/js`, immagini/font/cursori in `assets`.

## Token principali

I token finali sono definiti nella parte conclusiva del CSS:

- `--macos-ease` per transizioni di sistema.
- `--macos-spring` per micro-interazioni elastiche.
- `--macos-radius-*` per raggi coerenti.
- `--macos-input` e `--macos-input-focus` per login/input scuri, meno trasparenti e più solidi.
- `--surface`, `--container`, `--glass-bar`, `--shadow-win` per desktop e finestre.

## Regole operative

1. Non creare nuovi file CSS di pagina.
2. Aggiungere nuovi componenti solo dentro `assets/css/macos-system.css`.
3. Le pagine devono usare scope espliciti: `body.login-screen` e `body.desktop-screen`.
4. Ogni app deve avere layout interno, gerarchia, card e stati hover/focus.
5. Evitare CSS inline: le eccezioni devono essere solo variabili locali (`style="--kc:#..."`) o posizionamenti runtime delle finestre.
