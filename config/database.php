<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Default development settings
        $this->host = "localhost";
        $this->db_name = "mahasiswa_db";
        $this->username = "root";
        $this->password = "";

        // For production (Render.com) - use environment variables
        if (getenv('RENDER')) {
            $this->host = getenv('DB_HOST');
            $this->db_name = getenv('DB_NAME');
            $this->username = getenv('DB_USER');
            $this->password = getenv('DB_PASSWORD');
        }
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Don't show detailed errors in production
            if (getenv('RENDER')) {
                error_log("Database connection error: " . $exception->getMessage());
                die("Database connection failed. Please check your configuration.");
            } else {
                die("Connection error: " . $exception->getMessage());
            }
        }
        return $this->conn;
    }
}
?>