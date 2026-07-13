<?php

require_once 'config.php';

// Database Configuration

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'staffsync_db');

// Database Connection

$conn = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME
);

if (!$conn) {

    die("Database Connection Failed : " . mysqli_connect_error());

}

?>