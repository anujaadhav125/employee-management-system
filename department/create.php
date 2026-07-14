<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Add Department</h4>

</div>

<div class="card-body">

<form action="store.php" method="POST">

<div class="mb-3">

<label class="form-label">Department Name</label>

<input
type="text"
name="department_name"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">Description</label>

<textarea
name="description"
class="form-control"
rows="4"></textarea>

</div>

<button class="btn btn-success">

Save Department

</button>

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>