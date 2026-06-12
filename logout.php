<?php
/* ============================================================
   logout.php — chiude la sessione (come da schema del corso)
   ============================================================ */
session_start();

session_unset();             // svuota tutte le chiavi di $_SESSION
session_destroy();           // termina la sessione corrente

session_start();             // nuova sessione vuota...
session_regenerate_id(true); // ...con un NUOVO ID: il vecchio non è riutilizzabile
                             // (mitiga la session fixation anche dopo il logout)

header('Location: index.php');
exit;
