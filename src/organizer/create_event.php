<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start = $_POST['start_datetime'];
    $end = $_POST['end_datetime'];
    $max_players = (int)$_POST['max_players'];
    $organizer_id = $_SESSION['user_id'];

    if ($title && $description && $start && $end && $max_players > 0) {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, start_datetime, end_datetime, max_players, organizer_id) VALUES (:title, :desc, :start, :end, :max, :org)");
        $stmt->execute([
            'title' => $title,
            'desc' => $description,
            'start' => $start,
            'end' => $end,
            'max' => $max_players,
            'org' => $organizer_id
        ]);
        $success = "Event created successfully! Waiting for admin approval.";
    } else {
        $error = "Fill in all the required fields.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <h2>🛠️ Create a new event</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3 mt-3">
        <div class="col-md-6">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="max_players" class="form-label">Max Players</label>
            <input type="number" name="max_players" id="max_players" class="form-control" required min="1">
        </div>

        <div class="col-md-6">
            <label for="start_datetime" class="form-label">Start Datetime</label>
            <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="end_datetime" class="form-label">End Datetime</label>
            <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" required>
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-primary">➕ Create Event</button>
            <a href="my_events.php" class="btn btn-outline-secondary">🔙 Back</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>