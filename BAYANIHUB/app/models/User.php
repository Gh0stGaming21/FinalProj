<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password) {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            return $stmt->execute(); 
        } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
