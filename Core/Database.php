<?php
namespace App\Core;
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'hotel_management_db';
        
        // Create connection
        $conn = new \mysqli($host, $user, $pass);
        
        // Check connection
        if ($conn->connect_error) {
            throw new \Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Create database
        if (!$conn->query("CREATE DATABASE IF NOT EXISTS $dbname")) {
            throw new \Exception("Error creating database: " . $conn->error);
        }
        
        $conn->select_db($dbname);
        $conn->set_charset("utf8");
        
        $this->conn = $conn;
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
