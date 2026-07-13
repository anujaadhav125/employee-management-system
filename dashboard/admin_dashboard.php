<?php

session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){

    header("Location: ../auth/login.php");

    exit();

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-body">

<h2>

Welcome,

<?php echo $_SESSION['admin_name']; ?>

🎉

</h2>

<hr>

<p>

Email :

<strong>

<?php echo $_SESSION['admin_email']; ?>

</strong>

</p>

<a href="../auth/logout.php"

class="btn btn-danger">

Logout

</a>

</div>

</div>

</div>

</body>

</html>