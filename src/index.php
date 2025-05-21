<?php
session_start();
require_once 'config/database.php';

// Recupera ultimi 3 eventi validati
$stmt = $pdo->prepare("
    SELECT e.id, e.title, e.start_datetime, e.end_datetime, u.username AS organizer
    FROM events e
    JOIN users u ON e.organizer_id = u.id
    WHERE e.is_validated = 1
    ORDER BY e.start_datetime ASC
    LIMIT 3
");
$stmt->execute();
$latest_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-5">
    <h1 class="mb-4">Welcome on <span class="text-primary">Esportify</span></h1>
    <p class="lead">👋 This is a test Git</p>

    <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] === 'joueur'): ?>
            <div class="alert alert-primary">🎮 Ready to the competition? <a href="events.php">Register for an event</a>!</div><br>
        <?php elseif ($_SESSION['role'] === 'organisateur'): ?>
            <div class="alert alert-warning">🛠️ Organize a new tournament from <a href="organizer/create_event.php">here</a>!</div>
        <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <div class="alert alert-success">📊 Go to <a href="admin/summary.php">Admin Dashboard</a>.</div>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="register.php" class="btn btn-primary">Register now</a> to enter in the world of e-sports!</p>
    <?php endif; ?>

    <h3 class="text-center my-5">
        <span class="alert alert-primary">✨ Upcoming Events</span>
    </h3>
    <div class="row my-4">
        <?php foreach ($latest_events as $event): ?>
            <div class="col-md-4 my-3">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                        <p class="card-text">
                            📅 <?= date('d/m/Y', strtotime($event['start_datetime'])) ?><br>
                            🕒 <?= date('H:i', strtotime($event['start_datetime'])) ?> - <?= date('H:i', strtotime($event['end_datetime'])) ?><br>
                            🧑 Organized by <?= htmlspecialchars($event['organizer']) ?>
                        </p>
                        <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-outline-primary btn-sm">Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center">
        <a href="events.php" class="btn btn-primary">🔎 ALL Events</a>
    </div>
</div>
</div>

<?php include 'includes/footer.php'; ?>