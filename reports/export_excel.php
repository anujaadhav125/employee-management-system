<?php

require_once '../config/app.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Employee_Report.xls");

$query = "SELECT * FROM employees ORDER BY full_name ASC";
$result = mysqli_query($conn, $query);

echo "<table border='1'>";

echo "<tr>
<th>Employee Code</th>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Designation</th>
<th>Status</th>
<th>Phone</th>
<th>Joining Date</th>
</tr>";

while($row = mysqli_fetch_assoc($result)){

    echo "<tr>";

    echo "<td>".$row['employee_code']."</td>";
    echo "<td>".$row['full_name']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>".$row['department']."</td>";
    echo "<td>".$row['designation']."</td>";
    echo "<td>".$row['status']."</td>";
    echo "<td>".$row['phone']."</td>";
    echo "<td>".$row['joining_date']."</td>";

    echo "</tr>";

}

echo "</table>";