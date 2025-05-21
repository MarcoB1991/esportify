<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'joueur') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT e.id AS event_id, e.title, e.start_datetime, e.end_datetime, er.status
    FROM event_registrations er
    JOIN events e ON er.event_id = e.id
    WHERE er.user_id = :user_id
    ORDER BY e.start_datetime ASC
");
$stmt->execute(['user_id' => $user_id]);
$events = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-5">
    <div class="card shadow p-4">
        <h3 class="mb-3">🎮 Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
        <p class="mb-4">This is your personal area.</p>

        <h5>Your events:</h5>
        <?php if (count($events) === 0): ?>
            <div class="alert alert-info mt-3">You are not yet registered for any event.</div>
        <?php else: ?>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['title']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($e['start_datetime'])) ?></td>
                            <td>
                                <?= match ($e['status']) {
                                    'accepted' => '✅ Accepted',
                                    'rejected' => '❌ Rejected',
                                    default => '⏳ Waiting...'
                                } ?>
                            </td>
                            <td>
                                <?php if ($e['start_datetime'] > date('Y-m-d H:i:s')): ?>
                                    <form method="post" action="unregister_event.php" onsubmit="return confirm('Do you want to unsubscribe?');">
                                        <input type="hidden" name="event_id" value="<?= $e['event_id'] ?>">
                                        <button class="btn btn-danger btn-sm">Unsubscribe</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Past Event</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <br><a href="myaccount_settings.php" class="btn btn-outline-primary mb-3">⚙️ Profile Settings</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>