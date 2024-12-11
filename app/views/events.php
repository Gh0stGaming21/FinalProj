<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['error'])): ?>
    <p class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
    <?php unset($_SESSION['error']);  ?>
<?php endif; ?>

<h1>Community Events</h1>
<form method="GET" action="">
    <input type="text" name="location" placeholder="Filter by location">
    <input type="date" name="date" placeholder="Filter by date">
    <button type="submit">Filter</button>
    <a href="?page=dashboard">Back to Dashboard</a>
</form>

<?php if (!empty($events)): ?>
    <ul>
        <?php foreach ($events as $event): ?>
            <li>
                <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                <p><?php echo htmlspecialchars($event['description']); ?></p>
                <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                <form method="POST" action="?page=rsvp">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    <button type="submit">RSVP</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No events found. Please adjust your filters or check back later.</p>
<?php endif; ?>

