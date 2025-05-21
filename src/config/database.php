<?php
require_once 'constants.php'; // Importiamo i dati di connessione

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Impostiamo gli errori PDO su eccezione
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connessione riuscita
    // echo "Connessione al database avvenuta con successo!";
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}
?>