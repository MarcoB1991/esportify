<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Cancellazione utente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];

    // Previene eliminazione di se stesso
    if ($user_id !== $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $user_id]);
    }
}

// Recupera tutti gli utenti (eccetto l'admin attuale)
$stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
    <h2>👥 Registered Users</h2>

    <?php if (count($users) === 0): ?>
        <div class="alert alert-info mt-4">No registered users at the moment.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo ucfirst($u['role']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($u['created_at'])); ?></td>
                        <td>
                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                <form method="post" onsubmit="return confirm('Confirm to delete user <?php echo $u['username']; ?>?');">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <button class="btn btn-danger btn-sm">❌ Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">👑 current Admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="summary.php" class="btn btn-outline-secondary mt-4">📊 Back to summary</a>
</div>

<?php include '../includes/footer.php'; ?>