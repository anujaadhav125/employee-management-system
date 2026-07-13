<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/app.php';

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('login.php');

}

// Get form data
$email = sanitize($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate input
if (empty($email) || empty($password)) {

    $_SESSION['error'] = "Please enter both email and password.";

    redirect('login.php');

}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] = "Please enter a valid email address.";

    redirect('login.php');

}

// Fetch user by email
$query = "SELECT * FROM users WHERE email = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {

    die("Database Error: " . mysqli_error($conn));

}

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {

    $_SESSION['error'] = "Invalid email or password.";

    redirect('login.php');

}

$user = mysqli_fetch_assoc($result);

// Check account status
if ($user['status'] !== 'active') {

    $_SESSION['error'] = "Your account is inactive. Contact Administrator.";

    redirect('login.php');

}

// Verify password
if (!password_verify($password, $user['password'])) {

    $_SESSION['error'] = "Invalid email or password.";

    redirect('login.php');

}

// Create session
$_SESSION['user'] = [

    'id' => $user['id'],
    'name' => $user['full_name'],
    'email' => $user['email'],
    'role' => $user['role']

];

// Redirect based on role
if ($user['role'] === 'admin') {

    redirect('../dashboard/index.php');

}

if ($user['role'] === 'employee') {

    redirect('../dashboard/index.php');

}

// Fallback
session_destroy();

$_SESSION['error'] = "Unauthorized access.";

redirect('login.php');

?>