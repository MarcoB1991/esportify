<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: events.php');
    exit();
}

$event_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT e.*, u.username AS organizer
    FROM events e
    JOIN users u ON e.organizer_id = u.id
    WHERE e.id = :id AND e.is_validated = 1
");
$stmt->execute(['id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: events.php');
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-3">
    <div class="card shadow p-4">
        <div class="row">
            <!-- COLONNA SINISTRA: dettagli evento -->
            <div class="col-md-8">
                <h2 class="mb-3"><?php echo htmlspecialchars($event['title']); ?></h2>

                <p><strong>Organized by:</strong> <?php echo htmlspecialchars($event['organizer']); ?></p>
                <p><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($event['start_datetime'])); ?></p>
                <p><strong>Datetime:</strong> <?php echo date('H:i', strtotime($event['start_datetime'])); ?> - <?php echo date('H:i', strtotime($event['end_datetime'])); ?></p>
                <p><strong>Max players:</strong> <?php echo $event['max_players']; ?></p>

                <hr>
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

                <div class="mt-4">
                    <a href="events.php" class="btn btn-outline-secondary">🔙 Back to events</a>
                </div>
            </div>

            <!-- COLONNA DESTRA: logo evento -->
            <div class="col-md-4 d-flex align-items-center justify-content-center">
                <img src="assets/img/default_logo.png" alt="Logo evento" class="img-fluid rounded" style="max-height: 250px;">
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>