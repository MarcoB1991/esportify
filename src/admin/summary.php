<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// STATISTICHE UTENTI
$stmt = $pdo->query("SELECT role, COUNT(*) AS total FROM users GROUP BY role");
$user_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// STATISTICHE EVENTI
$stmt = $pdo->query("SELECT is_validated, COUNT(*) AS total FROM events GROUP BY is_validated");
$event_stats = [];
foreach ($stmt->fetchAll() as $row) {
    $event_stats[$row['is_validated']] = $row['total'];
}

// STATISTICHE ISCRIZIONI
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM event_registrations");
$registration_count = $stmt->fetchColumn();
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <h2>📊 Admin Summary</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <p>🎮 Players: <?= $user_stats['joueur'] ?? 0 ?></p>
                    <p>🛠️ Organizer: <?= $user_stats['organisateur'] ?? 0 ?></p>
                    <p>👩‍💼 Admin: <?= $user_stats['admin'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning mb-3">
                <div class="card-header">Events</div>
                <div class="card-body">
                    <p>✅ Validated: <?= $event_stats[1] ?? 0 ?></p>
                    <p>⏳ Pending: <?= $event_stats[0] ?? 0 ?></p>
                    <p>📅 Total Events: <?= ($event_stats[0] ?? 0) + ($event_stats[1] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-header">Registrations</div>
                <div class="card-body">
                    <p>📥 Total Registrations: <?= $registration_count ?></p>
                </div>
            </div>
        </div>
    </div>

    <a href="events.php" class="btn btn-outline-secondary mt-4"><strong>📅 Events Settings</strong></a>
    <hr class="my-3">
    <a href="users.php" class="btn btn-outline-secondary mt-4"><strong>🔐 Users Informations</strong></a>
    
    <hr class="my-5">
    <h3>📈 Graphical statistics</h3>

    <div class="row mt-4">
        <div class="col-md-6">
            <h5>User distribution</h5>
            <canvas id="userChart" width="300" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Events Status</h5>
            <canvas id="eventChart" width="400" height="300"></canvas>
        </div>
    </div>
</div>

<!-- SCRIPT CHART -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userChart = new Chart(document.getElementById('userChart'), {
            type: 'pie',
            data: {
                labels: ['Players', 'Organizer', 'Admin'],
                datasets: [{
                    data: [
                        <?= $user_stats['joueur'] ?? 0 ?>,
                        <?= $user_stats['organisateur'] ?? 0 ?>,
                        <?= $user_stats['admin'] ?? 0 ?>
                    ],
                    backgroundColor: ['#0d6efd', '#ffc107', '#dc3545']
                }]
            },
            options: { responsive: true }
        });

        const eventChart = new Chart(document.getElementById('eventChart'), {
            type: 'bar',
            data: {
                labels: ['Validated', 'Pending'],
                datasets: [{
                    label: 'Events',
                    data: [
                        <?= $event_stats[1] ?? 0 ?>,
                        <?= $event_stats[0] ?? 0 ?>
                    ],
                    backgroundColor: ['#198754', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>