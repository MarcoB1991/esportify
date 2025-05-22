<?php
// Costanti di configurazione del database
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'esportify_db';
$user = getenv('DB_USER') ?: 'esport';
$pass = getenv('DB_PASS') ?: 'esport123'; // Se hai una password mettila qui, altrimenti lascialo vuoto

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}
?>
