<?php
class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getEvents($filters = []) {
        $query = "SELECT * FROM events WHERE 1";
        $params = [];

        if (!empty($filters['location'])) {
            $query .= " AND location = :location";
            $params[':location'] = $filters['location'];
        }
        if (!empty($filters['date'])) {
            $query .= " AND event_date >= :date";
            $params[':date'] = $filters['date'];
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rsvp($eventId, $userId) {
        $query = "INSERT INTO event_rsvps (event_id, user_id) VALUES (:event_id, :user_id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':event_id' => $eventId, ':user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
?>
