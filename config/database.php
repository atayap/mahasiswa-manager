<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Default untuk InfinityFree
        $this->host = "sql103.infinityfree.com";
        $this->db_name = "if0_40367293_mahasiswa_db";
        $this->username = "if0_40367293";
        $this->password = "4bB41JgldB5WOr";
        
        // Untuk local development
        if ($_SERVER['HTTP_HOST'] == 'localhost' || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $this->host = "localhost";
            $this->db_name = "mahasiswa_db";
            $this->username = "root";
            $this->password = "";
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
            // Jangan tampilkan error detail di production
            if ($_SERVER['HTTP_HOST'] != 'localhost') {
                error_log("Database connection error: " . $exception->getMessage());
                die("Database connection failed. Please try again later.");
            } else {
                die("Connection error: " . $exception->getMessage());
            }
        }
        return $this->conn;
    }
}
?>