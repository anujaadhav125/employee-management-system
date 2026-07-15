<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM employees";

if($search != ""){

    $search = mysqli_real_escape_string($conn,$search);

    $query .= " WHERE full_name LIKE '%$search%'
                OR employee_code LIKE '%$search%'
                OR department LIKE '%$search%'";

}

$query .= " ORDER BY full_name ASC";

$result = mysqli_query($conn,$query);

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header d-flex justify-content-between">

<h4>Employee Report</h4>

<div>

<a href="export_excel.php" class="btn btn-success">

Export Excel

</a>

<a href="export_pdf.php" class="btn btn-danger">

Export PDF

</a>

</div>

</div>

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Employee..."
value="<?= htmlspecialchars($search); ?>">

</div>

<div class="col-md-2">

<button class="btn btn-primary">

Search

</button>

</div>

</div>

</form>

<br>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>

<th>Name</th>

<th>Email</th>

<th>Department</th>

<th>Designation</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

<td><?= htmlspecialchars($row['designation']); ?></td>

<td>

<span class="badge <?= ($row['status']=="Active")?"bg-success":"bg-danger"; ?>">

<?= htmlspecialchars($row['status']); ?>

</span>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>