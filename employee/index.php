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

    $countQuery = "SELECT COUNT(*) AS total
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

    $countQuery = "SELECT COUNT(*) AS total FROM employees";

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

<div class="d-flex justify-content-between align-items-center mb-3">

<h2>Employees</h2>

<a href="create.php" class="btn btn-primary">

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

<form class="row mb-3">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search by name or email..."
value="<?= htmlspecialchars($search); ?>">

</div>

<div class="col-md-2">

<button class="btn btn-dark">

Search

</button>

</div>

</form>

<div class="card shadow">

<div class="card-body">

<table class="table table-hover table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Code</th>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= $row['employee_code']; ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

<td>

<span class="badge <?= ($row['status']=='Active') ? 'bg-success' : 'bg-secondary'; ?>">

<?= $row['status']; ?>

</span>

</td>

<td>

<a href="view.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">

View

</a>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Deactivate employee?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<nav>

<ul class="pagination">

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