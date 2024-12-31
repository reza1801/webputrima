<?php
// File: connection.php

// Database connection settings
$host = 'localhost:3307';
$username = 'root';
$password = ''; // Adjust as necessary
$database = 'dataputrima';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
