<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: ../login.php');
    exit();
}

$organizer_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Recupera evento
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: my_events.php');
    exit();
}

$event_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND organizer_id = :org_id");
$stmt->execute(['id' => $event_id, 'org_id' => $organizer_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $error = "Evento non trovato o non autorizzato.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start = $_POST['start_datetime'];
    $end = $_POST['end_datetime'];
    $max_players = (int)$_POST['max_players'];

    if ($title && $description && $start && $end && $max_players > 0) {
        $stmt = $pdo->prepare("
            UPDATE events 
            SET title = :title, description = :description, start_datetime = :start, end_datetime = :end, max_players = :max 
            WHERE id = :id AND organizer_id = :org_id
        ");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'start' => $start,
            'end' => $end,
            'max' => $max_players,
            'id' => $event_id,
            'org_id' => $organizer_id
        ]);
        $success = "Event successfully updated!";
        // Refresh data
        $event['title'] = $title;
        $event['description'] = $description;
        $event['start_datetime'] = $start;
        $event['end_datetime'] = $end;
        $event['max_players'] = $max_players;
    } else {
        $error = "Fill in all the required fields.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <div class="card shadow p-4" style="max-width: 700px; margin: auto;">
        <h3 class="mb-4 text-center">✏️ Update Event</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_datetime" class="form-label">Start Datetime</label>
                    <input type="datetime-local" class="form-control" name="start_datetime" id="start_datetime" value="<?= date('Y-m-d\TH:i', strtotime($event['start_datetime'])) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="end_datetime" class="form-label">End Datetime</label>
                    <input type="datetime-local" class="form-control" name="end_datetime" id="end_datetime" value="<?= date('Y-m-d\TH:i', strtotime($event['end_datetime'])) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="max_players" class="form-label">Max Players</label>
                <input type="number" class="form-control" name="max_players" id="max_players" value="<?= $event['max_players'] ?>" required min="1">
            </div>

            <div class="d-flex justify-content-between">
                <a href="my_events.php" class="btn btn-outline-secondary">🔙 Back to Events</a>
                <button type="submit" class="btn btn-primary">💾 Save</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>