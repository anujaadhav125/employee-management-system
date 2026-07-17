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

$query = "SELECT * FROM employees WHERE id=?";

$stmt = mysqli_prepare($conn,$query);

mysqli_stmt_bind_param($stmt,"i",$id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)!=1){
    redirect('index.php');
}

$employee = mysqli_fetch_assoc($result);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="bi bi-person-badge-fill text-primary"></i>

Employee Profile

</h2>

<p class="text-muted mb-0">

View complete employee information

</p>

</div>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<div class="card shadow-lg border-0">

<div class="card-body">

<div class="row">

<!-- Left Profile -->

<div class="col-lg-4 text-center border-end">

<?php

$image = !empty($employee['profile_image'])
            ? "../assets/uploads/employees/".$employee['profile_image']
            : "../assets/uploads/employees/default.png";

?>

<img
src="<?= $image; ?>"
class="rounded-circle shadow mb-3"
width="180"
height="180"
style="object-fit:cover;"
onerror="this.src='../assets/uploads/employees/default.png';">

<h3 class="fw-bold">

<?= htmlspecialchars($employee['full_name']); ?>

</h3>

<p class="text-muted mb-2">

<?= htmlspecialchars($employee['designation']); ?>

</p>

<?php if($employee['status']=="Active"){ ?>

<span class="badge bg-success fs-6 px-3 py-2">

<i class="bi bi-check-circle-fill"></i>

Active

</span>

<?php }else{ ?>

<span class="badge bg-danger fs-6 px-3 py-2">

<i class="bi bi-x-circle-fill"></i>

Inactive

</span>

<?php } ?>

<hr>

<div class="text-start">

<p>

<i class="bi bi-envelope-fill text-primary"></i>

<strong>Email</strong><br>

<?= htmlspecialchars($employee['email']); ?>

</p>

<p>

<i class="bi bi-telephone-fill text-success"></i>

<strong>Phone</strong><br>

<?= htmlspecialchars($employee['phone']); ?>

</p>

</div>

</div>

<!-- Right Details -->

<div class="col-lg-8">

<h4 class="mb-4">

Employee Details

</h4>

<table class="table table-bordered table-striped align-middle">

<tbody>

<tr>

<th width="35%">

<i class="bi bi-upc-scan"></i>

Employee Code

</th>

<td><?= htmlspecialchars($employee['employee_code']); ?></td>

</tr>

<tr>

<th>

<i class="bi bi-gender-ambiguous"></i>

Gender

</th>

<td><?= htmlspecialchars($employee['gender']); ?></td>

</tr>

<tr>

<th>

<i class="bi bi-building"></i>

Department

</th>

<td><?= htmlspecialchars($employee['department']); ?></td>

</tr>

<tr>

<th>

<i class="bi bi-briefcase-fill"></i>

Designation

</th>

<td><?= htmlspecialchars($employee['designation']); ?></td>

</tr>

<tr>

<th>

<i class="bi bi-currency-rupee"></i>

Salary

</th>

<td>

₹ <?= number_format($employee['salary']); ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-calendar-event"></i>

Joining Date

</th>

<td>

<?= date("d M Y",strtotime($employee['joining_date'])); ?>

</td>

</tr>

<?php if(isset($employee['created_at'])){ ?>

<tr>

<th>

<i class="bi bi-clock-history"></i>

Created At

</th>

<td>

<?= date("d M Y h:i A",strtotime($employee['created_at'])); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<div class="mt-4">

<a
href="edit.php?id=<?= $employee['id']; ?>"
class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit Employee

</a>

<a
href="delete.php?id=<?= $employee['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Delete this employee?')">

<i class="bi bi-trash"></i>

Delete

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>