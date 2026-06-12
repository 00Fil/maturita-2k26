# PCTO · Maturità 2026

Sito di presentazione del percorso PCTO per l'esame di Maturità.
Login animato (morph button) → backend PHP minimale → MySQL.

## Struttura

| File | Cosa fa |
|---|---|
| `index.php` | La pagina con il tasto morph e il form di accesso |
| `login.php` | Backend: verifica il codice e registra l'accesso su MySQL |
| `setup.sql` | Crea il database `pcto` e la tabella `accessi` |
| `Dockerfile` | Immagine PHP 8.3 + Apache con `pdo_mysql` |
| `docker-compose.yml` | Stack completo: app + MySQL 8.4 (con init automatico) |

## Variabili d'ambiente

| Variabile | Default | Descrizione |
|---|---|---|
| `CODICE_ACCESSO` | `maturita2026` | Codice richiesto per entrare |
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
4. verifica del codice con `hash_equals` (confronto a tempo costante)
5. connessione PDO a MySQL
6. la **query SQL eseguita** (`INSERT INTO accessi …` come prepared statement)
7. lettura di controllo (`SELECT COUNT(*) FROM accessi`)
8. risposta JSON al browser, con i tempi in millisecondi

Se la modalità demo è spenta, il backend non calcola né invia nessun passaggio.

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
