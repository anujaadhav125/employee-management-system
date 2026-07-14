<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

$query = "SELECT d.*,
(
    SELECT COUNT(*)
    FROM employees e
    WHERE e.department = d.department_name
    AND e.status='Active'
) AS total_employees
FROM departments d
ORDER BY d.id DESC";

$result = mysqli_query($conn, $query);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Departments</h2>

<a href="create.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Department

</a>

</div>

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

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Department</th>

<th>Description</th>

<th>Employees</th>

<th>Status</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($result)>0){ ?>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['department_name']); ?></td>

<td><?= htmlspecialchars($row['description']); ?></td>

<td>

<span class="badge bg-primary">

<?= $row['total_employees']; ?>

</span>

</td>

<td>

<span class="badge <?= ($row['status']=="Active") ? "bg-success" : "bg-secondary"; ?>">

<?= $row['status']; ?>

</span>

</td>

<td>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">

Edit

</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this department?');">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="6" class="text-center">

No Departments Found

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>