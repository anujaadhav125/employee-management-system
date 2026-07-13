<?php

// Database Configuration

$host = "localhost";
$username = "root";
$password = "";
$database = "employee_management";

// Create Connection

$conn = mysqli_connect($host, $username, $password, $database);

// Check Connection

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

?>