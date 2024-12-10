<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/assets/dashStyle.css">
    <title>Dashboard</title>
</head>

<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h1>
    </header>

    <nav>
        <ul>
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=help_requests">Help Request Board</a></li>
            <li><a href="?page=resource_sharing">Resource Sharing</a></li>
            <li><a href="?page=events">Events Calendar</a></li>
            <li><a href="?page=profile">Profile</a></li>
            <li><a href="?page=auth&action=logout">Logout</a></li>
        </ul>
    </nav>

    <main>
        <section>
            <h2>Quick Stats</h2>
            <p><strong>Total Registered Users:</strong> <?= htmlspecialchars($totalUsers) ?></p>
        </section>

        <section>
            <h3>Recent Activities</h3>
            <ul>
                <?php foreach ($recentActivities as $activity): ?>
                    <li>
                        <?= htmlspecialchars($activity['date']) ?> - <?= htmlspecialchars($activity['activity']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>
