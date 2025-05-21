<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validazione campi
    if ($username && $email && $password) {

        // Controlla se email o username esistono già
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);

        if ($stmt->rowCount() === 0) {
            // Imposta ruolo in base alla email
            if (str_ends_with($email, '@esportify.com')) {
                $role = 'organisateur';
            } else {
                $role = 'joueur';
            }

            // Hash password e salva utente
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, role, created_at)
                VALUES (:u, :e, :p, :r, NOW())
            ");
            $stmt->execute([
                'u' => $username,
                'e' => $email,
                'p' => $hashed,
                'r' => $role
            ]);

            header('Location: login.php');
            exit();
        } else {
            $error = 'Email or username already in use.';
        }
    } else {
        $error = 'Fill in all the required fields.';
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <h4 class="mb-4 text-center">📝 Registration</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>

            <button class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3 mb-0">
        You already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

