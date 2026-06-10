<?php

namespace App\Core;

class Database
{

    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'hotel_management_db';

    private function __construct()
    {

        $this->conn = new \mysqli(
            $this->host,
            $this->user,
            $this->pass
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->query(
            "CREATE DATABASE IF NOT EXISTS {$this->dbname}"
        );

        $this->conn->select_db($this->dbname);
    }

    public static function getInstance()
    {

        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}
