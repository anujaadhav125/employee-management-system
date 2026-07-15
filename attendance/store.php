<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('create.php');
}

$employee_id = (int)$_POST['employee_id'];
$attendance_date = $_POST['attendance_date'];
$status = $_POST['status'];
$remarks = trim($_POST['remarks']);

// Check duplicate attendance

$check = mysqli_prepare(
    $conn,
    "SELECT id FROM attendance
     WHERE employee_id=?
     AND attendance_date=?"
);

mysqli_stmt_bind_param(
    $check,
    "is",
    $employee_id,
    $attendance_date
);

mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if(mysqli_num_rows($result)>0){

    $_SESSION['error']="Attendance already marked for this employee.";

    redirect('create.php');

}

// Insert attendance

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO attendance
    (employee_id,attendance_date,status,remarks)
    VALUES(?,?,?,?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "isss",
    $employee_id,
    $attendance_date,
    $status,
    $remarks
);

if(mysqli_stmt_execute($stmt)){

    $_SESSION['success']="Attendance marked successfully.";

}else{

    $_SESSION['error']="Something went wrong.";

}

redirect('index.php');