<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$id = (int)$_GET['id'];

$query = "SELECT * FROM attendance WHERE id=?";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$attendance = mysqli_fetch_assoc($result);

$employees = mysqli_query($conn,"
SELECT id,employee_code,full_name
FROM employees
WHERE status='Active'
ORDER BY full_name");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header bg-warning">

<h4>Edit Attendance</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $attendance['id']; ?>">

<div class="row">

<div class="col-md-4 mb-3">

<label>Employee</label>

<select name="employee_id" class="form-select">

<?php while($emp=mysqli_fetch_assoc($employees)){ ?>

<option value="<?= $emp['id']; ?>"
<?= ($attendance['employee_id']==$emp['id'])?'selected':''; ?>>

<?= $emp['employee_code']; ?> -
<?= $emp['full_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Date</label>

<input
type="date"
name="attendance_date"
class="form-control"
value="<?= $attendance['attendance_date']; ?>">

</div>

<div class="col-md-4 mb-3">

<label>Status</label>

<select
name="status"
class="form-select">

<option <?=($attendance['status']=="Present")?"selected":"";?>>
Present
</option>

<option <?=($attendance['status']=="Absent")?"selected":"";?>>
Absent
</option>

<option <?=($attendance['status']=="Leave")?"selected":"";?>>
Leave
</option>

</select>

</div>

<div class="col-md-12">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"><?= htmlspecialchars($attendance['remarks']); ?></textarea>

</div>

</div>

<br>

<button class="btn btn-success">

Update Attendance

</button>

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>