<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$today = date('Y-m-d');

$present = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM attendance
     WHERE attendance_date='$today'
     AND status='Present'"
));

$absent = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM attendance
     WHERE attendance_date='$today'
     AND status='Absent'"
));

$leave = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM attendance
     WHERE attendance_date='$today'
     AND status='Leave'"
));

$query = "SELECT
            employees.employee_code,
            employees.full_name,
            attendance.status
          FROM attendance
          INNER JOIN employees
          ON attendance.employee_id = employees.id
          WHERE attendance.attendance_date='$today'
          ORDER BY employees.full_name";

$result = mysqli_query($conn, $query);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Attendance Dashboard</h2>

<a href="mark.php" class="btn btn-primary">
    Mark Attendance
</a>

</div>

<div class="row">

<div class="col-md-4">

<div class="card bg-success text-white shadow">

<div class="card-body text-center">

<h1><?= $present['total']; ?></h1>

<h5>Present</h5>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-danger text-white shadow">

<div class="card-body text-center">

<h1><?= $absent['total']; ?></h1>

<h5>Absent</h5>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-warning shadow">

<div class="card-body text-center">

<h1><?= $leave['total']; ?></h1>

<h5>Leave</h5>

</div>

</div>

</div>

</div>

<br>

<div class="card shadow">

<div class="card-header">

<h4>Today's Attendance</h4>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Employee Code</th>

<th>Employee Name</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td>

<?php

$class = 'bg-secondary';

if($row['status'] == 'Present'){
    $class = 'bg-success';
}elseif($row['status'] == 'Absent'){
    $class = 'bg-danger';
}elseif($row['status'] == 'Leave'){
    $class = 'bg-warning text-dark';
}

?>

<span class="badge <?= $class; ?>">

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