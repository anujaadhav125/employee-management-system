<?php

require_once '../config/app.php';

if (!isset($_GET['department_id'])) {
    exit;
}

$department_id = (int)$_GET['department_id'];

$query = "SELECT id, designation_name
          FROM designations
          WHERE department_id = ?
          AND status='Active'
          ORDER BY designation_name";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $department_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

echo '<option value="">Select Designation</option>';

while($row = mysqli_fetch_assoc($result)){

    echo '<option value="'.$row['designation_name'].'">'
        .htmlspecialchars($row['designation_name']).
        '</option>';

}