<?php

require_once '../config/app.php';

if(!isLoggedIn()){
    redirect('../auth/login.php');
}

$id=(int)$_GET['id'];

$stmt=mysqli_prepare($conn,
"DELETE FROM attendance WHERE id=?");

mysqli_stmt_bind_param($stmt,"i",$id);

mysqli_stmt_execute($stmt);

$_SESSION['success']="Attendance deleted successfully.";

redirect("index.php");