<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getLoggedInUser();

/* ===============================
   Dashboard Statistics
=============================== */

$totalEmployees = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM employees")
)['total'];

$totalDepartments = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM departments")
)['total'];

$activeEmployees = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM employees WHERE status='Active'")
)['total'];

$inactiveEmployees = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM employees WHERE status='Inactive'")
)['total'];


/* ===============================
   Employees By Department
=============================== */

$deptLabels = [];
$deptCounts = [];

$result = mysqli_query($conn,"
SELECT department,COUNT(*) total
FROM employees
GROUP BY department
");

while($row=mysqli_fetch_assoc($result)){

    $deptLabels[]=$row['department'];
    $deptCounts[]=$row['total'];

}


/* ===============================
   Attendance
=============================== */

$today=date("Y-m-d");

$present=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE attendance_date='$today'
AND status='Present'
"))['total'];

$absent=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE attendance_date='$today'
AND status='Absent'
"))['total'];

$leave=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE attendance_date='$today'
AND status='Leave'
"))['total'];

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';
?>

<div class="dashboard">

<div class="dashboard-header">

<div>

<h2>
Welcome,
<?= htmlspecialchars($user['name']); ?> 👋
</h2>

<p>
Manage your organization efficiently with StaffSync HRMS.
</p>

</div>

<div class="text-end">

<h5 class="fw-bold">
<?= date("d M Y"); ?>
</h5>

<p class="text-muted">
HR Management Dashboard
</p>

</div>

</div>


<div class="row g-4 mb-4">

<!-- Employees -->

<div class="col-lg-3 col-md-6">

<div class="dashboard-card employees-card">

<div class="card-body d-flex justify-content-between align-items-center">

<div>

<h6>Total Employees</h6>

<h2><?= $totalEmployees ?></h2>

</div>

<div class="dashboard-icon">

<i class="bi bi-people-fill"></i>

</div>

</div>

</div>

</div>


<!-- Departments -->

<div class="col-lg-3 col-md-6">

<div class="dashboard-card department-card">

<div class="card-body d-flex justify-content-between align-items-center">

<div>

<h6>Departments</h6>

<h2><?= $totalDepartments ?></h2>

</div>

<div class="dashboard-icon">

<i class="bi bi-building"></i>

</div>

</div>

</div>

</div>


<!-- Active -->

<div class="col-lg-3 col-md-6">

<div class="dashboard-card active-card">

<div class="card-body d-flex justify-content-between align-items-center">

<div>

<h6>Active Employees</h6>

<h2><?= $activeEmployees ?></h2>

</div>

<div class="dashboard-icon">

<i class="bi bi-person-check-fill"></i>

</div>

</div>

</div>

</div>


<!-- Inactive -->

<div class="col-lg-3 col-md-6">

<div class="dashboard-card inactive-card">

<div class="card-body d-flex justify-content-between align-items-center">

<div>

<h6>Inactive Employees</h6>

<h2><?= $inactiveEmployees ?></h2>

</div>

<div class="dashboard-icon">

<i class="bi bi-person-x-fill"></i>

</div>

</div>

</div>

</div>

</div>



<!-- Charts -->

<div class="row g-4">

<div class="col-lg-8">

<div class="card chart-card">

<div class="card-header">

<h5>

<i class="bi bi-bar-chart-fill text-primary me-2"></i>

Employees by Department

</h5>

</div>

<div class="card-body">

<div style="height:370px;">

<canvas id="departmentChart"></canvas>

</div>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card chart-card">

<div class="card-header">

<h5>

<i class="bi bi-pie-chart-fill text-success me-2"></i>

Today's Attendance

</h5>

</div>

<div class="card-body">

<div style="height:370px;">

<canvas id="attendanceChart"></canvas>

</div>

</div>

</div>

</div>

</div>
<!-- ===========================
     Recent Employees
=========================== -->

<div class="card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
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

$recent=mysqli_query($conn,"
SELECT *
FROM employees
ORDER BY id DESC
LIMIT 5
");

if(mysqli_num_rows($recent)>0){

while($row=mysqli_fetch_assoc($recent)){

?>

<tr>

<td>

<strong>

<?= htmlspecialchars($row['employee_code']); ?>

</strong>

</td>

<td>

<?= htmlspecialchars($row['full_name']); ?>

</td>

<td>

<?= htmlspecialchars($row['department']); ?>

</td>

<td>

<?php if($row['status']=="Active"){ ?>

<span class="badge bg-success">

Active

</span>

<?php } else { ?>

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

<td colspan="4" class="text-center text-muted">

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

<!-- ===========================
        CHART DATA
=========================== -->

<script>

const departmentLabels = <?= json_encode($deptLabels); ?>;

const departmentCounts = <?= json_encode($deptCounts); ?>;

const attendanceData = [

<?= $present ?>,

<?= $absent ?>,

<?= $leave ?>

];

</script>

<!-- Chart JS -->

<script>

document.addEventListener("DOMContentLoaded",()=>{

/* ======================
Department Chart
====================== */

const bar=document.getElementById("departmentChart");

if(bar){

new Chart(bar,{

type:"bar",

data:{

labels:departmentLabels,

datasets:[{

label:"Employees",

data:departmentCounts,

backgroundColor:[

"#6366F1",

"#3B82F6",

"#10B981",

"#F59E0B",

"#EF4444",

"#8B5CF6",

"#06B6D4"

],

borderRadius:12,

borderSkipped:false

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{display:false}

},

animation:{

duration:1800

},

scales:{

x:{

grid:{display:false},

ticks:{

color:"#374151",

font:{

size:13,

weight:"600"

}

}

},

y:{

beginAtZero:true,

ticks:{

precision:0,

stepSize:1,

color:"#374151",

font:{

size:13,

weight:"600"

}

},

grid:{

color:"#E5E7EB"

}

}

}

}

});

}

/* ======================
Attendance Chart
====================== */

const pie=document.getElementById("attendanceChart");

if(pie){

new Chart(pie,{

type:"doughnut",

data:{

labels:[

"Present",

"Absent",

"Leave"

],

datasets:[{

data:attendanceData,

backgroundColor:[

"#22C55E",

"#EF4444",

"#F59E0B"

],

borderWidth:3,

hoverOffset:15

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

cutout:"70%",

plugins:{

legend:{

position:"bottom",

labels:{

padding:20,

usePointStyle:true,

pointStyle:"circle",

color:"#374151",

font:{

size:13,

weight:"600"

}

}

}

},

animation:{

duration:1800

}

}

});

}

});

</script>

<?php require_once '../includes/footer.php'; ?>