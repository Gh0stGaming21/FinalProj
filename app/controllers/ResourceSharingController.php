<?php
class ResourceSharingController {
  
    public function getResources($filters = []) {

        
        $database = new Database();
        $pdo = $database->connect();
        
        $sql = "SELECT * FROM resources"; 
        
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
           
            if (isset($filters['location'])) {
                $sql .= " AND location LIKE :location";
            }
            if (isset($filters['date'])) {
                $sql .= " AND date = :date";
            }
        }
        $stmt = $pdo->prepare($sql);

        if (isset($filters['location'])) {
            $stmt->bindValue(':location', '%' . $filters['location'] . '%');
        }
        if (isset($filters['date'])) {
            $stmt->bindValue(':date', $filters['date']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
?>