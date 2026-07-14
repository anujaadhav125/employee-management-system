<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$query = "SELECT id, employee_code, full_name
          FROM employees
          WHERE status='Active'
          ORDER BY full_name ASC";

$result = mysqli_query($conn, $query);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Mark Attendance</h4>

</div>

<div class="card-body">

<form action="store.php" method="POST">

<div class="mb-3">

<label class="form-label">Attendance Date</label>

<input
type="date"
name="attendance_date"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Employee Code</th>

<th>Employee Name</th>

<th width="350">Attendance</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td>

<input type="hidden"
name="employee_id[]"
value="<?= $row['id']; ?>">

<div class="d-flex gap-4">

<label>

<input
type="radio"
name="status[<?= $row['id']; ?>]"
value="Present"
checked>

Present

</label>

<label>

<input
type="radio"
name="status[<?= $row['id']; ?>]"
value="Absent">

Absent

</label>

<label>

<input
type="radio"
name="status[<?= $row['id']; ?>]"
value="Leave">

Leave

</label>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<button class="btn btn-success">

Save Attendance

</button>

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>