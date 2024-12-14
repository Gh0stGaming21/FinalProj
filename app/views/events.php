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
$filters = []; // Replace with actual filter logic
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
    <title>Community Events</title>
</head>
<body>
<div class="container">
    <nav>
        <div class="nav-left">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']); ?></h1>
        </div>

        <!-- Toggle Button for Mobile View -->
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
        <!-- Left Section: Community Events -->
        <div class="main-left">
            <h1>Community Events</h1>
            <form method="GET" action="?page=events" class="filter-form">
                <input type="text" name="location" placeholder="Filter by location" value="<?php echo htmlspecialchars($filters['location'] ?? ''); ?>">
                <input type="date" name="date" placeholder="Filter by date" value="<?php echo htmlspecialchars($filters['date'] ?? ''); ?>">
                <button type="submit">Filter</button>
            </form>

            <?php if (!empty($events)): ?>
                <ul class="events-list">
                    <?php foreach ($events as $event): ?>
                        <li class="event-item">
                            <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                            <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                            <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                            <form method="POST" action="?page=rsvp">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="rsvp-button">RSVP</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No events found. Please adjust your filters or check back later.</p>
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