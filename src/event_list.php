<?php
session_start();
require_once 'config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$organizer_id = isset($_GET['organizer_id']) ? $_GET['organizer_id'] : '';

$params = [];
$sql = "SELECT events.*, users.username AS organizer_name
        FROM events
        JOIN users ON events.organizer_id = users.id
        WHERE is_validated = 1";

if (!empty($search)) {
    $sql .= " AND events.title LIKE :search";
    $params['search'] = '%' . $search . '%';
}

if (!empty($start_date)) {
    $sql .= " AND DATE(events.start_datetime) >= :start_date";
    $params['start_date'] = $start_date;
}

if (!empty($organizer_id)) {
    $sql .= " AND organizer_id = :organizer_id";
    $params['organizer_id'] = $organizer_id;
}

$sql .= " ORDER BY start_datetime ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <?php foreach ($events as $event): ?>
        <div class="col-md-4">
            <div class="card mb-4 text-white shadow-sm">
                <div class="card-body" style="background-color: rgba(0,0,0,0.5);">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p class="card-text small">
                        📅 <?php echo date('d/m/Y', strtotime($event['start_datetime'])); ?><br>
                        🕒 <?php echo date('H:i', strtotime($event['start_datetime'])); ?> - <?php echo date('H:i', strtotime($event['end_datetime'])); ?><br>
                        🧑 Organizer: <?php echo htmlspecialchars($event['organizer_name']); ?>
                    </p>
                    <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-light btn-sm">💬 Details / Chat</a>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'joueur'): ?>
                        <form method="post" action="register_event.php" class="d-inline">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="btn btn-success btn-sm">✅ Submit</button>
                        </form>
                    <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <p class="mt-2 small text-warning">🔐 <a href="login.php" class="text-warning">Login</a> to submit</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (count($events) === 0): ?>
    <div class="alert alert-info">No events found with current filters.</div>
<?php endif; ?>
</div>