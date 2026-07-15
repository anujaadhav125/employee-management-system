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
$department = $_GET['department'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT * FROM employees WHERE 1=1";

if($search != ""){
    $search = mysqli_real_escape_string($conn,$search);
    $query .= " AND (
        full_name LIKE '%$search%'
        OR employee_code LIKE '%$search%'
        OR email LIKE '%$search%'
    )";
}

if($department != ""){
    $department = mysqli_real_escape_string($conn,$department);
    $query .= " AND department='$department'";
}

if($status != ""){
    $status = mysqli_real_escape_string($conn,$status);
    $query .= " AND status='$status'";
}

$query .= " ORDER BY full_name ASC";

$result = mysqli_query($conn,$query);

$departments = mysqli_query($conn,"
SELECT DISTINCT department
FROM employees
ORDER BY department
");

?>

<div class="dashboard">

<div class="card shadow">

<div class="card-header d-flex justify-content-between align-items-center">

<h4 class="mb-0">Employee Report</h4>

<div>

<a href="export_excel.php" class="btn btn-success">

<i class="bi bi-file-earmark-excel"></i>

Export Excel

</a>

<a href="export_pdf.php" class="btn btn-danger">

<i class="bi bi-file-earmark-pdf"></i>

Export PDF

</a>

</div>

</div>

<div class="card-body">

<form method="GET">

<div class="row g-3">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Employee..."
value="<?= htmlspecialchars($search); ?>">

</div>

<div class="col-md-3">

<select
name="department"
class="form-select">

<option value="">All Departments</option>

<?php while($dept=mysqli_fetch_assoc($departments)){ ?>

<option
value="<?= htmlspecialchars($dept['department']); ?>"
<?= ($department==$dept['department'])?'selected':'';?>>

<?= htmlspecialchars($dept['department']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-3">

<select
name="status"
class="form-select">

<option value="">All Status</option>

<option value="Active"
<?=($status=="Active")?"selected":"";?>>

Active

</option>

<option value="Inactive"
<?=($status=="Inactive")?"selected":"";?>>

Inactive

</option>

</select>

</div>

<div class="col-md-2 d-grid">

<button class="btn btn-primary">

Search

</button>

</div>

<div class="col-md-2 d-grid">

<a
href="employee_report.php"
class="btn btn-secondary">

Reset

</a>

</div>

</div>

</form>

<hr>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

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

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

<td><?= htmlspecialchars($row['designation']); ?></td>

<td>

<?php if($row['status']=="Active"){ ?>

<span class="badge bg-success">

Active

</span>

<?php }else{ ?>

<span class="badge bg-danger">

Inactive

</span>

<?php } ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6" class="text-center">

No Employees Found

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