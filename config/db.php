<?php

$user = "root";
$pass = "";
$host = "localhost";
$database = "hms_db";

$conn = new mysqli($host, $user, $pass, $database);

if ($conn->connect_error) {
    die("Could not connect to MySQL: " . $conn->connect_error);
}


?>