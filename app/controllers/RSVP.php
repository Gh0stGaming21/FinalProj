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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        echo "You need to be logged in to RSVP.";
        exit;
    }

    $userId = $_SESSION['user']['id']; 

    $controller = new EventsController();
    $controller->rsvpToEvent($eventId, $userId);

    echo "You have successfully RSVP'd for the event!";
}
?>
