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

        if (!empty($filters['location'])) {
            $query .= " AND location LIKE :location";
        }

        if (!empty($filters['date'])) {
            $query .= " AND event_date = :date";
        }

        $stmt = $this->pdo->prepare($query);

        if (!empty($filters['location'])) {
            $location = "%" . $filters['location'] . "%";
            $stmt->bindParam(':location', $location);
        }

        if (!empty($filters['date'])) {
            $stmt->bindParam(':date', $filters['date']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rsvpToEvent($eventId, $userId, $status = 'attending') {
        try {
            $query = "INSERT INTO events_rsvps (event_id, user_id, status) VALUES (:event_id, :user_id, :status)
                      ON DUPLICATE KEY UPDATE status = :status";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':event_id', $eventId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in RSVP: " . $e->getMessage());
        }
    }
}
?>
