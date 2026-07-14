<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$id = (int)$_GET['id'];

// Check employee exists
$query = "SELECT id FROM employees WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    $_SESSION['error'] = "Employee not found.";
    redirect("index.php");
}

// Soft Delete
$query = "UPDATE employees SET status='Inactive' WHERE id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    $_SESSION['success'] = "Employee marked as Inactive.";

} else {

    $_SESSION['error'] = "Failed to delete employee.";

}

redirect("index.php");