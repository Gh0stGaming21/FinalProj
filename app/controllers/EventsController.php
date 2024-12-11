<?php
require_once __DIR__ . '/../../config/Database.php';

class EventsController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function getEvents($filters = []) {
        $query = "SELECT * FROM events WHERE 1";

        if (isset($filters['location'])) {
            $query .= " AND location = :location";
        }

        if (isset($filters['date'])) {
            $query .= " AND event_date >= :date";
        }

        $stmt = $this->pdo->prepare($query);
        
        if (isset($filters['location'])) {
            $stmt->bindParam(':location', $filters['location']);
        }
        if (isset($filters['date'])) {
            $stmt->bindParam(':date', $filters['date']);
        }

        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC); 
       
        include __DIR__ . '/../views/events.php';  
    }

    public function rsvpToEvent($eventId, $userId) {
        $query = "INSERT INTO event_rsvps (event_id, user_id) VALUES (:event_id, :user_id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':event_id', $eventId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }
}
?>
