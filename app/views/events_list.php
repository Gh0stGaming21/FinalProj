<h2>Upcoming Events</h2>
<?php if (!empty($events)): ?>
    <ul>
        <?php foreach ($events as $event): ?>
            <li>
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
                    <button type="submit">RSVP</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No events available.</p>
<?php endif; ?>

<a href="?page=dashboard">Back to Dashboard</a> 
