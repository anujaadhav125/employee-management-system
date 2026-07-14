<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

// Dashboard Counts

$totalEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees")
)['total'];

$totalDepartments = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM departments")
)['total'];

$activeEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees WHERE status='Active'")
)['total'];

$inactiveEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees WHERE status='Inactive'")
)['total'];

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="row g-4">

<div class="col-md-3">

<div class="card stat-card text-center">

<div class="card-body">

<h1><?= $totalEmployees; ?></h1>

<p>Total Employees</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card stat-card text-center">

<div class="card-body">

<h1><?= $totalDepartments; ?></h1>

<p>Departments</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card stat-card text-center">

<div class="card-body">

<h1><?= $activeEmployees; ?></h1>

<p>Active Employees</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card stat-card text-center">

<div class="card-body">

<h1><?= $inactiveEmployees; ?></h1>

<p>Inactive Employees</p>

</div>

</div>

</div>

</div>

<br>

<div class="card shadow">

<div class="card-header">

<h4>Recent Employees</h4>

</div>

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Employee Code</th>

<th>Name</th>

<th>Department</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$query = "SELECT * FROM employees ORDER BY id DESC LIMIT 5";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

<td>

<span class="badge <?= ($row['status']=='Active') ? 'bg-success' : 'bg-secondary'; ?>">

<?= htmlspecialchars($row['status']); ?>

</span>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>