<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$employees = mysqli_query($conn,"
SELECT id, employee_code, full_name
FROM employees
WHERE status='Active'
ORDER BY full_name ASC
");

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">Mark Attendance</h4>

</div>

<div class="card-body">

<form action="store.php" method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">Employee</label>

<select
name="employee_id"
class="form-select"
required>

<option value="">Select Employee</option>

<?php while($emp=mysqli_fetch_assoc($employees)){ ?>

<option value="<?= $emp['id']; ?>">

<?= htmlspecialchars($emp['employee_code']); ?>
-
<?= htmlspecialchars($emp['full_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Date</label>

<input
type="date"
name="attendance_date"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Status</label>

<select
name="status"
class="form-select"
required>

<option value="Present">Present</option>

<option value="Absent">Absent</option>

<option value="Leave">Leave</option>

</select>

</div>

<div class="col-md-12 mb-3">

<label class="form-label">Remarks</label>

<textarea
name="remarks"
rows="3"
class="form-control"
placeholder="Optional"></textarea>

</div>

</div>

<button class="btn btn-success">

<i class="bi bi-check-circle"></i>

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