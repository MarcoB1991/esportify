<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: ../login.php');
    exit();
}

$organizer_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE organizer_id = :id ORDER BY start_datetime DESC");
$stmt->execute(['id' => $organizer_id]);
$events = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <h2>My Events</h2>
    <p><a href="create_event.php" class="btn btn-warning mt-3">➕ Create new event</a></p>
    <p><a href="manage_registrations.php" class="btn btn-outline-primary mt-4">👥 Registration management</a></p>


    <?php if (count($events) === 0): ?>
        <div class="alert alert-info">You have not created any events yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Start Datetime</th>
                    <th>End Datetime</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Start Event</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $e): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['title']); ?></td>
                        <td><?php echo $e['start_datetime']; ?></td>
                        <td><?php echo $e['end_datetime']; ?></td>
                        <td><?php echo $e['is_validated'] ? '✔️ Validated' : '⏳ Pending'; ?></td>
                        <td>
                            <?php
                            $now = date('Y-m-d H:i:s');
                            if ($e['start_datetime'] > $now): ?>
                                <a href="edit_event.php?id=<?= $e['id']; ?>" class="btn btn-outline-primary btn-sm">✏️ Settings</a>
                            <?php else: ?>
                                <span class="text-muted">Event started</span>
                            <?php endif; ?>

                        </td>
                        <td>
                            <?php if ($e['is_validated'] && $e['start_datetime'] > $now): ?>
                                <form method="post" action="launch_event.php">
                                    <input type="hidden" name="event_id" value="<?php echo $e['id']; ?>">
                                    <button type="submit" class="btn btn-outline-success btn-sm">🚀 Start Event</button>
                                </form>
                            <?php else: ?>
                                <em>N/A</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>