<?php

require_once 'Database.php';

use App\Core\Database;

// Get Database Connection
$conn = Database::getInstance();

// Create Tables
$tables = [
    "admins" => "CREATE TABLE IF NOT EXISTS admins (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
];

// Create Tables
foreach ($tables as $name => $sql) {
    if (!$conn->query($sql)) {
        die("Error creating table {$name}: " . $conn->error);
    }
}