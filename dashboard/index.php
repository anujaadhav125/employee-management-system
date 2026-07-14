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

<div class="row">

<div class="col-md-3">

<div class="card stat-card">

<div class="card-body text-center">

<h2>0</h2>

<p>Total Employees</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card stat-card">

<div class="card-body text-center">

<h2>0</h2>

<p>Departments</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card stat-card">

<div class="card-body text-center">

<h2>0</h2>

<p>Attendance</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card stat-card">

<div class="card-body text-center">

<h2>0</h2>

<p>Reports</p>

</div>

</div>

</div>

</div>

</div>

<?php

require_once '../includes/footer.php';

?>