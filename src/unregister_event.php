<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'joueur') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'] ?? null;

if (!$event_id) {
    die("Missing event ID.");
}

// Verifica che l'evento non sia ancora iniziato
$stmt = $pdo->prepare("SELECT start_datetime FROM events WHERE id = :event_id");
$stmt->execute(['event_id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event || $event['start_datetime'] <= date('Y-m-d H:i:s')) {
    die("You cannot unsubscribe from an event that has already started.");
}

// Elimina la registrazione
$stmt = $pdo->prepare("DELETE FROM event_registrations WHERE user_id = :user_id AND event_id = :event_id");
$stmt->execute([
    'user_id' => $user_id,
    'event_id' => $event_id
]);

header("Location: myaccount.php?unregister=ok");
exit();
