# PCTO · Maturità 2026

Sito di presentazione del percorso PCTO per l'esame di Maturità.
Lock screen in stile macOS → backend PHP minimale → MySQL → desktop/app in stile macOS con la presentazione.

## Struttura

| File | Cosa fa |
|---|---|
| `index.php` | Lock screen macOS con form di accesso |
| `login.php` | Backend: verifica il codice e registra l'accesso su MySQL |
| `hub.php` | Il desktop in stile macOS: la presentazione in 6 capitoli, protetta dalla sessione |
| `macos-system.css` | Design system unico: lock screen, desktop, app, dock, finestre, centro di controllo |
| `login.js` | Interazioni della schermata di login e modalità demo |
| `hub.js` | Interazioni core del desktop: finestre, dock, menubar, control center |
| `hub-extras.js` | Interazioni specialistiche: Spotlight e Mappe |
| `logout.php` | Chiude la sessione e torna al login |
| `setup.sql` | Crea il database `pcto` e la tabella `accessi` |
| `Dockerfile` | Immagine PHP 8.3 + Apache con `pdo_mysql` |
| `docker-compose.yml` | Stack completo: app + MySQL 8.4 (con init automatico) |

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

## Design system macOS

Il progetto usa **un solo file CSS**, `macos-system.css`. Dentro ci sono:

- token condivisi (`--mac-*`) per font, blur, easing, radius, hairline, ombre e superfici;
- base della lock screen e del desktop, centralizzata nello stesso linguaggio;
- componenti universali: finestre, semafori, dock, cards, sidebar, control center, login input;
- stili dedicati per le app che prima avevano contenuti non completamente stilati (`Su di me` e `Mappe`).

Regola di manutenzione: non creare nuovi file CSS per singole app. Estendi `macos-system.css` con token e componenti riusabili.

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
accesso (superfici neutre, bordi hairline) con un colore accento per ogni app.
La finestra "Presentazione" si apre da sola e guida i 10 minuti di esposizione in 5 app/capitoli:

1. **Su di me** — identità, competenze e percorso personale
2. **Fuori dall'aula** — progetti, concorsi e volontariato
3. **CS Metal Europe** — 240 ore di PCTO e primo contratto
4. **Dove voglio andare** — rotta dopo il diploma, università ed estero
5. **Spotlight** — frase conclusiva prima delle domande

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
