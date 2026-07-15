<?php

require_once '../config/app.php';

if(!isLoggedIn()){
    redirect('../auth/login.php');
}

$id=(int)$_POST['id'];

$employee=$_POST['employee_id'];
$date=$_POST['attendance_date'];
$status=$_POST['status'];
$remarks=trim($_POST['remarks']);

$stmt=mysqli_prepare($conn,"
UPDATE attendance
SET employee_id=?,
attendance_date=?,
status=?,
remarks=?
WHERE id=?");

mysqli_stmt_bind_param(
$stmt,
"isssi",
$employee,
$date,
$status,
$remarks,
$id
);

mysqli_stmt_execute($stmt);

$_SESSION['success']="Attendance updated successfully.";

redirect("index.php");