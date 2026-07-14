<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

$query = "SELECT
            designations.*,
            departments.department_name
          FROM designations
          INNER JOIN departments
          ON designations.department_id = departments.id
          ORDER BY designations.id DESC";

$result = mysqli_query($conn, $query);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Designations</h2>

<a href="create.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Designation

</a>

</div>

<?php if(isset($_SESSION['success'])){ ?>

<div class="alert alert-success">

<?= $_SESSION['success']; unset($_SESSION['success']); ?>

</div>

<?php } ?>

<?php if(isset($_SESSION['error'])){ ?>

<div class="alert alert-danger">

<?= $_SESSION['error']; unset($_SESSION['error']); ?>

</div>

<?php } ?>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Department</th>

<th>Designation</th>

<th>Status</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['department_name']); ?></td>

<td><?= htmlspecialchars($row['designation_name']); ?></td>

<td>

<span class="badge <?= ($row['status']=='Active') ? 'bg-success':'bg-secondary'; ?>">

<?= htmlspecialchars($row['status']); ?>

</span>

</td>

<td>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">

Edit

</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete designation?')">

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

<?php require_once '../includes/footer.php'; ?>