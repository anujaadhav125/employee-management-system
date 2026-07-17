<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

/* ---------- Pagination ---------- */

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$offset = ($page - 1) * $limit;

/* ---------- Search ---------- */

$search = trim($_GET['search'] ?? '');

if($search != ""){

    $searchLike = "%".$search."%";

    $countQuery = "SELECT COUNT(*) total
                   FROM employees
                   WHERE full_name LIKE ?
                   OR email LIKE ?";

    $stmt = mysqli_prepare($conn,$countQuery);

    mysqli_stmt_bind_param($stmt,"ss",$searchLike,$searchLike);

    mysqli_stmt_execute($stmt);

    $countResult = mysqli_stmt_get_result($stmt);

    $total = mysqli_fetch_assoc($countResult)['total'];

    $query = "SELECT *
              FROM employees
              WHERE full_name LIKE ?
              OR email LIKE ?
              ORDER BY id DESC
              LIMIT ?,?";

    $stmt = mysqli_prepare($conn,$query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $searchLike,
        $searchLike,
        $offset,
        $limit
    );

}else{

    $countQuery = "SELECT COUNT(*) total FROM employees";

    $countResult = mysqli_query($conn,$countQuery);

    $total = mysqli_fetch_assoc($countResult)['total'];

    $query = "SELECT *
              FROM employees
              ORDER BY id DESC
              LIMIT ?,?";

    $stmt = mysqli_prepare($conn,$query);

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $offset,
        $limit
    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$totalPages = ceil($total/$limit);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h2 class="fw-bold mb-1">
<i class="bi bi-people-fill text-primary"></i>
Employees
</h2>

<p class="text-muted mb-0">
Total Employees :
<strong><?= $total; ?></strong>
</p>

</div>

<a href="create.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Employee

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

<form class="row mb-4">

<div class="col-md-5">

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-search"></i>

</span>

<input
type="text"
name="search"
class="form-control"
placeholder="Search by Name or Email..."
value="<?= htmlspecialchars($search); ?>">

</div>

</div>

<div class="col-md-2">

<button class="btn btn-dark w-100">

Search

</button>

</div>

<div class="col-md-2">

<a href="index.php" class="btn btn-secondary w-100">

Reset

</a>

</div>

</form>

<div class="card shadow">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Photo</th>

<th>Code</th>

<th>Name</th>

<th>Email</th>

<th>Department</th>

<th>Status</th>

<th width="220">Action</th>

</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($result)>0){ ?>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td>

<img
src="../uploads/<?= $row['profile_image']; ?>"
width="45"
height="45"
class="rounded-circle border"
style="object-fit:cover;"
onerror="this.src='../uploads/default.png';">

</td>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td>

<strong><?= htmlspecialchars($row['full_name']); ?></strong>

</td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

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

<td>

<a
href="view.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm">

<i class="bi bi-eye"></i>

</a>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Deactivate Employee?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="8" class="text-center text-muted py-5">

<i class="bi bi-folder2-open fs-1"></i>

<br><br>

No Employees Found

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php for($i=1;$i<=$totalPages;$i++){ ?>

<li class="page-item <?= ($page==$i)?'active':''; ?>">

<a
class="page-link"
href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>">

<?= $i; ?>

</a>

</li>

<?php } ?>

</ul>

</nav>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>