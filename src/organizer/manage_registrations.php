<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: ../login.php');
    exit();
}

$organizer_id = $_SESSION['user_id'];

// Gestione azioni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_id'], $_POST['action'])) {
    $registration_id = (int)$_POST['registration_id'];
    $status = $_POST['action'] === 'accept' ? 'accepted' : 'rejected';

    $stmt = $pdo->prepare("
        UPDATE event_registrations er
        JOIN events e ON er.event_id = e.id
        SET er.status = :status
        WHERE er.id = :reg_id AND e.organizer_id = :org_id
    ");
    $stmt->execute([
        'status' => $status,
        'reg_id' => $registration_id,
        'org_id' => $organizer_id
    ]);
}

// Recupero iscrizioni agli eventi dell’organizzatore
$stmt = $pdo->prepare("
    SELECT er.id AS registration_id, er.status, u.username AS player,
           e.title AS event_title, e.start_datetime
    FROM event_registrations er
    JOIN users u ON er.user_id = u.id
    JOIN events e ON er.event_id = e.id
    WHERE e.organizer_id = :org_id
    ORDER BY e.start_datetime DESC
");
$stmt->execute(['org_id' => $organizer_id]);
$registrations = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <h2>📋 Manage registrations for your events</h2>

    <?php if (count($registrations) === 0): ?>
        <div class="alert alert-info">No players have signed up for your events yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Event</th>
                    <th>Datetime</th>
                    <th>Player</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['event_title']); ?></td>
                        <td><?php echo $r['start_datetime']; ?></td>
                        <td><?php echo htmlspecialchars($r['player']); ?></td>
                        <td><?php echo ucfirst($r['status']); ?></td>
                        <td>
                            <?php if ($r['status'] === 'pending'): ?>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="registration_id" value="<?php echo $r['registration_id']; ?>">
                                    <button name="action" value="accept" class="btn btn-success btn-sm">✅ Validate</button>
                                    <button name="action" value="reject" class="btn btn-danger btn-sm">❌ Reject</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">No action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="my_events.php" class="btn btn-outline-secondary mt-4">🔙 Back to My Events</a>
</div>

<?php include '../includes/footer.php'; ?>