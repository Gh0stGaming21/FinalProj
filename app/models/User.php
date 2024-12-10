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

    public function authenticateUser($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return 'inactive'; 
            }
            return $user; 
        }
        return false; 
    }

    public function create($name, $email, $password, $role = 'member', $status = 'active') {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, status) VALUES (:name, :email, :password, :role, :status)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':status', $status);
            return $stmt->execute(); 
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    


    public function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, email, role, status FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
