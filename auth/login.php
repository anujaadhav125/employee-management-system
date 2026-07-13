<?php

session_start();

require '../config/database.php';

$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {

        $error = "Please enter both email and password.";

    } else {

        // Prepare SQL query
        $query = "SELECT * FROM admins WHERE email = ?";

        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Check if email exists
        if (mysqli_num_rows($result) == 1) {

            $admin = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $admin['password'])) {

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_email'] = $admin['email'];

                header("Location: ../dashboard/dashboard.php");
                exit();

            } else {

                $error = "Invalid Password.";

            }

        } else {

            $error = "Email not found.";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EMS Admin Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-lg-4 col-md-6">

<div class="card shadow-lg">

<div class="card-header">

<h3>EMS Admin Portal</h3>

<p class="mb-0">Employee Management System</p>

</div>

<div class="card-body">

<h4 class="text-center mb-4">

Admin Login

</h4>

<?php if(!empty($error)){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Email Address

</label>

<input

type="email"

name="email"

class="form-control"

placeholder="Enter Email"

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

placeholder="Enter Password"

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