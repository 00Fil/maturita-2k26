<?php
/* ============================================================
   login.php — backend adattato alle nozioni del corso
   1. riceve nome + codice dal form (POST)             [form → $_POST]
   2. verifica il codice con password_verify()         [password hashing]
   3. apre la sessione per riconoscere l'utente        [sessioni]
   4. registra l'accesso con un prepared statement     [SQL injection]
   5. risponde in JSON con il codice di stato HTTP     [API REST]
   Con demo=1 la risposta include anche i passaggi eseguiti.
   ============================================================ */

session_start(); // prima di qualsiasi output

header('Content-Type: application/json; charset=utf-8');

/* ---------- CONFIGURAZIONE ----------
   In locale (Laragon) valgono i default qui sotto.
   In Docker/DokPloy vengono lette dalle variabili d'ambiente. */
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'pcto';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

/* Il codice d'accesso non si confronta MAI in chiaro:
   se ne conserva l'HASH (bcrypt, con salt casuale) e si verifica
   con password_verify(), come per le password degli utenti.
   In produzione imposta CODICE_ACCESSO_HASH, generato una volta con:
   php -r "echo password_hash('iltuocodice', PASSWORD_DEFAULT);" */
$CODICE_HASH = getenv('CODICE_ACCESSO_HASH')
    ?: password_hash(getenv('CODICE_ACCESSO') ?: 'maturita2026', PASSWORD_DEFAULT);

/* ---------- modalità demo ---------- */
$demo  = ($_POST['demo'] ?? '') === '1';
$passi = [];
$t0    = microtime(true);

function passo(string $titolo, string $dettaglio = '', ?string $sql = null, string $stato = 'ok'): void {
    global $demo, $passi, $t0;
    if (!$demo) return;
    $passi[] = [
        'titolo'    => $titolo,
        'dettaglio' => $dettaglio,
        'sql'       => $sql,
        'stato'     => $stato,
        'ms'        => round((microtime(true) - $t0) * 1000, 1),
    ];
}

/* Risponde in JSON con il codice di stato HTTP appropriato:
   200 OK · 400 Bad Request · 401 Unauthorized · 503 Service Unavailable */
function rispondi(bool $ok, string $messaggio = '', int $statoHttp = 200): void {
    global $demo, $passi;
    http_response_code($statoHttp);
    $out = ['ok' => $ok, 'messaggio' => $messaggio];
    if ($demo) $out['passi'] = $passi;
    echo json_encode($out);
    exit;
}

// 1. Leggi i dati del form: $_POST è un array associativo,
//    le chiavi sono gli attributi name degli input
$nome   = trim($_POST['nome'] ?? '');
$codice = $_POST['codice'] ?? '';

passo('Il server riceve la richiesta',
      'POST /login.php — $_POST è un array associativo: le chiavi sono gli attributi name del form. nome: "' . $nome . '", codice: "' . str_repeat('•', max(strlen($codice), 1)) . '"');

if ($nome === '' || $codice === '') {
    passo('Validazione campi', 'Un campo è vuoto: la richiesta si ferma con HTTP 400 (Bad Request).', null, 'errore');
    rispondi(false, 'Compila tutti i campi.', 400);
}
passo('Validazione campi', 'Entrambi i campi sono compilati: si procede.');

// 2. Verifica il codice d'accesso con password_verify():
//    l'hash è bcrypt (lento + salt casuale), mai confronti in chiaro
if (!password_verify($codice, $CODICE_HASH)) {
    passo("Verifica del codice d'accesso",
          'password_verify(codice, hash) → falso. Il codice è conservato come hash bcrypt (algoritmo lento + salt casuale), mai in chiaro. Risposta: HTTP 401 (Unauthorized).',
          null, 'errore');
    rispondi(false, "Codice d'accesso errato.", 401);
}
passo("Verifica del codice d'accesso",
      'password_verify(codice, hash) → vero. L\'hash è generato con password_hash (bcrypt: lento + salt casuale), quindi niente confronti in chiaro né rainbow table.');

// 3. Sessione: da qui in poi il server riconosce l'utente tra le pagine
session_regenerate_id(true); // nuovo ID di sessione: mitiga la session fixation
$_SESSION['nome'] = $nome;
passo('Sessione avviata',
      'session_regenerate_id(true) genera un nuovo ID di sessione (contro la session fixation); $_SESSION[\'nome\'] = "' . $nome . '" resta disponibile in tutte le pagine che fanno session_start().');

// 4. Salva l'accesso nel database
try {
    $pdo = new PDO(
        'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8mb4',
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    passo('Connessione a MySQL', 'PDO connesso a "' . $DB_HOST . '", database "' . $DB_NAME . '", con PDO::ERRMODE_EXCEPTION: gli errori diventano eccezioni gestite dal try/catch.');

    $stmt = $pdo->prepare('INSERT INTO accessi (nome) VALUES (?)');
    $stmt->execute([$nome]);
    passo('Query SQL eseguita',
          'Prepared statement: query e dati viaggiano separati, il valore non viene mai concatenato nella query → la SQL injection è impossibile. Riga creata: #' . $pdo->lastInsertId() . '. (Una sola INSERT è atomica: non serve una transazione.)',
          "INSERT INTO accessi (nome)\nVALUES ('" . $nome . "');");

    if ($demo) {
        $tot = (int) $pdo->query('SELECT COUNT(*) FROM accessi')->fetchColumn();
        passo('Lettura di controllo', 'Accessi registrati finora: ' . $tot . '.',
              'SELECT COUNT(*) FROM accessi;');
    }
} catch (PDOException $e) {
    passo('Connessione a MySQL', 'PDOException catturata dal try/catch: ' . $e->getMessage() . ' Risposta: HTTP 503 (Service Unavailable).', null, 'errore');
    rispondi(false, 'Database non raggiungibile. Il database è avviato? Hai eseguito setup.sql?', 503);
}

// 5. Tutto ok → HTTP 200
rispondi(true);
