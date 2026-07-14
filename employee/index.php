<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

// Fetch Employees
$query = "SELECT * FROM employees ORDER BY id DESC";
$result = mysqli_query($conn, $query);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Employees</h2>

        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Employee
        </a>

    </div>


    <div class="card shadow">

        <div class="card-body">
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

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result)>0){ ?>

                    <?php while($row=mysqli_fetch_assoc($result)){ ?>

                        <tr>

                            <td><?= $row['id']; ?></td>

                            <td><?= $row['employee_code']; ?></td>

                            <td><?= htmlspecialchars($row['full_name']); ?></td>

                            <td><?= htmlspecialchars($row['email']); ?></td>

                            <td><?= htmlspecialchars($row['department']); ?></td>

                            <td><?= htmlspecialchars($row['designation']); ?></td>

                            <td>

                                <?php if($row['status']=="Active"){ ?>

                                    <span class="badge bg-success">Active</span>

                                <?php }else{ ?>

                                    <span class="badge bg-danger">Inactive</span>

                                <?php } ?>

                            </td>

                            <td>

                                <a href="view.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">

                                    View

                                </a>

                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">

                                    Delete

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                <?php }else{ ?>

                    <tr>

                        <td colspan="8" class="text-center">

                            No Employees Found

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>
        <?php if(isset($_SESSION['error'])){ ?>

<div class="alert alert-danger">

    <?php

    echo $_SESSION['error'];

    unset($_SESSION['error']);

    ?>

</div>

<?php } ?>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>