<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../controllers/EventsController.php';

$eventsController = new EventsController();

// Fetch all events without filters
$events = $eventsController->getEvents();

// Handle RSVP submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    $userId = $_SESSION['user']['id'];
    $status = $_POST['status'] ?? 'attending';
    $eventsController->rsvpToEvent($eventId, $userId, $status);
    header("Location: ?page=events");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./public/assets/eventsStyle.css">
    <title>Community Events</title>
</head>
<body>
<div class="container">
    <nav>
        <div class="nav-left">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']); ?></h1>
        </div>
        <button class="nav-toggle" onclick="toggleNav()">☰</button>
        <div class="nav-center">
            <a href="?page=dashboard"><i class="fa-solid fa-house"></i></a>
            <a href="?page=help_requests"><i class="fa-solid fa-tv"></i></a>
            <a href="?page=resource_sharing"><i class="fa-solid fa-share"></i></a>
            <a href="?page=events"><i class="fa-solid fa-calendar"></i></a>
        </div>
        <div class="nav-right">
            <a href="?page=profile"><i class="fa-solid fa-user"></i></a>
            <a href="?page=logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </nav>

    <div class="main-content">
        <div class="main-left">
            <h1>Community Events</h1>

            <?php if (!empty($events)): ?>
                <ul class="events-list">
                    <?php foreach ($events as $event): ?>
                        <li class="event-item">
                            <h2><?= htmlspecialchars($event['name']); ?></h2>
                            <p><?= htmlspecialchars($event['description']); ?></p>
                            <p>Date: <?= htmlspecialchars($event['event_date']); ?></p>
                            <form method="POST" action="">
                                <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                                <button type="submit" name="status" value="attending" class="rsvp-button">RSVP</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No events found. Please check back later.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleNav() {
    const navCenter = document.querySelector('.nav-center');
    navCenter.classList.toggle('active');
}
</script>
</body>
</html>
