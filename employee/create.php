<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

// Fetch Active Departments
$departments = mysqli_query(
    $conn,
    "SELECT * FROM departments
     WHERE status='Active'
     ORDER BY department_name ASC"
);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Add New Employee</h4>
        </div>

        <div class="card-body">

            <form action="store.php" method="POST" enctype="multipart/form-data">

                <div class="row">

                    <!-- Full Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text"
                               name="full_name"
                               class="form-control"
                               required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               required>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text"
                               name="phone"
                               class="form-control">
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>

                        <select name="gender" class="form-select">

                            <option value="Male">Male</option>

                            <option value="Female">Female</option>

                            <option value="Other">Other</option>

                        </select>

                    </div>

                    <!-- Department -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Department</label>

                        <select
                            id="department"
                            name="department"
                            class="form-select"
                            required>

                            <option value="">Select Department</option>

                            <?php while($dept = mysqli_fetch_assoc($departments)){ ?>

                                <option value="<?= $dept['id']; ?>">

                                    <?= htmlspecialchars($dept['department_name']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- Designation -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Designation</label>

                        <select
                            id="designation"
                            name="designation"
                            class="form-select"
                            required>

                            <option value="">
                                Select Designation
                            </option>

                        </select>

                    </div>

                    <!-- Salary -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Salary</label>

                        <input type="number"
                               name="salary"
                               class="form-control">

                    </div>

                    <!-- Joining Date -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Joining Date</label>

                        <input type="date"
                               name="joining_date"
                               class="form-control">

                    </div>

                    <!-- Profile Image -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Profile Image</label>

                        <input type="file"
                               name="profile_image"
                               class="form-control">

                    </div>

                </div>

                <button type="submit" class="btn btn-success">
                    Save Employee
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>