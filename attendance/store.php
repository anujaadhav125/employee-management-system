<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if($_SERVER['REQUEST_METHOD']!='POST'){
    redirect('mark.php');
}

$date = $_POST['attendance_date'];

foreach($_POST['employee_id'] as $empId){

    $status = $_POST['status'][$empId];

    // Prevent duplicate attendance
    $check = mysqli_prepare(
        $conn,
        "SELECT id
         FROM attendance
         WHERE employee_id=?
         AND attendance_date=?"
    );

    mysqli_stmt_bind_param($check,"is",$empId,$date);

    mysqli_stmt_execute($check);

    $exists = mysqli_stmt_get_result($check);

    if(mysqli_num_rows($exists)>0){

        continue;

    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO attendance
        (employee_id,attendance_date,status)
        VALUES(?,?,?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $empId,
        $date,
        $status
    );

    mysqli_stmt_execute($stmt);

}

$_SESSION['success']="Attendance Saved Successfully.";

redirect("index.php");