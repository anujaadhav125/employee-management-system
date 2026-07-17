<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

/* ===============================
   Dashboard Statistics
================================ */

// Total Employees
$totalEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees")
)['total'];

// Total Departments
$totalDepartments = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM departments")
)['total'];

// Active Employees
$activeEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees WHERE status='Active'")
)['total'];

// Inactive Employees
$inactiveEmployees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM employees WHERE status='Inactive'")
)['total'];


/* ===============================
   Employees By Department
================================ */

$deptLabels = [];
$deptCounts = [];

$query = mysqli_query($conn, "
    SELECT department, COUNT(*) AS total
    FROM employees
    GROUP BY department
");

while ($row = mysqli_fetch_assoc($query)) {
    $deptLabels[] = $row['department'];
    $deptCounts[] = $row['total'];
}


/* ===============================
   Today's Attendance
================================ */

$today = date("Y-m-d");

$present = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM attendance
        WHERE attendance_date='$today'
        AND status='Present'
    ")
)['total'];

$absent = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM attendance
        WHERE attendance_date='$today'
        AND status='Absent'
    ")
)['total'];

$leave = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM attendance
        WHERE attendance_date='$today'
        AND status='Leave'
    ")
)['total'];

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="dashboard">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Welcome, <?= htmlspecialchars($user['name']); ?> 👋
            </h2>

            <p class="text-muted mb-0">
                Manage your organization efficiently with StaffSync.
            </p>
        </div>

        <div class="text-end">
            <h5 class="fw-bold mb-0"><?= date("d M Y"); ?></h5>
            <small class="text-muted">HR Management Dashboard</small>
        </div>

    </div>

    <!-- Statistics -->

    <div class="row g-4 mb-4">

        <!-- Employees -->

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">Total Employees</small>

                        <h2 class="fw-bold mt-2">
                            <?= $totalEmployees; ?>
                        </h2>

                    </div>

                    <div class="fs-2 text-primary">

                        <i class="bi bi-people-fill"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Departments -->

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">Departments</small>

                        <h2 class="fw-bold mt-2">
                            <?= $totalDepartments; ?>
                        </h2>

                    </div>

                    <div class="fs-2 text-success">

                        <i class="bi bi-building"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Active -->

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">Active Employees</small>

                        <h2 class="fw-bold mt-2">
                            <?= $activeEmployees; ?>
                        </h2>

                    </div>

                    <div class="fs-2 text-success">

                        <i class="bi bi-person-check-fill"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Inactive -->

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">Inactive Employees</small>

                        <h2 class="fw-bold mt-2">
                            <?= $inactiveEmployees; ?>
                        </h2>

                    </div>

                    <div class="fs-2 text-danger">

                        <i class="bi bi-person-x-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- =========================
     Charts
========================= -->

<div class="row g-4">

    <!-- Employees by Department -->

    <div class="col-lg-7">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-header bg-white border-0">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                    Employees by Department
                </h5>

            </div>

            <div class="card-body">

                <div style="height:350px;">

                    <canvas id="departmentChart"></canvas>

                </div>

            </div>

        </div>

    </div>

    <!-- Attendance -->

    <div class="col-lg-5">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-header bg-white border-0">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-pie-chart-fill text-success me-2"></i>
                    Today's Attendance
                </h5>

            </div>

            <div class="card-body">

                <div style="height:350px;">

                    <canvas id="attendanceChart"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- =========================
     Recent Employees
========================= -->

<div class="card shadow-sm border-0 rounded-4 mt-4">

    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

        <h5 class="fw-bold mb-0">

            <i class="bi bi-clock-history text-primary me-2"></i>

            Recent Employees

        </h5>

        <a href="../employee/index.php" class="btn btn-primary btn-sm">

            View All

        </a>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>Employee Code</th>

                        <th>Name</th>

                        <th>Department</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $recent = mysqli_query($conn,"
                    SELECT *
                    FROM employees
                    ORDER BY id DESC
                    LIMIT 5
                ");

                if(mysqli_num_rows($recent)>0):

                    while($row=mysqli_fetch_assoc($recent)):

                ?>

                    <tr>

                        <td><?= htmlspecialchars($row['employee_code']); ?></td>

                        <td><?= htmlspecialchars($row['full_name']); ?></td>

                        <td><?= htmlspecialchars($row['department']); ?></td>

                        <td>

                            <?php if($row['status']=="Active"): ?>

                                <span class="badge bg-success">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php

                    endwhile;

                else:

                ?>

                    <tr>

                        <td colspan="4" class="text-center">

                            No Employees Found

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
<script>

document.addEventListener("DOMContentLoaded", function () {

    // ==========================
    // Employees by Department
    // ==========================

    const departmentChart = document.getElementById("departmentChart");

    if (departmentChart) {

        new Chart(departmentChart, {

            type: "bar",

            data: {

                labels: <?= json_encode($deptLabels); ?>,

                datasets: [{

                    label: "Employees",

                    data: <?= json_encode($deptCounts); ?>,

                    backgroundColor: [

                        "#6F42C1",
                        "#0D6EFD",
                        "#198754",
                        "#FD7E14",
                        "#DC3545",
                        "#20C997",
                        "#6610F2"

                    ],

                    borderRadius: 10,
                    borderSkipped: false

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                animation: {

                    duration: 1500

                },

                plugins: {

                    legend: {

                        display: false

                    }

                },

                scales: {

                    x: {

                        grid: {

                            display: false

                        },

                        ticks: {

                            color: "#374151",

                            font: {

                                size: 12,

                                weight: "bold"

                            }

                        }

                    },

                    y: {

                        beginAtZero: true,

                        ticks: {

                            stepSize: 1,

                            precision: 0,

                            color: "#374151",

                            font: {

                                size: 12,

                                weight: "bold"

                            }

                        },

                        grid: {

                            color: "#e5e7eb"

                        }

                    }

                }

            }

        });

    }

    // ==========================
    // Attendance Chart
    // ==========================

    const attendanceChart = document.getElementById("attendanceChart");

    if (attendanceChart) {

        new Chart(attendanceChart, {

            type: "doughnut",

            data: {

                labels: [

                    "Present",

                    "Absent",

                    "Leave"

                ],

                datasets: [{

                    data: [

                        <?= $present ?>,

                        <?= $absent ?>,

                        <?= $leave ?>

                    ],

                    backgroundColor: [

                        "#198754",

                        "#DC3545",

                        "#FFC107"

                    ],

                    borderWidth: 2,

                    hoverOffset: 12

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: "65%",

                animation: {

                    animateRotate: true,

                    duration: 1500

                },

                plugins: {

                    legend: {

                        position: "bottom",

                        labels: {

                            usePointStyle: true,

                            pointStyle: "circle",

                            padding: 20,

                            color: "#374151",

                            font: {

                                size: 13,

                                weight: "bold"

                            }

                        }

                    }

                }

            }

        });

    }

});

</script>