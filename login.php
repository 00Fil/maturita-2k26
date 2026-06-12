<?php
/* ============================================================
   login.php — backend minimale
   1. riceve nome + codice dal form (POST)
   2. verifica il codice d'accesso
   3. salva l'accesso su MySQL
   4. risponde in JSON
   Con demo=1 la risposta include anche i passaggi eseguiti
   (per la modalità "Demo backend" della pagina).
   ============================================================ */

header('Content-Type: application/json; charset=utf-8');

/* ---------- CONFIGURAZIONE ----------
   In locale (Laragon) valgono i default qui sotto.
   In Docker/DokPloy vengono lette dalle variabili d'ambiente. */
$CODICE_ACCESSO = getenv('CODICE_ACCESSO') ?: 'maturita2026';
$DB_HOST       = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME       = getenv('DB_NAME') ?: 'pcto';
$DB_USER       = getenv('DB_USER') ?: 'root';
$DB_PASS       = getenv('DB_PASS') ?: '';
/* ------------------------------------ */

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

function rispondi(bool $ok, string $messaggio = ''): void {
    global $demo, $passi;
    $out = ['ok' => $ok, 'messaggio' => $messaggio];
    if ($demo) $out['passi'] = $passi;
    echo json_encode($out);
    exit;
}

// 1. Leggi i dati del form
$nome   = trim($_POST['nome'] ?? '');
$codice = $_POST['codice'] ?? '';

passo('Il server riceve la richiesta',
      'POST /login.php — nome: "' . $nome . '", codice: "' . str_repeat('•', max(strlen($codice), 1)) . '"');

if ($nome === '' || $codice === '') {
    passo('Validazione campi', 'Un campo è vuoto: la richiesta si ferma qui.', null, 'errore');
    rispondi(false, 'Compila tutti i campi.');
}
passo('Validazione campi', 'Entrambi i campi sono compilati: si procede.');

// 2. Verifica il codice d'accesso
if (!hash_equals($CODICE_ACCESSO, $codice)) {
    passo("Verifica del codice d'accesso",
          'hash_equals(CODICE_ACCESSO, codice) → falso. Confronto sicuro, a tempo costante.',
          null, 'errore');
    rispondi(false, "Codice d'accesso errato.");
}
passo("Verifica del codice d'accesso",
      'hash_equals(CODICE_ACCESSO, codice) → vero. Confronto sicuro, a tempo costante.');

// 3. Salva l'accesso nel database
try {
    $pdo = new PDO(
        'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8mb4',
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    passo('Connessione a MySQL', 'PDO connesso a "' . $DB_HOST . '", database "' . $DB_NAME . '".');

    $stmt = $pdo->prepare('INSERT INTO accessi (nome) VALUES (?)');
    $stmt->execute([$nome]);
    passo('Query SQL eseguita',
          'Prepared statement: il valore viaggia come parametro, niente SQL injection. Riga creata: #' . $pdo->lastInsertId() . '.',
          "INSERT INTO accessi (nome)\nVALUES ('" . $nome . "');");

    if ($demo) {
        $tot = (int) $pdo->query('SELECT COUNT(*) FROM accessi')->fetchColumn();
        passo('Lettura di controllo', 'Accessi registrati finora: ' . $tot . '.',
              'SELECT COUNT(*) FROM accessi;');
    }
} catch (PDOException $e) {
    passo('Connessione a MySQL', 'Errore: ' . $e->getMessage(), null, 'errore');
    rispondi(false, 'Database non raggiungibile. Il database è avviato? Hai eseguito setup.sql?');
}

// 4. Tutto ok
rispondi(true);
