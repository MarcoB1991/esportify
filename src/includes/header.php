<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esportify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<?php
$pageClass = match (basename($_SERVER['SCRIPT_NAME'])) {
    'index.php' => 'body-home',
    'login.php' => 'body-login',
    'register.php' => 'body-register',
    'events.php' => 'body-events',
    'myaccount.php', 'myaccount_settings.php', 'my_events.php', 'edit_event.php' => 'body-myaccount',
    'event_details.php' => 'body-events-details',
    'summary.php', 'users.php' => 'body-summary',
    'info.php' => 'body-info',
    default => ''
};
?>

<body class="<?php echo $pageClass; ?>">