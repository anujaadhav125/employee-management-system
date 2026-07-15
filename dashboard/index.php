<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

/* Dashboard Counts */

$totalEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees")
)['total'];

$totalDepartments = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM departments")
)['total'];

$activeEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees WHERE status='Active'")
)['total'];

$inactiveEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees WHERE status='Inactive'")
)['total'];

/* Employees by Department */

$deptLabels = [];
$deptCounts = [];

$query = "SELECT department, COUNT(*) AS total
          FROM employees
          GROUP BY department";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

    $deptLabels[] = $row['department'];
    $deptCounts[] = $row['total'];

}

/* Attendance Chart */

$today = date('Y-m-d');

$present = mysqli_fetch_assoc(mysqli_query(
$conn,
"SELECT COUNT(*) total FROM attendance
WHERE attendance_date='$today'
AND status='Present'"
))['total'];

$absent = mysqli_fetch_assoc(mysqli_query(
$conn,
"SELECT COUNT(*) total FROM attendance
WHERE attendance_date='$today'
AND status='Absent'"
))['total'];

$leave = mysqli_fetch_assoc(mysqli_query(
$conn,
"SELECT COUNT(*) total FROM attendance
WHERE attendance_date='$today'
AND status='Leave'"
))['total'];

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

<div class="row g-4">

<div class="col-md-3">
<div class="card stat-card text-center shadow">
<div class="card-body">
<h1><?= $totalEmployees; ?></h1>
<p>Total Employees</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card stat-card text-center shadow">
<div class="card-body">
<h1><?= $totalDepartments; ?></h1>
<p>Departments</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card stat-card text-center shadow">
<div class="card-body">
<h1><?= $activeEmployees; ?></h1>
<p>Active Employees</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card stat-card text-center shadow">
<div class="card-body">
<h1><?= $inactiveEmployees; ?></h1>
<p>Inactive Employees</p>
</div>
</div>
</div>

</div>

<!-- Charts -->

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header">

<h5 class="mb-0">Employees by Department</h5>

</div>

<div class="card-body">

<canvas id="departmentChart"></canvas>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header">

<h5 class="mb-0">Today's Attendance</h5>

</div>

<div class="card-body">

<canvas id="attendanceChart"></canvas>

</div>

</div>

</div>

</div>

<!-- Recent Employees -->

<div class="card shadow mt-4">

<div class="card-header">

<h4>Recent Employees</h4>

</div>

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Employee Code</th>
<th>Name</th>
<th>Department</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$query = "SELECT * FROM employees ORDER BY id DESC LIMIT 5";

$result = mysqli_query($conn,$query);

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?= htmlspecialchars($row['employee_code']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

<td>

<span class="badge <?= ($row['status']=='Active')?'bg-success':'bg-secondary'; ?>">

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

<script>

<?php require_once '../includes/footer.php'; ?>

<?php require_once '../includes/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===========================
    // Employees by Department
    // ===========================

    const deptCanvas = document.getElementById("departmentChart");

    if (deptCanvas) {

        new Chart(deptCanvas, {

            type: "bar",

            data: {

                labels: <?= json_encode($deptLabels); ?>,

                datasets: [{

                    label: "Employees",

                    data: <?= json_encode($deptCounts); ?>,

                    backgroundColor: "#6f42c1",

                    borderRadius: 10,

                    borderSkipped: false

                }]

            },

            options: {

                responsive: true,

                indexAxis: "y",

                plugins: {

                    legend: {

                        display: false

                    }

                },

                animation: {

                    duration: 1500

                },

                scales: {

                    x: {

                        beginAtZero: true,

                        grid: {

                            color: "#eeeeee"

                        }

                    },

                    y: {

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

    }

    // ===========================
    // Today's Attendance
    // ===========================

    const attendanceCanvas = document.getElementById("attendanceChart");

    if (attendanceCanvas) {

        new Chart(attendanceCanvas, {

            type: "doughnut",

            data: {

                labels: ["Present","Absent","Leave"],

                datasets: [{

                    data: [

                        <?= $present ?>,

                        <?= $absent ?>,

                        <?= $leave ?>

                    ],

                    backgroundColor: [

                        "#198754",

                        "#dc3545",

                        "#ffc107"

                    ],

                    borderWidth: 2

                }]

            },

            options: {

                responsive: true,

                cutout: "65%",

                plugins: {

                    legend: {

                        position: "bottom"

                    }

                },

                animation: {

                    animateRotate: true,

                    duration: 1800

                }

            }

        });

    }

});
</script>