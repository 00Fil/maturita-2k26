<?php
/* ============================================================
   schema.php — "Dietro le quinte": il flusso del backend
   - protetta dalla SESSIONE: senza login si torna a index.php
   - ?spiegazioni=1 → versione spiegata (Studio)
   - default        → solo i passaggi (Esposizione):
     le spiegazioni NON vengono nemmeno inviate al browser
   - il contatore degli accessi è letto in tempo reale da MySQL
   ============================================================ */
session_start();

if (!isset($_SESSION['nome'])) {
    header('Location: index.php');
    exit;
}

$nome   = $_SESSION['nome'];
$spiega = isset($_GET['spiegazioni']);

$totale = null;
try {
    $pdo = new PDO(
        'mysql:host=' . (getenv('DB_HOST') ?: '127.0.0.1') . ';dbname=' . (getenv('DB_NAME') ?: 'pcto') . ';charset=utf8mb4',
        getenv('DB_USER') ?: 'root',
        getenv('DB_PASS') ?: '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $totale = (int) $pdo->query('SELECT COUNT(*) FROM accessi')->fetchColumn();
} catch (PDOException $e) {
    $totale = null; // il database non risponde: la pagina funziona lo stesso
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dietro le quinte · PCTO Maturità 2026</title>
<style>
/* ============================================================
   STILE — stesso design system del login form:
   palette neutra, radius 32, spring 240/18/1.1, aurora bg
   ============================================================ */
:root {
  --scene: #FAFAFA;
  --container: #F4F4F4;
  --container-hover: #ebeaea;
  --surface: #FEFEFE;
  --surface-hover: #fafafa;
  --border: rgba(231,230,230,0.65);
  --text: #18181B;
  --text-strong: rgba(0,0,0,0.9);
  --placeholder: #A1A1AA;
  --error: #DC2626;
  --ok: #22C55E;
  --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
  --shadow-panel: 0 1px 2px 0 rgba(0,0,0,0.05);
  --r: 32px;
  --r-full: 999px;
  --spring: cubic-bezier(0.3, 1.36, 0.46, 1);
  --spring-soft: cubic-bezier(0.22, 1, 0.36, 1);
  --dur: 0.8s;
  --aur-1: rgba(186,190,255,0.55);
  --aur-2: rgba(255,214,222,0.5);
  --aur-3: rgba(199,235,224,0.5);
  --aur-4: rgba(255,236,200,0.45);
}
@media (prefers-color-scheme: dark) {
  :root {
    --scene: #0A0A0A;
    --container: #1C1C1E;
    --container-hover: #252529;
    --surface: #2C2C2E;
    --surface-hover: #3A3A3C;
    --border: rgba(255,255,255,0.05);
    --text: #fefefe;
    --text-strong: #fefefe;
    --placeholder: #B2B2B2;
    --error: #F87171;
    --shadow-sm: 0 10px 15px -3px rgba(0,0,0,0.35);
    --shadow-panel: 0 20px 25px -5px rgba(0,0,0,0.45);
    --aur-1: rgba(99,102,241,0.16);
    --aur-2: rgba(236,121,160,0.10);
    --aur-3: rgba(45,212,191,0.10);
    --aur-4: rgba(250,204,21,0.07);
  }
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  background: var(--scene);
  color: var(--text);
  -webkit-font-smoothing: antialiased;
  min-height: 100vh;
}

/* sfondo aurora */
.bg { position: fixed; inset: 0; overflow: hidden; z-index: 0; }
.bg .blob { position: absolute; border-radius: 50%; filter: blur(110px); will-change: transform; }
.bg .b1 { width: 60vmax; height: 60vmax; top: -25vmax; left: -15vmax; background: radial-gradient(circle, var(--aur-1), transparent 65%); animation: drift1 26s ease-in-out infinite alternate; }
.bg .b2 { width: 55vmax; height: 55vmax; bottom: -28vmax; right: -18vmax; background: radial-gradient(circle, var(--aur-2), transparent 65%); animation: drift2 32s ease-in-out infinite alternate; }
.bg .b3 { width: 45vmax; height: 45vmax; top: 45%; left: -20vmax; background: radial-gradient(circle, var(--aur-3), transparent 65%); animation: drift3 38s ease-in-out infinite alternate; }
.bg .b4 { width: 40vmax; height: 40vmax; top: -18vmax; right: -10vmax; background: radial-gradient(circle, var(--aur-4), transparent 65%); animation: drift4 30s ease-in-out infinite alternate; }
@keyframes drift1 { from { transform: translate(0,0) scale(1); }    to { transform: translate(8vmax, 6vmax) scale(1.12); } }
@keyframes drift2 { from { transform: translate(0,0) scale(1.08); } to { transform: translate(-7vmax, -5vmax) scale(1); } }
@keyframes drift3 { from { transform: translate(0,0) scale(1); }    to { transform: translate(10vmax, -6vmax) scale(1.15); } }
@keyframes drift4 { from { transform: translate(0,0) scale(1.1); }  to { transform: translate(-6vmax, 7vmax) scale(1); } }
.bg .wash { position: absolute; inset: 0; background: radial-gradient(ellipse 90% 70% at 50% 50%, transparent 30%, var(--scene) 100%); }

.wrap {
  position: relative;
  z-index: 1;
  max-width: 680px;
  margin: 0 auto;
  padding: 56px 20px 80px;
}

/* header */
header { margin-bottom: 30px; padding-left: 6px; animation: enter var(--dur) var(--spring) both; }
.eyebrow {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.12em;
  color: var(--placeholder); margin-bottom: 6px;
}
header h1 {
  font-size: 28px; font-weight: 700; letter-spacing: -0.025em;
  color: var(--text); margin-bottom: 8px;
}
header p { font-size: 14px; font-weight: 500; color: var(--placeholder); line-height: 1.5; }

.controls { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; }
.badge {
  display: inline-flex; align-items: center; gap: 8px;
  height: 36px; padding: 0 16px;
  background: var(--container);
  border: 1.1px solid var(--border);
  border-radius: var(--r-full);
  font-size: 12.5px; font-weight: 700; letter-spacing: -0.01em;
  color: var(--placeholder);
  text-decoration: none;
  transition: background .3s ease, color .3s ease, transform .4s var(--spring);
}
a.badge:hover { background: var(--container-hover); color: var(--text); }
a.badge:active { transform: scale(.95); }
.badge.on { color: var(--text); background: var(--surface); box-shadow: var(--shadow-sm); }
.badge .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--ok); box-shadow: 0 0 10px rgba(34,197,94,.6); }
@keyframes enter {
  from { opacity: 0; transform: scale(0.96) translateY(8px); filter: blur(4px); }
  to   { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
}

/* timeline */
.flow { position: relative; }
.flow::before {
  content: '';
  position: absolute;
  left: 21px; top: 16px; bottom: 16px;
  width: 1.5px;
  background: var(--border);
}

.step {
  position: relative;
  display: flex;
  gap: 14px;
  margin-bottom: 14px;
  opacity: 0; transform: translateY(14px) scale(.97); filter: blur(4px);
  transition: opacity .55s var(--spring-soft), transform .7s var(--spring), filter .55s var(--spring-soft);
}
.step.in { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }

.num {
  position: relative;
  z-index: 1;
  flex-shrink: 0;
  width: 44px; height: 44px;
  border-radius: var(--r-full);
  background: var(--container);
  border: 1.1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; font-weight: 700;
  color: var(--text-strong);
}

.card {
  flex: 1;
  min-width: 0;
  background: var(--container);
  border: 1.1px solid var(--border);
  border-radius: 24px;
  padding: 8px;
  transition: background 0.3s ease;
}
.card:hover { background: var(--container-hover); }
.card .inner {
  background: var(--surface);
  border-radius: 17px;
  box-shadow: var(--shadow-sm);
  padding: 14px 16px 13px;
}

.head { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.head h2 { font-size: 16.5px; font-weight: 700; letter-spacing: -0.02em; flex: 1; min-width: 0; }
.zone {
  font-size: 10px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.1em;
  color: var(--placeholder);
  background: var(--container);
  border-radius: var(--r-full);
  padding: 4px 10px;
  white-space: nowrap;
}

pre {
  margin-top: 10px;
  background: var(--container);
  border-radius: 12px;
  padding: 10px 12px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  font-size: 12px;
  line-height: 1.55;
  color: var(--text);
  white-space: pre-wrap;
  word-break: break-word;
}

<?php if ($spiega): ?>
.exp { margin-top: 10px; font-size: 13.5px; font-weight: 500; color: var(--text); line-height: 1.6; opacity: .92; }
.exp + .exp { margin-top: 7px; }
.nozione {
  margin-top: 11px;
  display: flex; gap: 9px; align-items: flex-start;
  background: var(--container);
  border-radius: 12px;
  padding: 9px 12px;
  font-size: 12.5px; font-weight: 600;
  color: var(--placeholder);
  line-height: 1.5;
}
.nozione b { color: var(--text); font-weight: 700; }
.nozione .led { width: 8px; height: 8px; border-radius: 50%; background: var(--ok); flex-shrink: 0; margin-top: 5px; }
<?php endif; ?>

/* esiti possibili */
.outcomes { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
.pill {
  font-size: 11.5px; font-weight: 700;
  border-radius: var(--r-full);
  padding: 5px 12px;
  background: var(--container);
  color: var(--placeholder);
  display: inline-flex; align-items: center; gap: 6px;
}
.pill .d { width: 7px; height: 7px; border-radius: 50%; }
.pill .d.g { background: var(--ok); }
.pill .d.r { background: var(--error); }

footer {
  margin-top: 26px;
  text-align: center;
  font-size: 12px;
  font-weight: 600;
  color: var(--placeholder);
  opacity: 0; transition: opacity 1s ease 0.4s;
}
footer.in { opacity: 1; }

@media print {
  .bg { display: none; }
  body { background: #fff; }
  .step, footer { opacity: 1 !important; transform: none !important; filter: none !important; }
}
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.001s !important; transition-duration: 0.001s !important; }
}
</style>
</head>
<body>

<div class="bg">
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="blob b3"></div>
  <div class="blob b4"></div>
  <div class="wash"></div>
</div>

<div class="wrap">

  <header>
    <div class="eyebrow">PCTO · Maturità 2026 — Dietro le quinte</div>
    <h1>Come funziona il login</h1>
    <p><?= $spiega
        ? 'Il viaggio di nome e codice: dal form del browser fino a MySQL e ritorno.'
        : 'Gli 8 passaggi, senza spiegazioni: il resto lo racconti tu.' ?></p>
    <div class="controls">
      <span class="badge on"><span class="dot"></span>Ciao, <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?></span>
      <a class="badge<?= $spiega ? ' on' : '' ?>" href="schema.php?spiegazioni=1">Studio — spiegata</a>
      <a class="badge<?= $spiega ? '' : ' on' ?>" href="schema.php">Esposizione — solo passaggi</a>
      <a class="badge" href="logout.php">Esci</a>
    </div>
  </header>

  <div class="flow">

    <!-- 1 -->
    <div class="step">
      <div class="num">1</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Il browser invia la richiesta</h2><span class="zone">Browser</span></div>
        <pre>fetch('login.php', { method: 'POST', body: dati })
// dati = nome + codice (+ demo)</pre>
<?php if ($spiega): ?>
        <div class="exp">Il form HTML raccoglie i dati grazie agli attributi <b>name</b> degli input (<code>name="nome"</code>, <code>name="codice"</code>). Con il metodo <b>POST</b> i dati viaggiano nel <b>corpo</b> della richiesta HTTP, non nell'URL: più adatto a dati riservati rispetto a GET, che li mostrerebbe nella barra degli indirizzi.</div>
        <div class="exp"><code>fetch</code> è <b>asincrono</b>: la pagina non si ricarica e l'animazione continua a girare. La Promise si risolverà con un oggetto <b>Response</b> (passo 8).</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> form HTML · metodo GET vs POST · fase di fetch</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 2 -->
    <div class="step">
      <div class="num">2</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Il server riceve i dati</h2><span class="zone">Server PHP</span></div>
        <pre>$nome   = trim($_POST['nome'] ?? '');
$codice = $_POST['codice'] ?? '';</pre>
<?php if ($spiega): ?>
        <div class="exp">PHP deposita i dati del form in <b>$_POST</b>, un <b>array associativo</b>: la <b>chiave</b> è l'attributo <code>name</code> dell'input, il <b>valore</b> è ciò che l'utente ha digitato.</div>
        <div class="exp"><code>trim()</code> elimina gli spazi all'inizio e alla fine; <code>?? ''</code> (null coalescing) evita errori se la chiave non esiste.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> array associativi · $_POST · attributo name</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 3 -->
    <div class="step">
      <div class="num">3</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Validazione dei campi</h2><span class="zone">Server PHP</span></div>
        <pre>if ($nome === '' || $codice === '') → stop</pre>
        <div class="outcomes"><span class="pill"><span class="d g"></span>ok → si procede</span><span class="pill"><span class="d r"></span>campo vuoto → HTTP 400 Bad Request</span></div>
<?php if ($spiega): ?>
        <div class="exp">Non ci si fida <b>mai</b> dei soli controlli nel browser: chiunque può chiamare <code>login.php</code> direttamente, saltando il form. La validazione vera si fa <b>lato server</b>.</div>
        <div class="exp">Se manca un campo la richiesta si ferma subito con il codice di stato <b>400</b>: la categoria 4xx indica un errore del client.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> validazione server-side · codici di stato HTTP 4xx</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 4 -->
    <div class="step">
      <div class="num">4</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Verifica del codice d'accesso</h2><span class="zone">Server PHP</span></div>
        <pre>password_verify($codice, $CODICE_HASH)
// hash generato con password_hash(..., PASSWORD_DEFAULT)</pre>
        <div class="outcomes"><span class="pill"><span class="d g"></span>vero → si procede</span><span class="pill"><span class="d r"></span>falso → HTTP 401 Unauthorized</span></div>
<?php if ($spiega): ?>
        <div class="exp">Il codice non viene mai salvato né confrontato <b>in chiaro</b>: se ne conserva l'<b>hash</b>. L'hashing è <b>a senso unico</b>: dall'hash è computazionalmente impossibile risalire al codice originale (non è crittografia, non si “decripta”).</div>
        <div class="exp"><code>password_hash()</code> usa <b>bcrypt</b>: volutamente <b>lento</b> (rende impraticabile il brute-force) e con <b>salt casuale</b> per ogni hash (rende inutili le rainbow table: due codici uguali producono hash diversi). MD5 e SHA1 non si usano più: troppo veloci e vulnerabili alle collisioni.</div>
        <div class="exp"><code>password_verify()</code> rifà il confronto in modo sicuro. Se il codice è sbagliato → <b>401 Unauthorized</b>.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> password_hash() / password_verify() · bcrypt · salt · niente MD5</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 5 -->
    <div class="step">
      <div class="num">5</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Sessione avviata</h2><span class="zone">Server PHP</span></div>
        <pre>session_regenerate_id(true);
$_SESSION['nome'] = $nome;</pre>
<?php if ($spiega): ?>
        <div class="exp">HTTP è senza memoria: ogni richiesta è indipendente. La <b>sessione</b> risolve il problema: il server crea un “cassetto personale” per il visitatore (<b>$_SESSION</b>, un array associativo) e dà al browser un cookie con il solo <b>ID di sessione</b>.</div>
        <div class="exp"><code>session_regenerate_id(true)</code> genera un <b>nuovo ID</b> subito dopo il login: se qualcuno avesse rubato l'ID precedente, non varrebbe più nulla. È la difesa contro la <b>session fixation</b>.</div>
        <div class="exp"><b>Questa pagina ne è la prova</b>: schema.php ti riconosce (“Ciao” in alto) leggendo <code>$_SESSION['nome']</code>, e senza sessione attiva ti rimanda al login.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> sessioni · $_SESSION · session fixation</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 6 -->
    <div class="step">
      <div class="num">6</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Connessione al database</h2><span class="zone">Database</span></div>
        <pre>new PDO("mysql:host=$DB_HOST;dbname=pcto", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);</pre>
        <div class="outcomes"><span class="pill"><span class="d g"></span>connesso</span><span class="pill"><span class="d r"></span>PDOException → HTTP 503</span></div>
<?php if ($spiega): ?>
        <div class="exp"><b>PDO</b> (PHP Data Objects) è l'estensione di PHP per parlare con i database: stessa interfaccia per MySQL e altri DBMS.</div>
        <div class="exp">Con <code>ERRMODE_EXCEPTION</code> ogni errore diventa una <b>PDOException</b>: il blocco <b>try/catch</b> la cattura e il sito risponde con un messaggio pulito e <b>503 Service Unavailable</b>, senza mai mostrare dettagli interni del database all'utente.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> connessione PDO · try/catch · gestione eccezioni</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 7 -->
    <div class="step">
      <div class="num">7</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Query SQL — prepared statement</h2><span class="zone">Database</span></div>
        <pre>$stmt = $pdo->prepare('INSERT INTO accessi (nome) VALUES (?)');
$stmt->execute([$nome]);</pre>
<?php if ($spiega): ?>
        <div class="exp">La query parte <b>prima</b>, con il segnaposto <code>?</code>; il dato viaggia <b>separato</b>, dopo. Il valore non viene mai concatenato dentro la stringa SQL → la <b>SQL injection è impossibile</b>: anche un nome malevolo come <code>'); DROP TABLE accessi;--</code> resta semplice testo e finisce nella colonna <code>nome</code>.</div>
        <div class="exp">La riga creata: <code>id</code> è AUTO_INCREMENT (lo assegna MySQL), <code>data_accesso</code> ha DEFAULT CURRENT_TIMESTAMP (la mette il database da solo). Una <b>singola INSERT è atomica</b>: o riesce tutta o non riesce affatto — le transazioni (commit/rollback) servono solo quando <b>più</b> query devono riuscire o fallire insieme.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> prepared statement · SQL injection · atomicità vs transazioni</span></div>
<?php endif; ?>
      </div></div>
    </div>

    <!-- 8 -->
    <div class="step">
      <div class="num">8</div>
      <div class="card"><div class="inner">
        <div class="head"><h2>Risposta JSON al browser</h2><span class="zone">Server → Browser</span></div>
        <pre>http_response_code(200);
echo json_encode(['ok' => true]);</pre>
        <div class="outcomes"><span class="pill"><span class="d g"></span>200 OK</span><span class="pill"><span class="d r"></span>400 · 401 · 503</span></div>
<?php if ($spiega): ?>
        <div class="exp">Il server risponde con un <b>codice di stato HTTP</b> che riassume l'esito (2xx successo, 4xx errore del client, 5xx errore del server) e un corpo <b>JSON</b>.</div>
        <div class="exp">Nel browser la fetch si risolve con l'oggetto <b>Response</b>: <code>risposta.status</code> è il codice, <code>risposta.json()</code> legge il corpo. In base a <code>json.ok</code> l'interfaccia mostra il pannello di benvenuto oppure l'errore con lo shake.</div>
        <div class="nozione"><span class="led"></span><span><b>Nozione:</b> codici di stato HTTP · oggetto Response · JSON</span></div>
<?php endif; ?>
      </div></div>
    </div>

  </div>

  <footer id="foot"><?= $totale !== null ? 'Accessi registrati finora: ' . $totale . ' · ' : '' ?>index.php · login.php · MySQL (tabella accessi)</footer>

</div>

<script>
const steps = document.querySelectorAll('.step');
const io = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in');
      io.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });
steps.forEach((s, i) => {
  if (i < 3) setTimeout(() => s.classList.add('in'), 150 + i * 220);
  else io.observe(s);
});
setTimeout(() => document.getElementById('foot').classList.add('in'), 900);
</script>
</body>
</html>
