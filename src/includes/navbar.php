<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="/index.php">
      <img src="/assets/img/logo.jpg" alt="Logo" width="40" height="40" class="me-2">
      <span>Esportify</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <?php if (isset($_SESSION['username'])) : ?>
          <li class="nav-item">
            <span class="nav-link user-greeting">Hello, <?php echo $_SESSION['username']; ?>!</span>
          </li>

          <li class="nav-item">
            <?php
            switch ($_SESSION['role']) {
              case 'admin':
                echo '<li class="nav-item"><a class="nav-link user-greeting" href="/admin/summary.php">Dashboard</a></li>';
                break;

              case 'organisateur':
                echo '<li class="nav-item"><a class="nav-link user-greeting" href="/organizer/my_events.php">Dashboard</a></li>';
                break;
              case 'joueur':
                echo '<li class="nav-item"><a class="nav-link user-greeting" href="/myaccount.php">Dashboard</a></li>';
                break;
            }
            ?>
          </li>
          <li class="nav-item"><a class="nav-link user-greeting" href="/events.php">Events</a></li>
          <li class="nav-item"><a class="nav-link user-greeting" href="/info.php">ℹ️ Info</a></li>
          <li class="nav-item"><a class="nav-link user-greeting" href="/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link user-greeting" href="/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link user-greeting" href="/register.php">Register</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>