<?php
require_once __DIR__ . '/../../config/Database.php';
require_once 'path/to/EventsController.php';

if (!isset($_GET['event_id'])) {
    echo "Event ID is required.";
    exit;
}

$eventId = $_GET['event_id'];
?>

<h1>RSVP to Event</h1>
<p>Are you sure you want to RSVP for this event?</p>
<form method="POST">
    <button type="submit">Confirm RSVP</button>
</form>

<?php
// Handle RSVP submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        echo "You need to be logged in to RSVP.";
        exit;
    }

    $userId = $_SESSION['user']['id']; // Assuming the user ID is stored in session

    // Call the RSVP function
    $controller = new EventsController();
    $controller->rsvpToEvent($eventId, $userId);

    echo "You have successfully RSVP'd for the event!";
}
?>
