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
    header("Location: events.php?register=invalid");
    exit();
}

// Controllo se già iscritto
$stmt = $pdo->prepare("SELECT id FROM event_registrations WHERE user_id = :user_id AND event_id = :event_id");
$stmt->execute([
    'user_id' => $user_id,
    'event_id' => $event_id
]);
if ($stmt->rowCount() > 0) {
    header("Location: events.php?register=duplicate");
    exit();
}

// Controllo se ci sono posti disponibili
$stmt = $pdo->prepare("
    SELECT max_players, 
    (SELECT COUNT(*) FROM event_registrations WHERE event_id = :event_id AND status IN ('pending', 'accepted')) AS current
    FROM events
    WHERE id = :event_id
");
$stmt->execute(['event_id' => $event_id]);
$event = $stmt->fetch();

if (!$event || $event['current'] >= $event['max_players']) {
    header("Location: events.php?register=full");
    exit();
}

// Inserisce registrazione
$stmt = $pdo->prepare("INSERT INTO event_registrations (user_id, event_id, status) VALUES (:user_id, :event_id, 'pending')");
$stmt->execute([
    'user_id' => $user_id,
    'event_id' => $event_id
]);

header("Location: events.php?register=success");
exit();