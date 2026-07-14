<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('create.php');
}

$department_name = sanitize($_POST['department_name']);
$description = sanitize($_POST['description']);

// Check Duplicate
$query = "SELECT id FROM departments WHERE department_name = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $department_name);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)>0){

    $_SESSION['error']="Department already exists.";

    redirect("create.php");

}

// Insert
$query = "INSERT INTO departments
(department_name,description)
VALUES(?,?)";

$stmt = mysqli_prepare($conn,$query);

mysqli_stmt_bind_param(
$stmt,
"ss",
$department_name,
$description
);

if(mysqli_stmt_execute($stmt)){

$_SESSION['success']="Department Added Successfully.";

}else{

$_SESSION['error']="Unable to add department.";

}

redirect("index.php");