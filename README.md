# PCTO · Maturità 2026

Sito di presentazione del percorso PCTO per l'esame di Maturità.
Login animato (morph button) → backend PHP minimale → MySQL.

## Struttura

| File | Cosa fa |
|---|---|
| `index.php` | La pagina con il tasto morph e il form di accesso |
| `login.php` | Backend: verifica il codice e registra l'accesso su MySQL |
| `schema.php` | "Dietro le quinte": il flusso del backend, protetta dalla sessione |
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

## Modalità Demo (per la presentazione)

In basso a destra c'è il tasto **Demo backend** (in alternativa apri il sito con `?demo=1`).
Quando è attiva, ad ogni accesso compare un pannello "Dietro le quinte" che mostra
in tempo reale i passaggi che l'app esegue davvero:

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

## Pagina "Dietro le quinte" (schema.php)

Dopo il login si viene portati su `schema.php`, **protetta dalla sessione**:
senza login si torna automaticamente a `index.php`. Due modalità:

- **Esposizione** (default): solo gli 8 passaggi, senza spiegazioni in chiaro nel sorgente
- **Studio** (`?spiegazioni=1`): ogni passaggio spiegato con le nozioni del corso

Il contatore degli accessi in fondo è letto in tempo reale da MySQL.
Il tasto **Esci** chiama `logout.php` (`session_unset` + `session_destroy` + nuovo ID di sessione).

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
