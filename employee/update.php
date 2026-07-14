<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('index.php');
}

$id = (int)$_POST['id'];

$full_name    = sanitize($_POST['full_name']);
$email        = sanitize($_POST['email']);
$phone        = sanitize($_POST['phone']);
$gender       = sanitize($_POST['gender']);
$department   = sanitize($_POST['department']);
$designation  = sanitize($_POST['designation']);
$salary       = $_POST['salary'];
$joining_date = $_POST['joining_date'];
$status       = $_POST['status'];

$imageName = $_POST['old_image'];

// Upload new image
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {

    $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png'];

    if (in_array($extension, $allowed)) {

        $imageName = time() . "." . $extension;

        move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            "../assets/uploads/employees/" . $imageName
        );

        if ($_POST['old_image'] != "default.png" &&
            file_exists("../assets/uploads/employees/" . $_POST['old_image'])) {

            unlink("../assets/uploads/employees/" . $_POST['old_image']);
        }
    }
}

// Check duplicate email
$query = "SELECT id FROM employees WHERE email = ? AND id != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $email, $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = "Email already exists.";
    redirect("edit.php?id=$id");
}

// Update Employee
$query = "UPDATE employees SET
full_name=?,
email=?,
phone=?,
gender=?,
department=?,
designation=?,
salary=?,
joining_date=?,
profile_image=?,
status=?
WHERE id=?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "sssssdssssi",
    $full_name,
    $email,
    $phone,
    $gender,
    $department,
    $designation,
    $salary,
    $joining_date,
    $imageName,
    $status,
    $id
);

if (mysqli_stmt_execute($stmt)) {

    $_SESSION['success'] = "Employee Updated Successfully.";

} else {

    $_SESSION['error'] = "Failed to update employee.";

}

redirect("index.php");