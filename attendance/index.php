<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$query = "SELECT
attendance.*,
employees.employee_code,
employees.full_name
FROM attendance
JOIN employees
ON attendance.employee_id = employees.id
ORDER BY attendance.attendance_date DESC,
employees.full_name ASC";

$result = mysqli_query($conn, $query);

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header d-flex justify-content-between align-items-center">

<h4 class="mb-0">Attendance Management</h4>

<a href="create.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Mark Attendance

</a>

</div>

<div class="card-body">

<?php if(isset($_SESSION['success'])){ ?>

<div class="alert alert-success">

<?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); } ?>

<?php if(isset($_SESSION['error'])){ ?>

<div class="alert alert-danger">

<?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); } ?>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Employee Code</th>

<th>Employee Name</th>

<th>Date</th>

<th>Status</th>

<th>Remarks</th>

<th width="160">Action</th>

</tr>

</thead>

<tbody>

<?php

$i = 1;

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td><?= $i++; ?></td>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= date('d-m-Y', strtotime($row['attendance_date'])); ?></td>

<td>

<?php

if($row['status']=="Present"){

echo '<span class="badge bg-success">Present</span>';

}elseif($row['status']=="Absent"){

echo '<span class="badge bg-danger">Absent</span>';

}else{

echo '<span class="badge bg-warning text-dark">Leave</span>';

}

?>

</td>

<td><?= htmlspecialchars($row['remarks']); ?></td>

<td>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">

Edit

</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this attendance record?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>