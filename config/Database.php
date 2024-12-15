<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'bayanihub';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
    }

    public function connect() {
        if ($this->conn) {
            return $this->conn;
        }

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage(), 3, __DIR__ . '/logs/database.log');
            die("An error occurred while connecting to the database.");
        }

        return $this->conn;
    }

    public function validateAdminAccess() {
        session_start();
        if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || !isset($_SESSION['user']['role']) || !in_array($_SESSION['user']['role'], ['admin', 'member'])) {
            header("HTTP/1.1 403 Forbidden");
            echo "Unauthorized access.";
            exit;
        }
    }
}

?>