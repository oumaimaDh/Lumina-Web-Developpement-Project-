<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "lumina";  // ✅ YOUR DATABASE NAME
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Set error mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Set UTF-8 encoding
            $this->conn->exec("SET NAMES utf8");
            
        } catch(PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
        
        return $this->conn;
    }
}
?>