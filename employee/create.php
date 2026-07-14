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

            <h4>Add New Employee</h4>

        </div>

        <div class="card-body">

            <form action="store.php" method="POST" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Full Name</label>

                        <input type="text" name="full_name" class="form-control" required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Email</label>

                        <input type="email" name="email" class="form-control" required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Phone</label>

                        <input type="text" name="phone" class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Gender</label>

                        <select name="gender" class="form-control">

                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Department</label>

                        <input type="text" name="department" class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Designation</label>

                        <input type="text" name="designation" class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Salary</label>

                        <input type="number" name="salary" class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Joining Date</label>

                        <input type="date" name="joining_date" class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Profile Image</label>

                        <input type="file" name="profile_image" class="form-control">

                    </div>

                </div>

                <button class="btn btn-success">

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