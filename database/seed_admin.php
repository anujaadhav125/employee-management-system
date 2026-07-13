<?php

require_once '../config/database.php';

$fullName = "Administrator";
$email = "admin@staffsync.com";
$password = "Admin@123";
$role = "admin";

// Hash Password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check Existing User
$query = "SELECT id FROM users WHERE email = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    echo "Admin already exists.";

} else {

    $query = "INSERT INTO users
    (full_name,email,password,role)
    VALUES (?,?,?,?)";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $fullName,
        $email,
        $hashedPassword,
        $role
    );

    if (mysqli_stmt_execute($stmt)) {

        echo "Admin created successfully.";

    } else {

        echo mysqli_error($conn);

    }

}