<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$date = $_GET['date'] ?? '';
$employee = $_GET['employee'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT
attendance.*,
employees.employee_code,
employees.full_name
FROM attendance
INNER JOIN employees
ON attendance.employee_id = employees.id
WHERE 1=1";

if($date != ""){
    $query .= " AND attendance.attendance_date='$date'";
}

if($employee != ""){
    $employee = mysqli_real_escape_string($conn,$employee);
    $query .= " AND attendance.employee_id='$employee'";
}

if($status != ""){
    $status = mysqli_real_escape_string($conn,$status);
    $query .= " AND attendance.status='$status'";
}

$query .= " ORDER BY attendance.attendance_date DESC";

$result = mysqli_query($conn,$query);

$employees = mysqli_query($conn,"
SELECT id,full_name
FROM employees
ORDER BY full_name");
?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header d-flex justify-content-between align-items-center">

<h4>Attendance Report</h4>

<div>

<a href="attendance_excel.php" class="btn btn-success">
Export Excel
</a>

<a href="attendance_pdf.php" class="btn btn-danger">
Export PDF
</a>

</div>

</div>

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-3">

<input
type="date"
name="date"
class="form-control"
value="<?= htmlspecialchars($date); ?>">

</div>

<div class="col-md-3">

<select
name="employee"
class="form-select">

<option value="">All Employees</option>

<?php while($emp=mysqli_fetch_assoc($employees)){ ?>

<option
value="<?= $emp['id']; ?>"
<?= ($employee==$emp['id'])?'selected':'';?>>

<?= htmlspecialchars($emp['full_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-3">

<select
name="status"
class="form-select">

<option value="">All Status</option>

<option value="Present"
<?=($status=="Present")?"selected":"";?>>

Present

</option>

<option value="Absent"
<?=($status=="Absent")?"selected":"";?>>

Absent

</option>

<option value="Leave"
<?=($status=="Leave")?"selected":"";?>>

Leave

</option>

</select>

</div>

<div class="col-md-3">

<button class="btn btn-primary">

Filter

</button>

<a href="attendance_report.php"
class="btn btn-secondary">

Reset

</a>

</div>

</div>

</form>

<br>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Employee Code</th>

<th>Employee Name</th>

<th>Date</th>

<th>Status</th>

<th>Remarks</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= date('d-m-Y',strtotime($row['attendance_date'])); ?></td>

<td>

<?php

$badge='bg-secondary';

if($row['status']=='Present'){

$badge='bg-success';

}elseif($row['status']=='Absent'){

$badge='bg-danger';

}elseif($row['status']=='Leave'){

$badge='bg-warning text-dark';

}

?>

<span class="badge <?= $badge; ?>">

<?= htmlspecialchars($row['status']); ?>

</span>

</td>

<td><?= htmlspecialchars($row['remarks']); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>