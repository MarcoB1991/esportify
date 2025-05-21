<?php
session_start();
session_unset();   // Elimina tutte le variabili di sessione
session_destroy(); // Distrugge la sessione

header('Location: login.php'); // Torna alla pagina di login
exit();
