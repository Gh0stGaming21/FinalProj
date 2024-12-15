<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit(); 
}

// Fetch events (assuming you have a controller or method to do this)
$events = []; // Replace with actual fetching logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./public/assets/eventsStyle.css"> <!-- Link to your unique CSS file -->
    <title>Upcoming Events</title>
</head>
<body>
<div class="container">
    <nav>
        <div class="nav-left">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']); ?></h1>
        </div>
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
            <h2>Upcoming Events</h2>
            <?php if (!empty($events)): ?>
                <ul class="events-list">
                    <?php foreach ($events as $event): ?>
                        <li class="event-item">
                            <strong>
                                <?php echo isset($event['name']) ? htmlspecialchars($event['name']) : 'No Event name'; ?>
                            </strong><br>
                            Location: <?php echo isset($event['location']) ? htmlspecialchars($event['location']) : 'No Location'; ?><br>
                            
                            <?php 
                                if (isset($event['event_date'])) {
                                    $date = new DateTime($event['event_date']);
                                    echo "Date: " . $date->format('F j, Y');
                                } else {
                                    echo 'No Date';
                                }
                            ?><br>
                            
                            <form action="?page=events&action=rsvp" method="post">
                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user']['id']); ?>">
                                <button type="submit" class="rsvp-button">RSVP</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No events available.</p>
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