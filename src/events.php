<?php
session_start();
require_once 'config/database.php';

// Filtro titolo (search box)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$params = [];

$sql = "SELECT events.*, users.username AS organizer_name
        FROM events
        JOIN users ON events.organizer_id = users.id
        WHERE is_validated = 1";

if (!empty($search)) {
    $sql .= " AND events.title LIKE :search";
    $params['search'] = '%' . $search . '%';
}

$sql .= " ORDER BY start_datetime ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recupera tutti gli eventi validati
$stmt = $pdo->query("
    SELECT events.*, users.username AS organizer_name
    FROM events
    JOIN users ON events.organizer_id = users.id
    WHERE is_validated = 1
    ORDER BY start_datetime ASC
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recupera tutti gli organizzatori
$stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'organisateur'");
$organizers = $stmt->fetchAll();
?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main style="padding: 20px; max-width: 1000px; margin: auto;">

    <h2 class="mb-3">🎮 Events available</h2>

    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Event Title">
        </div>

        <div class="col-md-3">
            <input type="date" id="startDateInput" name="start_date" class="form-control">
        </div>

        <div class="col-md-3">
            <select id="organizerInput" name="organizer_id" class="form-select">
                <option value="">All the organizers</option>
                <?php foreach ($organizers as $o): ?>
                    <option value="<?php echo $o['id']; ?>"><?php echo htmlspecialchars($o['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <button type="button" id="resetBtn" class="btn btn-secondary">🔄</button>
        </div>
    </form>

    <div id="eventContainer">
        <!-- Qui verranno caricati dinamicamente gli eventi -->
    </div>

    <?php if (isset($_GET['register']) && $_GET['register'] === 'full'): ?>
        <div class="alert alert-warning">⚠️ This event has already reached the maximum number of participants.</div>
    <?php endif; ?>

    <?php if (isset($_GET['register'])): ?>
        <?php if ($_GET['register'] === 'success'): ?>
            <div class="alert alert-success">✅ Registration for the event was successful!</div>
        <?php elseif ($_GET['register'] === 'duplicate'): ?>
            <div class="alert alert-danger">❌ You are already registered for this event.</div>
        <?php elseif ($_GET['register'] === 'invalid'): ?>
            <div class="alert alert-warning">⚠️ Error: Invalid request.</div>
        <?php endif; ?>
    <?php endif; ?>

    <!--
    <?php if (isset($_GET['fav']) && $_GET['fav'] === 'ok'): ?>
        <div class="alert alert-warning">⭐ Event added to favorites!</div>
    <?php endif; ?>
    -->
    
    <?php if (count($events) === 0): ?>
        <p>There are currently no events available.</p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>