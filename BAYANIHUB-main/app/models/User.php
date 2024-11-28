<?php
namespace App\Models;

use Core\DB;

class User {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance(); 
    }

    public function findUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]); 
        return $stmt->fetch(); 
    }

    public function createUser($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
        ]);
    }
}
?>

