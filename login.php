<?php
/* ============================================================
   login.php — backend minimale
   1. riceve nome + codice dal form (POST)
   2. verifica il codice d'accesso
   3. salva l'accesso su MySQL
   4. risponde in JSON
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

function rispondi(bool $ok, string $messaggio = ''): void {
    echo json_encode(['ok' => $ok, 'messaggio' => $messaggio]);
    exit;
}

// 1. Leggi i dati del form
$nome   = trim($_POST['nome'] ?? '');
$codice = $_POST['codice'] ?? '';

if ($nome === '' || $codice === '') {
    rispondi(false, 'Compila tutti i campi.');
}

// 2. Verifica il codice d'accesso
if (!hash_equals($CODICE_ACCESSO, $codice)) {
    rispondi(false, "Codice d'accesso errato.");
}

// 3. Salva l'accesso nel database
try {
    $pdo = new PDO(
        'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8mb4',
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare('INSERT INTO accessi (nome) VALUES (?)');
    $stmt->execute([$nome]);
} catch (PDOException $e) {
    rispondi(false, 'Database non raggiungibile. Il database è avviato? Hai eseguito setup.sql?');
}

// 4. Tutto ok
rispondi(true);
