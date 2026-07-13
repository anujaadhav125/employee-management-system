<?php

session_start();

require_once '../config/config.php';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user'])) {

    header("Location: ../dashboard/index.php");
    exit();

}

// Show error message if available
$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | <?php echo APP_NAME; ?></title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-lg-4 col-md-6">

<div class="card shadow-lg">

<div class="card-header">

<h3>💜 StaffSync</h3>

<p class="mb-0">

Smart Employee Management

</p>

</div>

<div class="card-body">

<h4 class="text-center mb-4">

Sign In

</h4>

<?php if (!empty($error)) { ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form
    action="login_process.php"
    method="POST">

<div class="mb-3">

<label class="form-label">

Email Address

</label>

<input
    type="email"
    name="email"
    class="form-control"
    placeholder="Enter your email"
    required>

</div>

<div class="mb-4">

<label class="form-label">

Password

</label>

<input
    type="password"
    name="password"
    class="form-control"
    placeholder="Enter your password"
    required>

</div>

<button
    type="submit"
    class="btn btn-login w-100">

Sign In

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>