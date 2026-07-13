<?php
require '../config/database.php';

// Admin Details
$fullName = "Administrator";
$email = "admin@gmail.com";
$password = "admin123";

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$checkQuery = "SELECT id FROM admins WHERE email = ?";
$stmt = mysqli_prepare($conn, $checkQuery);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "Admin already exists!";
} else {
    $insertQuery = "INSERT INTO admins (full_name, email, password)
                    VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        echo "Admin created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>