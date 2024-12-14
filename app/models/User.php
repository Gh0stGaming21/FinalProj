<?php
class User {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Method to check if an email already exists
    public function emailExists($email)
    {
        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Returns true if email exists
    }

    // Method to fetch the total number of users
    public function getTotalUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS total_users FROM users");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_users'];
        } catch (PDOException $e) {
            error_log("Error fetching total users: " . $e->getMessage());
            return 0;
        }
    }

    // Method to authenticate a user based on email and password
    public function authenticateUser ($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Method to register a new user
    public function register($name, $email, $password)
    {
        try {
            // Check if email already exists
            if ($this->emailExists($email)) {
                return false; // Email already in use
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            
            $stmt->execute();

            return true; // Registration successful
        } catch (PDOException $e) {
            error_log("Error during registration: " . $e->getMessage());
            return false; // Registration failed
        }
    }
}
?>