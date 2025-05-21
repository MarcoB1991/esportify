<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Azioni di approvazione/sospensione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'], $_POST['action'])) {
    $event_id = (int)$_POST['event_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE events SET is_validated = 1 WHERE id = :id");
        $stmt->execute(['id' => $event_id]);
    } elseif ($action === 'suspend') {
        $stmt = $pdo->prepare("UPDATE events SET is_validated = 0 WHERE id = :id");
        $stmt->execute(['id' => $event_id]);
    }
}

// Recupero eventi
$stmt = $pdo->query("
    SELECT events.*, users.username AS organizer_name
    FROM events
    JOIN users ON events.organizer_id = users.id
    ORDER BY start_datetime DESC
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">🛠️ Events Settings</h2>

    <?php if (count($events) === 0): ?>
        <div class="alert alert-info">No events available.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Organizer</th>
                        <th>Start Datetime</th>
                        <th>Validated?</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['organizer_name']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($event['start_datetime'])); ?></td>
                            <td>
                                <?php echo $event['is_validated'] ? '✔️ Yes' : '⏳ No'; ?>
                            </td>
                            <td>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <?php if ($event['is_validated']): ?>
                                        <button type="submit" name="action" value="suspend" class="btn btn-danger btn-sm">
                                            ❌ Reject
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">
                                            ✅ Validate
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="summary.php" class="btn btn-outline-secondary mt-4">📊 Back to summary</a>
</div>

<?php include '../includes/footer.php'; ?>