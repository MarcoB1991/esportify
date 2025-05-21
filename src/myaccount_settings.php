<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'joueur') {
  header('Location: login.php');
  exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Recupera i dati attuali dell'utente
$stmt = $pdo->prepare("SELECT username, email, password FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Modifica dati (username/email)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $new_username = trim($_POST['username']);
  $new_email = trim($_POST['email']);

  if ($new_username && $new_email) {
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
    $stmt->execute([
      'username' => $new_username,
      'email' => $new_email,
      'id' => $user_id
    ]);

    $_SESSION['username'] = $new_username; // aggiorna sessione
    $success = 'Updated profile data.';
  } else {
    $error = 'Fill in all fields.';
  }
}

// Cambio password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if (password_verify($current_password, $user['password'])) {
    if ($new_password === $confirm_password) {
      $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
      $stmt->execute([
        'password' => $hashed_new_password,
        'id' => $user_id
      ]);
      $success = 'Password updated successfully.';
    } else {
      $error = 'New passwords do not match.';
    }
  } else {
    $error = 'Current password is incorrect.';
  }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-5">
  <div class="card shadow p-4" style="max-width: 500px; margin: auto;">
    <h4 class="mb-4 text-center">⚙️ Profile Settings</h4>

    <?php if ($success): ?>
      <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php elseif ($error): ?>
      <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="#">

      <input type="hidden" name="update_profile" value="1">

      <div class="mb-3">
        <label class="form-label">Username:</label>
        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
      </div>

      <button type="submit" class="btn btn-outline-primary mb-3">Save</button>
    </form>

    <br>

    <h4 class="mb-4 text-center">⚙️ Update Password</h4>

    <form method="post" action="#">

      <input type="hidden" class="form-control" name="change_password" value="1">

      <div class="mb-3">
        <label class="form-label">Current Password:</label>
        <input type="password" class="form-control" name="current_password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">New Password:</label>
        <input type="password" class="form-control" name="new_password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm new Password:</label>
        <input type="password" class="form-control" name="confirm_password" required>
      </div>

      <button type="submit" class="btn btn-outline-primary mb-3">Update Password</button>
    </form>

    <br><a href="myaccount.php" class="btn btn-outline-primary mb-3">🔙 Back to Dashboard</a>

  </div>
</div>

<?php include 'includes/footer.php'; ?>