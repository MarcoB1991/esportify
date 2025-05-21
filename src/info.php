<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-5">
  <div class="card shadow p-4" style="max-width: 900px; margin: auto;">
    <h2 class="mb-4 text-center">ℹ️ Esportify Informations</h2>

    <section class="mb-4">
      <h4>🎮 Who we are</h4>
      <p>
      Esportify is a platform dedicated to the promotion of esports events and tournaments.
      Our mission is to create connections between organizers and players around the world, offering a safe, dynamic and professional environment.
      </p>
    </section>

    <section class="mb-4">
      <h4>📜 Our History</h4>
      <p>
      Founded in 2024 by a group of video game and web development enthusiasts, Esportify began as an academic project and then transformed into a real esports community.
      </p>
    </section>

    <section class="mb-4">
      <h4>👩‍💻 The founders</h4>
      <ul>
        <li>Marco Bertello – CEO & UX strategist</li>
        <li>Alex Smash – CTO & Backend engineer</li>
        <li>Luna Rives – Event manager</li>
      </ul>
    </section>

    <section class="mb-4">
      <h4>📬 Contacts</h4>
      <p>
      You can write for collaborations, requests or questions to:<br>
        <strong>info@esportify.com</strong><br>
        Or follow us on <a href="https://www.instagram.com/">Instagram</a>, <a href="https://www.facebook.com/">Facebook</a> o <a href="https://www.linkedin.com/">LinkedIn</a>.
      </p>
    </section>

    <div class="text-center">
      <a href="index.php" class="btn btn-outline-primary">🏠 Back to Home</a>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>