<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('create.php');
}

// Collect Data
$full_name    = sanitize($_POST['full_name']);
$email        = sanitize($_POST['email']);
$phone        = sanitize($_POST['phone']);
$gender       = sanitize($_POST['gender']);
$department   = sanitize($_POST['department']);
$designation  = sanitize($_POST['designation']);
$salary       = $_POST['salary'];
$joining_date = $_POST['joining_date'];

// Check Email
$check = mysqli_query($conn, "SELECT id FROM employees WHERE email='$email'");

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Email already exists.";

    redirect('create.php');
}

// Default Image
$imageName = "default.png";

// Upload Image
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {

    $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png'];

    if (in_array($extension, $allowed)) {

        $imageName = time() . "." . $extension;

        move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            "../assets/uploads/employees/" . $imageName
        );
    }
}

// Generate Employee Code
$count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM employees")) + 1;

$employee_code = "EMP-" . date('Y') . "-" . str_pad($count,3,"0",STR_PAD_LEFT);

// Insert Employee
$query = "INSERT INTO employees
(employee_code,full_name,email,phone,gender,department,designation,salary,joining_date,profile_image)
VALUES
(?,?,?,?,?,?,?,?,?,?)";

$stmt = mysqli_prepare($conn,$query);

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssss",
    $employee_code,
    $full_name,
    $email,
    $phone,
    $gender,
    $department,
    $designation,
    $salary,
    $joining_date,
    $imageName
);

if(mysqli_stmt_execute($stmt)){

    $_SESSION['success']="Employee Added Successfully.";

}else{

    $_SESSION['error']="Something went wrong.";

}

redirect('index.php');