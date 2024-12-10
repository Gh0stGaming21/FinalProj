<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'bayanihub';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function connect() {
        if ($this->conn) return $this->conn;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
            exit();
        }
        
        return $this->conn;
    }
}
?>
