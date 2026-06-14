# PCTO · Maturità 2026

Sito di presentazione del percorso PCTO per l'esame di Maturità.
Login animato (morph button) → backend PHP minimale → MySQL → desktop in stile macOS con la presentazione.

## Struttura

| File | Cosa fa |
|---|---|
| `index.php` | La pagina con il tasto morph e il form di accesso |
| `login.php` | Backend: verifica il codice e registra l'accesso su MySQL |
| `hub.php` | Il desktop in stile macOS: la presentazione in 6 capitoli, protetta dalla sessione |
| `macos.css` | Design system unico: token, vetro, finestre, dock, login, app e responsive |
| `hub.js` / `login.js` / `sound.js` | Interazioni desktop, lock screen e micro-suoni |
| `logout.php` | Chiude la sessione e torna al login |
| `setup.sql` | Crea il database `pcto` e la tabella `accessi` |
| `Dockerfile` | Immagine PHP 8.3 + Apache con `pdo_mysql` |
| `docker-compose.yml` | Stack completo: app + MySQL 8.4 (con init automatico) |

## Design system macOS

Il progetto ora usa **un solo file CSS**, `macos.css`. Dentro ci sono:

- token globali: font SF Pro, colori, accenti, ombre, hairline, raggi e curve di easing;
- componenti base: menubar, dock, finestre, semafori, Centro di Controllo, card glass;
- lock screen: input password meno bianco/trasparente, blur più materico e transizione più rapida verso il desktop;
- contenuti delle app: “Su di me” è una vera app Note macOS, mentre agenda PCTO, Launchpad e Mappe hanno layout e identità coerenti;
- responsive e accessibilità: focus ring, reduced motion e fallback dark mode.

I vecchi fogli `hub.css` e `hub-polish.css` sono stati consolidati in `macos.css` e non vengono più caricati.

## Variabili d'ambiente

| Variabile | Default | Descrizione |
|---|---|---|
| `CODICE_ACCESSO` | `maturita2026` | Codice richiesto per entrare (in chiaro, solo per sviluppo) |
| `CODICE_ACCESSO_HASH` | — | Hash bcrypt del codice (consigliato in produzione). Generalo con: `php -r "echo password_hash('iltuocodice', PASSWORD_DEFAULT);"` |
| `DB_HOST` | `127.0.0.1` | Host MySQL (`db` in Docker) |
| `DB_NAME` | `pcto` | Nome database |
| `DB_USER` | `root` | Utente MySQL |
| `DB_PASS` | *(vuota)* | Password MySQL |
| `DB_ROOT_PASS` | — | Password root MySQL (solo compose) |

Senza variabili impostate valgono i default di Laragon: il progetto funziona
anche copiato in `C:\laragon\www\pcto` senza Docker.

## Modalità Demo (per la presentazione)

La demo si attiva **solo** aprendo il sito con `?demo=1` nell'URL (nessun tasto visibile).
Sotto il form compare un widget schematico **Browser → Server PHP → MySQL**, integrato
nello stesso stile del login: mostra un passaggio alla volta ed evidenzia dove sta
avvenendo, con un "pacchetto" animato che viaggia sulla freccia giusta (andata e
ritorno client ↔ server ↔ database). Un controllerino (indietro · play/pausa · avanti ·
puntini cliccabili) permette di fermare la sequenza per spiegare con calma ogni
messaggio. I passaggi mostrati sono quelli reali:

1. il browser invia la richiesta (`fetch POST → login.php`)
2. il server riceve nome + codice (il codice viene mascherato)
3. validazione dei campi
4. verifica del codice con `password_verify()` (hash bcrypt, mai confronti in chiaro)
5. avvio della sessione (`$_SESSION` + `session_regenerate_id()`)
6. connessione PDO a MySQL
7. la **query SQL eseguita** (`INSERT INTO accessi …` come prepared statement)
8. lettura di controllo (`SELECT COUNT(*) FROM accessi`)
9. risposta JSON al browser, con codice di stato HTTP e tempi in millisecondi

Se la modalità demo è spenta, il backend non calcola né invia nessun passaggio.

## Il desktop (hub.php)

Dopo il login si arriva su un desktop in stile macOS — stessa estetica della pagina di
accesso, governata da `macos.css`: superfici glass, bordi hairline, raggi coerenti,
motion Apple-like e un colore accento per ogni app.
La finestra "Scaletta" si apre da sola e guida i 10 minuti di esposizione in 6 capitoli:

1. **Da dove parto** — il filo conduttore personale (1')
2. **Il percorso in azienda** — due anni in CS Metal Europe, 240 ore (3')
3. **Cosa ho imparato** — tre lezioni oltre gli strumenti (2')
4. **Il progetto** — questo sito, dal login al desktop (2')
5. **I collegamenti** — le materie dentro l'esperienza (1')
6. **Cosa porto via** — bilancio e direzione (1')

Ogni capitolo è un'app nel dock (ingrandimento al passaggio, rimbalzo all'apertura);
le finestre si trascinano, si chiudono e si massimizzano con i semafori.
La pagina è **protetta dalla sessione**: senza login si torna a `index.php`.

## Nozioni applicate nel backend

| Nozione | Dove |
|---|---|
| Form → `$_POST` (array associativo, chiavi = attributi `name`) | `login.php`, punto 1 |
| `password_hash()` / `password_verify()` — mai confronti in chiaro | `login.php`, punto 2 |
| Sessioni: `$_SESSION`, `session_regenerate_id()` contro la session fixation | `login.php`, punto 3 |
| PDO con try/catch e `ERRMODE_EXCEPTION` | `login.php`, punto 4 |
| Prepared statement contro la SQL injection | `login.php`, punto 4 |
| Codici di stato HTTP (200, 400, 401, 503) | `login.php`, `rispondi()` |
| `fetch` + oggetto `Response` (status + JSON) | `index.php`, invio form |

## Avvio locale con Docker

```bash
docker compose up -d --build
```

Poi apri http://localhost:8080 — al primo avvio MySQL esegue `setup.sql` da solo.

## Deploy su DokPloy

1. In DokPloy crea un nuovo servizio di tipo **Compose**
2. Collega questa repository (`maturita-2k26`, branch `main`)
3. In **Environment** imposta:
   - `DB_PASS` — una password robusta
   - `DB_ROOT_PASS` — una password robusta per root
   - `CODICE_ACCESSO` — il codice che vuoi usare
4. Aggiungi il tuo dominio puntando al servizio `app`, porta **80**
5. Deploy — fine.

Gli accessi registrati sono nella tabella `accessi` del database `pcto`.
