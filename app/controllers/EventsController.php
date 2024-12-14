<?php
class EventsController {

private $pdo;

public function __construct($pdo) {
    $this->pdo = $pdo;
}

public function getEvents($filters = []) {
    $sql = "SELECT * FROM events WHERE 1";

    if (!empty($filters['location'])) {
        $sql .= " AND location = :location";
    }
    if (!empty($filters['date'])) {
        $sql .= " AND event_date = :date";
    }

    $stmt = $this->pdo->prepare($sql);

    if (!empty($filters['location'])) {
        $stmt->bindParam(':location', $filters['location']);
    }
    if (!empty($filters['date'])) {
        $stmt->bindParam(':date', $filters['date']);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $event_name = isset($_POST['event_name']) ? htmlspecialchars($_POST['event_name']) : '';
        $location = isset($_POST['location']) ? htmlspecialchars($_POST['location']) : '';
        $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : '';

        if (empty($event_name) || empty($location) || empty($event_date)) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: ?page=events&action=create'); 
            exit();
        }
        $query = "INSERT INTO events (event_name, location, event_date) VALUES (:event_name, :location, :event_date)";
        
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':event_date', $event_date);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Event created successfully!';
        } else {
            $_SESSION['error'] = 'Failed to create event. Please try again.';
        }

        header('Location: ?page=events');
        exit();
    }

    require_once 'app/views/create_event.php';
}

public function rsvpToEvent($eventId, $userId) {
    $stmt = $this->pdo->prepare("SELECT * FROM event_rsvps WHERE event_id = :event_id AND user_id = :user_id");
    $stmt->bindParam(':event_id', $eventId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "You have already RSVP'd to this event.";
        return;
    }
    $stmt = $this->pdo->prepare("INSERT INTO event_rsvps (event_id, user_id) VALUES (:event_id, :user_id)");
    $stmt->bindParam(':event_id', $eventId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    echo "RSVP successful!";
}
}
?>