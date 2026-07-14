<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

// Fetch Active Departments
$query = "SELECT * FROM departments WHERE status='Active' ORDER BY department_name ASC";
$departments = mysqli_query($conn, $query);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Add Designation</h4>

</div>

<div class="card-body">

<form action="store.php" method="POST">

<div class="mb-3">

<label class="form-label">Department</label>

<select name="department_id" class="form-select" required>

<option value="">Select Department</option>

<?php while($dept = mysqli_fetch_assoc($departments)){ ?>

<option value="<?= $dept['id']; ?>">

<?= htmlspecialchars($dept['department_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">Designation Name</label>

<input
type="text"
name="designation_name"
class="form-control"
required>

</div>

<button class="btn btn-success">

Save Designation

</button>

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>