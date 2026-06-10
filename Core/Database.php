<?php

namespace App\Core;

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $dbname = $_ENV['DB_NAME'];

        $this->conn = new \mysqli(
            $host,
            $user,
            $pass
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->query(
            "CREATE DATABASE IF NOT EXISTS `$dbname`"
        );

        $this->conn->select_db($dbname);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}