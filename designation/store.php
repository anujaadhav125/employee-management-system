<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('create.php');
}

$department_id = (int)$_POST['department_id'];
$designation_name = sanitize($_POST['designation_name']);

// Duplicate Check
$query = "SELECT id
          FROM designations
          WHERE designation_name = ?
          AND department_id = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $designation_name,
    $department_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)>0){

    $_SESSION['error']="Designation already exists.";

    redirect("create.php");

}

// Insert
$query = "INSERT INTO designations
(department_id,designation_name)
VALUES(?,?)";

$stmt = mysqli_prepare($conn,$query);

mysqli_stmt_bind_param(
$stmt,
"is",
$department_id,
$designation_name
);

if(mysqli_stmt_execute($stmt)){

    $_SESSION['success']="Designation Added Successfully.";

}else{

    $_SESSION['error']="Unable to add designation.";

}

redirect("index.php");