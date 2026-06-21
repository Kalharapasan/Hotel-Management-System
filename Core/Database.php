<?php
namespace App\Core;
class Database {
    private static $instance = null;
    private $conn;
    private function __construct() {
        $host = 'localhost'; $user = 'root'; $pass = ''; $dbname = 'hotel_management_db';
        $conn = new \mysqli($host, $user, $pass);
        $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
        $conn->select_db($dbname);
        $this->conn = $conn;
    }
    public static function getInstance() {
        if (self::$instance === null) { self::$instance = new Database(); }
        return self::$instance->conn;
    }
}
