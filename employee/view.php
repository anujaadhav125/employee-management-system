<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$id = (int)$_GET['id'];

$query = "SELECT * FROM employees WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    redirect('index.php');
}

$employee = mysqli_fetch_assoc($result);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header bg-info text-white">

<h3>Employee Profile</h3>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 text-center">

<img
src="../assets/uploads/employees/<?= $employee['profile_image']; ?>"
class="img-fluid rounded-circle border"
style="width:180px;height:180px;object-fit:cover;">

<h4 class="mt-3">
<?= htmlspecialchars($employee['full_name']); ?>
</h4>

<p class="text-muted">
<?= htmlspecialchars($employee['designation']); ?>
</p>

<?php if($employee['status']=="Active"){ ?>

<span class="badge bg-success">
Active
</span>

<?php } else { ?>

<span class="badge bg-danger">
Inactive
</span>

<?php } ?>

</div>

<div class="col-md-9">

<table class="table table-bordered">

<tr>

<th width="30%">Employee Code</th>

<td><?= $employee['employee_code']; ?></td>

</tr>

<tr>

<th>Email</th>

<td><?= htmlspecialchars($employee['email']); ?></td>

</tr>

<tr>

<th>Phone</th>

<td><?= htmlspecialchars($employee['phone']); ?></td>

</tr>

<tr>

<th>Gender</th>

<td><?= $employee['gender']; ?></td>

</tr>

<tr>

<th>Department</th>

<td><?= htmlspecialchars($employee['department']); ?></td>

</tr>

<tr>

<th>Designation</th>

<td><?= htmlspecialchars($employee['designation']); ?></td>

</tr>

<tr>

<th>Salary</th>

<td>₹ <?= number_format($employee['salary'],2); ?></td>

</tr>

<tr>

<th>Joining Date</th>

<td><?= $employee['joining_date']; ?></td>

</tr>

<tr>

<th>Created At</th>

<td><?= $employee['created_at']; ?></td>

</tr>

</table>

<a href="edit.php?id=<?= $employee['id']; ?>" class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit

</a>

<a href="index.php" class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>