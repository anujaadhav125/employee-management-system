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

        <div class="card-header bg-warning text-dark">

            <h4>Edit Employee</h4>

        </div>

        <div class="card-body">

            <form action="update.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= $employee['id']; ?>">
                <input type="hidden" name="old_image" value="<?= $employee['profile_image']; ?>">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                            value="<?= htmlspecialchars($employee['full_name']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($employee['email']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control"
                            value="<?= htmlspecialchars($employee['phone']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Gender</label>

                        <select name="gender" class="form-control">

                            <option value="Male" <?= ($employee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>

                            <option value="Female" <?= ($employee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>

                            <option value="Other" <?= ($employee['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Department</label>
                        <?php

                        $deptQuery = "SELECT department_name
                           FROM departments
                           WHERE status='Active'
                           ORDER BY department_name ASC";

                        $deptResult = mysqli_query($conn, $deptQuery);

                        ?>

                        <select name="department" class="form-select" required>

                        <?php while($dept = mysqli_fetch_assoc($deptResult)){ ?>

                        <option
                        value="<?= htmlspecialchars($dept['department_name']); ?>"

                        <?= ($employee['department']==$dept['department_name']) ? 'selected' : ''; ?>>

                        <?= htmlspecialchars($dept['department_name']); ?>

                        </option>

                    <?php } ?>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Designation</label>
                        <input type="text" name="designation" class="form-control"
                            value="<?= htmlspecialchars($employee['designation']); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Salary</label>
                        <input type="number" step="0.01" name="salary" class="form-control"
                            value="<?= $employee['salary']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Joining Date</label>
                        <input type="date" name="joining_date" class="form-control"
                            value="<?= $employee['joining_date']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Current Image</label><br>

                        <img src="../assets/uploads/employees/<?= $employee['profile_image']; ?>"
                             width="100"
                             class="img-thumbnail">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Change Image</label>

                        <input type="file"
                               name="profile_image"
                               class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="Active" <?= ($employee['status']=='Active')?'selected':''; ?>>Active</option>

                            <option value="Inactive" <?= ($employee['status']=='Inactive')?'selected':''; ?>>Inactive</option>

                        </select>

                    </div>

                </div>

                <button class="btn btn-warning">

                    Update Employee

                </button>

                <a href="index.php" class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>