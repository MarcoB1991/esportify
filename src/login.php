<?php
session_start();
require_once 'config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirezione in base al ruolo
        switch ($user['role']) {
            case 'admin': header('Location: admin/summary.php'); break;
            case 'organisateur': header('Location: organizer/my_events.php'); break;
            case 'joueur': header('Location: myaccount.php'); break;
        }
        exit();
    } else {
        $error = "Wrong credentials.";
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h4 class="mb-4 text-center">🔐 Login</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <p class="text-center mt-3 mb-0">
        You don't have an account? <a href="register.php">Register</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>